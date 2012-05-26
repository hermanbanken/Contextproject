<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Features extends Controller_Abstract_Object {

	protected static $entity = 'monument';

	/**
	 * Function to import MATLAB extracted featurues
	 * in the folder "public/files/feature_extractions/dev1_*"
	 */
	public function action_index() {
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
				$this->import_data($img_data);
				$img_data = array();
				$upper_bound += $step;
			}

			$i++;
		}

		$this->import_data($img_data);
		$img_data = array();

		$v->set('title', 'Feature Import');
		$v->set('text', 'Er zijn foto-features geïmporteerd.');

		$this->template->body = $v;
	}

	/**
	 * Function to import MATLAB extracted featurues
	 * in the folder "public/files/feature_extractions/dev1_*"
	 */
	public function action_normalize1() {
		$v = View::factory('default');

		set_time_limit(0);

		// Data needs to be imported in chunks, step size
		$step = 100;

		// Loop through csv-files containing image features
		// Save maximum and minimum, save values per image
		$upper_bound = $step;

		$columnsResult = ORM::factory('photo')->list_columns();
		unset($columnsResult['id']);
		unset($columnsResult['id_monument']);
		$maxValsSelect = "";
		foreach ($columnsResult AS $aColumn) {
			if (strlen($maxValsSelect) > 0) {
				//Seperator
				$maxValsSelect .= ", ";
			}

			$maxValsSelect .= "MAX(" . $aColumn['column_name'] . ") AS 'max_" . $aColumn['column_name'] . "', MIN(" . $aColumn['column_name'] . ") AS 'min_" . $aColumn['column_name'] . "'";
		}

		$query = DB::select(DB::expr($maxValsSelect))->from('photos')->execute();
		$max_min = $query[0];

		//process the results....

		$photos = DB::select('*')->from('photos')->execute();
		$i = 0;
		$norm = array();
		foreach ($photos AS $photo) {
			$id_photo = $photo['id'];
			unset($photo['id']);
			unset($photo['id_monument']);

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
		
			if ($i == $upper_bound) {
				$this->import_data($norm, true);
				$norm = array();
				$upper_bound += $step;
			}
			
			$i++;
		}
		
		$this->import_data($norm, true);
		
		/*
		 unset($img_data);

		$query = DB::delete('photos')->where('1', '=', '1');
		$i = 0;
		$img_data = array();
		foreach ($norm AS $mid => $types) {
		$img_data[$mid] = $types;

		if ($i == $upper_bound) {
		$this->import_data($img_data);
		$img_data = array();
		$upper_bound += $step;
		}

		$i++;
		}

		$this->import_data($img_data);
		$img_data = array();
		*/
		$v->set('title', 'Feature Import');
		$v->set('text', 'Er zijn '.$i.' foto-features geïmporteerd.');

		$this->template->body = $v;
	}

	public static function import_data($img_data, $nan = false) {
		$db = Database::instance();
		// Start the transactions
		$db->begin();

		$columns = array();
		foreach ($img_data AS $mid => $values) {
			foreach ($values AS $type => $value) {
				$columns[] = $type;
			}
			$columns[] = 'id_monument';

			break;
		}

		$sql = "INSERT INTO ".Kohana::$config->load('database.default.table_prefix')."photos (`".implode('`, `', $columns)."`) VALUES ";

		$i = false;
		foreach ($img_data AS $mid => $values) {
			if (!array_search('NaN', $values) || $nan) {
				if ($i) {
					$sql .= ", ";
				}
				$i = true;

				$values['id_monument'] = $mid;
				$sql .= "(".implode(', ', $values).")";
			}
		}
		//   ('Helen', 24),
		//   ('Katrina', 21),
		//   ('Samia', 22),
		//   ('Hui Ling', 25),
		//   ('Yumie', 29)";

		$query = DB::query(Database::INSERT, $sql)->execute();

		$db->commit();
	}

	public static function import_data1($img_data) {
		$db = Database::instance();
		// Start the transactions
		$db->begin();

		foreach ($img_data AS $mid => $values) {
			$values['id_monument'] = $mid;

			$columns = array();
			foreach ($values AS $type => $value) {
				$columns[] = $type;
			}

			try {
				$query = DB::insert('photos', $columns)->values($values)->execute();
			}
			catch (Exception $e) {
				echo $e->getMessage().'<br />';
			}
		}

		$db->commit();
	}

	public static function normalize_data($img_data) {

		// Linearly normalize data
		/*
		 $norm = array();
		foreach ($img_data AS $type => $vals) {
		$max = max($img_data[$type]);
		$min = min($img_data[$type]);
		foreach ($vals AS $mid => $value) {
		if ($max - $min != 0) {
		$norm[$mid][$type] = ($value - $min) / ($max - $min);
		}
		else {
		$norm[$mid][$type] = ($value - $min) / 0.000000000001;
		}
		}
		}

		// Loop through normalized data, update or insert photo
		$i = 0;
		foreach ($norm AS $mid => $types) {
		// Search for photo
		$photo = ORM::factory('photo')->where('id_monument', '=', $mid)->find();
		if ($photo->id_monument == NULL) {
		// Bind right information
		$photo->id_monument = $mid;
		foreach ($types AS $type => $value) {
		$photo->$type = $value;
		}

		// Save photo
		$photo->save();
		$i++;
		}
		}
		*/
	}

	/**
	 * Function to create SQL for the feature-columns
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
			if (($handle = fopen("files/feature_extractions/dev1_".$ftype.".txt", "r")) !== FALSE) {
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


	/**
	 * Function for importing PCA features
	 * from the folder "public/files/feature_extractions/dev1_pca_*"
	 */
	public function action_pca() {
		$v = View::factory('features');

		// Feature extraction types in array
		$types = array('all', 'color', 'composition', 'orientation', 'texture');

		// Create empty arrays to be used
		$pca = array();
		$max = array();
		$min = array();

		// Loop through csv-files containing image features
		// Save maximum and minimum, save values per image
		foreach ($types AS $ftype) {
			if (($handle = fopen("files/feature_extractions/dev1_pca_".$ftype.".score.txt", "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					if (!isset($pca[$ftype])) $pca[$ftype] = array();

					if ($data[0] != 'filename') {
						if (!isset($max[$ftype])) $max[$ftype] = $data[1];
						if (!isset($min[$ftype])) $min[$ftype] = $data[1];

						$max[$ftype] = max($max[$ftype], $data[1]);
						$min[$ftype] = min($min[$ftype], $data[1]);

						$id = str_replace(array('C:\wamp\www\public\photos\\', '.jpg'), '', $data[0]);

						$pca[$ftype][$id] = $data[1];
					}
				}
			}
		}

		// Linearly normalize data
		$norm = array();
		foreach ($pca AS $ftype => $vals) {
			foreach ($vals AS $mid => $val) {
				$norm[$mid][$ftype] = ($val - $min[$ftype]) / ($max[$ftype] - $min[$ftype]);
			}
		}

		// Loop through normalized data, update or insert photo
		foreach ($norm AS $mid => $types) {
			// Search for photo
			$photo = ORM::factory('photo')->where('id_monument', '=', $mid)->find();

			// Bind right information
			$photo->id_monument = $mid;
			$photo->total = $types['all'];
			$photo->color = $types['color'];
			$photo->composition = $types['composition'];
			$photo->orientation = $types['orientation'];
			$photo->texture = $types['texture'];

			// Save photo
			$photo->save();
		}

		$this->template->body = $v;
	}
}
?>