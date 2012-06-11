<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Importing
 *
 * Import monuments, features e.d.
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class Importer {
	/**
	 * Function to import monuments from the folder "files/monuments"
	 * Files should be xml-files named {id_monument}.xml
	 *
	 * @return int aantal monumenten
	 */
	public static function monuments() {
		// Set time limit at unlimited (long import)
		set_time_limit(0);

		// Set counter
		$i = 0;

		// Make a list of id's from monument that are already stored
		$ids = array();
		$monuments_orm = ORM::factory('monument')->find_all();
		foreach ($monuments_orm AS $monument) {
			$ids[] = $monument->id_monument;
		}

		// Loop through all files in the map /public/files/monuments
		if ($monuments = opendir('files/monuments')) {
			while (($monument_file = readdir($monuments)) !== false) {
				// Check if file is xml-file
				if (substr($monument_file, -3) == 'xml') {
					// Set some basis arrays
					$data = array();
					$link = array();

					// Find id in filename
					$monument_id = intval($monument_file);

					// If it is an unparsed monument
					if (!in_array($monument_id, $ids)) {

						// Read file and parse into array
						$info = XMLParser::xml2array(@file_get_contents('files/monuments/'.$monument_id.'.xml'));

						// Array for the records of wikimedia in the form (name_in_file => name_in_database)
						$wikimedia_array = array('monumentNumber' => 'id_monument',
								'title' => 'name',
								'description' => 'description_commons',
								'longitude' => 'lng',
								'latitude' => 'lat',
								'usageOnOtherWikis' => 'wiki',
								'usageOnWikiMediaCommons' => 'commons');

						// Loop through data and save in link- or data-array
						foreach ($wikimedia_array AS $wikimedia => $field) {
							if (isset($info['Monument']['wikimediaCommons'][$wikimedia])) {
								if ($wikimedia == 'usageOnOtherWikis' || $wikimedia == 'usageOnWikiMediaCommons') {
									$link[$field] = $info['Monument']['wikimediaCommons'][$wikimedia];

								}
								else {
									$data[$field] = urldecode($info['Monument']['wikimediaCommons'][$wikimedia]);
								}
							}
						}

						// Array for the records of monumentregistry in the form (name_in_file => name_in_database)
						$mr_array = array('province' => 'id_province',
								'municipality' => 'id_municipality',
								'town' => 'id_town',
								'street' => 'id_street',
								'streetNumber' => 'streetNumber',
								'zipCode' => 'zipCode',
								'mainCategory' => 'id_category',
								'subCategory' => 'id_subcategory',
								'function' => 'id_function',
								'description' => 'description');

						// Loop through data and save in link- or data-array
						foreach ($mr_array AS $mr => $field) {
							if (isset($info['Monument']['monumentRegistry'][$mr])) {
								if (is_array($info['Monument']['monumentRegistry'][$mr])) {
									$link[$field] = $info['Monument']['monumentRegistry'][$mr];
								}
								else {
									$data[$field] = urldecode($info['Monument']['monumentRegistry'][$mr]);
								}
							}
						}

						// If category is present, find id or insert new category in database
						if (isset($data['id_category'])) {
							$category = ORM::factory('category')->where('name', '=', $data['id_category'])->find();
							if ($category->name == NULL) {
								$category->name = $data['id_category'];
								try {
									$category->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['id_category'] = $category->id_category;

							// If subcategory is present, find id or insert new category in database
							if (isset($data['id_subcategory'])) {
								$subcategory = ORM::factory('subcategory')->where('name', '=', $data['id_subcategory'])->find();
								if ($subcategory->name == NULL) {
									$subcategory->name = $data['id_subcategory'];
									$subcategory->id_category = $category->id_category;
									try {
										$subcategory->save();
									}
									catch (Exception $e) {
										echo $e->getMessage().'<br />';
									}
								}
								$data['id_subcategory'] = $subcategory->id_subcategory;
							}
						}

						// If province is present, find id or insert new category in database
						if (isset($data['id_province'])) {
							$province = ORM::factory('province')->where('name', '=', $data['id_province'])->find();
							if ($province->name == NULL) {
								$province->name = $data['id_province'];
								try {
									$province->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['id_province'] = $province->id_province;

							// If municipality is present, find id or insert new category in database
							if (isset($data['id_municipality'])) {
								$municipality = ORM::factory('municipality')->where('name', '=', $data['id_municipality'])->find();
								if ($municipality->name == NULL) {
									$municipality->name = $data['id_municipality'];
									$municipality->id_province = $province->id_province;
									try {
										$municipality->save();
									}
									catch (Exception $e) {
										echo $e->getMessage().'<br />';
									}
								}
								$data['id_municipality'] = $municipality->id_municipality;

								// If town is present, find id or insert new category in database
								if (isset($data['id_town'])) {
									$town = ORM::factory('town')->where('name', '=', $data['id_town'])->find();
									if ($town->name == NULL) {
										$town->name = $data['id_town'];
										$town->id_municipality = $municipality->id_municipality;
										try {
											$town->save();
										}
										catch (Exception $e) {
											echo $e->getMessage().'<br />';
										}
									}
									$data['id_town'] = $town->id_town;

									// If street is present, find id or insert new category in database
									if (isset($data['id_street'])) {
										$street = ORM::factory('street')->where('name', '=', $data['id_street'])->and_where('id_town', '=', $town->id_town)->find();
										if ($street->name == NULL) {
											$street->name = $data['id_street'];
											$street->id_town = $town->id_town;
											try {
												$street->save();
											}
											catch (Exception $e) {
												echo $e->getMessage().'<br />';
											}
										}
										$data['id_street'] = $street->id_street;
									}
								}
							}
						}

						// If function is present, find id or insert new category in database
						if (isset($data['id_function'])) {
							$function = ORM::factory('function')->where('name', '=', $data['id_function'])->find();
							if ($function->name == NULL) {
								$function->name = $data['id_function'];
								try {
									$function->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['id_function'] = $function->id_function;
						}

						// Insert new monument in database
						$monument = ORM::factory('monument')->where('id_monument', '=', $data['id_monument'])->find();
						if ($monument->id_monument == NULL) {
							foreach ($data AS $key => $value) {
								$monument->$key = $value;
							}
							try {
								$monument->save();
							}
							catch (Exception $e) {
								echo $e->getMessage().'<br />';
							}
						}

						// Insert links in database
						foreach ($link AS $key => $url) {
							// Use recursive function (url depth is unknonw, sometimes 5 nodes)
							Importer::monuments_link($monument->id_monument, $url, $key);
						}

						$i++;
					}
				}
			}
		}

		closedir($monuments);

		return $i;
	}

	/**
	 * Recursive function to save links belonging to monuments because
	 * the depth of the url array is unknown and sometimes more than 6,7
	 *
	 * @param int $id_monument
	 * @param string $url
	 * @param string $name
	 */
	public static function monuments_link($id_monument, $url, $name) {
		if (is_array($url)) {
			foreach ($url AS $value) {
				Importer::monuments_link($id_monument, $value, $name);
			}
		}
		else {
			// Look if link is already in database, otherwise insert new one
			$link = ORM::factory('link')->where('url', '=', $url)->find();
			if ($link->url == NULL) {
				$link->url = $url;
				$link->name = $name;
				try {
					$link->save();
				}
				catch (Exception $e) {
					echo $e->getMessage().'<br />';
				}
			}

			// Connect the inserted link to the monument it belongs to if not already done
			$links = DB::select()->from('monument_link')
			->where('id_monument', '=', $id_monument)
			->and_where('id_link', '=', $link->id_link)
			->execute();
			if ($links->count() == 0) {
				$query = DB::insert("monument_link", array(intval($id_monument), intval($link->id_link)));
				var_dump($query);
			}
		}
	}

	/**
	 * Function which extracts tags from monument descriptions
	 * and inserts them in database.
	 *
	 * @return int number of inserted tags
	 */
	public static function tags() {
		// execution can take a long time
		set_time_limit(0);
		// ini_set('memory_limit',0);

		// stopwoorden
		$stopwords = array("aan","af","al","alles","als","altijd","andere","ben","bij","daar","dan","dat","de","der","deze","die","dit","doch","doen","door","dus","een","eens","en","enz","er","etc","ge","geen","geweest","haar","had","heb","hebben","heeft","hem","hen","het","hier","hij ","hoe","hun","iemand","iets","ik","in","is","ja","je ","jouw","jullie","kan","kon","kunnen","maar","me","meer","men","met","mij","mijn","moet","na","naar","niet","niets","nog","nu","of","om","omdat","ons","onze","ook","op","over","reeds","te","ten","ter","tot","tegen","toch","toen","tot","u","uit","uw","van","veel","voor","want","waren","was","wat","we","wel","werd","wezen","wie","wij","wil","worden","zal","ze","zei","zelf","zich","zij","zijn","zo","zonder","zou");

		// total occurrences
		$totaloccurrences = array();

		// number of monuments where the word occurs
		$mixedoccurrences = array();

		// relativity of a word
		$relativeoccurrences = array();

		// keep the original (unedited) tags
		$originalkeywords = array();

		// keep track of in which monuments tags are
		$inmonuments = array();

		// keep track of categories
		$catmonuments = array();

		// collect all monuments
		$monuments = DB::select("description", "name", "id_monument", "id_category")
		->from("monuments")
		->order_by("id_monument", "desc")
		->execute();

		// for each monument
		foreach($monuments as $monument) {
			// find the description, lowercase it, ignore encoding
			if(!isset($monument['description'])) {
				continue;
			}

			// filter unused characters
			$description = strtolower(preg_replace('/[^a-zA-Z0-9\-\_\s]/','',utf8_decode($monument['description'])));
			
			// explode original keywords into array
			$originals = explode(' ',$description);

			// filter stopwords
			$originals = array_diff($originals, $stopwords);

			// explode search keywords into array
			$description = explode(' ',preg_replace('/[^a-zA-Z0-9\s]/','',$description));

			// filter stopwords
			$description = array_diff($description, $stopwords);

			// importance of an occurrence
			$percentage = 1/count($description);

			$tf = array();
			// add occurrence to total and mixed
			foreach($description as $key => $des) {
				$originalkeywords[$des] = $originals[$key];
				$totaloccurrences[$des] = isset($totaloccurrences[$des]) ? ($totaloccurrences[$des] + 1) : 1;
				$tf[$des] = isset($tf[$des]) ? ($tf[$des] + $percentage) : $percentage;
				// keep track of where tags occur
				if(isset($inmonuments[$des])) {
					$inmonuments[$des][$monument['id_monument']] = isset($inmonuments[$des][$monument['id_monument']]) ? ($inmonuments[$des][$monument['id_monument']] + 1) : 1;
				} else {
					$inmonuments[$des] = array($monument['id_monument'] => 1);
				}
			}

			// keep track of uniqueness
			$unique = array();
			foreach($description as $des) {
				$unique[$des] = true;
			}
			foreach($unique as $key => $un) {
				$mixedoccurrences[$key] = isset($mixedoccurrences[$key]) ? ($mixedoccurrences[$key] + 1) : 1;
				$relativeoccurrences[$key] = isset($relativeoccurrences[$key])?
				($relativeoccurrences[$key] + $tf[$key]) / 2 : $tf[$key];
				// categories
				if(!isset($monument['id_category'])) continue;
				if(isset($catmonuments[$key])) {
					$catmonuments[$key][$monument['id_category']] = isset($catmonuments[$key][$monument['id_category']]) ? ($catmonuments[$key][$monument['id_category']] + 1) : 1;
				} else {
					$catmonuments[$key] = array($monument['id_category'] => 1);
				}
			}
		}

		// foreach tag that has been found
		foreach($catmonuments as $key => $catmonument) {
			// we'll check the TFIDF of the tag relative to it's categories
			foreach($catmonument as $cat => &$category) {
				$catmonuments[$key][$cat] = isset($category) && $category > 0 ? ($category / array_sum($catmonument)) * log(19864 / $mixedoccurrences[$key]) : 0;
				//	echo $key.' occurrence: '.$category.', category: '.$cat.', tfidf: '.$cattfidf[$key][$cat].'<br />';

			}
		}


		// id to insert
		$i = 1;
		// sort by total occurrence
		arsort($totaloccurrences);
		// for each word
		foreach($totaloccurrences as $key => $occ) {

			// check if data is set
			if($key == '' || !isset($mixedoccurrences[$key]) OR !isset($totaloccurrences[$key])) continue;

			// check if really relevant
			$jaartal = preg_match('/^[^a-z]+$/', $key) OR preg_match('/^(?=.)(?i)m*(d?c{0,3}|c[dm])(l?x{0,3}|x[lc])(v?i{0,3}|i[vx])$/',$key);
			if($occ < 2
					OR (strlen($key)<5 AND !$jaartal)
					OR (!preg_match('/^[a-z]+$/',$key) AND !$jaartal)
					OR in_array($key,$stopwords)
			) continue;


			// term frequency is saved as mean
			$tf = $relativeoccurrences[$key];

			// inverse document frequency = log(D/D(t))
			$idf = log(25500 / (1 + $mixedoccurrences[$key]));

			// the importance of a word is tf*idf calculated
			$tfidf = $tf * $idf;

			// skip irrelevant words
			if($tfidf==0) continue;
			for($j=1; $j < 15; $j++) {
				if(!isset($catmonuments[$key][$j])) $catmonuments[$key][$j] = 0;
			}

			$sql = "insert into dev_tags values(".$i.",'".$originalkeywords[$key]."',".$mixedoccurrences[$key].",".$tfidf.",".$catmonuments[$key][1].",".$catmonuments[$key][2].",".$catmonuments[$key][3].",".$catmonuments[$key][4].",".$catmonuments[$key][5].",".$catmonuments[$key][6].",".$catmonuments[$key][7].",".$catmonuments[$key][8].",".$catmonuments[$key][9].",".$catmonuments[$key][10].",".$catmonuments[$key][11].",".$catmonuments[$key][12].",".$catmonuments[$key][13].",".$catmonuments[$key][14].");";
			DB::query(Database::INSERT, $sql)->execute();

			/*DB::insert("tags", array($i, $originalkeywords[$key], $mixedoccurrences[$key], $tfidf, $catmonuments[$key][1],
			 $catmonuments[$key][2],
					$catmonuments[$key][3],
					$catmonuments[$key][4],
					$catmonuments[$key][5],
					$catmonuments[$key][6],
					$catmonuments[$key][7],
					$catmonuments[$key][8],
					$catmonuments[$key][9],
					$catmonuments[$key][10],
					$catmonuments[$key][11],
					$catmonuments[$key][12],
					$catmonuments[$key][13],
					$catmonuments[$key][14]
			))->execute();*/

			foreach($inmonuments[$key] as $monumentid => $monumentoccurrence) {
				$sql = "insert into dev_tag_monument values(".$monumentid.",".$i.",".$monumentoccurrence.");";
				DB::query(Database::INSERT, $sql)->execute();
				//DB::insert("tag_monument", array($monumentid, $i, $monumentoccurrence))->execute();
			}

			$i++;
		}

		return $i;
	}

	/**
	 * Function to import MATLAB extracted featurues
	 * in the folder "public/files/feature_extractions/dev_*"
	 */
	public static function features() {
		set_time_limit(0);

		// Data needs to be imported in chunks, step size
		$step = 100;

		// Feature extraction types in array
		$types = array('acq', 'gabor', 'hsv', 'light', 'rgb', 'segment', 'spatial', 'texture');

		// Loop through csv-files containing image features
		// Save maximum and minimum, save values per image
		$img_data = array();
		$upper_bound = $step;

		foreach ($types AS $ftype) {
			$handle[$ftype] = fopen("files/feature_extractions/dev_".$ftype.".txt", "r");
			$columns[$ftype] = fgetcsv($handle[$ftype], 0, ",");
			$data[$ftype] = fgetcsv($handle[$ftype], 0, ",");
			$data[$ftype] = fgetcsv($handle[$ftype], 0, ",");
		}

		$i = 0;
		while ($data[$types[0]] !== FALSE) {
			$id = str_replace(array('O:\photos\\', '.jpg'), '', $data[$types[0]][0]);

			foreach ($types AS $ftype) {
				foreach ($data[$ftype] AS $key => $value) {
					if ($key != 0) {
						$img_data[$id][$columns[$ftype][$key]] = $value;
					}
				}
			}

			foreach ($types AS $ftype) {
				$data[$ftype] = fgetcsv($handle[$ftype], 0, ",");
			}

			if ($i == $upper_bound) {
				Importer::insert_data($img_data, 'photo');
				$img_data = array();
				$upper_bound += $step;
			}

			$i++;
		}

		Importer::insert_data($img_data, 'photo');

		return $i;
	}

	/**
	 * Function to normalize the imported MATLAB features
	 * already put in database with function action_import
	 * 
	 * @param string $type, 'photo' or 'pca'
	 */
	public static function normalize_features($type) {
		// No time limit
		set_time_limit(0);

		// Data needs to be normalized in chunks, step size
		$step = 100;

		// Set first upperbound (= step size)
		$upper_bound = $step;

		// Get all features in table photos
		$features = ORM::factory($type)->list_columns();
		unset($features['id']);
		unset($features['id_monument']);

		// Create select-sql for getting maxima and mima per feature
		$sql = "";
		foreach ($features AS $feature) {
			if (strlen($sql) > 0) {
				//Seperator
				$sql .= ", ";
			}

			$sql .= "MAX(" . $feature['column_name'] . ") AS 'max_" . $feature['column_name'] . "', MIN(" . $feature['column_name'] . ") AS 'min_" . $feature['column_name'] . "'";
		}

		// Select maxima and mima per feature
		$query = DB::select(DB::expr($sql))->from($type.'s')->execute();
		$max_min = $query[0];

		// Set counter and init array
		$i = 0;
		$norm = array();

		// For each photo in the table, normalize data
		$photos = DB::select('*')->from($type.'s')->execute();
		foreach ($photos AS $photo) {
			$id_photo = $photo['id'];
			unset($photo['id']);
			unset($photo['id_monument']);

			// Put normalized data in array with formula (normalized) = (value_of_photo - feature_minimum) / (feature_maximum - feature_minimum)
			foreach ($photo AS $type1 => $value) {
				$min = $max_min['min_'.$type1];
				$max = $max_min['max_'.$type1];
					
				if ($max - $min != 0) {
					$norm[$id_photo][$type1] = ($value - $min) / ($max - $min);
				}
				else {
					$norm[$id_photo][$type1] = ($value - $min) / 0.000000000001;
				}
			}

			// If upperbound is reached, increasee upper_bound, imort data and clear array
			if ($i == $upper_bound) {
				Importer::update_data($norm, $type);
				$norm = array();
				$upper_bound += $step;
			}

			$i++;
		}

		// Import last data
		Importer::update_data($norm, $type);
	}

	/**
	 * Function to import data chunks into photo_table
	 * @param array $img_data
	 * @param string $type 'photo' or 'pca'
	 */
	public static function update_data($img_data, $type) {
		// Open database isntance for beginning and committing
		$db = Database::instance();

		// Begin transaction
		$db->begin();

		foreach ($img_data AS $id => $values) {
			try {
				$query = DB::update($type.'s')->set($values)->where('id', '=', $id)->execute();
			}
			catch (Exception $e) {
				echo $e->getMessage().'<br />';
			}
		}

		// Commit transaction
		$db->commit();
	}

	/**
	 * Function to import data chunks into photo_table
	 * @param array $img_data
	 * param string $type 'photo' or 'pca'
	 */
	public static function insert_data($img_data, $type) {
		// Open database isntance for beginning and committing
		$db = Database::instance();

		// Begin transaction
		$db->begin();

		// Create array with column names
		$columns = array();
		foreach ($img_data AS $mid => $values) {
			foreach ($values AS $type1 => $value) {
				$columns[] = $type1;
			}
			$columns[] = 'id_monument';

			break;
		}

		// Create sql for a query with multiple inserts
		// Will look like INSERT INTO photos (column1, column2) VALUES (value1_1, value2_1), (value1_2, value2_2), (value1_3, value2_3)......
		$sql = "INSERT INTO ".Kohana::$config->load('database.default.table_prefix').$type."s (`".implode('`, `', $columns)."`) VALUES ";

		$i = false;
		foreach ($img_data AS $mid => $values) {
			if (!array_search('NaN', $values)) {
				if ($i) {
					$sql .= ", ";
				}
				$i = true;

				$values['id_monument'] = $mid;
				$sql .= "(".implode(', ', $values).")";
			}
		}

		// Execute insert-query
		$query = DB::query(Database::INSERT, $sql)->execute();

		// Commit transaction
		$db->commit();
	}

	/**
	 * Function to import pca feature data
	 */
	public static function pca_features() {
		set_time_limit(0);

		// Data needs to be imported in chunks, step size
		$step = 100;

		// Feature extraction types in array
		$types = array('color', 'composition', 'orientation', 'texture');;

		// Loop through csv-files containing image features
		// Save maximum and minimum, save values per image
		$img_data = array();
		$upper_bound = $step;

		foreach ($types AS $ftype) {
			$handle[$ftype] = fopen("files/feature_extractions/pca/dev_pca_".$ftype.".score.txt", "r");
			$columns[$ftype] = fgetcsv($handle[$ftype], 0, ",");
			$data[$ftype] = fgetcsv($handle[$ftype], 0, ",");
		}

		$i = 0;
		while ($data[$types[0]] !== FALSE) {
			$id = str_replace(array('O:\photos\\', '.jpg'), '', $data[$types[0]][0]);

			foreach ($types AS $ftype) {
				foreach ($data[$ftype] AS $key => $value) {
					if ($key != 0) {
						$img_data[$id][$ftype.'_'.$columns[$ftype][$key]] = $value;
					}
				}
			}

			foreach ($types AS $ftype) {
				$data[$ftype] = fgetcsv($handle[$ftype], 0, ",");
			}

			// If upperbound is reached, increasee upper_bound, imort data and clear array
			if ($i == $upper_bound) {
				Importer::insert_data($img_data, 'pca');
				$img_data = array();
				$upper_bound += $step;
			}

			$i++;
		}

		Importer::insert_data($img_data, 'pca');

		return $i;
	}
	
	/**
	 * Import categories based on textual analysis
	 */
	public static function categories() {
		// can take a long time
		set_time_limit(0);
		
		// select all uncategorized monuments
		$monuments = ORM::factory('monument')->where('id_category','is',null)->or_where('id_category','=',12)->find_all();
		$i = 0;
		foreach($monuments as $monument) {
		
			$category = $monument->extractCategory();
		
			// save the extracted category to the database
			$monument->id_category = $category;
			$monument->category_extracted = 1;
			if($category > 0) {
				$i++;
				$monument->save();
			}
		}
		
		return $i;
	}
}
?>