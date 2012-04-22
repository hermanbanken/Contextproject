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
	
	public function action_getpins() {
		die(var_dump($this->request->current()));
		$categorie = 1;
		$subcategorie = 2;
		
		$monumenten = ORM::factory('monument');
		
		if(isset($categorie)) $monumenten = $monumenten->where('id_category','=',$categorie);
		//if(isset($subcategorie)) $monumenten = $monumenten->where('id_subcategory','=',$subcategorie);
		die($monumenten = $monumenten->count_all());
	}
}
?>