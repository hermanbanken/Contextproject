<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for links of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Link extends ORM {
	
	protected $_primary_key = "id_link";
	
	public static function schema(){
		$prefix = Kohana::$config->load('database.default.table_prefix');
		return sprintf(static::$schema_sql, $prefix."links", $prefix."monument_link");
	}
	
	protected $_has_many = array(
			'monuments' => array(
					'model' => 'monument',
					'foreign_key' => 'id_link',
			)
	);

	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_link` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	`url` varchar(150) NOT NULL,
	PRIMARY KEY (`id_link`),
	KEY `id_monument` (`id_monument`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	
	CREATE TABLE IF NOT EXISTS `%s` (
	`id_monument` int(10) unsigned NOT NULL,
	`id_link` int(10) unsigned NOT NULL,
	KEY `id_monument` (`id_monument`),
	KEY `id_link` (`id_link`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
?>