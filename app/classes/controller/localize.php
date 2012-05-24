<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Localize extends Controller_Template {

	public function action_index()
	{
		// Change language
		$lang = $this->request->param('lang');
		if($lang && !empty($lang))
		{
			Session::instance()->set('lang', $lang);
		}
		
		if($this->request->query('redirect')){
			$this->request->redirect($this->request->query('redirect'));
		}
	}

	public function action_menu(){
	  	$this->template = View::factory('lang')
			->bind('lang', $lang)
			->bind('languages', $langs);
		$lang = $this->lang();
		$langs = Kohana::$config->load('lang.languages');
	}
	
	public function action_todo(){
		$files = Kohana::list_files('');
		//$this->find_i18n($files);
		$this->template->body = "";
		$i18n = $this->find_i18n($files);
		foreach($i18n as $file => $strs){
			foreach($strs as $val){
				list($nr, $msg, $line) = $val;
				$msg = htmlentities($msg);
				$line = str_replace($msg, "<b>$msg</b>", htmlentities($line));
				$line = preg_replace("/\s+/", " ", $line);
				$this->template->body .= "<div>$file</div><pre class='prettyprint
				     linenums'>$line</pre>";
			}
		}
	}
	
	public function find_i18n($files){
		$strings = array();
		if(!empty($files)){
			foreach($files as $file){
				
				if(is_array($file))
					$strings = array_merge($strings, $this->find_i18n($file));
				else {
					if(strpos($file, "app") < 0) continue;
					if(strpos($file, "/kohana/") > 0) continue;
					$code = file_get_contents($file);
					$m = preg_match_all("/__\(([^)]+)\)/", $code, $matches);
					$lines = explode("\n", $code);
					
					foreach($matches[1] as $k => $str){
						$line_number = substr_count($code, "\n", 0, strpos($code, $str)) + 1;
						$line = $lines[$line_number - 1];
						
						if(!isset($strings[$file]))
							$strings[$file] = array();
						
						$strings[$file][] = array($line_number, $str, $line);
					}
				}
			}
		}
		return $strings;
	}
}