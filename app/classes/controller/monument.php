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
		
		$monuments = ORM::factory('monument');
		
		if(isset($post['category']) AND $post['category'] >= 0) $monuments = $monuments->where('id_category', '=', $post['category']);
		$monuments = $monuments->limit(20);
		if(isset($post['town']) AND $post['town'] != 'stad' && $post['town'] != '') $monuments = $monuments->where('town','=',$post['town']);
		if(isset($post['street']) AND $post['street'] != 'straat' && $post['street'] != '') $monuments = $monuments->where('street','=',$post['street']);
		if(isset($post['limit'])) $monuments = $monuments->limit($post['limit']);
		if(isset($post['offset']) AND isset($post['offset'])) $monuments = $monuments->offset($post['offset']);
	
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