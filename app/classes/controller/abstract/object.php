<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Abstract_Object extends Controller_Template {
	
	protected static $entity = 'abstract';
	
	// Name of template, before resets this to the actual template
	public $template = "layout";
	
	/**
	 * action_index
	 * Action for listing all objects of type
	 */
	public function action_index(){
		$v = View::factory(static::$entity.'/list');
		$this->template->body = $v;
	}
	
	/**
	 * action_index
	 * Action for listing all objects of type
	 */
	public function action_list(){
		$v = View::factory(static::$entity.'/list');
		$this->template->body = $v;
	}
	
	/**
	 * action_id
	 * Action for getting one particular object by id in single view
	 */
	public function action_id(){
		$v = View::factory(static::$entity.'/single');
		$id = $this->request->param('id');
		$model = ORM::factory(static::$entity)->find($id);
		$v->bind('model', $object);
		$this->template->body = $v;
	}
	
// **Was used for creating categories
//		public function action_categories() {
// 		$oDb = Database::instance();
		
// 		$category_sql = "SELECT DISTINCT(id_category) FROM dev_monuments WHERE id_category != ''";
	
// 		$category_res = $oDb->query(Database::SELECT, $category_sql);
// 		foreach ($category_res AS $key => $value) {
// 			$category = ORM::factory('category');
// 			$category->name = $value['id_category'];
// 				$category->save();
			
// 			$sub_category_sql = "SELECT DISTINCT(id_subcategory) FROM dev_monuments WHERE id_category = '".$category->name."'";
// 			$sub_category_res = $oDb->query(Database::SELECT, $sub_category_sql);
// 			foreach ($sub_category_res AS $key1 => $value1) {
// 				$subcategory = ORM::factory('subcategory');
// 				$subcategory->id_category = $category->id;
// 				$subcategory->name = $value1['id_subcategory'];
// 				$subcategory->save();
				
// 				$oDb->query(Database::UPDATE, "UPDATE dev_monuments SET category = ".$category->id.", subcategory = ".$subcategory->id." WHERE id_category = '".mysql_real_escape_string($category->name)."' AND  id_subcategory = '".mysql_real_escape_string($subcategory->name)."'");
// 			}
// 		}
		
// 		$v = View::factory(static::$entity.'/list');
// 		$this->template->body = $v;
// 	}
	
	public function action_map(){
		$v = View::factory(static::$entity.'/map');
		$this->template->body = $v;
		
	}
}
?>