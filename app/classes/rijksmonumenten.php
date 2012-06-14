<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Rijksmonumenten API
 *
 * Get extra information about monuments
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class Rijksmonumenten {
	/**
	 * Get extra information about monument
	 *
	 * @param monument $monument
	 */
	public static function monument($monument) {
		// Request data from wunderground with our key
		$base_url = "http://api.rijksmonumenten.info/select/?q=rce_obj_nummer:%s&wt=json";
		$url = sprintf($base_url, $monument->id_monument);

		// Get content of response and translate to json
		$response_json = Request::factory($url)->execute();
		$response = @json_decode($response_json);

		if (isset($response->response->docs[0])) {
			$response = $response->response->docs[0];
		}
		else {
			$response = false;
		}

		return $response;
	}
}
?>