<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Photo extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array();
	protected $_belongs_to = array(
		'monument'=> array(
			'model' => 'monument',
			'foreign_key' => 'id_monument',
		)
	);
	
	protected static $entity = "photo";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id` int(10) unsigned AUTO_INCREMENT,
	  	`id_monument` int(10) unsigned NOT NULL,
	  	`total` double NOT NULL,
	  	`color` double NOT NULL,
	  	`composition` double NOT NULL,
	  	`orientation` double NOT NULL,
	  	`texture` double NOT NULL,
	  	PRIMARY KEY (`id`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>