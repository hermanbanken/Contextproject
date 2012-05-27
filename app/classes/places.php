<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Google Places.
 *
 * Get recommendations for cafe's, bars around the monuments.
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class Places {
    /**
     * Google Places API KEY
     */
	const KEY = 'AIzaSyDil96bzN3gQ6LToMoz8ib0Lz39BYmTfko';
	
	/**
	 * Function to get places near monument from Google Places
	 * @param int $id_monument
	 * @param string $categories
	 * @param string $rankby
	 * @param double $radius
	 * @param boolean $sensor
	 * @param int $limit
	 * @return array with monuments => name, website, vicinity, rating, distance, longitude, latitude
	*/
	public static function get_places($id_monument, $categories, $rankby, $radius, $sensor, $limit) {			
		$monument = ORM::factory('monument', $id_monument);

		$response = file_get_contents(
			"https://maps.googleapis.com/maps/api/place/search/json".URL::query( array(
				"location" => $monument->lng.','.$monument->lat,
				"rankby" => $rankby,
				"types" => $categories,
				"sensor" => $sensor ? 'true' : 'false',
				"key" => self::KEY,
				"radius" => $rankby != 'distance' ? $radius : null,
			))
		);
		
		$places = @json_decode($response);
		
		$list = array();
		$ratings = array();
		foreach ($places->results as $place){
			$loc = $place->geometry->location;
			$venue = array(
				"details" => "https://maps.googleapis.com/maps/api/place/details/json".
					URL::query( array(
						"reference" => $place->reference,
						"sensor" => false,
						"key" => self::KEY,
					)),
				"distance" => self::distance(
					$loc->lat, $loc->lng, 
					$monument->lng, $monument->lat, 'K'
					),
				"rating" => @$place->rating,
				"vicinity" => $place->vicinity,
				"name" => $place->name,
			);
			
			$rating = @$place->rating;
			if ($rating == NULL) {
				$rating = 0;
			}
			
			$ratings[] = (5 - $rating);
			$list[] = $venue;
		}
		
		// Sort list-array by rating
		array_multisort($ratings, $list);
		
		// Limit array
		$limit_list = array();
		
		$i = 0;
		foreach ($list AS $venue) {
			$limit_list[] = $venue;
			$i++;
			if ($i == $limit) break;
		}
		
		return $limit_list;
	}

	/**
	 * Function to calculate distance between two positions (longitude / latitude)
	 * @param double Latitude of A
	 * @param double Longitude of A
	 * @param double Latitude of B
	 * @param double Longitude of B
	 * @param string K or N, convert the distance to the correct metric system
	 * @return double Distance in system $unit
	 */
	private static function distance($lat1, $lon1, $lat2, $lon2, $unit) {

		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
}