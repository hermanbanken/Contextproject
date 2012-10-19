<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Event extends Controller_Abstract_Object {

	protected static $entity = 'event';
	
	public function action_id() {
		$this->js("ca-single", "js/single.js", true);
		
		$id = $this->request->param('id');
		$event = ORM::factory('event', $id);
			
		if(!$event->loaded())
			throw new HTTP_Exception_404(__('event.notfound'));

		if($this->is_json())
		{
			$obj = $event->object();
			$obj['photoUrl'] = $event->thumb;
			$obj['summary'] = $event->descr;
			$obj['name'] = $event->title;
			$this->set_json(json_encode($obj));
		}
		else
		{
			$user = Auth::instance()->get_user();
			
			// Log monument
			//Logger::instance()->monument($event);

			$meta = array(
				"og:title" => $event->title,
				"og:url" => URL::site("event/id/".$event->id_event, "http"),
				"og:type" => "cultuurapp:event",
				"og:image" => URL::site($event->media, "http"),
				"og:description" => $event->descr,
				"cultuurapp:location:longitude" => $event->lat,
				"cultuurapp:location:latitude" => $event->lng,
				"cultuurapp:location:altitude" => "0"
			);
			foreach($meta as $prop => $val){
				$this->snippet($prop, sprintf("<meta property='$prop' content='%s' />", addslashes($val)));
			}

			$v = View::factory('event');
			$v->bind('model', $event);
			$v->bind('user', $user);
			$this->template->body = $v;
		}
	}
	
	public function getDefaults(){}
	
	public function action_list() {
		$v = View::factory(static::$entity.'/list');
		$this->js("ca-list", "js/list-event.js", true);
		
		$events = ORM::factory('event');
		
		// Get view for form
		$f = View::factory(static::$entity.'/selection');
		// Give variables to view
		$f->set('param', $this->getDefaults());
		$f->set('type', "event");
		$f->set('action', 'event/list');
		$f->set('formname', 'filter_list');
		$f->set('types', $this->types());
		$v->bind('selection_form', $f);
		
		$this->template->body = $v;
	}
	
	private $_defaults = array(
			'search' => '',
			'town' => '',
			'sort' => '',
			'page' => 1,
			'category' => false
	);
	private $_params = null;
	
	public function parameter($key = null)
	{
		if ($this->_params == null)
		{
			$this->_params = Arr::overwrite(Arr::overwrite($this->_defaults, $this->request->query()), $this->request->post());
		}
		return $key ? $this->_params[$key] : $this->_params;
	}
	
	public function action_search(){		
		// Set view
		$result = array();

		$query = $this->query()->select('events.id_event');
		
		$events = $query->execute();

		$limit = 8;
		$offset = $limit * (intval($this->parameter("page")) - 1);
		
		// Create pagination (count query without limit and offset)
		$pagination = Pagination::factory(array(
				'total_items' => $total = $events->count(),
				'items_per_page' => $limit,
				'view' => '../../../views/pagination'
		));
		// Tell pagination where we are
		$pagination->route_params(array('controller' => 'event', 'action' => $this->request->action()));

		if($pagination->valid_page($this->parameter("page")))
		{
			// Redo query with all fields but also with offset and limit
			$events = $query
			->select("events.*")
			->limit($limit)
			->offset($offset)
			->execute();

			$categories = $this->types();

			$result['pagination'] = (string) $pagination;
			$result['events'] = array();
			foreach($events as $m){
				$event = ORM::factory("event", $m['id_event']);
				$m = $event->object();
				$m['photoUrl'] = $event->thumb;
				$m['name'] = $event->title;
				$m['summary'] = $event->descr;
				$result['events'][] = $m;
			}

			$result['more'] = $pagination->valid_page($this->parameter("page")+1);
			$result['total'] = $total;

			// Include bench marks
			// $result['bench'] = (string) View::factory('profiler/stats');
		} else {
			$this->response->status(404);
		}

		// Add view to template
		$this->auto_render = false;
		$this->response->body(json_encode($result));
	}
	
	/**
	 * Return all markers for display on the map
	 */
	public function action_markers() {
		$result = DB::select_array(array("id_event", "lat", "lng"))->from("events")->execute();
		$rows = array();
		foreach($result as $row){
			$rows[] = implode(";", $row);
		}
		$this->auto_render = false;
		$this->response->body(implode("|", $rows));
	}
	
	/**
	 * Return all types of the events
	 */
	function types(){
		if(false && $types = Cache::instance()->get('event.types', false)){} else {
			$results = DB::select_array(array(DB::expr("DISTINCT `types`")))->from("events")->as_object()->execute();
			$types = array();
			foreach($results as $result) $types[] = $result->types;
			$types = array_unique(explode(",", implode(",", $types)));
			Cache::instance()->set('event.types', $types);
		}
		return $types;
	}
	
	/**
	 * Create select-query from parameters
	 * @param array $fields
	 * @return Database_Query_Builder_Select Query
	 */
	function query($fields = array()) {
		$params = $this->parameter();
		extract($params);

		$query = DB::select_array($fields)->from("events");

		//******* FILTERS *******
		if (!empty($category) && $category != "-1")
		{
			$query->and_where("types", "LIKE", "%$category%");
		}
		
		if ($town)
		{
			$query->and_where("city", "LIKE", "%$town%");
		}

		// add string search
		if (!empty($search)) {
			$query->where(DB::expr("CONCAT(title, ' ',descr)"), "LIKE", "%$search%");
		}

		$query->group_by('events.id_event');
		
		//****** ORDERING ********
		switch ($sort)
		{
			case "name":
				$query->order_by("title");
				break;
			case "date":
				$query->order_by("date_start");
				break;
			case "relevance":
				if (isset($piped))
				{
					$case = "CASE
					WHEN title = '".$search."' THEN 0
					WHEN title LIKE '".$search."%' THEN 1
					WHEN title LIKE '%".$search."%' THEN 2
					WHEN title LIKE '% ".$search." %' THEN 3
					WHEN title REGEXP '".$piped."' THEN 4
					WHEN descr REGEXP '".$piped."' THEN 5
					ELSE 6
					END, title ";
					$query->order_by(DB::expr($case));
					break;
				} elseif (!empty($search))
				{
					$case = "CASE
					WHEN title = '".$search."' THEN 0
					WHEN title LIKE '".$search."%' THEN 0
					WHEN title LIKE '% ".$search." %' THEN 1
					WHEN title LIKE '%".$search."%' THEN 2
					WHEN descr LIKE '% ".$search." %' THEN 3
					WHEN descr LIKE '%".$search."%' THEN 4
					ELSE 5
					END, title ";
					$query->order_by(DB::expr($case));
					break;
				}

				// Close is relevant, not random, do not break
				continue;

			default:
				$query->order_by("title");
				break;
		}

		return $query;
	}

	
}