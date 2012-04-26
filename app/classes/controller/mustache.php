<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Mustache extends Controller {
	
	/**
	 * index
	 * Get a list of the names of all Mustache templates, that's all / replaced by _.
	 * @return names[]: One-dimensional list of mustache templates
	 */
	public function index(){
		$templates = Kohana::list_files('templates');
		$list = array();
		
		function extract_template_name($arr){
			if(!is_array($arr)){
				// Get name of template
				preg_match("/templates\/(.*).mustache/", $arr, $match);
				// Replace /'s to comply to Kohana naming convention and prevent routing issues
				return str_replace("/", "_", $match[1]);
			} else {
				$list = array_map("extract_template_name", $arr);
				// Un-nest sub-arrays.
				foreach($list as $key => $val){
					if(is_array($val)){
						unset($list[$key]);
						$list = array_merge($list, $val);
					}
				}
				// Because un-nesting we return a single dimension list here
				return $list;
			}
		}
		
		return array_values(extract_template_name($templates));
	}
	
	/**
	 * action_index
	 * @return templates.json: The list of template names
	 */
	public function action_index(){
		echo json_encode($this->index());
	}
	
	/**
	 * action_name
	 * Get the mustache template by it's name, that's all / replaced by _.
	 * When the template doesn't exist display that instead.
	 * @return mustache template
	 */
	public function action_name(){
		$base = str_replace("_", "/", $this->request->param('id'));
		$file = Kohana::find_file('templates', $base, 'mustache');
		if($file !== false && $tmpl = file_get_contents($file))
			echo $tmpl;
		else
			echo "404: template not found";
	}
	
}

?>