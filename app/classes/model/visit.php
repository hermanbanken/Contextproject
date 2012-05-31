<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for visits of users to monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Visit extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected static $entity = "visit";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`id_user` int(10) unsigned NOT NULL,
	  	`id_monument` int(10) unsigned NOT NULL,
	  	`date` timestamp DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`),
		KEY `id_user` (`id_user`),
		KEY `id_monument` (`id_monument`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>