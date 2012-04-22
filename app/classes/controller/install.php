<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Install extends Controller {

	/**
	 * create_tables
	 */
	public static function create_tables(){
		$result = array();
		foreach(self::get_schemas() as $sql){
			$result[] = DB::query(Database::UPDATE, $sql)->execute();
		}
		return $result;
	}

	/**
	 * get_schemas
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
	 * find_models
	 * @return string[] : classname of the models found in directory structure
	 */
	public static function find_models(){
		$names = array();
		// Get filenames of model classes
		$models = Kohana::list_files('classes/model');
		// Get classnames of models
		foreach($models as $short => $m){
			if(is_string($m) && file_exists($m))
				$names = array_merge($names, self::file_get_php_classes($m));
		}
		// Filter out non-ORM models
		$names = array_filter($names, function($child){
			return is_subclass_of($child, "ORM");
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