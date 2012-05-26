<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Monument extends Controller_Abstract_Object {

	protected static $entity = 'monument';
	
	/**
	 * View to compare images visual
	 */
	public function action_visualcomparison() {
		$v = View::factory(static::$entity.'/visualcomparison');

		// Get standard information
		$id = $this->request->param('id');
		$user = Auth::instance()->get_user();
		$monument = ORM::factory('monument', $id);

		// Get similar monuments
		$post = $this->request->post();
		$cats = array('color', 'composition', 'texture', 'orientation');
		$cur_cats = array();
		foreach ($cats AS $cat) {
			if (isset($post[$cat])) {
				$cur_cats[] = $cat;
			}
		}

		$photo = $monument->getphoto();
		
		$features = array();
		foreach ($cur_cats AS $cat) {
			$features = array_merge($photo->features_cat($cat), $features);
		}
		
		$similars = $monument->similars(20, $features);
		
		$v->set('selected', $cur_cats);
		$v->set('similars', $similars);
		$v->bind('monument', $monument);
		$v->bind('user', $user);
		 
		$this->template->body = $v;
	}
	
    /**
     * textual analysis
     */
    public function action_indexwords() {

        // execution can take a long time
        set_time_limit(0);
       // ini_set('memory_limit',0);

        // stopwoorden
        $stopwords = array("aan","af","al","alles","als","altijd","andere","ben","bij","daar","dan","dat","de","der","deze","die","dit","doch","doen","door","dus","een","eens","en","enz","er","etc","ge","geen","geweest","haar","had","heb","hebben","heeft","hem","hen","het","hier","hij ","hoe","hun","iemand","iets","ik","in","is","ja","je ","jouw","jullie","kan","kon","kunnen","maar","me","meer","men","met","mij","mijn","moet","na","naar","niet","niets","nog","nu","of","om","omdat","ons","onze","ook","op","over","reeds","te","ten","ter","tot","tegen","toch","toen","tot","u","uit","uw","van","veel","voor","want","waren","was","wat","we","wel","werd","wezen","wie","wij","wil","worden","zal","ze","zei","zelf","zich","zij","zijn","zo","zonder","zou");

        // all the monuments
        $limit = 26000;

        // total occurrences
        $totaloccurrences = array();

        // number of monuments where the word occurs
        $mixedoccurrences = array();

        // relativity of a word
        $relativeoccurrences = array();

        // keep the original (unedited) tags
        $originalkeywords = array();

        // keep track of in which monuments tags are
        $inmonuments = array();

        // collect all monuments
        $monuments = DB::select("description", "name", "id_monument")
            ->from("monuments")
            ->order_by("id_monument", "desc")
            ->limit($limit)
            ->execute();

        // for each monument
        foreach($monuments as $monument) {
            // find the description, lowercase it, ignore encoding
            if(!isset($monument['description'])) {
                continue;
            }

            // filter unused characters
            $description = preg_replace('/[^a-zA-Z0-9\-\_\s]/','',$monument['description']);

            // explode original keywords into array
            $originals = explode(' ',$description);

            // filter stopwords
            $originals = array_diff($originals, $stopwords);

            // explode search keywords into array
            $description = explode(' ',preg_replace('/[^a-zA-Z0-9\s]/','',$description));

            // filter stopwords
            $description = array_diff($description, $stopwords);

            // importance of an occurrence
            $percentage = 1/count($description);

            $tf = array();
            // add occurrence to total and mixed
            foreach($description as $key=>$des) {
                $originalkeywords[$des] = $originals[$key];
                $totaloccurrences[$des] = isset($totaloccurrences[$des])?($totaloccurrences[$des]+1):1;
                $tf[$des] = isset($tf[$des])?($tf[$des]+$percentage):$percentage;
                // keep track of where tags occur
                if(isset($inmonuments[$des])) {
                    $inmonuments[$des][$monument['id_monument']] = isset($inmonuments[$des][$monument['id_monument']])?($inmonuments[$des][$monument['id_monument']]+1):1;
                } else {
                    $inmonuments[$des] = array($monument['id_monument'] => 1);
                }
            }

            // keep track of uniqueness
            $unique = array();
            foreach($description as $des) {
                $unique[$des] = true;
            }
            foreach($unique as $key=>$un) {
                $mixedoccurrences[$key] = isset($mixedoccurrences[$key])?($mixedoccurrences[$key]+1):1;
                $relativeoccurrences[$key] = isset($relativeoccurrences[$key])?
                    ($relativeoccurrences[$key]+$tf[$key])/2:$tf[$key];
            }
        }


        // id to insert
        $i = 1;
        // sort by total occurrence
        arsort($totaloccurrences);
        // for each word
        foreach($totaloccurrences as $key=>$occ) {

            // check if data is set
            if($key == '' || !isset($mixedoccurrences[$key]) OR !isset($totaloccurrences[$key])) continue;

            // check if really relevant
            $jaartal = preg_match('/^[^a-z]+$/', $key) OR preg_match('/^(?=.)(?i)m*(d?c{0,3}|c[dm])(l?x{0,3}|x[lc])(v?i{0,3}|i[vx])$/',$key);
            if($occ<2
                OR (strlen($key)<5 AND !$jaartal)
                OR (!preg_match('/^[a-z]+$/',$key) AND !$jaartal)
                OR in_array($key,$stopwords)
            ) continue;


            // term frequency is saved as mean
            $tf = $relativeoccurrences[$key];

            // inverse document frequency = log(D/D(t))
            $idf = log(25500 / (1+$mixedoccurrences[$key]));

            // the importance of a word is tf*idf calculated
            $tfidf = $tf*$idf;

            // skip irrelevant words
            if($tfidf==0) continue;

            DB::insert("tags", array($i, $originalkeywords[$key], $mixedoccurrences[$key], $tfidf))->execute();

            foreach($inmonuments[$key] as $monumentid => $monumentoccurrence) {
                DB::insert("tag_monument", array($monumentid, $i, $monumentoccurrence))->execute();
            }
            $i++;
        }

        echo "<h1>KLAAR!</h1><p>".$i." tags toegevoegd...</p>";
        $v = View::factory(static::$entity.'/test');

        $this->template->body = $v;
    }

    /**
     * @param $size size of the tagcloud measured in words
     * @return array with tags and their size
     */
    public function getTagCloud($size) {

        // get random tags with high tfidf importance
        $limit = $size;
        $tagset = DB::select()->from("tags")->where(DB::expr("length(content)"), ">", 4)->and_where("occurrences", ">", 2)->and_where("importance", ">", 0.141430140)->order_by(DB::expr("RAND()"))->limit($limit)->execute();

        // convert to array
        $tags = array();
        foreach($tagset as $key=>$tag) {
            $tags[$tag['importance']] = array('content' => strtolower(Translator::translate('tag', $tag['id'], 'tag', $tag['content'])));
        }

        // sort by importance and add fontsize
        ksort($tags);
        $i = 0;
        foreach($tags as $key=>&$tag) {
            $tag['fontsize'] = 12+$i;
            $i+=1;
        }
        // sort alphabetically
        asort($tags);

       return $tags;

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

        // add searchterm for external links
        $search = $this->request->param('id');
        if(isset($search) AND $search != '') $p['search'] = $search;

        // Give variables to view
		$f->set('post', $p);
		$f->set('provinces', $provinces);
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
	public function action_id(){
		$v = View::factory('monument/single');
		$id = $this->request->param('id');
		$user = Auth::instance()->get_user();
		$monument = ORM::factory('monument', $id);
		
		$v->bind('monument', $monument);
		$v->bind('user', $user);
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
		$v->set('text', 'Er zijn een '.$i.' monumenten ge�mporteerd.');

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
            $links = DB::select()->from('monument_link')
                ->where('id_monument', '=', $id_monument)
                ->and_where('id_link', '=', $link->id_link)
                ->execute();
			if ($links->count() == 0) {
                DB::insert("monument_link", array(intval($id_monument), intval($link->id_link)))->execute();
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


	public static function getSynonyms($search) {
        $synonyms = DB::select(array("w2.word", "synoniem"))
            ->from(
                array("thesaurus_words", "w1"))
            ->join(array("thesaurus_links", "l"))->on("w1.id", "=", "l.word")
            ->join(array("thesaurus_words", "w2"))->on("w2.id", "=", "l.synonym")
            ->and_where("w1.word", "=", $search)->execute();
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
				${$key} = $value;
			}
		}

		// extract synonyms
		if(isset($search)) {
			$synonyms = $this->getSynonyms($search);
		}

		// prepare sql statement
        $query = DB::select(DB::expr("*"));
		$sql = "SELECT * ";

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
            $query->where("id_town", "=", $orm_town->id_town);
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
		// Set view
		$v = View::factory(static::$entity.'/list');

		// Get post-data
		$p = $this->request->post();

        // add searchterm for external links
        $search = $this->request->param('id');
        if(isset($search) AND $search != '') {
        	// If searching for tag, remove other filterings
        	foreach ($p AS $key => $value) {
        		unset($p[$key]);
        	}
        	$p['search'] = $search;
        }

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

        $tags = $this->getTagCloud(20);
        // create the view
        $t = View::factory(static::$entity.'/tagcloud');
        // bind the tags
        $t->bind('tags',$tags);
        // add tagcloud to page
        $v->set('tagcloud',$t);

        // Get view for form
		$f = View::factory(static::$entity.'/selection');

		// Give variables to view
		$f->set('post', $p);
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

	/*
	 * Function to parse xml in an array
	*/
	public static function xml2array($contents, $get_attributes=1, $priority = 'tag') {
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