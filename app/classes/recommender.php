<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Recommender
 *
 * Recommend monuments depedend on user or monument
 * @package CultuurApp
 * @category Helpers
 * @author Rutger Plak
 */
class Recommender {
	public static function recommend($limit) {
		// Get trackers of user
		$tracker = ORM::factory('tracker')->tracker();
		$monuments = $tracker->monuments();

		$trackers = ORM::factory('tracker')->find_all();
		$similars = array();
		$max_score = -1;
		foreach ($trackers AS $atracker) {
			$amonuments = $atracker->monuments();
			$score = self::matches($monuments, $amonuments);
			if ($score > $max_score) {
				$max_score = $score;
				$similars = $amonuments;
			}
		}

		$ids = self::removematches($monuments, $similars);
		$order = '';
		if (count($ids) != 0) {
			// Make sure order is always different
			shuffle($ids);
			
			// Set up order-field
			$order = 'FIELD(id_monument';
			foreach ($ids AS $value) {
				$order .= ','.$value;
			}
			$order .= ')';
		}

		// Build query
		$monuments = ORM::factory('monument');

		if ($order != '') {
			$monuments = $monuments->order_by(DB::expr($order), 'desc');
		}

		$monuments = $monuments->limit($limit);
		$monuments = $monuments->find_all();
		
		return $monuments;
	}

	public static function removematches($original, $new) {
		foreach ($original AS $id) {
			$key = array_search($id, $new);
			if ($key) {
				unset($new[$key]);
			}
		}

		return $new;
	}

	public static function matches($arr1, $arr2) {
		$score = 0;
		foreach ($arr1 AS $id) {
			if (array_search($id, $arr2)) {
				$score++;
			}
		}

		return $score;
	}

	// 	public static function recommend($monument, $limit) {
	// 		// Get trackers and count trackers that are connected to this monument
	// 		$trackers = DB::select('id_tracker', array(DB::expr('COUNT(id_tracker)'), 'count'))
	// 		->from('logs_monuments')
	// 		->join('logs')->on('logs.id_log', '=', 'logs_monuments.id_log')
	// 		->where('id_monument', '=', $monument->id_monument)
	// 		->group_by('id_tracker')
	// 		->execute()
	// 		->as_array();

	// 		// For each tracker, get all other monuments and count monuments (except monument which is selected)
	// 		$recommends = array();
	// 		foreach ($trackers AS $tracker) {
	// 			$monuments_query = DB::select('id_monument', array(DB::expr('COUNT(id_monument)'), 'count'))
	// 			->from('logs_monuments')
	// 			->join('logs')->on('logs.id_log', '=', 'logs_monuments.id_log')
	// 			->where('id_tracker', '=', $tracker['id_tracker'])
	// 			->where('id_monument', '!=', $monument->id_monument)
	// 			->order_by('count', 'desc')
	// 			->limit($limit * 5)
	// 			->group_by('id_monument')
	// 			->execute()
	// 			->as_array();

	// 			$recommends[$tracker['id_tracker']]['count'] = $tracker['count'];
	// 			foreach ($monuments_query AS $amonument) {
	// 				$recommends[$tracker['id_tracker']]['monuments'][$amonument['id_monument']]['count'] = $amonument['count'];
	// 			}
	// 		}

	// 		// For each tracker, each monument, count score (number of trackers * number of monuments)
	// 		$scores = array();
	// 		foreach ($recommends AS $id_tracker => $tracker) {
	// 			if (isset($tracker['monument'])) {
	// 				foreach ($tracker['monuments'] AS $id_monument => $monument) {
	// 					$scores[$id_tracker][$id_monument] = $tracker['count'] + $monument['count'];
	// 				}
	// 			}
	// 		}

	// 		// Count score for each monument alone (combine trackers)
	// 		$fscores = array();
	// 		foreach ($scores AS $tracker => $monuments) {
	// 			foreach ($monuments AS $id_monument => $score) {
	// 				if (!isset($fscores[$id_monument])) $fscores[$id_monument] = 0;
	// 				$fscores[$id_monument] += $score;
	// 			}
	// 		}

	// 		// Sort scores (keep keys)
	// 		asort($fscores);

	// 		if (count($fscores) != 0) {
	// 			// Set up order-field
	// 			$order = 'FIELD(id_monument';
	// 			foreach ($fscores AS $key => $foo) {
	// 				$order .= ','.$key;
	// 			}
	// 			$order .= ')';

	// 			// Build query
	// 			$monuments = ORM::factory('monument');

	// 			if ($order != '') {
	// 				$monuments = $monuments->order_by(DB::expr($order), 'desc');
	// 			}

	// 			$monuments = $monuments->limit($limit)
	// 			->find_all();
	// 		}
	// 		else {
	// 			// Find visually similar
	// 			$pca = ORM::factory('pca')->where('id_monument', '=', $monument->id_monument)->find();
	// 			$monuments = $monument->visuallySimilars(5, $pca->features(), true);
	// 		}

	// 		return $monuments;
	// 	}
}
?>