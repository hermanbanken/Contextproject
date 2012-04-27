<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Monument extends Controller_Abstract_Object {

	protected static $entity = 'monument';

	/**
	 * action_map
	 * Action for getting all monuments on a map view
	 */
	public function action_map(){
		$v = View::factory(static::$entity.'/map');

		$this->template->body = $v;
	}

	/**
	 * action_id
	 * Action for getting one particular object by id in single view
	 */
	public function action_id(){
		$v = View::factory('monument/single');
		$id = $this->request->param('id');

		$monument = ORM::factory('monument', $id);

		$v->bind('monument', $monument);
		$this->template->body = $v;
	}

	public function action_getsteden() {
		$monuments = ORM::factory('monument')
		->group_by('town')
		->find_all();

		$towns = array();
		foreach ($monuments AS $monument) {
			$towns[] = $monument->town;
		}

		die(json_encode($towns));
	}

	
	
	
	function buildQuery() {
		
		// extract post values
		$post = $this->request->post();
		
		// extract post values and ignore if they're default input values
		$defaults = array('zoeken','stad','-1','');
		foreach($post as $key=>$value) {
			if(!in_array($value,$defaults)) {
				${$key} = $value;
			}
		}
		
		// prepare sql statement
		$sql = "SELECT * ";
		// search for distance if needed
		if((isset($distance) AND $distance!='0') OR isset($sort) AND $sort ==  'distance') {
			$sql.= ",((ACOS(SIN(".$longitude." * PI() / 180) * SIN(lat * PI() / 180) + COS(".$longitude." * PI() / 180) * COS(lat * PI() / 180) * COS((".$latitude." - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance ";
		}
		// from dev_monuments
		$sql.= " FROM dev_monuments ";
		// prepare where clause
		$sql.= " HAVING 1 ";
		// search for distance if needed
		if(isset($distance) && $distance!='0') {
			$sql.= " AND distance < ".$distance." ";
		}
		// add category search
		if(isset($category)) {
			$sql.="AND id_category = ".$category." ";
		}
		// add town search
		if(isset($town)) {
			$sql.="AND town = '".$town."' ";
		}
		// add string search
		if(isset($search)) {
			$sql.=" AND CONCAT(name,description) LIKE '%".$search."%' ";
		}
		// ordering
		$sql.= " ORDER BY ";
		$sort=isset($sort)?$sort:"default";
		switch($sort) {
			case "relevance":
				// prioritize resultset by relevance
				if(isset($search)) {
					$sql.=" CASE
						WHEN name LIKE '".$search."%' THEN 0
						WHEN name LIKE '% ".$search." %' THEN 1
						WHEN name LIKE '%".$search."%' THEN 2
		               	WHEN description LIKE '% ".$search." %' THEN 4
						WHEN description LIKE '%".$search."%' THEN 5
			            ELSE 6
		        	END, name ";
				} else {
					$sql.= " RAND() ";
				}
				break;
			case "name":
				$sql.= " name ";
				break;
			case "distance":
				$sql.= " distance ASC ";
				break;
			case "street":
				$sql.= " street ";
				break;
			default:
				$sql.= " RAND() ";
				break;
		}
		
		// add the limit
		$sql.="LIMIT ".(isset($limit)?$limit:'500')." ";
		// add the offset
		$sql.="OFFSET ".(isset($offset)?$offset:'0').";";
		// return the query
		//die($sql);
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

	public function monumentsToJSON($monuments) {
		$_return = array();
		foreach($monuments as $key=>$monument) {
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
}
?>