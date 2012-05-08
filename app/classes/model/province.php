<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Province extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected $_primary_key = "id_province";
	protected $_has_many = array(
	);
	
	protected static $entity = "province";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_province` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`name` varchar(100) NOT NULL,
	  	PRIMARY KEY (`id_province`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>