<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Wunderground
 *
 * Get weather around monuments
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class Wunderground {
	/**
	 * Wunderground API KEY
	 */
	const KEY = 'bc055f4e835cb59a';

	/**
	 * Function to get forecast near monument from Wunderground
	 * 
	 * @param monument $monument
	 * @return array with forecasts => array(date, image, forecast)
	 */
	public static function forecast($monument) {
		// Get cached forecasts (only forecasts that are not too old, max is one day)
		$forecasts_query = ORM::factory('forecast')
		->where('id_town', '=', $monument->id_town)
		->and_where('cachedOn', '>', date('Y-m-d H:i:s', mktime(6, 0, 0, date('n'), date('j'), date('Y'))));

		// Execute query (reset = false, so we can use it at later time)
		$forecasts = $forecasts_query->reset(false)->find_all();

		// Check if there are cached forecasts
		if ($forecasts->count() == 0) {
			// Clear cache
			DB::delete('forecasts')->where('id_town', '=', $monument->id_town)->execute();

			// Import new forecasts
			Wunderground::import($monument);

			// Get new forecasts from database
			$forecasts = $forecasts_query->reset(false)->find_all();
		}

		return $forecasts;
	}

	/**
	 * Import forecasts from WUnderground
	 * 
	 * @param monument $monument
	 */
	public static function import($monument) {
		// Request data from wunderground with our key
		$base_url = "http://api.wunderground.com/api/%s/forecast/lang:NL/q/%s,%s.json";
		$url = sprintf($base_url, self::KEY, $monument->lng, $monument->lat);

		// Get content of response and translate to json
		$response_json = file_get_contents($url);
		$response = @json_decode($response_json);

		// Check if forecast is found
		if (isset($response->forecast->simpleforecast->forecastday)) {

			$forecast = $response->forecast->simpleforecast->forecastday;

			// Loop through forecasts, cache in database
			foreach ($forecast AS $f) {
				$forecast_orm = ORM::factory('forecast');
				$forecast_orm->id_town = $monument->id_town;
				$forecast_orm->icon = $f->icon;
				$forecast_orm->low = $f->low->celsius;
				$forecast_orm->high = $f->high->celsius;
				$forecast_orm->date = date('Y-m-d', mktime(0, 0, 0, $f->date->month, $f->date->day, $f->date->year));
				$forecast_orm->save();
			}
		}
	}
}