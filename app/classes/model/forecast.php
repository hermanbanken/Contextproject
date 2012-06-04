<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for forecasts at towns
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
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

	/**
	 * Get temperature of low and high temperatures
	 */
	public function temperature() {
		return (($this->low + $this->high) / 2);
	}
	
	/**
	 * Get abbreviation of day string
	 * @return string abbreviation of day string
	 */
	public function day() {
		$days = __('forecast.days');
		$dayofweek = date('N', strtotime($this->date));
		
		return $days[$dayofweek - 1];
	}

	protected static $entity = "forecast";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_forecast` int(10) NOT NULL AUTO_INCREMENT,
	`id_town` int(10) NOT NULL,
	`icon` varchar(155) NOT NULL,
	`low` double NOT NULL,
	`high` double NOT NULL,
	`date` date NOT NULL,
	`cachedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_forecast`),
	KEY `id_town` (`id_town`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
}
?>