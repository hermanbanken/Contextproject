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
		
		$monument = ORM::factory('monument', $id);
		
		$v->bind('monument', $monument);
		$this->template->body = $v;
	}
	
	public function action_getmonumenten() {
		$post = $this->request->post();
		$map = preg_match('/map/',$this->request->initial()->referrer());
		
		foreach($post as $key=>$value) {
			${$key} = $value;
		}
		
		$monuments = ORM::factory('monument');
		
		if(isset($category) AND $category >= 0) $monuments = $monuments->where('id_category', '=', $category);
		if(isset($town) AND $town != 'stad' AND $post['town'] != '') $monuments = $monuments->where('town','=',$town);
		if(isset($street) AND $street != 'straat' AND $street != '') $monuments = $monuments->where('street','=',$street);
		
		if(!$map) $monuments = $monuments->limit(20);
		if(isset($limit)) $monuments = $monuments->limit($limit);
		if(isset($limit) AND isset($offset)) $monuments = $monuments->offset($offset);
	
		$monuments = $monuments->order_by(DB::expr('RAND()'));
		
		//if(isset($subcategorie)) $monumenten = $monumenten->where('id_subcategory','=',$subcategorie);
		$monuments = $monuments->find_all();
		$_return = array();
		foreach($monuments as $key=>$monument) {
			//echo $monument->lng.",".$monument->lat;
			if($map) {
				$_return[] = array("description" => $monument->description, 
								"longitude" => $monument->lng,
								"latitude" => $monument->lat,
								"name" => $monument->name,
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