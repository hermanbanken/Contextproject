<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Monument extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
        'province' => array(
            'not_empty'  => NULL,
            'min_length' => array(3),
            'max_length' => array(50),
        ),
	);
	
	protected static $entity = "monument";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`province` varchar(12) NOT NULL,
	  	`municipality` varchar(50) NOT NULL,
	  	`town` varchar(15) NOT NULL,
	  	`street` varchar(30) NOT NULL,
	  	`streetNumber` varchar(4) NULL,
	  	`zipCode` varchar(7) NOT NULL,
	  	`function` varchar(50) NULL,
	  	`description` text NULL,
	  	`x` double(10, 5) NULL,
	  	`y` double(10, 5) NULL,
	  	PRIMARY KEY (`id`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
