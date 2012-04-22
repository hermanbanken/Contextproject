<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Monument extends Controller_Abstract_Object {

	protected static $entity = 'monument';
	
	/**
	 * action_map
	 * Action for getting all monuments on a map view
	 */
	public function action_map(){
		$v = View::factory(static::$entity.'/map');
		
		$this->template->body = $v;
	}
	
	/**
	 * action_id
	 * Action for getting one particular object by id in single view
	 */
	public function action_id(){
		$v = View::factory('monument/single');
		$id = $this->request->param('id');
		
// 		echo $id;
		
		$monument = ORM::factory('monument')
		->where('id_monument', '=', $id)
		->find();
		
		$v->bind('monument', $monument);
		$this->template->body = $v;
	}
	
	public function action_getmonumenten() {
		$post = $this->request->post();
		$map = preg_match('/map/',$this->request->initial()->referrer());
		$category = $post['category'];
		
		$monuments = ORM::factory('monument');
		
		if(isset($category) AND $category >= 0) $monuments = $monuments->where('id_category', '=', $category);
		//if(isset($subcategorie)) $monumenten = $monumenten->where('id_subcategory','=',$subcategorie);
		$monuments = $monuments->find_all();
		$_return = array();
		foreach($monuments as $key=>$monument) {
			//echo $monument->lng.",".$monument->lat;
			if($map) {
				$_return[] = array("description" => $monument->description, 
								"longitude" => $monument->lng,
								"latitude" => $monument->lat,
								"id" => $monument->id_monument);
			} else {
				$_return[] = array("description" => $monument->description, 
								"name" => $monument->name,
								"id" => $monument->id_monument);	
			}
		}
		die(json_encode($_return));
		
	}
}
?>