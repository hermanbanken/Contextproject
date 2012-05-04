<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Features extends Controller_Abstract_Object {

	protected static $entity = 'monument';

	/**
	 * action_index
	 * Action for importing image features
	 */
	public function action_index() {
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