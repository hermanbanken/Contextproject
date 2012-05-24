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
	
	
		$lang = strtolower(Session::instance()->get('lang') );
		
		
		if ( (!isset($lang)) || ($lang=='nl') || ($lang=='')){return $default;} 
		
	
		//$table = str_ireplace(".",".dev_",$table);
		$from = "cultuur.dev_translation";
		$sql = 'SELECT translation FROM ' . $from . ' WHERE `table`="' . $table . '" AND `field`="' . $field . '" AND `pk`=' . $pk . ';';
        $query = DB::query(Database::SELECT,$sql,TRUE)->execute();
	
		if ($query->count() == 1){
			return $query[0]['translation'];
			
		}
		else{
			
			$translated = Translator::googleTranslate($default, $lang);
			$query = DB::insert('translation', array('table', 'pk', 'field', 'translation', 'lang'))->values(array($table, $pk, $field, utf8_encode($translated), $lang))->execute();
			
			return $translated;
		}
		// Return text to be translated until we can really translate
		return $default;
	}
	
	private static function googleTranslate($text, $language){
	
		
		$rules = explode(". ",$text);
		
		//$text = urlencode($text);
		//$text = str_replace("+","%20",$text);
		
		
		//$file = "http://translate.google.nl/translate_a/t?client=t&text=" . $text ."&hl=nl&sl=nl&tl=" . $language . "&multires=1&otf=2&ssel=3&tsel=0&sc=1";
		
		$post_data = array(
			'client' => 't',
			'text' => $text,
			'hl' => 'nl',
			'sl' => 'nl',
			'tl' => $language,
			'multires' => 1,
			'otf' => 2,
			'ssel' => 3,
			'tsel' => 0,
			'sc' => 1
		);
		
		
		$result = Translator::post_request('http://translate.google.nl/translate_a/t', $post_data);
 
		if ($result['status'] == 'ok'){
		 
			// Print headers 
			//echo $result['header']; 
		 
			//echo '<hr />';
		 
			// print the result of the whole request:
			$response = ($result['content']);
		 
		}
		else {
			//echo 'A error occured: ' . $result['error']; 
			return "error: " . $result['error'];
		}
		
		
		//$response = file($file);
		//return $response;
		$assemble = "";
		//return $response;
		foreach($rules as $rule){
			$temp = stristr($response, '","' . $rule,true);
			$assemble = $assemble . " " . substr($temp,strrpos($temp,'["') +2,strlen($temp)-4);
		}
		
		return $assemble;	
	}
	
	
	private static function post_request($url, $data, $referer='') {
 
		// Convert the data array into URL Parameters like a=b&foo=bar etc.
		$data = http_build_query($data);
	 
		// parse the given URL
		$url = parse_url($url);
	 
		if ($url['scheme'] != 'http') { 
			die('Error: Only HTTP request are supported !');
		}
	 
		// extract host and path:
		$host = $url['host'];
		$path = $url['path'];
	 
		// open a socket connection on port 80 - timeout: 30 sec
		$fp = fsockopen($host, 80, $errno, $errstr, 30);
	 
		if ($fp){
	 
			// send the request headers:
			fputs($fp, "POST $path HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");
	 
			if ($referer != '')
				fputs($fp, "Referer: $referer\r\n");
	 
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ". strlen($data) ."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $data);
	 
			$result = ''; 
			while(!feof($fp)) {
				// receive the results of the request
				$result .= fgets($fp, 128);
			}
		}
		else { 
			return array(
				'status' => 'err', 
				'error' => "$errstr ($errno)"
			);
		}
	 
		// close the socket connection:
		fclose($fp);
	 
		// split the result header from the content
		$result = explode("\r\n\r\n", $result, 2);
	 
		$header = isset($result[0]) ? $result[0] : '';
		$content = isset($result[1]) ? $result[1] : '';
	 
		// return as structured array:
		return array(
			'status' => 'ok',
			'header' => $header,
			'content' => $content
		);
	}


}