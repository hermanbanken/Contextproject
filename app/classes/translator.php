<?php defined('SYSPATH') or die('No direct access allowed.');

class Translator {

	/**
	 * Translate fields from objects
	 * @param string $table Name of object
	 * @param int $pk Primary key of object
	 * @param string $field Field name that should be translated
	 * @param string $default Text that must be translated and fallback to this text if we can't translate
     * @return string Translation
     */
	public static function translate($table, $pk, $field, $default)
	{
		$lang = strtolower(Session::instance()->get('lang') );
		
		if ( (!isset($lang)) || ($lang=='nl') || ($lang==''))
        {
            return $default;
        }
	
		$query = DB::select('translation')
            ->from("translation")
            ->where('table', '=', $table)
            ->and_where('field', '=', $field)
            ->and_where('pk', '=', $pk)
            ->execute();
	
		if ($query->count() > 0){
			return $query[0]['translation'];
        }
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
     * @param $text Original text
     * @param $language Translation language
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
        foreach($obj[0] as $sentence){
            list($trans, $orig) = $sentence;
            // Remove space before dot Google added.
            $trans = preg_replace("~\s+\.~", ".", $trans);
            $translation .= $trans;
        }

        return $translation;
	}

}