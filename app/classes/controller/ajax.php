<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Controller for Ajax calls
 * All data which is put into $this->return 
 * will be returned as a json object
 * @author Sjoerd
 *
*/
class Controller_Ajax extends Kohana_Controller_Template {
	public function action_index() {
		$this->return = false;
	}

	/**
	 * Function to get recommandations for single view
	 * @param (POST) (int) id_monument
	 * @return array with monuments
	 */
	public function action_single_aanbevelingen() {
		$post = $this->request->post();
		$monument = ORM::factory('monument', $post['id_monument']);
		$similars = $monument->similars400(8);
		$monuments = $similars['monuments']->as_array();

		foreach ($monuments AS $key => $monument) {
			$photo = $monument->photo();
			$monuments[$key] = $monument->as_array();
			$monuments[$key]['photo'] = $photo;
		}

		$this->return = $monuments;
	}

	/**
	 * Google Places Ajax Controller for single view
	 * @param (POST) (int) id_monument
	 * @param (POST) (string) categories
	 * @return array with monuments => name, website, vicinity, rating, distance, longitude, latitude
	 */
	public function action_single_places() {
		$post = $this->request->post();
		$monument = ORM::factory('monument', $post['id_monument']);

		$key = 'AIzaSyDil96bzN3gQ6LToMoz8ib0Lz39BYmTfko';
		$places = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/search/json?location='.$monument->lng.','.$monument->lat.'&rankby=distance&types='.$post['categories'].'&sensor=false&key='.$key));

		$i = 1;
		$this->return = array();
		foreach ($places->results AS $place) {
			$details = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?reference='.$place->reference.'&sensor=false&key='.$key));
			
			$details = $details->result;

			$longitude = $details->geometry->location->lng;
			$latitude = $details->geometry->location->lat;
			if (isset($place->name) && isset($place->rating) && isset($place->vicinity) && isset($details->website)) {
				$this->return[] = array('longitude' => $longitude, 'latitude' => $latitude, 'distance' => $this->distance($latitude, $longitude, $monument->lng, $monument->lat, 'K'), 'name' => $place->name, 'rating' => $place->rating, 'vicinity' => $place->vicinity, 'website' => $details->website);

				$i++;
			}

			if ($i > 5) {
				break;
			}
		}
	}

	/**
	 * Function to get monument information by id
	 * @param (POST) (int) id_monument
	 * @return monument array
	 */
	public function action_monument() {
		$post = $this->request->post();
		$monument = ORM::factory('monument', $post['id_monument'])->as_array();

		$this->return = $monument;
	}

	/**
	 * Clear before function
	 */
	public function before() { }

	/**
	 * Rewrite after function so no real template gets loaded
	 */
	public function after() {
		$v = View::factory('ajax');
		$v->set('return', $this->return);
		$this->response->body($v);
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
	function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	
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
?>