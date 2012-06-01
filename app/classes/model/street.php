<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for streets of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Street extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected $_primary_key = "id_street";
	protected $_belongs_to = array(
			'town'=> array(
					'model' => 'town',
					'foreign_key' => 'id_town',
			),
	);
	
	protected static $entity = "street";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_street` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`id_town` int(10) unsigned NOT NULL,
	  	`name` varchar(100) NOT NULL,
	  	PRIMARY KEY (`id_street`),
		KEY `id_town` (`id_town`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>