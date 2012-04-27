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
	
	/**
	 * 
	 * Funtion to look for monuments with a search string, prioritizing the resultset
	 * @param array $values, postvalues originated from the form
	 */
	public function searchmonuments($values) {
		// check if we're in a map or not
		$map = preg_match('/map/',$this->request->initial()->referrer());
		
		// extract post values and ignore if they're default input values
		$defaults = array('zoeken','stad','-1','');
		foreach($values as $key=>$value) {
			if(!in_array($value,$defaults)) {
				${$key} = $value;
			}
		}
		
		if(!isset($limit)) {
			$limit = $map?500:20;
		}
		// prepare sql statement
		$sql = "SELECT * 
				FROM dev_monuments 
				WHERE 1 AND ";
		// add category search
		if(isset($category)) $sql .= "id_category = ".$category." ";
		// add town search
		if(isset($town))	  $sql .= "town = ".$town." ";
		
		// add string search 
		$sql .= "CONCAT(name,description) LIKE '%".$search."%' ";
		// prioritize resultset
		$sql .= "ORDER BY CASE 
				WHEN name LIKE '".$search."%' THEN 0
				WHEN name LIKE '% ".$search." %' THEN 1
				WHEN name LIKE '%".$search."%' THEN 2
               	WHEN description LIKE '% ".$search." %' THEN 4
				WHEN description LIKE '%".$search."%' THEN 5
	            ELSE 6
	        END, name ";
		// add the limit
		$sql .= "LIMIT ".$limit." ";
		// add the offset
		$sql .= "OFFSET ".(isset($offset)?$offset:'0').";";
		// execute the query
		$db = Database::instance();
		$monuments = $db->query(Database::SELECT,$sql,TRUE);
		
		$this->monumentsToJSON($monuments);
	}
	
	public function monumentsToJSON($monuments) {
		$_return = array();
		foreach($monuments as $key=>$monument) {
			//echo $monument->lng.",".$monument->lat;
			$_return[] = array("description" => $monument->description, 
								"longitude" => $monument->lng,
								"latitude" => $monument->lat,
								"name" => $monument->name,
								"id" => $monument->id_monument);
		}
		die(json_encode($_return));
	}
	
	/**
	 * getmonumenten is een actie om monumenten op te halen
	 * in de post kunnen verschillende waarden worden meegegeven
	 */
	public function action_getmonumenten() {
		// extract post values
		$post = $this->request->post();
		
		// if a search is being committed, searchmonuments must be called
		if(isset($post['search']) && $post['search'] != 'zoeken' && $post['search'] != '') {
			return $this->searchmonuments($post);
		}
		
		// check if we're in a map or not
		$map = preg_match('/map/',$this->request->initial()->referrer());
		
		// extract post values and ignore if they're default input values
		$defaults = array('zoeken','stad','-1','');
		foreach($post as $key=>$value) {
			if(!in_array($value,$defaults)) {
				${$key} = $value;
			}
		}
	
		$monuments = ORM::factory('monument');
		
		// WHERE CLAUSE
		// category
		if(isset($category)) $monuments = $monuments->where('id_category', '=', $category);
		// town
		if(isset($town)) $monuments = $monuments->where('town','=',$town);
		// search
		if(isset($search)) $monuments = $monuments->where('name' , 'like','%'.$search.'%')->or_where('description','like','%'.$search.'%');
		
		
		// DIE TOTAL IF NEEDED
		if(isset($findtotal)) die($monuments->count_all());
		
		// OFFSET ANDLIMIT
		if(isset($limit) AND isset($offset)) $monuments = $monuments->offset($offset);
		if(isset($limit)) $monuments = $monuments->limit($limit);
		
		// ORDER AT RANDOM FOR MAPS, OTHERWISE ORDER BY STREET / GIVEN ORDER
		if($map) $monuments = $monuments->order_by(DB::expr('RAND()'));
		else	 $monuments = $monuments->order_by(isset($sort)&&$sort!=='0'?$sort:'street');
		
		// if(isset($subcategorie)) $monumenten = $monumenten->where('id_subcategory','=',$subcategorie);
		$monuments = $monuments->find_all();
		// set to json 
		$this->monumentsToJSON($monuments);
	}
	
	public function action_closestby() {
		// extract post values
		$post = $this->request->post();
		$longitude = $post['longitude'];
		$latitude = $post['latitude'];
		$limit = isset($limit)?$limit:20;
		$offset = isset($offset)?$offset:0;
		$distance = isset($distance)?$distance:1;	
		// KILLER query
		$sql = " SELECT *,((ACOS(SIN(".$latitude." * PI() / 180) * SIN(lat * PI() / 180) + COS(".$latitude." * PI() / 180) * COS(lat * PI() / 180) * COS((".$longitude." - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance FROM dev_monuments HAVING distance<=".$distance." ORDER BY distance ASC limit ".$limit;
		$db = Database::instance();
		$monuments = $db->query(Database::SELECT,$sql,TRUE);
		
		$this->monumentsToJSON($monuments);
	}
}
?>