<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Town extends Model_Abstract_Cultuurorm {

	protected $_rules = array(
	);

	protected $_primary_key = "id_town";
	protected $_belongs_to = array(
			'municipality'=> array(
					'model' => 'municipality',
					'foreign_key' => 'id_municipality',
			),
	);

	protected static $entity = "town";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_town` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_municipality` int(10) unsigned NOT NULL,
	`name` varchar(90) NOT NULL,
	PRIMARY KEY (`id_town`),
	KEY `id_municipality` (`id_municipality`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
?>