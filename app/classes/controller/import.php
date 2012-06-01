<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Import extends Controller_Abstract_Object {
	/**
	 * Import features
	 */
	public function action_features() {
		$v = View::factory('import');

		$count = Importer::features();
		Importer::normalize_features('photo');
		
		$v->set('type', 'feature');
		$v->set('count', $count);
		
		$this->template->body = $v;
	}
	
	/**
	 * Import tags
	 */
	public function action_tags() {
		$v = View::factory('import');

		$count = Importer::tags();
		
		$v->set('type', 'tag');
		$v->set('count', $count);
		
		$this->template->body = $v;
	}
	
	/**
	 * Import monuments
	 */
	public function action_monuments() {
		$v = View::factory('import');

		$count = Importer::monuments();
		
		$v->set('type', 'monument');
		$v->set('count', $count);
		
		$this->template->body = $v;
	}
	
	/**
	 * Import pca's
	 */
	public function action_pca() {
		$v = View::factory('import');

		$count = Importer::pca_features();
		Importer::normalize_features('pca');
		
		$v->set('type', 'pca');
		$v->set('count', $count);
		
		$this->template->body = $v;
	}
	
	/**
	 * Import extracted categories
	 */
	public function action_categories() {
		$v = View::factory('import');

		$count = Importer::categories();
		
		$v->set('type', 'category');
		$v->set('count', $count);
		
		$this->template->body = $v;
	}
}
?>