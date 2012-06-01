<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for Function (of monument)
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Function extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected $_primary_key = "id_function";
	protected $_has_many = array(
	);
	
	protected static $entity = "function";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_function` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`name` varchar(100) NOT NULL,
	  	PRIMARY KEY (`id_function`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>