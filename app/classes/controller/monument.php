<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Monument extends Controller_Abstract_Object {

	protected static $entity = 'monument';
	
	/**
	 * View to compare images visual
	 */
	public function action_visualcomparison() {
		$v = View::factory(static::$entity.'/visualcomparison');

		// Get post, query, user and monument information
		$id = $this->request->param('id');
		$user = Auth::instance()->get_user();
		$monument = ORM::factory('monument', $id);
		$post = $this->request->post();
		$query = $this->request->query();
		$session = Session::instance()->as_array();

		// If nothing is posted, use recent post in session if it exists
		if (!isset($post['posted']) && isset($session['vc'])) {
			$post = $session['vc'];
		}

		// Set categories
		$cats = array('color', 'composition', 'texture', 'orientation');
		$cur_cats = array();
		foreach ($cats AS $cat) {
			if (isset($post[$cat])) {
				$cur_cats[] = $cat;
			}
		}

		// Determine if we have to use 400 or 4 features
		$type = 'pca';
		if (isset($post['advanced'])) {
			$type = 'photo';
		}
		$photo = ORM::factory($type)->where('id_monument', '=', $monument->id_monument)->find();

		// Find features to compare on
		$features = array();
		foreach ($cur_cats AS $cat) {
			$features = array_merge($photo->features_cat($cat), $features);
		}

		// Set needed variables for view
		$similars = array();
		$posted = false;

		// If there is a post-request, find similar monuments and acknowledge post
		if (isset($post['posted']) || isset($query['posted'])) {
			$_SESSION['vc'] = $post;
			$similars = $monument->visuallySimilars(16, $features, ($type == 'pca'));
			$posted = true;
		}

		// Bind variables to view
		$v->set('selected', $cur_cats);
		$v->set('advanced', ($type != 'pca'));
		$v->set('similars', $similars);
		$v->set('posted', $posted);
		$v->bind('monument', $monument);
		$v->bind('user', $user);
			
		// Bind view to template
		$this->template->body = $v;
	}

	/**
	 * action_map
	 * Action for getting all monuments on a map view
	 */
	public function action_map(){
		$this->less('css/map.less');

		$v = View::factory(static::$entity.'/map');

		// Get data from session or set default data
		$session = Session::instance();
		$session = $session->as_array();
		if (false && isset($session['selection'])) {
			$p = $session['selection'];
		}
		else {
			$p = Arr::overwrite($this->getDefaults(), $this->request->post());
			$p = Arr::overwrite($p, $this->request->query());
		}
		// Get provinces and categories for selection
		$categories = ORM::factory('category')->where('name', '!=', 'N.V.T.')->order_by('name')->find_all();

		// Get view for form
		$f = View::factory(static::$entity.'/selection');

		// add searchterm for external links
		$search = $this->request->param('id');
		if(isset($search) AND $search != '') $p['search'] = $search;

		// Give variables to view
		$f->set('param', $p);
		$f->set('categories', $categories);
		$f->set('action', '');
		$f->set('formname', 'filter');

		$v->set('selection_form', $f);

		$this->template->body = $v;
	}

	/**
	 * action_id
	 * Action for getting one particular object by id in single view
	 */
	public function action_id()
	{
		$this->js("ca-single", "js/single.js", true);
		
		$id = $this->request->param('id');
		$monument = ORM::factory('monument', $id);
			
		if(!$monument->loaded())
			throw new HTTP_Exception_404(__('monument.notfound'));

		if($this->is_json())
		{
			$obj = $monument->object();
			$obj['photoUrl'] = $monument->thumbUrl();
			$obj['summary'] = $monument->summary();
			$obj['name'] = $monument->name();
			$this->set_json(json_encode($obj));
		}
		else
		{
			$user = Auth::instance()->get_user();
			
			// Log monument
			Logger::instance()->monument($monument);

			$meta = array(
				"og:title" => $monument->name(),
				"og:url" => URL::site("monument/id/".$monument->id_monument, "http"),
				"og:type" => "cultuurapp:monument",
				"og:image" => URL::site($monument->photoUrl(), "http"),
				"og:description" => $monument->description,
				"cultuurapp:location:longitude" => $monument->lat,
				"cultuurapp:location:latitude" => $monument->lng,
				"cultuurapp:location:altitude" => "0"
			);
			foreach($meta as $prop => $val){
				$this->snippet($prop, sprintf("<meta property='$prop' content='%s' />", addslashes($val)));
			}
			$this->snippet(
				"facebook-api",
				"<div id='fb-root'></div> <script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = '//connect.facebook.net/nl_NL/all.js#xfbml=1&appId=297097407038174';
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>"
			);

			$v = View::factory('monument/single-sleek');
			$v->bind('monument', $monument);
			$v->bind('user', $user);
			$this->template->body = $v;
		}
	}

	public function getDefaults() {
		$defaults = array('search' => '',
				'town' => '',
				'province' => '-1',
				'category' => -1,
				'sort' => 'street',
				'latitude' => '',
				'longitude' => '',
				'distance' => 0,
				'distance_show' => 0);

		return $defaults;
	}

	/**
	 * action_index
	 * Action for listing all objects of type
	 */
	public function action_list() {
		$v = View::factory(static::$entity.'/list-dynanmic');
		$this->js("ca-list", "js/list.js", true);

		// Get provinces and categories for selection
		$provinces = ORM::factory('province')->order_by('name')->find_all();
		$categories = ORM::factory('category')->where('name', '!=', 'N.V.T.')->order_by('name')->find_all();

		$tags = TextualMagic::tagcloud(20);
		// create the view
		$t = View::factory(static::$entity.'/tagcloud');
		// bind the tags
		$t->bind('tags',$tags);
		// add tagcloud to page
		$v->set('tagcloud',$t);

		// Get view for form
		$f = View::factory(static::$entity.'/selection');

		// Give variables to view
		$f->set('param', $this->getDefaults());
		$f->set('provinces', $provinces);
		$f->set('categories', $categories);
		$f->set('action', 'monument/list');
		$f->set('formname', 'filter_list');

		$v->bind('selection_form', $f);

		$this->template->body = $v;
	}
}
?>