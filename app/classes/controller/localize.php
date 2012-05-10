<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Localize extends Controller_Template {

	public function action_index()
	{
		// Change language
		$lang = $this->request->param('lang');
		if($lang && !empty($lang))
		{
			Session::instance()->set('lang', $lang);
		}
		
		if($this->request->query('redirect')){
			$this->request->redirect($this->request->query('redirect'));
		}
	}

	public function action_menu(){
	  	$this->template = View::factory('lang')
			->bind('lang', $lang)
			->bind('languages', $langs);
		$lang = $this->lang();
		$langs = Kohana::$config->load('lang.languages');
	}
}