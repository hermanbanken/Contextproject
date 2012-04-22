<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Template extends Kohana_Controller_Template {
	
	protected static $entity = 'abstract';
	
	// Name of template, before resets this to the actual template
	public $template = "layout";

	private $css = array();
	private $js = array();
	
	/**
	 * before
	 * Set header and footer so the view won't break;
	 */
	public function before(){
		parent::before();
		
		$this->template->header = new View('header');
		$this->template->footer = new View('footer');
	}
	
	/**
	 * css
	 * Add stylesheet to View
	 * @param file: url relative to public
	 * @param media: media attribute for link
	 * @param rel: rel attribute for link 
	 */
	public function css($file, $media = 'screen', $rel = 'stylesheet'){
		$this->css[] = array('href'=>URL::site($file), 'media'=>$media, 'rel'=>$rel);
	}
	
	/**
	 * less
	 * See Controller_Template::css
	 */
	public function less($file, $media = 'screen', $rel = 'stylesheet/less'){
		$this->css($file, $media, $rel);
	}
	
	/**
	 * js
	 * Add javascript to View
	 * @param name: name of script, used for dependency control
	 * @param file: url relative to public
	 * @param head: to include the script in the head, otherwise it's in the footer
	 * @param depends[]: array of scripts this new script depends on
	 * @todo dependencies not yet implemented 
	 */
	public function js($name, $file, $head = false, $depends = null){
		$this->js[$name] = array('src'=>URL::site($file), 'head'=>$head, 'dependson'=>$depends);
	} 
	
	public function after(){
		// Prepare javascript includes
		$js_foot = array();
		$js_head = array();
		foreach($js as $name => $j){
			$n = sprintf("<script src='%s'></script>", $j['src']);
			if($j['head']) $js_head[] = $n;
			else $js_food[] = $n;
		}
		
		// Prepare css includes
		$css = array();
		foreach($css as $c){
			$css[] = sprintf("<link rel='%s' type='text/css' href='%s' media='%s' />", $c['rel'], $c['href'], $c['media']);
		}
		
		View::set_global('js_head', implode('\n', $js_head));
		View::set_global('js_foot', implode('\n', $js_foot));
		View::set_global('css', 	implode('\n', $css));
		
		parent::after();
	}
	
}
?>