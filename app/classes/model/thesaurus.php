<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Model for thesaurus
 *
 * @package CultuurApp
 * @category Models
 * @author Rutger Plak
 */
class Model_Thesaurus extends ORM {

	public static function schema(){
		$prefix = Kohana::$config->load('database.default.table_prefix');
		return sprintf(static::$schema_sql, $prefix."thesaurus_words", $prefix."thesaurus_links");
	}
	
	protected static $schema_sql = "CREATE TABLE `%s` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; CREATE TABLE `%s` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `word` int(11) NOT NULL,
  `synonym` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
}