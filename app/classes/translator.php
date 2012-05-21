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
	
	
		$lang = strtoupper(Session::instance()->get('lang') );
		
		
		if (($lang=='NL') || ($lang=='')){return $default;} 
		
	
		//$table = str_ireplace(".",".dev_",$table);
		$from = "cultuur.dev_translation";
		$sql = 'SELECT translation FROM ' . $from . ' WHERE `table`="' . $table . '" AND `field`="' . $field . '" AND `pk`=' . $pk . ';';
        $query = DB::query(Database::SELECT,$sql,TRUE)->execute();
	
		if ($query->count() == 1){
			return $query[0]['translation'];
			
		}
		else{
			
			$translated = Translator::googleTranslate($default, $lang);
			
			DB::query(Database::INSERT, sprintf("INSERT INTO " . $from . " VALUES ('" . $table . "', " . $pk . ", '" . $field . "', '" . $translated . "', '" . $lang . "', " . 'NOW());'))->execute();
			
			
			
			return $translated;
		}
		// Return text to be translated until we can really translate
		return $default;
	}
	
	private static function googleTranslate($text, $language){
	
		
		$rules = explode(". ",$text);
		
		$text = urlencode($text);
		$text = str_replace("+","%20",$text);
		
		
		$file = "http://translate.google.nl/translate_a/t?client=t&text=" . $text ."&hl=nl&sl=nl&tl=" . $language . "&multires=1&otf=2&ssel=3&tsel=0&sc=1";
		
		$response = file($file);
		
		$assemble = "";
		foreach($rules as $rule){
			$temp = stristr($response[0], '","' . $rule,true);
			$assemble = $assemble . " " . substr($temp,strrpos($temp,'["') +2,strlen($temp)-4);
		}
		
		return $assemble;	
	}
}