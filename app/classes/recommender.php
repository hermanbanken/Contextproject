<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Recommender
 *
 * Recommend monuments depedend on user or monument
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class Recommender {
	/**
	 * Get recommended monuments based on user history
	 *
	 * @param int $limit
	 * @return monuments $monuments
	 */
	public static function recommend($limit) {
		// Get tracker of user
		$utracker = ORM::factory('tracker')->tracker();
		
		// Get similar trackers
		$similartrackers = $utracker->similars();
		
		// If tracker is found, select it
		$similartracker = ORM::factory('tracker');
		if (count($similartrackers) != 0) {
			$similartracker = $similartrackers[0]['tracker'];
		}
		
		// Remove matches
		$ids = self::removematches($utracker->monuments(), $similartracker->monuments());
		
		// Get monuments
		$monuments = self::query($ids, $limit);
		
		// Return monuments and tracker
		return array('monuments' => $monuments, 'tracker' => $similartracker);
	}

	/**
	 * Get recommended monuments for a monument
	 * based on all users history
	 *
	 * @param monument $monument
	 * @param int $limit
	 */
	public static function recommend_monument($monument, $limit) {
		// Get tracker of  user
		$utracker = ORM::factory('tracker')->tracker();
		
		// Get similar trackers
		$similartrackers = $utracker->similars();
		
		// Check if monument is found in trackers
		foreach ($similartrackers AS $key => $similartracker) {
			if (!in_array($monument->id_monument, $similartracker['tracker']->monuments())) {
				unset($similartrackers[$key]);
			}
		}
		
		// Get first similar tracker with monument in it
		$similartracker = ORM::factory('tracker');
		foreach ($similartrackers AS $similartracker) {
			$similartracker = $similartracker['tracker'];
			break;
		}
		
		// Remove matches
		$ids = self::removematches($utracker->monuments(), $similartracker->monuments());
		
		// Get monuments
		$monuments = self::query($ids, $limit);
		
		// Return monuments and tracker
		return array('monuments' => $monuments, 'tracker' => $similartracker);
	}

	/**
	 * Get recommended keywords based on keywords
	 *
	 * @param monument $monument
	 * @param int $limit
	 */
	public static function recommend_keywords($keywords, $limit) {
		$atracker = ORM::factory('tracker')->tracker();

		$recommendations = array();
		$keywords = explode(' ', $keywords);
		
		if (count($keywords) > 0) {
			foreach ($keywords AS $keyword) {
				$trackers = DB::select('id_tracker')
				->distinct(true)
				->from('logs_keywords')
				->join('logs')->on('logs.id_log', '=', 'logs_keywords.id_log')
				->where('value', '=', $keyword)
				->where('id_tracker', '!=', $atracker->id_tracker)
				->order_by(DB::expr('RAND()'))
				->execute();

				foreach ($trackers AS $tracker) {
					$tracker = ORM::factory('tracker', $tracker['id_tracker']);

					$akeywords = $tracker->keywords();
					
					foreach ($akeywords AS $akeyword) {
						if (!in_array($akeyword, $recommendations) && !in_array($akeyword, $keywords)) {
							$recommendations[] = $akeyword;
						}
					}
				}
			}
		}

		shuffle($recommendations);

		$return = array();
		$i = 0;
		foreach ($recommendations AS $keyword) {
			$return[] = $keyword;
			$i++;
			if ($i == $limit) break;
		}

		return $return;
	}

	/**
	 * Create ordered query based on an array of ids and limit
	 *
	 * @param array $ids
	 * @param int $limit
	 */
	public static function query($ids, $limit) {
		// Create order for query
		$order = '';
		if (count($ids) != 0) {
			// Set up order-field
			$order = 'FIELD(id_monument';
			foreach ($ids AS $value) {
				$order .= ','.$value;
			}
			$order .= ')';
		}

		// Build query
		$monuments = ORM::factory('monument');

		// If order is set, use order, otherwise randomize
		if ($order != '') {
			$monuments = $monuments->order_by(DB::expr($order), 'desc');
		}
		else {
			$monuments = $monuments->order_by(DB::expr('RAND()'));
		}

		// Add limit and find monuments
		$monuments = $monuments->limit($limit);
		$monuments = $monuments->find_all();

		// Return monuments
		return $monuments;
	}

	/**
	 * Remove matches of array $new
	 * and return $new array
	 *
	 * @param array $original
	 * @param arary $new
	 */
	public static function removematches($original, $new) {
		// Loop through original and remove matches in new
		foreach ($original AS $id) {
			$key = array_search($id, $new);
			if ($key) {
				unset($new[$key]);
			}
		}

		// Return new without matching elements
		return $new;
	}

	/**
	 * Number of matches between two strings
	 *
	 * @param array $arr1
	 * @param array $arr2
	 */
	public static function matches($arr1, $arr2) {
		// Standard score is zero
		$score = 0;

		// For each match between two arrays, increment score
		foreach ($arr1 AS $id) {
			if (array_search($id, $arr2)) {
				$score++;
			}
		}

		// Return final score
		return $score;
	}
}
?>