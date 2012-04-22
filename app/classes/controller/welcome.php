<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Welcome extends Controller {
	
	public function action_index(){
		$v = View::factory("splash");
		$this->response->body($v);
	}
	
}

?>