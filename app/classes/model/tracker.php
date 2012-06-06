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