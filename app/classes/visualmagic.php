<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for VisualMagic
 *
 * Get visually similar monuments
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class VisualMagic {
	/**
	 * Function to get visually similar monuments
	 * by getting the monuments with the smallest
	 * euclidian distance between the features
	 * of the photos of the monuments.
	 *
	 * @param monument $monument
	 * @param int $limit;
	 * @param array with features $features;
	 * @return array with monuments
	 */
	public static function similars($monument, $limit = 12, $features = false) {
		// Set initial array
		$monuments = array();

		// Check if photo exists (some photos are missing)
		$photo = $monument->getphoto();
		if ($photo->id_monument != NULL) {
			// Set euclidian select part for query
			$euclidian = 'sqrt(';
			$i = 0;

			// If features are set, use them, otherwise get all features
			if (!$features) {
				$features = $photo->features();
			}

			// Loop through features and complete euclidian select part for query
			foreach ($features AS $key => $value) {
				if ($key != 'id' && $key != 'id_monument') {
					if ($i != 0) $euclidian .= ' + ';
					$euclidian .= 'POW("'.$key.'" - '.$value.', 2)';
					$i++;
				}
			}
			$euclidian .= ')';

			// Find monuments based on euclidian distance
			$monuments = ORM::factory('monument')
			->select('*', array($euclidian, 'p'))
			->join('photos')->on('photos.id_monument', '=', 'monument.id_monument')
			->order_by('p', 'asc')
			->where('monument.id_monument', '!=', $monument->id_monument)
			->limit($limit)
			->find_all();
		}

		// Return monumentlist
		return $monuments;
	}

	/**
	 * Function to get visually similar monuments
	 * by getting the monuments with the smallest
	 * euclidian distance between the features
	 * of the photos of the monuments.
	 *
	 * @param monument $monument
	 * @param int $limit;
	 * @param array with features $features;
	 * @return array with monuments
	 */
	public static function similars_pca($monument, $limit = 12, $features = false) {
		// Set initial array
		$monuments = array();

		// Check if photo exists (some photos are missing)
		$pca = ORM::factory('PCA')->where('id_monument', '=', $monument->id_monument)->find();
		if ($pca->id_monument != NULL) {
			// Set euclidian select part for query
			$euclidian = 'sqrt(';
			$i = 0;

			// If features are set, use them, otherwise get all features
			if (!$features) {
				$features = $pca->features();
			}

			// Loop through features and complete euclidian select part for query
			foreach ($features AS $key => $value) {
				if ($key != 'id' && $key != 'id_monument') {
					if ($i != 0) $euclidian .= ' + ';
					$euclidian .= 'POW("'.$key.'" - '.$value.', 2)';
					$i++;
				}
			}
			$euclidian .= ')';
			
			// Find monuments based on euclidian distance
			$monuments = ORM::factory('monument')
			->select('*', array($euclidian, 'p'))
			->join('pcas')->on('pcas.id_monument', '=', 'monument.id_monument')
			->order_by('p', 'asc')
			->where('monument.id_monument', '!=', $monument->id_monument)
			->limit($limit)
			->find_all();
		}

		// Return monumentlist
		return $monuments;
	}
}