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
	 * @param monument $monument
	 * @return array with forecasts => array(date, image, forecast)
	 */
	public static function forecast($monument, $lang = 'NL') {
		// Get cached forecasts (only forecasts that are not too old, max is one day)
		$forecasts_query = ORM::factory('forecast')
		->where('id_town', '=', $monument->id_town)
		->and_where('cachedOn', '>', date('Y-m-d H:i:s', mktime(6, 0, 0, date('n'), date('j'), date('Y'))));
		
		// Execute query (reset = false, so we can use it at later time)
		$forecasts = $forecasts_query->reset(false)->find_all();

		// Check if there are cached forecasts
		if ($forecasts->count() == 0) {
			// No cached forecasts? Request data from wunderground with our key
			$url = "http://api.wunderground.com/api/".self::KEY."/forecast/lang:".$lang."/q/".$monument->lng.",".$monument->lat.".json";
// 			$url = "http://api.wunderground.com/api/".self::KEY."/geolookup/conditions/forecast/lang:".$lang."/q/NL/".urlencode($monument->town->name).".json";
			$response_json = file_get_contents($url);
			$response = @json_decode($response_json);

			// Check if forecast is found
			if (isset($response->forecast->txt_forecast->forecastday)) {
				// Clear cache
				DB::delete('forecasts')->where('id_town', '=', $monument->id_town)->execute();
					
				$forecast = $response->forecast->txt_forecast->forecastday;
				$i = 0;
				$date = time();

				// Loop through forecasts, cache in database
				foreach ($forecast AS $f) {
					if ($i % 2 == 0) {
						$forecast_orm = ORM::factory('forecast');
						$forecast_orm->id_town = $monument->id_town;
						$forecast_orm->icon = $f->icon_url;
						$forecast_orm->forecast = $f->fcttext_metric;
						$forecast_orm->date = date('Y-m-d', $date);
						$forecast_orm->save();
							
						// Date + 1 day
						$date += 24 * 60 * 60;
					}

					$i++;
				}
				
				$forecasts = $forecasts_query->reset(false)->find_all();
			}
		}

		return $forecasts;
	}
}