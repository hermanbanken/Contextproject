<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Template extends Kohana_Controller_Template {
	
	protected static $entity = 'abstract';
	
	// Name of template, before resets this to the actual template
	public $template = "layout";
	public $page_title = "Template Title";

	private $css = array();
	private $js = array();
	private $snip = array();
	
	/**
	 * before
	 * Set header and footer so the view won't break;
	 */
	public function before(){
		parent::before();
		
		$this->template->header = new View('header');
		$this->template->footer = new View('footer');
		
        View::bind_global('page_title', $this->page_title);
		
		$googlekey = "AIzaSyDil96bzN3gQ6LToMoz8ib0Lz39BYmTfko";
		$this
			//->less('lib/bootstrap/less/bootstrap.less')
			//->less('lib/bootstrap/less/responsive.less')
			->css('css/lib/jquery-ui-1.8.19.custom.css')
			->css('css/lib/shadowbox.css')
			->less('lib/bootstrap/docs/assets/css/docs.css')
			->less('css/app.less')
			->js('Less.js', 'js/lib/less-1.3.0.min.js', true)
			->js('jquery', 'js/lib/jquery-1.7.2.min.js', true)
			->js('shadowbox', 'js/lib/shadowbox.js', true)
			->js('raphael', 'js/lib/raphael-min.js', true)
			->js('iscroll', 'js/lib/iscroll.js', true)
			->js('jquery-ui', 'js/lib/jquery-ui-1.8.19.custom.min.js', true)
			->js('gmaps', 'http://maps.googleapis.com/maps/api/js?key='.$googlekey.'&libraries=places&sensor=true', true)
			->js('selection', 'js/selection.js', true)
			->js('ca-application', 'js/app.js', true)
			->js('clusterer', 'js/lib/markerclusterer.js', true)
			->js('bootstrap-alert', 'lib/bootstrap/js/bootstrap-alert.js')
			->js('bootstrap-dropdown', 'lib/bootstrap/js/bootstrap-dropdown.js')
			->js('bootstrap-collapse', 'lib/bootstrap/js/bootstrap-collapse.js')
			->js('bootstrap-transition', 'lib/bootstrap/js/bootstrap-transition.js')
			->js('bootstrap-tooltip', 'lib/bootstrap/js/bootstrap-tooltip.js')
			->js('bootstrap-modal', 'lib/bootstrap/js/bootstrap-modal.js')
			->js('ca-forms', 'js/forms.js')
			->snippet("og:title", "<meta property='og:title' content='CultuurApp' />")
			->snippet("og:description", "<meta property='og:description' content='Discover monuments in the Netherlands' />")
			->snippet("og:image", "<meta property='og:image' content='http://cultuurapp.nl/images/touch-icon-114x114.png' />")
			->snippet(
				"facebook-api",
				"<div id='fb-root'></div> <script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = '//connect.facebook.net/nl_NL/all.js#xfbml=1&appId=297097407038174';
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>");
	}
	
	/**
	 * css
	 * Add stylesheet to View
	 * @param file: url relative to public
	 * @param media: media attribute for link
	 * @param rel: rel attribute for link 
	 * @return Controller $this - for chainability
	 */
	public function css($file, $media = 'screen', $rel = 'stylesheet'){
		$href = file_exists(DOCROOT.$file) ? URL::site($file) : $file;
		$this->css[] = array('href'=>$href, 'media'=>$media, 'rel'=>$rel);
		return $this;
	}
	
	/**
	 * less
	 * See Controller_Template::css
	 */
	public function less($file, $media = 'screen', $rel = 'stylesheet/less'){
		$this->css($file, $media, $rel);
		return $this;
	}
	
	/**
	 * js
	 * Add javascript to View
	 * @param name: name of script, used for dependency control
	 * @param file: url relative to public
	 * @param head: to include the script in the head, otherwise it's in the footer
	 * @param depends[]: array of scripts this new script depends on
	 * @todo dependencies not yet implemented 
	 * @return Controller $this - for chainability
	 */
	public function js($name, $file, $head = false, $depends = null){
		$src = file_exists(DOCROOT.$file) ? URL::site($file."?v=".time()) : $file;
		$this->js[$name] = array('src'=>$src, 'head'=>$head, 'dependson'=>$depends);
		return $this;
	}

	/**
	 * Add a snippet to the dom of the layout
	 * @param $name
	 * @param $html
	 * @param bool $head
	 */
	public function snippet($name, $html, $head = true)
	{
		$this->snip[$name] = array($head, $html);
		return $this;
	}
	
	public function after(){
		// Prepare javascript includes
		$js_foot = array();
		$js_head = array();
		foreach($this->js as $name => $j){
			$n = sprintf("<script src='%s'></script>", $j['src']);
			if($j['head']) 
				$js_head[] = $n;
			else 
				$js_foot[] = $n;
		}
		
		// Prepare css includes
		$css = array();
		foreach($this->css as $c){
			$css[] = sprintf("<link rel='%s' type='text/css' href='%s' media='%s' />", $c['rel'], $c['href']."?v=".time(), $c['media']);
		}

		foreach($this->snip as $s){
			if($s[0])
				$js_head[] = $s[1];
			else
				$js_foot[] = $s[1];
		}
		
		$js_head = implode("\n", $js_head);
		$js_foot = implode("\n", $js_foot);
		$css = implode("\n", $css);
		
		View::set_global('js_head', $js_head);
		View::set_global('js_foot', $js_foot);
		View::set_global('css', $css);

		$class = Request::$initial->controller() . " " . Request::$initial->controller() . "-" . Request::$initial->action();
		$this->template->set("class", $class);
		
		parent::after();
	}

  public function is_json()
  {
    return $this->request->accept_type("application/json") > $this->request->accept_type("text/html");
  }

  public function set_json($json = "")
  {
    $this->auto_render = false;
    $this->response->headers("Content-Type", "application/json");
    $this->response->body($json);
  }
}
?>