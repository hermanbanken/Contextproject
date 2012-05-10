<?php defined('SYSPATH') or die('No direct access allowed.');

/*
 * Class which allows you to make ajax functions
* Everything will be returned as json
* Put return-value non-json in $this->return
*/
class Controller_Ajax extends Kohana_Controller_Template {
	public function action_index() {
		$this->return = false;
	}

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

	public function action_single_places() {
		$post = $this->request->post();
		$monument = ORM::factory('monument', $post['id_monument']);

		$key = 'AIzaSyDil96bzN3gQ6LToMoz8ib0Lz39BYmTfko';
		$places = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/search/json?location='.$monument->lng.','.$monument->lat.'&rankby=prominence&radius=5000&types='.$post['categories'].'&sensor=false&key='.$key));

		$i = 1;
		$this->return = array();
		foreach ($places->results AS $place) {
			$details = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?reference='.$place->reference.'&sensor=false&key='.$key));
			$details = $details->result;
			if (isset($place->name) && isset($place->rating) && isset($place->vicinity) && isset($details->website)) {
				$this->return[] = array('name' => $place->name, 'rating' => $place->rating, 'vicinity' => $place->vicinity, 'website' => $details->website);

				$i++;
			}

			if ($i > 5) {
				break;
			}
		}
	}

	public function action_monument() {
		$post = $this->request->post();
		$monument = ORM::factory('monument', $post['id_monument'])->as_array();

		$this->return = $monument;
	}

	public function before() {
	}

	public function after() {
		$v = View::factory('ajax');
		$v->set('return', $this->return);
		$this->response->body($v);
	}
}
?>