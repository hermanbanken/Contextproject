<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Welcome extends Controller_Template {
	
	public $template = "layout";
	
	public function action_index(){
		$v = View::factory("splash");
		$this->template->body = $v;
	}
	
}

?>