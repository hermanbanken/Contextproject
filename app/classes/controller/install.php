<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Install extends Controller {

	/**
	 * Make a table in the database for each monument that has a schema inside.
	 */
	public static function update_tables(){
		$result = array();
		$deltas = self::get_deltas();
		if(!empty($deltas['sql']))
			DB::query(Database::UPDATE, $deltas['sql'])->execute();
	}
	
	/**
	 * Find deltas of the table definitions in the current 
	 * database versus the defined schemas in the model classes.
	 * @return array : Array with SQL statement that can update the tables and an array with a description of the changes
	 */
	public static function get_deltas(){
		include Kohana::find_file('vendor', 'wordpress/dbdelta');
		$deltas = '';
		$messages = array();
		foreach(self::get_schemas() as $new){
			$messages[] = dbDelta($new, array('Controller_Install', 'query'), $deltas);
		}
		return array('sql'=>$deltas, 'messages'=>$messages);
	}
	
	/**
	 * Provide a callback for the vendor scripts to execute queries on the database.
	 * @param string SQL to execute
	 * @return array of row objects from the result
	 */
	public static function query($query){
		try{
			$r = DB::query(Database::SELECT, $query)->execute()->as_array();
		} catch (Database_Exception $e){
			return array();
		}
		foreach($r as $k => $a){
			$r[$k] = (object) $a;
		}
		return $r;
	}

	/**
	 * Find all schemas defined in the model classes.
	 * @return string[] : SQL schemas of models that have a schema function
	 */
	public static function get_schemas(){
		$sql = array();
		$models = self::find_models();
		foreach($models as $m){
			if(method_exists($m, 'schema'))
				$sql[] = $m::schema();
		}
		return $sql;
	}

	/**
	 * Find all Models defined in the application
	 * @return string[] : classname of the models found in directory structure
	 */
	public static function find_models(){
		$names = array();
		// Get filenames of model classes
		$models = array_values(Kohana::list_files('classes/model'));
		
		// Flatten
		for($i = 0; $i < count($models); $i++){
			if(is_array($models[$i])){
				$models = array_merge($models, array_values($models[$i]));
				unset($models[$i]);
			}
		}
		// Get classnames of models
		foreach($models as $short => $m){
			if(is_string($m) && file_exists($m))
				$names = array_merge($names, self::file_get_php_classes($m));
		}
		
		// Filter out non-ORM models
		$names = array_filter($names, function($child){
			$class = new ReflectionClass($child);
			return !$class->isAbstract() && $class->isSubClassOf("ORM");
		});
		
		return $names;
	}

	/**
	 * file_get_php_classes
	 * Get all php classes in a file by parsing it with get_php_classes.
	 * 
	 * @return string[] : array of classnames
	 */
	private static function file_get_php_classes($filepath) {
	  $php_code = file_get_contents($filepath);
	  $classes = self::get_php_classes($php_code);
	  return $classes;
	}

	/**
	 * get_php_classes
	 * Get all php classes in a code string by parsing it with token_get_all from Zend.
	 * 
	 * @return string[] : array of classnames
	 */
	private static function get_php_classes($php_code) {
	  $classes = array();
	  $tokens = token_get_all($php_code);
	  $count = count($tokens);
	  for ($i = 2; $i < $count; $i++) {
	    if (   $tokens[$i - 2][0] == T_CLASS
	        && $tokens[$i - 1][0] == T_WHITESPACE
	        && $tokens[$i][0] == T_STRING) {

	        $class_name = $tokens[$i][1];
	        $classes[] = $class_name;
	    }
	  }
	  return $classes;
	}
}