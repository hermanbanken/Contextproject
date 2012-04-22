<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Abstract_Object extends Controller_Template {
	
	protected static $entity = 'abstract';
	
	// Name of template, before resets this to the actual template
	public $template = "layout";
	
	public function action_index(){
		$v = View::factory(static::$entity.'/list.php');
		$this->template->body = $v;
		
	}
	
	public function action_id(){
		$v = View::factory(static::$entity.'/single.php');
		$id = $this->request->param('id');
		$v->bind('model', ORM::factory(static::$entity)->find($id));
		$this->template->body = $v;
	}
	
	public function action_map(){
		$v = View::factory(static::$entity.'/map.php');
		$this->template->body = $v;
		
	}
	
}

?>