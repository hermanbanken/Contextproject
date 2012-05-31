<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Forecast extends Model_Abstract_Cultuurorm {

	protected $_rules = array(
	);

	protected $_primary_key = "id_forecast";
	protected $_belongs_to = array(
			'street'=> array(
					'model' => 'street',
					'foreign_key' => 'id_street',
			),
	);

	protected static $entity = "forecast";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_forecast` int(10) NOT NULL AUTO_INCREMENT,
	`id_town` int(10) NOT NULL,
	`icon` varchar(155) NOT NULL,
	`forecast` text NOT NULL,
	`date` date NOT NULL,
	`cachedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_forecast`),
	KEY `id_town` (`id_town`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
}
?>