<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Search extends Controller_Template {

	private $_defaults = array(
			'search' => '',
			'town' => '',
			'province' => -1,
			'category' => -1,
			'sort' => 'distance',
			'latitude' => false,
			'longitude' => false,
			'distance' => 0,
			'distance_show' => 0,
			'page' => 1,
	);

	protected static $entity = "monument";

	private $_params = null;

	public function log() {
		$logger = Logger::instance();
		$logger->category(ORM::factory('category', $this->_params['category']));
		$logger->town(ORM::factory('town')->where('name', '=', $this->_params['town'])->find());
		$logger->keywords($this->_params['search']);
	}
	
	public function parameter($key = null)
	{
		if ($this->_params == null)
		{
			$this->_params = Arr::overwrite(Arr::overwrite($this->_defaults, $this->request->query()), $this->request->post());
		}
		return $key ? $this->_params[$key] : $this->_params;
	}

	public function action_map()
	{
		$query = $this->query()->select("id_monument", "lng", "lat");
		
		// Log user info
		$this->log();
		
		$monuments = $query->execute();
		$r = array("monuments"=>array(), "debug"=>array());
		foreach($monuments as $m){
			$r['monuments'][] = array($m['id_monument'], $m['lng'], $m['lat']);
		}

		$r['debug'] = array("params"=>$this->parameter(),"count"=>count($r['monuments']),"sql" => $query->compile(Database::instance()));

		$this->auto_render = false;

		if($monuments->count() > 0)
			$this->response->body(json_encode($r));
		else
			$this->response->status(404);
	}

	public function action_list(){		
		// Set view
		$result = array();

		$query = $this->query()->select('id_monument');

		// Log user info
		$this->log();
		
		$monuments = $query->execute();

		$limit = 8;
		$offset = $limit * (intval($this->parameter("page")) - 1);
		
		// Create pagination (count query without limit and offset)
		$pagination = Pagination::factory(array(
				'total_items' => $total = $monuments->count(),
				'items_per_page' => $limit,
				'view' => '../../../views/pagination'
		));
		// Tell pagination where we are
		$pagination->route_params(array('controller' => 'monument', 'action' => $this->request->action()));

		if($pagination->valid_page($this->parameter("page")))
		{
			// Redo query with all fields but also with offset and limit
			$monuments = $query
			->select("monuments.*")
			->limit($limit)
			->offset($offset)
			->execute();

			$provinces = ORM::factory('province')->order_by('name')->find_all();
			$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();

			// Get view for form
			$f = View::factory(static::$entity.'/selection');

			// Give variables to view
			$f->set('provinces', $provinces);
			$f->set('categories', $categories);
			$f->set('action', 'monument/list');
			$f->set('formname', 'filter_list');

			$result['pagination'] = (string) $pagination;
			$result['monuments'] = array();
			foreach($monuments as $m){
				$monument = ORM::factory("monument", $m['id_monument']);
				$m = $monument->object();
				$m['photoUrl'] = $monument->photoUrl();
				$m['summary'] = $monument->summary();
				$result['monuments'][] = $m;
			}
			$result['more'] = $pagination->valid_page($this->parameter("page")+1);
			$result['total'] = $total;

			// Add view to template
			$this->auto_render = false;

			// Include bench marks
			$result['bench'] = (string)View::factory('profiler/stats');

			$this->response->body(json_encode($result));
		}
	}

	function action_cloud() {
		$tags = TextualMagic::tagcloud(20);
		// create the view
		$this->template = View::factory(static::$entity.'/tagcloud')->bind('tags',$tags);
	}

	/**
	 * Create select-query from parameters
	 * @param array $fields
	 * @return Database_Query_Builder_Select Query
	 */
	function query($fields = array()) {
		$params = $this->parameter();
		extract($params);

		$query = DB::select_array($fields)->from("monuments");

		//****** FIELDS ********
		if ((($distance > 0 && $distance_show == 1) || $sort == 'distance') && $longitude && $latitude)
		{
			$calc = "((ACOS(SIN(:lng * PI() / 180) * SIN(lat * PI() / 180) + COS(:lng * PI() / 180) * COS(lat * PI() / 180) * COS((:lat - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)*1.6 ";
			$dexp = DB::expr($calc, array(
					":lng" => $longitude,
					":lat" => $latitude
			));

			$query->select(array( $dexp, "distance" ));

			if ($distance > 0 && $distance_show == 1)
			{
				$query->where($dexp, "<", $distance);
			}

		} elseif($sort == 'distance') {
			// If the longitude or latitude isn't set we can't calculate the distance.
			$sort = 'rand';
		}

		//$sql.= "HAVING 1 ";


		//******* FILTERS *******
		if ($category >= 0)
			$query->where("id_category", "=", $category);

		if ($province >= 0)
			$query->where("id_province", "=", $province);

		if ($town)
		{
			$orm_town = ORM::factory('town')->where('name', '=', $town)->find();
			$query->where("id_town", "=", $orm_town->id_town);
		}

		// add string search
		if (!empty($search)) {
			$synonyms = TextualMagic::synonyms($search);

			if ($synonyms)
			{
				$piped = $search . "|" . implode("|",$synonyms);
				$query->where(DB::expr("CONCAT(name, ' ', description)"), "REGEXP", $piped);
			}
			else
			{
				$query->where(DB::expr("CONCAT(name, ' ',description)"), "LIKE", "%$search%");
			}
		}

		//****** ORDERING ********
		switch ($sort)
		{
			case "street":
				$query->order_by("id_street");
				break;

			case "name":
				$query->order_by("name");
				break;

			case "distance":
				$query->order_by("distance", "ASC");
				break;

			case "relevance":
				if (isset($piped))
				{
					$case = "CASE
					WHEN name = '".$search."' THEN 0
					WHEN name LIKE '".$search."%' THEN 1
					WHEN name LIKE '%".$search."%' THEN 2
					WHEN name LIKE '% ".$search." %' THEN 3
					WHEN name REGEXP '".$piped."' THEN 4
					WHEN description REGEXP '".$piped."' THEN 5
					ELSE 6
					END, name ";
					$query->order_by(DB::expr($case));
					break;
				} elseif (!empty($search))
				{
					$case = "CASE
					WHEN name = '".$search."' THEN 0
					WHEN name LIKE '".$search."%' THEN 0
					WHEN name LIKE '% ".$search." %' THEN 1
					WHEN name LIKE '%".$search."%' THEN 2
					WHEN description LIKE '% ".$search." %' THEN 3
					WHEN description LIKE '%".$search."%' THEN 4
					ELSE 5
					END, name ";
					$query->order_by(DB::expr($case));
					break;
				}

				// Close is relevant, not random, do not break
				continue;

			default:
				if($longitude && $latitude)
				{
					$query->order_by("distance", "ASC");
				} else {
					$query->order_by("id_street");
				}
				break;

		}

		return $query;
	}
}

?>