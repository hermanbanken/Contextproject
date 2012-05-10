<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Controller extends Kohana_Controller {
	
	public function before(){
		parent::before();
	
		// Set language
		I18n::lang($this->lang());
	}
	
	/**
	 * Get language to be used. Based on
	 * - Request
	 * - Session
	 * - Default configuration
	 */
	public function lang(){
		$lang = $this->request->param('lang');
		if ( $lang && !empty($lang) )
		{
			// Nothing
		}
		// Language set in session
		else if( Session::instance()->get('lang') )
		{
			$lang = Session::instance()->get('lang');
		} 
		// Language set in defaults
		else 
		{
			$lang = Kohana::$config->load("lang.default");
		}
		
		return $lang;
	}
	
}