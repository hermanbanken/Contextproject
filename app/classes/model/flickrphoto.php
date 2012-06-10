<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for Function (of monument)
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Flickrphoto extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected $_primary_key = "id_flickrphoto";
	protected $_has_many = array(
	);
	
	public function thumb() {
		return substr($this->url, 0, -4).'_t.jpg';
	}
	
	public function large() {
		return substr($this->url, 0, -4).'_c.jpg';
	}
	
	protected static $entity = "flickrphoto";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id_flickrphoto` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  	`id_monument` int(10) unsigned NOT NULL,
	  	`url` varchar(150) NOT NULL,
		`cachedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  	PRIMARY KEY (`id_flickrphoto`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";	
}
?>