<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Features extends Controller_Abstract_Object {

	protected static $entity = 'monument';

	public function action_index() {
		$v = View::factory('default');
		
		set_time_limit(0);

		// Feature extraction types in array
		$types = array('acq', 'gabor', 'hsv', 'light', 'rgb', 'segment', 'spatial', 'texture');

		// Loop through csv-files containing image features
		// Save maximum and minimum, save values per image
		$img_data = array();
		foreach ($types AS $ftype) {
			if (($handle = fopen("files/feature_extractions/dev1_".$ftype.".txt", "r")) !== FALSE) {
				$columns = array();
				while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
					if ($data[0] == 'filename') {
						$columns = $data;
					}
					elseif ($data[0] != 'string') {
						$id = str_replace(array('C:\wamp\www\public\photos\\', '.jpg'), '', $data[0]);

						foreach ($data AS $key => $value) {
							if ($key != 'filename') {
								$img_data[$columns[$key]][$id] = $data[$key];
							}
						}
					}
				}
			}
		}

		// Linearly normalize data
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
		
		$v->set('title', 'Feature Import');
		$v->set('text', 'Er zijn '.$i.' foto-features geïmporteerd.');

		$this->template->body = $v;
	}

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
	 * action_index
	 * Action for importing image features after pca
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

	public function action_check() {
		$v = View::factory('features');

		$monuments = ORM::factory('monument')
		->join('photos')->on('photos.id_monument', '=', 'monument.id_monument')
		->where('id_category', '!=', 0)
		->and_where('id_category', '!=', NULL)
		->find_all();

		set_time_limit(0);
		$fp = fopen('data_newextract_median.txt', 'w+');

		foreach ($monuments AS $monument) {
			fwrite($fp, $monument->id_monument.",".$monument->extractcategory2().",".$monument->id_subcategory."\n");
		}

		$this->template->body = $v;
	}
}
?>