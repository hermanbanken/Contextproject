<?php 
/**
 * This code is lend from the open-source Wordpress project.
 * It's used to describe the 
 */

/**
 * Actually query the SQL statements from DBDelta. 
 * Provides an access to the applications ORM layer.
 */
function dbDeltaQuery($query){
	return DB::instance()->query(DB::SELECT, $query);
}

/**
 * Generate SQL for altering the tables to comply
 * to their new specification given by the first parameter.
 *
 * @param string $queries
 * @param callback $executer
 * @param pointer $deltas
 * @return array with description of updates
 */
function dbDelta( $queries = '', $executer = null, &$deltas ) {
	if(!$executer) $executer = 'dbDeltaQuery';
	
	// Separate individual queries into an array
	if ( !is_array($queries) ) {
		$queries = explode( ';', $queries );
		if ('' == $queries[count($queries) - 1]) array_pop($queries);
	}
	
	foreach($queries as $k => $q){
		$q = str_replace("IF NOT EXISTS ", "", $q);
		$q = str_replace("`", "", $q);
		$queries[$k] = $q;
	}
	
	$cqueries = array(); // Creation Queries
	$iqueries = array(); // Insertion Queries
	$for_update = array();

	// Create a tablename index for an array ($cqueries) of queries
	foreach($queries as $qry) {		
		if (preg_match("|CREATE\s+TABLE\s+([^ ]*)|", $qry, $matches)) {
			$cqueries[trim( strtolower($matches[1]), '`' )] = $qry;
			$for_update[$matches[1]] = 'Created table '.$matches[1];
		} else if (preg_match("|CREATE\s+DATABASE\s+([^ ]*)|", $qry, $matches)) {
			array_unshift($cqueries, $qry);
		} else if (preg_match("|INSERT\s+INTO\s+([^ ]*)|", $qry, $matches)) {
			$iqueries[] = $qry;
		} else if (preg_match("|UPDATE\s+([^ ]*)|", $qry, $matches)) {
			$iqueries[] = $qry;
		} else {
			// Unrecognized query type
		}
	}

	foreach ( $cqueries as $table => $qry ) {

		// Fetch the table column structure from the database
		$tablefields = call_user_func($executer, "DESCRIBE {$table};");
		
		if ( ! $tablefields )
			continue;

		// Clear the field and index arrays
		$cfields = $indices = array();
		// Get all of the field names in the query from between the parens
		preg_match("|\((.*)\)|ms", $qry, $match2);
		$qryline = trim($match2[1]);

		// Separate field lines into an array
		$flds = explode("\n", $qryline);

		// For every field line specified in the query
		foreach ($flds as $fld) {
			// Extract the field name
			preg_match("|^([^ ]*)|", trim($fld), $fvals);
			$fieldname = trim( $fvals[1], '`' );

			// Verify the found field name
			$validfield = true;
			switch (strtolower($fieldname)) {
			case '':
			case 'primary':
			case 'index':
			case 'fulltext':
			case 'unique':
			case 'key':
				$validfield = false;
				$indices[] = trim(trim($fld), ", \n");
				break;
			}
			$fld = trim($fld);

			// If it's a valid field, add it to the field array
			if ($validfield) {
				$cfields[strtolower($fieldname)] = trim($fld, ", \n");
			}
		}

		// For every field in the table
		foreach ($tablefields as $tablefield) {
			// If the table field exists in the field array...
			if (array_key_exists(strtolower($tablefield->Field), $cfields)) {
				// Get the field type from the query
				preg_match("|".$tablefield->Field." ([^ ]*( unsigned)?)|i", $cfields[strtolower($tablefield->Field)], $matches);
				$fieldtype = $matches[1];

				// Is actual field type different from the field type in query?
				if ($tablefield->Type != $fieldtype) {
					// Add a query to change the column type
					$cqueries[] = "ALTER TABLE {$table} CHANGE COLUMN {$tablefield->Field} " . $cfields[strtolower($tablefield->Field)];
					$for_update[$table.'.'.$tablefield->Field] = "Changed type of {$table}.{$tablefield->Field} from {$tablefield->Type} to {$fieldtype}";
				}

				// Get the default value from the array
				if (preg_match("| DEFAULT '(.*)'|i", $cfields[strtolower($tablefield->Field)], $matches)) {
					$default_value = $matches[1];
					if ($tablefield->Default != $default_value) {
						// Add a query to change the column's default value
						$cqueries[] = "ALTER TABLE {$table} ALTER COLUMN {$tablefield->Field} SET DEFAULT '{$default_value}'";
						$for_update[$table.'.'.$tablefield->Field] = "Changed default value of {$table}.{$tablefield->Field} from {$tablefield->Default} to {$default_value}";
					}
				}

				// Remove the field from the array (so it's not added)
				unset($cfields[strtolower($tablefield->Field)]);
			} else {
				// This field exists in the table, but not in the creation queries?
			}
		}

		// For every remaining field specified for the table
		foreach ($cfields as $fieldname => $fielddef) {
			// Push a query line into $cqueries that adds the field to that table
			$cqueries[] = "ALTER TABLE {$table} ADD COLUMN $fielddef";
			$for_update[$table.'.'.$fieldname] = 'Added column '.$table.'.'.$fieldname;
		}

		// Index stuff goes here
		// Fetch the table index structure from the database
		$tableindices = call_user_func($executer, "SHOW INDEX FROM {$table};");

		if ($tableindices) {
			// Clear the index array
			unset($index_ary);

			// For every index in the table
			foreach ($tableindices as $tableindex) {
				// Add the index to the index data array
				$keyname = $tableindex->Key_name;
				$index_ary[$keyname]['columns'][] = array('fieldname' => $tableindex->Column_name, 'subpart' => $tableindex->Sub_part);
				$index_ary[$keyname]['unique'] = ($tableindex->Non_unique == 0)?true:false;
			}

			// For each actual index in the index array
			foreach ($index_ary as $index_name => $index_data) {
				// Build a create string to compare to the query
				$index_string = '';
				if ($index_name == 'PRIMARY') {
					$index_string .= 'PRIMARY ';
				} else if($index_data['unique']) {
					$index_string .= 'UNIQUE ';
				}
				$index_string .= 'KEY ';
				if ($index_name != 'PRIMARY') {
					$index_string .= $index_name;
				}
				$index_columns = '';
				// For each column in the index
				foreach ($index_data['columns'] as $column_data) {
					if ($index_columns != '') $index_columns .= ',';
					// Add the field to the column list string
					$index_columns .= $column_data['fieldname'];
					if ($column_data['subpart'] != '') {
						$index_columns .= '('.$column_data['subpart'].')';
					}
				}
				// Add the column list to the index create string
				$index_string .= ' ('.$index_columns.')';
				// Remove extra whitespace
				$index_string = str_replace("  ", " ", $index_string);
				
				if (!(($aindex = array_search($index_string, $indices)) === false)) {
					unset($indices[$aindex]);
				}
			}
		}

		// For every remaining index specified for the table
		foreach ( (array) $indices as $index ) {
			// Push a query line into $cqueries that adds the index to that table
			$cqueries[] = "ALTER TABLE {$table} ADD $index";
			$for_update[$table.'.'.$fieldname] = 'Added index '.$table.' '.$index;
		}

		// Remove the original table creation query from processing
		unset( $cqueries[ $table ], $for_update[ $table ] );
	}

	$allqueries = array_merge($cqueries, $iqueries);
	foreach ($allqueries as $query) {
		$deltas .= empty($deltas) ? $query : ';'.$query;
	}
	
	return $for_update;
}