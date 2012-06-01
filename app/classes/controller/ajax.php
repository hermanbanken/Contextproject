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
	 * Function for wunderground weather
	 */
	public function action_forecast() {
		$post = $this->request->post();

		$monument = ORM::factory('monument', $post['id_monument']);
		$forecasts = $monument->forecast();

		// Translate forecasts to array
		$return_forecasts = array();
		foreach ($forecasts AS $forecast) {
			$return_forecasts[] = $forecast->as_array();
		}

		$this->return = array('forecasts' => $return_forecasts, 'now' => date('Y-m-d'));
	}

	/**
	 * Function to get the url of a photo for google maps
	 * @param (POST) int id_monument
	 * @return string url of photo
	 */
	public function action_map_photo() {
		$post = $this->request->post();
		if (isset($post['id_monument'])) {
			$photo = ORM::factory('photo');
			$this->return = array('url' => $photo->url($post['id_monument']));
		}
		else {
			$this->return = array('url' => '');
		}
	}

	/**
	 * Function to get recommendations for single view
	 * @param (POST) (int) id_monument
	 * @return array with monuments
	 */
	public function action_single_aanbevelingen() {
		$post = $this->request->post();
		if(isset($post['id_monument']))	{
			$monument = ORM::factory('monument', $post['id_monument']);
			$pca = ORM::factory('pca')->where('id_monument', '=', $monument->id_monument)->find();
			$similars = $monument->visuallySimilars(8, $pca->features(), true);

			$monuments = array();
			foreach ($similars AS $key => $monument) {
				$url = $monument->getphoto()->url();
				$monuments[$key] = $monument->as_array();
				$monuments[$key]['photo_url'] = $url;
			}

			$this->return = $monuments;
		} else $this->return = array();
	}

	/**
	 * Function to add monument to visited
	 * @param (POST) (int) id_monument
	 * @return boolean success
	 */
	public function action_single_visited() {
		$post = $this->request->post();
		$monument = ORM::factory('monument', $post['id_monument']);
		$user = Auth::instance()->get_user();

		if ($monument->loaded() && $user->loaded()) {
			$visit = ORM::factory('visit')->where('id_monument', '=', $monument->id_monument)->and_where('id_user', '=', $user->id)->find();
			if ($visit->loaded()) {
				$visit->delete();

				$this->return = array('success' => true, 'action' => 'delete', 'buttonvalue' => __('single.not-visited'));
			}
			else {
				$visit->id_monument = $monument->id_monument;
				$visit->id_user = $user->id;
				$visit->save();

				$this->return = array('success' => true, 'action' => 'add', 'buttonvalue' => __('single.visited'));
			}
		}
		else {
			$this->return = array('success' => false, 'action' => NULL, 'buttonvalue' => NULL);
		}
	}

	/**
	 * Google Places Ajax Controller for single view
	 * @param (POST) (int) id_monument
	 * @param (POST) (string) categories
	 * @return array with monuments => name, website, vicinity, rating, distance, longitude, latitude
	 */
	public function action_single_places() {
		if($this->request->post('id_monument'))
		{
			$post = $this->request->post();
			
			// Find monument
			$monument = ORM::factory('monument', $post['id_monument']);
			
			// Get places via google places
			$places = $monument->places($post['categories'], 5);
			
			// Translate places to array
			$return_places = array();
			foreach ($places AS $key => $place) {
				$distance = GooglePlaces::distance($place->lat, $place->lng, $monument->lng, $monument->lat, 'K');
				
				$return_places[$key] = $place->as_array();
				$return_places[$key]['distance'] = $distance;
			}
			
			$this->return = $return_places;
		} else $this->return = array();
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
	public function before() {
		// Set language
		I18n::lang($this->lang());
	}

	/**
	 * Rewrite after function so no real template gets loaded
	 */
	public function after() {
		$v = View::factory('ajax');
		$v->set('return', $this->return);
		$this->response->body($v);
	}
}
?>