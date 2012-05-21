<?php defined('SYSPATH') or die('No direct access allowed.');

class Translator {

	/**
	 * Translate fields from objects
	 * @param string $table Name of object
	 * @param int $pk Primary key of object
	 * @param string $field Field name that should be translated
	 * @param string $default Text that must be translated and fallback to this text if we can't translate
	 */
	public static function translate($table, $pk, $field, $default)
	{	
		// Return text to be translated until we can really translate
		return $default;
	}

}