<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for municipalities of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Tracker extends Model_Abstract_Cultuurorm {

	protected $_rules = array(
	);

	protected $_primary_key = "id_tracker";
	protected $_belongs_to = array(
	);

	protected $_table_columns = array(
		"id_tracker" => array( "type" => "int", "key" => "PRI", "extra" => "auto_increment" ),
		"id_user" 	 =>	array( "type" => "int", "key" => "MUL" ),
		"hash"		 => array( "type" => "string", "character_maximum_length" => 30 ),
		"dateCreated" => array( "type" => "string", "data_type" => "timestamp" ),
		"dateLastUpdated" => array( "type" => "string", "column_default" => "CURRENT_TIMESTAMP", "data_type" => "timestamp" ),
	);

	protected static $entity = "tracker";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_tracker` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_user` int(10) unsigned NULL,
	`hash` varchar(30) NOT NULL,
	`dateCreated` datetime NOT NULL,
	`dateLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_tracker`),
	KEY `id_user` (`id_user`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
?>