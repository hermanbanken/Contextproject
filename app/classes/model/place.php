<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for places int he neighbourhood of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Place extends Model_Abstract_Cultuurorm {

	protected $_rules = array(
	);

	protected $_primary_key = "id_forecast";
	protected $_belongs_to = array(
			'monument'=> array(
					'model' => 'monument',
					'foreign_key' => 'id_monument',
			),
	);

	protected static $entity = "place";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_place` int(10) NOT NULL AUTO_INCREMENT,
	`id_monument` int(11) NOT NULL,
	`categories` varchar(55) NOT NULL,
	`name` varchar(55) NOT NULL,
	`vicinity` varchar(155) NOT NULL,
	`lng` double NOT NULL,
	`lat` double NOT NULL,
	`distance` double NOT NULL,
	`rating` double NOT NULL,
	`cachedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_place`),
	KEY `id_monument` (`id_monument`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
}
?>