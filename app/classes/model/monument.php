<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Monument extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
        'province' => array(
            'not_empty'  => NULL,
            'min_length' => array(3),
            'max_length' => array(50),
        ),
	);
	protected $_primary_key = 'id_monument';
	protected $_belongs_to = array(
		'category'=> array(
			'model' => 'category',
			'foreign_key' => 'id_category',
		),
		'subcategory'=> array(
			'model' => 'subcategory',
			'foreign_key' => 'id_subcategory',
		),
	);
	
	public function photo(){
		return URL::site('photos/'.$this->id_monument).".jpg";
	}
	
	protected static $entity = "monument";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_monument` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`id_category` int(10) unsigned  NULL,
	  	`id_subcategory` int(10) unsigned  NULL,
	  	`name` varchar(80) NOT NULL,
	  	`wiki_url` varchar(200) NOT NULL,
	  	`province` varchar(12) NOT NULL,
	  	`municipality` varchar(50) NOT NULL,
	  	`town` varchar(15) NOT NULL,
	  	`street` varchar(30) NOT NULL,
	  	`streetNumber` varchar(4) NULL,
	  	`zipCode` varchar(7) NOT NULL,
	  	`function` varchar(50) NULL,
	  	`description` text NULL,
	  	`lng` double(10, 5) NULL,
	  	`lat` double(10, 5) NULL,
	  	PRIMARY KEY (`id_monument`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>