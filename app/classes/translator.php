<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for translating fields of models
 * @package CultuurApp
 * @category Helpers
 * @author Herman Banken
 * @copyright 2012 CultuurApp Team
 */
class Translator {

	/**
	 * Translate fields from objects
	 * @param string    Name of object
	 * @param int       Primary key of object
	 * @param string    Field name that should be translated
	 * @param string    Text that must be translated and fallback to this text if we can't translate
     * @return string   Translation
     * @author Tim Eversdijk
     */
	public static function translate($table, $pk, $field, $default)
	{
		$lang = strtolower(Session::instance()->get('lang', 'nl') );
		
		if ( $lang=='nl' )
        {
            return $default;
        }
	
		$query = DB::select('translation')
            ->from("translation")
            ->where('table', '=', $table)
            ->and_where('field', '=', $field)
            ->and_where('pk', '=', $pk)
            ->execute();

        // Return the translation already in database
		if ($query->count() > 0){
			return $query[0]['translation'];
        }
        // Make a translation since we don't have it yet
		else{
			$translated = Translator::googleTranslate($default, $lang);

            $query = DB::insert('translation', array('table', 'pk', 'field', 'translation', 'lang'))
                ->values(array($table, $pk, $field, $translated, $lang))
                ->execute();
			
			return $translated;
		}

		// Return text to be translated until we can really translate
		return $default;
	}

    /**
     * Translate text in the given language by using Googles translate website
     * @static
     * @param string Original text
     * @param string Translation language
     * @return string Translation
     * @author Herman Banken
     */
	private static function googleTranslate($text, $language){

		$post_data = array(
			'client' => 't',
			'hl' => 'nl',
			'sl' => 'nl',
			'tl' => $language,
			'multires' => 1,
			'otf' => 2,
			'ssel' => 3,
			'tsel' => 0,
			'sc' => 1,
            'text' => $text,
		);

		$response = Request::factory( "http://translate.google.nl/translate_a/t" )
            ->method("POST")
            ->body(http_build_query($post_data))->execute();

        // Response isn't valid JSON since it contains comma's after comma's

        $obj = utf8_encode($response->body());
        $obj = preg_replace("~,+~", ",", $obj);

        $obj = json_decode($obj);

        $translation = "";

		if(!is_array($obj[0])) return $text;

        foreach($obj[0] as $sentence){
            list($trans) = $sentence;
            // Remove space Google added before dots.
            $trans = preg_replace("~\s+\.~", ".", $trans);
            $translation .= $trans;
        }

        return $translation;
	}

}