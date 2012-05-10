<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Monument extends Controller_Abstract_Object {

	protected static $entity = 'monument';

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
		if (isset($session['selection'])) {
			$p = $session['selection'];
		}
		else {
			$p = $this->getDefaults();
		}
		
		// Get provinces and categories for selection
		$provinces = ORM::factory('province')->order_by('name')->find_all();
		$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();

		// Get view for form
		$f = View::factory(static::$entity.'/selection');

		// Give variables to view
		$f->set('post', $p);
		$f->set('provinces', $provinces);
		$f->set('categories', $categories);
		$f->set('formname', 'filter');

		$v->set('selection_form', $f);

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

	/*
	 * Import monuments from files stored in /public/files/monuments/
	* Should be xml files with names like 1001.xml when id_monument = 1001
	*/
	public function action_import() {
		// Set default view
		$v = View::factory('default');

		// Set time limit at unlimited (long import)
		set_time_limit(0);

		// Set counter
		$i = 0;

		// Make a list of id's from monument that are already stored
		$ids = array();
		$monuments_orm = ORM::factory('monument')->find_all();
		foreach ($monuments_orm AS $monument) {
			$ids[] = $monument->id_monument;
		}

		// Loop through all files in the map /public/files/monuments
		if ($monuments = opendir('files/monuments')) {
			while (($monument_file = readdir($monuments)) !== false) {
				// Check if file is xml-file
				if (substr($monument_file, -3) == 'xml') {
					// Set some basis arrays
					$data = array();
					$link = array();

					// Find id in filename
					$monument_id = intval($monument_file);

					// If it is an unparsed monument
					if (!in_array($monument_id, $ids)) {

						// Read file and parse into array
						$info = $this->xml2array(@file_get_contents('files/monuments/'.$monument_id.'.xml'));

						// Array for the records of wikimedia in the form (name_in_file => name_in_database)
						$wikimedia_array = array('monumentNumber' => 'id_monument',
								'title' => 'name',
								'description' => 'description_commons',
								'longitude' => 'lng',
								'latitude' => 'lat',
								'usageOnOtherWikis' => 'wiki',
								'usageOnWikiMediaCommons' => 'commons');

						// Loop through data and save in link- or data-array
						foreach ($wikimedia_array AS $wikimedia => $field) {
							if (isset($info['Monument']['wikimediaCommons'][$wikimedia])) {
								if ($wikimedia == 'usageOnOtherWikis' || $wikimedia == 'usageOnWikiMediaCommons') {
									$link[$field] = $info['Monument']['wikimediaCommons'][$wikimedia];

								}
								else {
									$data[$field] = urldecode($info['Monument']['wikimediaCommons'][$wikimedia]);
								}
							}
						}

						// Array for the records of monumentregistry in the form (name_in_file => name_in_database)
						$mr_array = array('province' => 'id_province',
								'municipality' => 'id_municipality',
								'town' => 'id_town',
								'street' => 'id_street',
								'streetNumber' => 'streetNumber',
								'zipCode' => 'zipCode',
								'mainCategory' => 'id_category',
								'subCategory' => 'id_subcategory',
								'function' => 'id_function',
								'description' => 'description');

						// Loop through data and save in link- or data-array
						foreach ($mr_array AS $mr => $field) {
							if (isset($info['Monument']['monumentRegistry'][$mr])) {
								if (is_array($info['Monument']['monumentRegistry'][$mr])) {
									$link[$field] = $info['Monument']['monumentRegistry'][$mr];
								}
								else {
									$data[$field] = urldecode($info['Monument']['monumentRegistry'][$mr]);
								}
							}
						}

						// If category is present, find id or insert new category in database
						if (isset($data['id_category'])) {
							$category = ORM::factory('category')->where('name', '=', $data['id_category'])->find();
							if ($category->name == NULL) {
								$category->name = $data['id_category'];
								try {
									$category->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['id_category'] = $category->id_category;

							// If subcategory is present, find id or insert new category in database
							if (isset($data['id_subcategory'])) {
								$subcategory = ORM::factory('subcategory')->where('name', '=', $data['id_subcategory'])->find();
								if ($subcategory->name == NULL) {
									$subcategory->name = $data['id_subcategory'];
									$subcategory->id_category = $category->id_category;
									try {
										$subcategory->save();
									}
									catch (Exception $e) {
										echo $e->getMessage().'<br />';
									}
								}
								$data['id_subcategory'] = $subcategory->id_subcategory;
							}
						}

						// If province is present, find id or insert new category in database
						if (isset($data['province'])) {
							$province = ORM::factory('province')->where('name', '=', $data['province'])->find();
							if ($province->name == NULL) {
								$province->name = $data['province'];
								try {
									$province->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['province'] = $province->id_province;

							// If municipality is present, find id or insert new category in database
							if (isset($data['municipality'])) {
								$municipality = ORM::factory('municipality')->where('name', '=', $data['municipality'])->find();
								if ($municipality->name == NULL) {
									$municipality->name = $data['municipality'];
									$municipality->id_province = $province->id_province;
									try {
										$municipality->save();
									}
									catch (Exception $e) {
										echo $e->getMessage().'<br />';
									}
								}
								$data['municipality'] = $municipality->id_municipality;

								// If town is present, find id or insert new category in database
								if (isset($data['town'])) {
									$town = ORM::factory('town')->where('name', '=', $data['town'])->find();
									if ($town->name == NULL) {
										$town->name = $data['town'];
										$town->id_municipality = $municipality->id_municipality;
										try {
											$town->save();
										}
										catch (Exception $e) {
											echo $e->getMessage().'<br />';
										}
									}
									$data['town'] = $town->id_town;

									// If street is present, find id or insert new category in database
									if (isset($data['street'])) {
										$street = ORM::factory('street')->where('name', '=', $data['street'])->and_where('id_town', '=', $town->id_town)->find();
										if ($street->name == NULL) {
											$street->name = $data['street'];
											$street->id_town = $town->id_town;
											try {
												$street->save();
											}
											catch (Exception $e) {
												echo $e->getMessage().'<br />';
											}
										}
										$data['street'] = $street->id_street;
									}
								}
							}
						}

						// If function is present, find id or insert new category in database
						if (isset($data['function'])) {
							$function = ORM::factory('function')->where('name', '=', $data['function'])->find();
							if ($function->name == NULL) {
								$function->name = $data['function'];
								try {
									$function->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['function'] = $function->id_function;
						}

						// Insert new monument in database
						$monument = ORM::factory('monument')->where('id_monument', '=', $data['id_monument'])->find();
						if ($monument->id_monument == NULL) {
							foreach ($data AS $key => $value) {
								$monument->$key = $value;
							}
							try {
								$monument->save();
							}
							catch (Exception $e) {
								echo $e->getMessage().'<br />';
							}
						}

						// Insert links in database
						foreach ($link AS $key => $url) {
							// Use recursive function (url depth is unknonw, sometimes 5 nodes)
							$this->savelink($monument->id_monument, $url, $key);
						}

						$i++;
					}
				}
			}
		}

		closedir($monuments);

		$v->set('title', 'Monumenten Import');
		$v->set('text', 'Er zijn een '.$i.' monumenten geïmporteerd.');

		$this->template->body = $v;

	}

	// Url sometimes has depth of 5 nodes, so recursive function is handy
	function savelink($id_monument, $url, $name) {
		if (is_array($url)) {
			foreach ($url AS $value) {
				$this->savelink($id_monument, $value, $name);
			}
		}
		else {
			// Look if link is already in database, otherwise insert new one
			$link = ORM::factory('link')->where('url', '=', $url)->find();
			if ($link->url == NULL) {
				$link->url = $url;
				$link->name = $name;
				try {
					$link->save();
				}
				catch (Exception $e) {
					echo $e->getMessage().'<br />';
				}
			}

			// Connect the inserted link to the monument it belongs to if not already done
			if (DB::select()->from('monument_link')->where('id_monument', '=', $id_monument)->and_where('id_link', '=', $link->id_link)->execute()->count() == 0) {
				DB::query(Database::INSERT, sprintf("INSERT INTO dev_monument_link VALUES (%d, %d)", $id_monument, $link->id_link))->execute();
			}
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

	public static function getSynonyms($search) {
		$sql = "select w2.word as synoniem
		from dev_thesaurus_words w1,
		dev_thesaurus_words w2,
		dev_thesaurus_links l
		where w1.id = l.word
		and w2.id = l.synonym
		and w1.word = '".$search."'";
		$db = Database::instance();
		$synonyms = $db->query(Database::SELECT,$sql,TRUE);
		if($synonyms->count()==0) return false;
		return $synonyms->as_array();
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
			$post = $this->request->post();
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
				${
					$key} = $value;
			}
		}

		// extract synonyms
		if(isset($search)) {
			$synonyms = $this->getSynonyms($search);
		}

		// prepare sql statement
		$sql = "SELECT * ";
		// search for distance if needed
		if((isset($distance) && $distance != 0 && isset($distance_show) && $distance_show == 1) || (isset($sort) && $sort == 'distance')) {
			$sql.= ",((ACOS(SIN(".$longitude." * PI() / 180) * SIN(lat * PI() / 180) + COS(".$longitude." * PI() / 180) * COS(lat * PI() / 180) * COS((".$latitude." - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)*1.6 AS distance ";
		}

		// from dev_monuments
		$sql.= "FROM dev_monuments ";

		// prepare where clause
		$sql.= "HAVING 1 ";

		// search for distance if needed
		if((isset($distance) && $distance != 0 && isset($distance_show) && $distance_show == 1)) {
			$sql.= "AND distance < ".$distance." ";
		}

		// add category search
		if(isset($category)) {
			$sql.="AND id_category = ".$category." ";
		}

		// add category search
		if(isset($province)) {
			$sql.="AND id_province = ".$province." ";
		}

		// add town search
		if(isset($town)) {
			$orm_town = ORM::factory('town')->where('name', '=', $town)->find();
			$sql.="AND id_town = ".$orm_town->id_town." ";
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
			} else {
				// if not, a LIKE operator is enough
				$sql.="AND CONCAT(name,description) LIKE '%".$search."%' ";
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
						$sql.="CASE
						WHEN name = '".$search."' THEN 0
						WHEN name LIKE '".$search."%' THEN 1
						WHEN name LIKE '%".$search."%' THEN 2
						WHEN name LIKE '% ".$search." %' THEN 3
						WHEN name REGEXP '".$syns."' THEN 4
						WHEN description REGEXP '".$syns."' THEN 5
						ELSE 6
						END, name ";
					} else {
						$sql.="CASE
						WHEN name = '".$search."' THEN 0
						WHEN name LIKE '".$search."%' THEN 0
						WHEN name LIKE '% ".$search." %' THEN 1
						WHEN name LIKE '%".$search."%' THEN 2
						WHEN description LIKE '% ".$search." %' THEN 3
						WHEN description LIKE '%".$search."%' THEN 4
						ELSE 5
						END, name ";
					}
				} else {
					$sql.= "RAND() ";
				}
				break;
			case "name":
				$sql.= "name ";
				break;
			case "distance":
				$sql.= "distance ASC ";
				break;
			case "street":
				$sql.= "id_street ";
				break;
			default:
				$sql.= "RAND() ";
				break;
					
		}

		// add the limit
		$sql.="LIMIT ".(isset($limit)?$limit:'40000')." ";
		
		// add the offset
		$sql.="OFFSET ".(isset($offset)?$offset:'0').";";

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

	public function monumentsToJSON($monuments) {
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
		// Set view
		$v = View::factory(static::$entity.'/list');

		// Get post-data
		$p = $this->request->post();

		// If no post-data is set, get data from session or set default data
		$session = Session::instance();
		$session = $session->as_array();
		if (count($p) == 0) {
			if (isset($session['selection'])) {
				$p = $session['selection'];
			}
			else {
				$p = $this->getDefaults();
			}
		}

		foreach ($this->getDefaults() AS $key => $value) {
			if (!isset($p[$key])) $p[$key] = $value;
		}

		// Create pagination to find out limit and offset
		$pagination = Pagination::factory(array(
				'total_items' => ORM::factory("monument")->count_all(),
				'items_per_page' => 8,
		));

		// Set new limit and offset to post-data
		$p['limit'] = $pagination->items_per_page;
		$p['offset'] = $pagination->offset;

		// Build new query with limit and offset
		$sql = $this->buildQuery($p);
		$monuments = DB::query(Database::SELECT, $sql)->execute();

		// Create pagination again with correct total
		$total = DB::query(Database::SELECT, "SELECT FOUND_ROWS();")->execute()->get('FOUND_ROWS()');
		$pagination = Pagination::factory(array(
				'total_items' => $total,
				'items_per_page' => 8,
				'view' => '../../../views/pagination'
		));
		// Tell pagination where we are
		$pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action()));

		// Get provinces and categories for selection
		$provinces = ORM::factory('province')->order_by('name')->find_all();
		$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();

		// Get view for form
		$f = View::factory(static::$entity.'/selection');

		// Give variables to view
		$f->set('post', $p);
		$f->set('provinces', $provinces);
		$f->set('categories', $categories);
		$f->set('formname', 'filter_list');

		$v->set('pagination', $pagination);
		$v->set('selection_form', $f);
		$v->bind('monuments', $monuments);

		// Add view to template
		$this->template->body = $v;
	}

	/*
	 * Function to parse xml in an array
	*/
	function xml2array($contents, $get_attributes=1, $priority = 'tag') {
		if(!$contents) return array();

		if(!function_exists('xml_parser_create')) {
			//print "'xml_parser_create()' function not found!";
			return array();
		}

		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);

		if(!$xml_values) return;//Hmm...

		//Initializations
		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();

		$current = &$xml_array; //Refference

		//Go through the tags.
		$repeated_tag_index = array();//Multiple tags with same name will be turned into an array
		foreach($xml_values as $data) {
			unset($attributes,$value);//Remove existing values, or there will be trouble

			//This command will extract these variables into the foreach scope
			// tag(string), type(string), level(int), attributes(array).
			extract($data);//We could use the array by itself, but this cooler.

			$result = array();
			$attributes_data = array();

			if(isset($value)) {
				if($priority == 'tag') $result = $value;
				else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
			}

			//Set the attributes too.
			if(isset($attributes) and $get_attributes) {
				foreach($attributes as $attr => $val) {
					if($priority == 'tag') $attributes_data[$attr] = $val;
					else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}

			//See tag status and do the needed.
			if($type == "open") {//The starting of the tag '<tag>'
				$parent[$level-1] = &$current;
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
					$current[$tag] = $result;
					if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
					$repeated_tag_index[$tag.'_'.$level] = 1;

					$current = &$current[$tag];

				} else { //There was another element with the same tag name

					if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
						$repeated_tag_index[$tag.'_'.$level]++;
					} else {//This section will make the value an array if multiple tags with the same name appear together
						$current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
						$repeated_tag_index[$tag.'_'.$level] = 2;

						if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
							$current[$tag]['0_attr'] = $current[$tag.'_attr'];
							unset($current[$tag.'_attr']);
						}

					}
					$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
					$current = &$current[$tag][$last_item_index];
				}

			} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
				//See if the key is already taken.
				if(!isset($current[$tag])) { //New Key
					$current[$tag] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 1;
					if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

				} else { //If taken, put all things inside a list(array)
					if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

						// ...push the new element into that array.
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

						if($priority == 'tag' and $get_attributes and $attributes_data) {
							$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag.'_'.$level]++;

					} else { //If it is not an array...
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if($priority == 'tag' and $get_attributes) {
							if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}

							if($attributes_data) {
								$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
					}
				}

			} elseif($type == 'close') { //End of tag '</tag>'
				$current = &$parent[$level-1];
			}
		}

		return($xml_array);
	}
}
?>