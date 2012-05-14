<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Google Places
 */
class Places {
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

		$url = 'https://maps.googleapis.com/maps/api/place/search/json?location='.$monument->lng.','.$monument->lat.'&rankby='.$rankby.'&types='.$categories.'&sensor='.($sensor ? 'true' : 'false').'&key='.self::KEY;
		if ($rankby != 'distance') {
			$url .= '&radius='.$radius;
		}

		$places = json_decode(file_get_contents($url));

		$i = 1;
		$return = array();
		foreach ($places->results AS $place) {
			$details = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?reference='.$place->reference.'&sensor=false&key='.self::KEY));

			$details = $details->result;

			$longitude = $details->geometry->location->lng;
			$latitude = $details->geometry->location->lat;
			if (isset($place->name) && isset($place->rating) && isset($place->vicinity) && isset($details->website)) {
				$return[] = array('longitude' => $longitude, 'latitude' => $latitude, 'distance' => Places::distance($latitude, $longitude, $monument->lng, $monument->lat, 'K'), 'name' => $place->name, 'rating' => $place->rating, 'vicinity' => $place->vicinity, 'website' => $details->website);

				$i++;
			}

			if ($i > $limit) {
				break;
			}
		}

		return $return;
	}

	/**
	 * Function to calculate distance between two positions (longitude / latitude)
	 * @param double $lat1
	 * @param double $lon1
	 * @param double $lat2
	 * @param double $lon2
	 * @param string $unit
	 * @return double
	 */
	public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {

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