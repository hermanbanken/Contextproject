<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Model_Abstract_Cultuurorm extends ORM {
	
	protected static $schema_sql = "SQL create statement for %s";
	protected static $entity = "abstract";
	
	/**
	 * Get the SQL schema of the model table for the database. Handles the database prefix and plural name of the entity.
	 * @return string : database schema of class
	 */
	public static function schema($entity = null, $schema = null){
		// Use static values if no parameters are set.
		$entity = $entity == null ? static::$entity : $entity;
		$schema = $schema == null ? static::$schema_sql : $schema;
		
		$prefix = Kohana::$config->load('database.default.table_prefix');
		$sql = sprintf($schema, $prefix.Inflector::plural($entity));
		return $sql;
	}
	
}
?>