<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for municipalities of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Municipality extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected $_primary_key = "id_municipality";
	protected $_belongs_to = array(
			'province'=> array(
					'model' => 'province',
					'foreign_key' => 'id_province',
			),
	);
	
	protected static $entity = "municipality";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_municipality` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`id_province` int(10) unsigned NOT NULL,
	  	`name` varchar(100) NOT NULL,
	  	PRIMARY KEY (`id_municipality`),
		KEY `id_province` (`id_province`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>