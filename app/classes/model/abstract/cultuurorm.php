<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Model_Abstract_Cultuurorm extends ORM {
	
	protected static $schema_sql = "SQL create statement for %s";
	protected static $entity = "abstract";
	
	/**
	 * schema
	 * @return string : database schema of class
	 */
	public static function schema(){
		$prefix = Kohana::$config->load('database.default.table_prefix');
		$sql = sprintf(static::$schema_sql, Inflector::plural($prefix.static::$entity));
		return $sql;
	}
	
}
?>