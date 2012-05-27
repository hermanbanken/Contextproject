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
	 * Function to get weather near monument from Wunderground
	 * @param monument $monument
	 * @return array with forecasts => array(date, image, forecast)
	 */
	public static function weather($monument) {
		$url = "http://api.wunderground.com/api/".self::KEY."/geolookup/conditions/forecast/lang:NL/q/NL/".$monument->town->name.".json";
		$response_json = file_get_contents($url);
		$response = @json_decode($response_json);

		$weather = array();
		if (!isset($response->forecast->txt_forecast->forecastday)) {
			$weather = array();
		}
		else {
			$forecast = $response->forecast->txt_forecast->forecastday;
			$i = 0;
			$date = time();
			foreach ($forecast AS $f) {
				if ($i % 2 == 0) {
					$weather[] = array('date' => date('Y-m-d', $date), 'image' => $f->icon_url, 'forecast' => $f->fcttext_metric);
					$date += 24 * 60 * 60;
				}

				$i++;
			}
		}

		return $weather;
	}
}