<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for logs of users
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Log extends ORM {
		
	protected $_rules = array(
	);
	
	protected $_primary_key = "id_log";
	protected $_belongs_to = array(
	);
	
	protected static $entity = "log";

	protected $_table_columns = array(
		"id_log" => 	array( "type" => "int", "key" => "PRI" ),
		"id_tracker" => array( "type" => "int", "key" => "MUL" ),
		"dateCreated" =>array( "type" => "string", "column_default" => "CURRENT_TIMESTAMP", "data_type" => "timestamp" ),
	);
	
	public static function schema(){
		$prefix = Kohana::$config->load('database.default.table_prefix');
		return sprintf(static::$schema_sql, 
				$prefix."logs", 
				$prefix."logs_monuments", 
				$prefix."logs_categories", 
				$prefix."logs_towns", 
				$prefix."logs_keywords");
	}
	
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_log` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`id_tracker` int(10) unsigned NOT NULL,
		`dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  	PRIMARY KEY (`id_log`),
		KEY `id_tracker` (`id_tracker`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	  
	CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_log` int(10) unsigned NOT NULL,
	  	`id_monument` int(10) unsigned NOT NULL,
		KEY `id_log` (`id_log`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	  
	CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_log` int(10) unsigned NOT NULL,
	  	`id_category` int(10) unsigned NOT NULL,
		KEY `id_log` (`id_log`),
		KEY `id_category` (`id_category`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	  
	CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_log` int(10) unsigned NOT NULL,
	  	`id_town` int(10) unsigned NOT NULL,
		KEY `id_log` (`id_log`),
		KEY `id_city` (`id_town`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	  
	CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_log` int(10) unsigned NOT NULL,
	  	`value` varchar(100) NOT NULL,
		KEY `id_log` (`id_log`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>