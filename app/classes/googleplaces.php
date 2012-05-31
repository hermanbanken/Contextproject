<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Google Places.
 *
 * Get recommendations for cafe's, bars around the monuments.
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class GooglePlaces {
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
	public static function places($monument, $categories, $rankby, $radius, $sensor, $limit) {
		// Get cached places (only places that are not too old, max is 10 days)
		$places_orm_query = ORM::factory('place')
		->where('id_monument', '=', $monument->id_monument)
		->and_where('categories', '=', $categories)
		->and_where('cachedOn', '>', date('Y-m-d H:i:s', mktime(0, 0, 0, date('n'), date('j'), date('Y')) - 10 * 24 * 60 * 60))
		->order_by('rating', 'desc')
		->limit($limit);
		
		// Execute query (reset = false, so we can use it at later time)
		$places_orm = $places_orm_query->reset(false)->find_all();

		// Check if there are cached forecasts
		if ($places_orm->count() == 0) {
			// Clear cache
			DB::delete('places')->where('id_monument', '=', $monument->id_monument)->and_where('categories', '=', $categories)->execute();

			// Get places from google places
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

			// Save places to database
			foreach ($places->results as $place){
				$loc = $place->geometry->location;

				$rating = 0;
				if (isset($place->rating)) {
					$rating = $place->rating;
				}

				$place_orm = ORM::factory('place');
				$place_orm->id_monument = $monument->id_monument;
				$place_orm->categories = $categories;
				$place_orm->vicinity = $place->vicinity;
				$place_orm->name = $place->name;
				$place_orm->lng = $loc->lng;
				$place_orm->lat = $loc->lat;
				$place_orm->rating = $rating;
				$place_orm->save();
			}

			// Get places (use old query)
			$places_orm = $places_orm_query->reset(false)->find_all();
		}

		return $places_orm;
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