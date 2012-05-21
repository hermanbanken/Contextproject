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
		if($this->request->post('id_monument'))
		{
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
		} else $this->return = array();
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
		
			$this->return = Places::get_places(
				$post['id_monument'], 
				$post['categories'], 'distance', false, false, 5);
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
	public function before() { }

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