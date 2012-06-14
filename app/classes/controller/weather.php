<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Weather extends Controller {

	public function action_monument() {
		$id = $this->request->param('id');
		$monument = ORM::factory('monument', $id);

		$v = View::factory("weather");
		$v->set('forecasts', $monument->loaded() ? $monument->forecast() : array());
		$this->response->body($v);
	}

}