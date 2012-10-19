<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for town of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Event extends Model_Abstract_Cultuurorm {

	protected $_rules = array(
	);

	protected $_primary_key = "id_event";
	protected $_belongs_to = array();

	protected $_table_columns = array(
		"id_event" => array( "type" => "int", "key" => "PRI", "extra" => "auto_increment" ),
		"id_event" => array( "type" => "int" ),
	);

	protected static $entity = "event";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  `id_event` int(11) NOT NULL AUTO_INCREMENT,
	  `trcid` varchar(40) DEFAULT NULL,
	  `title` varchar(100) DEFAULT NULL,
	  `shortdesc` text,
	  `descr` text,
	  `summary` text,
	  `types` text,
	  `ids` varchar(40) DEFAULT NULL,
	  `location` varchar(255) DEFAULT NULL,
	  `city` varchar(255) DEFAULT NULL,
	  `address` varchar(255) DEFAULT NULL,
	  `zipcode` varchar(255) DEFAULT NULL,
	  `lat` varchar(13) DEFAULT NULL,
	  `lng` varchar(13) DEFAULT NULL,
	  `urls` varchar(255) DEFAULT NULL,
	  `media` varchar(255) DEFAULT NULL,
	  `thumb` varchar(255) DEFAULT NULL,
	  `date_start` varchar(255) DEFAULT NULL,
	  `date_end` varchar(255) DEFAULT NULL,
	  `date_other` varchar(255) DEFAULT NULL,
	  PRIMARY KEY (`id_event`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
?>