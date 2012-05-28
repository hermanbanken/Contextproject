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
		// Get cached forecasts
		$forecasts_orm = ORM::factory('forecast')
		->where('id_town', '=', $monument->id_town)
		->and_where('cachedOn', '>', date('Y-m-d H:i:s', mktime(0, 0, 0, date('n'), date('j'), date('Y')) - 24 * 60 * 60))
		->find_all();

		// Initialize return array
		$forecasts = array();
		
		// Check if there are cached forecasts
		if ($forecasts_orm->count() == 0) {
			// No cached forecasts? Request data from wunderground with our key
			$url = "http://api.wunderground.com/api/".self::KEY."/geolookup/conditions/forecast/lang:".$lang."/q/NL/".$monument->town->name.".json";
			$response_json = file_get_contents($url);
			$response = @json_decode($response_json);
			
			// Check if forecast is found
			if (isset($response->forecast->txt_forecast->forecastday)) {
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
							
						$forecasts[] = $forecast_orm;
						$date += 24 * 60 * 60;
					}

					$i++;
				}
			}
		}
		else {
			// If cached forecasts are found, save them in return array
			$forecasts = $forecasts_orm;
		}

		return $forecasts;
	}
}