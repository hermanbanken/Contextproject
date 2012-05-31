<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Features extends Controller_Abstract_Object {

	protected static $entity = 'monument';

	public function action_index() {
		$v = View::factory('default');

// 				$monument = ORM::factory('monument')->order_by(DB::expr('RAND()'))->find();
		$monument = ORM::factory('monument', 10048);
		while ($monument->getphoto()->id_monument == NULL) $monument = ORM::factory('monument')->order_by(DB::expr('RAND()'))->find();

		echo '<table><tr><td>'.$monument->id_monument.'</td><td colspan="8"><img src="'.$monument->getphoto()->url().'" style="max-width: 300px; max-height: 300px;" /></td></tr>';

		$features = $monument->getphoto()->features_cat('color');
		$monuments = $monument->similars(8, $features);
		echo '<tr><td>acq, gabor, hsv, light, rgb, segment, spatial, texture (all)</td>';
		foreach ($monuments AS $monument) {
			echo '<td><img src="'.$monument->getphoto()->url().'" style="max-width: 150px; max-height: 150px;" /></td>';
		}
		echo '</tr>';

		echo '</table>';

		$v->set('title', 'Test knaap');
		$v->set('text', 'Test knaap');

		$this->template->body = $v;
	}

	/**
	 * Function to import MATLAB extracted featurues
	 * in the folder "public/files/feature_extractions/dev_*"
	 */
	public function action_import() {
		$v = View::factory('default');

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
				$this->insert_data($img_data);
				$img_data = array();
				$upper_bound += $step;
			}

			$i++;
		}

		$this->insert_data($img_data);
		$img_data = array();

		$v->set('title', 'Feature Import');
		$v->set('text', 'Er zijn '.$i.' foto-features geimporteerd.');

		$this->template->body = $v;
	}

	/**
	 * Function to normalize the imported MATLAB features
	 * already put in database with function action_import
	 */
	public function action_normalize1() {
		$v = View::factory('default');

		// No time limit
		set_time_limit(0);

		// Data needs to be normalized in chunks, step size
		$step = 100;

		// Set first upperbound (= step size)
		$upper_bound = $step;

		// Get all features in table photos
		$features = ORM::factory('photo')->list_columns();
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
		$query = DB::select(DB::expr($sql))->from('photos')->execute();
		$max_min = $query[0];

		// Set counter and init array
		$i = 0;
		$norm = array();

		// For each photo in the table, normalize data
		$photos = DB::select('*')->from('photos')->execute();
		foreach ($photos AS $photo) {
			$id_photo = $photo['id'];
			unset($photo['id']);
			unset($photo['id_monument']);

			// Put normalized data in array with formula (normalized) = (value_of_photo - feature_minimum) / (feature_maximum - feature_minimum)
			foreach ($photo AS $type => $value) {
				$min = $max_min['min_'.$type];
				$max = $max_min['max_'.$type];
					
				if ($max - $min != 0) {
					$norm[$id_photo][$type] = ($value - $min) / ($max - $min);
				}
				else {
					$norm[$id_photo][$type] = ($value - $min) / 0.000000000001;
				}
			}

			// If upperbound is reached, increasee upper_bound, imort data and clear array
			if ($i == $upper_bound) {
				$this->update_data($norm);
				$norm = array();
				$upper_bound += $step;
			}

			$i++;
		}

		// Import last data
		$this->update_data($norm);

		$v->set('title', 'Normalize Features');
		$v->set('text', 'Er zijn '.$i.' foto-features genormaliseerd.');

		$this->template->body = $v;
	}

	/**
	 * Function to import data chunks into photo_table
	 * @param array $img_data
	 */
	public static function update_data($img_data) {
		// Open database isntance for beginning and committing
		$db = Database::instance();

		// Begin transaction
		$db->begin();

		foreach ($img_data AS $id => $values) {
			try {
				$query = DB::update('photos')->set($values)->where('id', '=', $id)->execute();
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
	 */
	public static function insert_data($img_data) {
		// Open database isntance for beginning and committing
		$db = Database::instance();

		// Begin transaction
		$db->begin();

		// Create array with column names
		$columns = array();
		foreach ($img_data AS $mid => $values) {
			foreach ($values AS $type => $value) {
				$columns[] = $type;
			}
			$columns[] = 'id_monument';

			break;
		}

		// Create sql for a query with multiple inserts
		// Will look like INSERT INTO photos (column1, column2) VALUES (value1_1, value2_1), (value1_2, value2_2), (value1_3, value2_3)......
		$sql = "INSERT INTO ".Kohana::$config->load('database.default.table_prefix')."photos (`".implode('`, `', $columns)."`) VALUES ";

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
	 * Function to create SQL for the feature-columns
	 * From the files in "files/feature_extractions/dev_*.txt"
	 */
	public function action_sql() {
		$v = View::factory('features');

		// Feature extraction types in array
		$types = array('acq', 'gabor', 'hsv', 'light', 'rgb', 'segment', 'spatial', 'texture');

		// Loop through csv-files containing image features
		// Save maximum and minimum, save values per image

		$sql = "CREATE TABLE IF NOT EXISTS `%s` (
		`id` int(10) unsigned AUTO_INCREMENT,
		`id_monument` int(10) unsigned NOT NULL,";

		foreach ($types AS $ftype) {
			if (($handle = fopen("files/feature_extractions/dev_".$ftype.".txt", "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
					foreach ($data AS $column) {
						if ($column != 'filename') {
							$sql .= "
							`".$column."` double NULL,";
						}
					}
					break;
				}
			}
		}

		$sql .= "
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

		echo '<pre>'.$sql.'</pre>';

		$this->template->body = $v;
	}
}
?>