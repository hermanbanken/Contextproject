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

	public function action_getsteden() {
		$towns = DB::select('id_town', 'name')
		->from('towns')
		->execute()
		->as_array();

		$towns_array = array();
		foreach ($towns AS $id => $town) {
			$towns_array[$id] = $town['name'];
		}

		die(json_encode($towns_array));
	}

	public function action_getprovincies() {
		$provinces = DB::select('id_province', 'name')
		->from('provinces')
		->execute()
		->as_array();

		$province_array = array();
		foreach ($provinces AS $id => $province) {
			$province_array[$id] = $province['name'];
		}

		die(json_encode($province_array));
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

	function buildQuery($post = false) {
		if (!$post) {
			$post = $this->request->query();
		}

		if (!isset($post['not_in_session'])) {
			// Save post-data to session
			$session = Session::instance();
			$session->delete('selection');
			foreach ($this->getDefaults() AS $key => $value) {
				if (isset($post[$key])) {
					$_SESSION['selection'][$key] = $post[$key];
				}
				else {
					$_SESSION['selection'][$key] = $value;
				}
			}
		}

		// extract post values and ignore if they're default input values
		$defaults = array('zoeken','stad','-1','');
		foreach($post as $key=>$value) {
			if(!in_array($value,$defaults)) {
				${$key} = $value;
			}
		}

		// extract synonyms
		if(isset($search)) {
			$synonyms = TextualMagic::synonyms($search);
		}

		// prepare sql statement
		$query = DB::select(DB::expr("*"));
		$sql = "SELECT *, dev_monuments.id_monument AS id_monument, COUNT(dev_visits.id) AS popularity ";

		// search for distance if needed
		if((isset($distance) && $distance != 0 && isset($distance_show) && $distance_show == 1) || (isset($sort) && $sort == 'distance')) {
			$query->select_array(array(
					DB::expr("*"),
					array(DB::expr("1.6", array(":lon"=>$longitude, ":lat"=>$latitude)), "distance")
			));
			$extr = "((ACOS(SIN(:lon * PI() / 180) * SIN(lat * PI() / 180) + COS(:lon * PI() / 180) * COS(lat * PI() / 180) * COS((:lat - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)*";
			$sql.= ",((ACOS(SIN(".$longitude." * PI() / 180) * SIN(lat * PI() / 180) + COS(".$longitude." * PI() / 180) * COS(lat * PI() / 180) * COS((".$latitude." - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)*1.6 AS distance ";
		}

		$query->from("monuments");
		// from dev_monuments
		$sql.= "FROM dev_monuments ";

		$sql .= "LEFT JOIN dev_visits ON dev_visits.id_monument = dev_monuments.id_monument ";


		$sql.="GROUP BY dev_monuments.id_monument ";

		// prepare where clause
		$sql.= "HAVING 1 ";

		// search for distance if needed
		if((isset($distance) && $distance != 0 && isset($distance_show) && $distance_show == 1)) {
			$query->where("distance", "<", $distance);
			$sql.= "AND distance < ".$distance." ";
		}

		// add category search
		if(isset($category)) {
			$query->where("id_category", "=", $category);
			$sql.="AND id_category = ".$category." ";
		}

		// add category search
		if(isset($province)) {
			$query->where("id_province", "=", $province);
			$sql.="AND id_province = ".$province." ";
		}

		// add town search
		if(isset($town)) {
			$orm_town = ORM::factory('town')->where('name', '=', $town)->find();
			if ($orm_town->loaded()) {
				$query->where("id_town", "=", $orm_town->id_town);
				$sql.="AND id_town = ".$orm_town->id_town." ";
			}
			else {
				$query->where("id_town", "=", 0);
				$sql.="AND id_town = 0 ";
			}
		}

		// add string search
		if(isset($search)) {
			// if synonyms are found in the thesaurus, those synonyms have to be looked for.
			if($synonyms) {
				$syns = $search;
				foreach($synonyms as $syn) {
					$syns.="|".$syn->synoniem;
				}
				$sql.= "AND CONCAT(name,description) REGEXP '".$syns."' ";
				$query->where(DB::expr("CONCAT(name, description)"), "REGEXP", $syns);
			} else {
				// if not, a LIKE operator is enough
				$sql.="AND CONCAT(name,description) LIKE '%".$search."%' ";
				$query->where(DB::expr("CONCAT(name, description)"), "LIKE", "%$search%");
			}
		}

		// ordering
		$sql.= "ORDER BY ";
		$sort=isset($sort)?$sort:"default";
		switch($sort) {
			case "relevance":
				// prioritize resultset by relevance
				if(isset($search)) {
					if($synonyms) {
						$sql.= $case = "CASE
						WHEN name = '".$search."' THEN 0
						WHEN name LIKE '".$search."%' THEN 1
						WHEN name LIKE '%".$search."%' THEN 2
						WHEN name LIKE '% ".$search." %' THEN 3
						WHEN name REGEXP '".$syns."' THEN 4
						WHEN description REGEXP '".$syns."' THEN 5
						ELSE 6
						END, name ";
						$query->order_by(DB::expr($case));
					} else {
						$sql.= $case = "CASE
						WHEN name = '".$search."' THEN 0
						WHEN name LIKE '".$search."%' THEN 0
						WHEN name LIKE '% ".$search." %' THEN 1
						WHEN name LIKE '%".$search."%' THEN 2
						WHEN description LIKE '% ".$search." %' THEN 3
						WHEN description LIKE '%".$search."%' THEN 4
						ELSE 5
						END, name ";
						$query->order_by(DB::expr($case));
					}
				} else {
					$sql.= "RAND() ";
					$query->order_by(DB::expr("RAND()"));
				}
				break;
			case "name":
				$sql.= "name ";
				$query->order_by("name");
				break;
			case "distance":
				$sql.= "distance ASC ";
				$query->order_by("distance", "ASC");
				break;
			case "popularity":
				$sql.= "popularity DESC ";
				$query->order_by("popularity");
				break;
			case "street":
				$sql.= "id_street ";
				$query->order_by("id_street");
				break;
			default:
				$query->order_by(DB::expr("RAND()"));
				$sql.= "RAND() ";
				break;
					
		}

		// add the limit
		if (isset($limit)){
			$sql.="LIMIT " . $limit ." ";
		}
		// add the offset
		if (isset($offset)){
			$sql.="OFFSET " . $offset." ";
		}
		// return the query
		//die($sql);
		$sql.=";";
		return $sql;
	}
	/**
	 *
	 * Funtion to look for monuments with a search string, prioritizing the resultset
	 * @param array $values, postvalues originated from the form
	 */
	public function action_getmonumenten() {
		// build the query
		$sql = $this->buildQuery();

		// execute the query
		$db = Database::instance();
		$monuments = $db->query(Database::SELECT,$sql,TRUE);

		$this->monumentsToJSON($monuments);
	}

	public function action_photo() {
		$post = $this->request->post();

		$monument = ORM::factory('monument', $post['id']);

		die(json_encode(array('url' => $monument->photo())));
	}

	public static function monumentsToJSON($monuments) {
		$_return = array();
		foreach($monuments as $key=>$monument) {
			if($monument->lng == 0 OR $monument->lat == 0 OR $monument->lng > 57.5) continue;
			//echo $monument->lng.",".$monument->lat;
			$_return[] = array("distance" => isset($monument->distance)?$monument->distance:0,
					"description" => $monument->description,
					"longitude" => $monument->lng,
					"latitude" => $monument->lat,
					"name" => $monument->name,
					"id" => $monument->id_monument);
		}
		die(json_encode($_return));
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

		return;

		// Set view
		$v = View::factory(static::$entity.'/list');

		// get defaults
		$p = $this->getDefaults();

		// Get post-data
		$pform = $this->request->query();

		// override values
		foreach($pform as $key => $value) {
			if($value != '') $p[$key] = $value;
		}
	
		// If no post-data is set, get data from session or set default data
		$session = Session_Native::instance();
		$session = $session->as_array();

		// fetch variables saved in session
		foreach($session as $key => $value) {
			if(!isset($p[$key]) OR $p[$key] == '') $p[$key] = $value;
		}
	
		// add searchterm for external links
		$search = $this->request->param('id');
		if(isset($search) AND $search != '') {
			// If searching for tag, remove irrelevant filterings, but keep post values used for sorting
			unset($p['category']);
			unset($p['town']);
			unset($p['distance']);
			$p['search'] = $search;
		}

		// re-initialize unset variables
		foreach ($this->getDefaults() AS $key => $value) {
			if (!isset($p[$key])) $p[$key] = $value;
		}

		// Get query with post-data (without limit and offset)
		$sql = $this->buildQuery($p);
		$monuments = DB::query(Database::SELECT, $sql)->execute();

		// Create pagination (count query without limit and offset)
		$pagination = Pagination::factory(array(
				'total_items' => $monuments->count(),
				'items_per_page' => 8,
				'view' => '../../../views/pagination'
		));

		// Tell pagination where we are
		$pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action()));

		// Set new limit and offset to post-data
		$p['limit'] = $pagination->items_per_page;
		$p['offset'] = $pagination->offset;

		// Build new query with limit and offset
		$sql = $this->buildQuery($p);
		$monuments = DB::query(Database::SELECT, $sql)->execute();

		// Get provinces and categories for selection
		$provinces = ORM::factory('province')->order_by('name')->find_all();
		$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();

		$tags = TextualMagic::tagcloud(20);
		// create the view
		$t = View::factory(static::$entity.'/tagcloud');
		// bind the tags
		$t->bind('tags',$tags);
		// add tagcloud to page
		$v->set('tagcloud',$t);
		
		// Logging
		$logger = Logger::instance();
		$logger->category(ORM::factory('category', $p['category']));
		$logger->town(ORM::factory('town')->where('name', '=', $p['town'])->find());
		$logger->keywords($p['search']);

		// Get view for form
		$f = View::factory(static::$entity.'/selection');

		// Give variables to view
		$f->set('param', $p);
		$f->set('provinces', $provinces);
		$f->set('categories', $categories);
		$f->set('action', 'monument/list');
		$f->set('formname', 'filter_list');

		$v->set('pagination', $pagination);
		$v->set('selection_form', $f);
		$v->bind('monuments', $monuments);

		// Add view to template
		$this->template->body = $v;
	}
}
?>