<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Category extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected static $entity = "category";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`name` varchar(90) NOT NULL,
	  	PRIMARY KEY (`id_category`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>