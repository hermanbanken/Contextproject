<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_SubCategory extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected $_primary_key = "id_subcategory";
	protected $_has_many = array(
		'monuments' => array(
			'model' => 'monument',
			'foreign_key' => 'id_subcategory',
			'far_key' => 'id_monument',
			'through' => 'monuments',
		)
	);
	protected $_belongs_to = array(
		'category' => array(
			'foreign_key' => 'id_category'
		)
	)
	
	protected static $entity = "subcategory";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_subcategory` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`id_category` varchar(90) NOT NULL,
	  	`name` varchar(90) NOT NULL,
	  	PRIMARY KEY (`id_subcategory`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>