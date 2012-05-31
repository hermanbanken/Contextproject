<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Monument extends Controller_Abstract_Object {

	protected static $entity = 'monument';


	/** guess category for all uncategorized monuments **/
	public function action_guesscategory() {
		
		// can take a long time
		set_time_limit(0);
		
		// select all uncategorized monuments
        $monuments = ORM::factory('monument')->where('id_category','is',null)->find_all();
		foreach($monuments as $monument) {
			
			// get keywords of monument
			$keywords = $monument->getKeywords(5);
			$category = null;
			$probabilities = array();
			
			// calculate the probability of the occurrence of the current tagword in categories
			foreach($keywords as $keyword) {
				$tags = DB::select('*')->from('tags')->where('content', '=', $keyword)->execute();
				foreach($tags as $tag) {
					for($i = 1; $i < 15; $i++) {
						$probabilities[$i] = isset($probabilities[$i]) ? $probabilities[$i] + $tag['importance'] * $tag['cat'.$i.'tfidf'] : $tag['importance'] * $tag['cat'.$i.'tfidf'];
					}
				}
			}
			$max = 0;
			$sum = 0;
			
			// check what category is most likely
			foreach($probabilities as $key => $probability) {
				if($probability > $max) {
					$category = $key;
					$max = $probability;
				}
				$sum += $probability;
			}
			
			// save the extracted category to the database
			$monument->id_category = $category;
			$monument->category_extracted = 1;
			$monument->save();
		}
		$timeTaken = time() - $_SERVER['REQUEST_TIME'];
		echo "<h1>Er zijn fucking veel monumtenten gecategoriseerd</h1>";
		echo "Dit script heeft ".($timeTaken/3600)." uur gedraaid<br />.";
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

		// keep track of categories
		$catmonuments = array();

        // collect all monuments
        $monuments = DB::select("description", "name", "id_monument", "id_category")
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
            $description = strtolower(preg_replace('/[^a-zA-Z0-9\-\_\s]/','',$monument['description']));

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
            foreach($description as $key => $des) {
                $originalkeywords[$des] = $originals[$key];
                $totaloccurrences[$des] = isset($totaloccurrences[$des]) ? ($totaloccurrences[$des] + 1) : 1;
                $tf[$des] = isset($tf[$des]) ? ($tf[$des] + $percentage) : $percentage;
                // keep track of where tags occur
                if(isset($inmonuments[$des])) {
                    $inmonuments[$des][$monument['id_monument']] = isset($inmonuments[$des][$monument['id_monument']]) ? ($inmonuments[$des][$monument['id_monument']] + 1) : 1;
                } else {
                    $inmonuments[$des] = array($monument['id_monument'] => 1);
                }
            }

            // keep track of uniqueness
            $unique = array();
            foreach($description as $des) {
                $unique[$des] = true;
            }
            foreach($unique as $key => $un) {
                $mixedoccurrences[$key] = isset($mixedoccurrences[$key]) ? ($mixedoccurrences[$key] + 1) : 1;
                $relativeoccurrences[$key] = isset($relativeoccurrences[$key])?
                    ($relativeoccurrences[$key] + $tf[$key]) / 2 : $tf[$key];
				// categories
				if(!isset($monument['id_category'])) continue;
				if(isset($catmonuments[$key])) {
					$catmonuments[$key][$monument['id_category']] = isset($catmonuments[$key][$monument['id_category']]) ? ($catmonuments[$key][$monument['id_category']] + 1) : 1;
				} else {
                    $catmonuments[$key] = array($monument['id_category'] => 1);
                }
            }
        }
		
		// foreach tag that has been found
		foreach($catmonuments as $key => $catmonument) {
			// we'll check the TFIDF of the tag relative to it's categories
			foreach($catmonument as $cat => &$category) {
				$catmonuments[$key][$cat] = isset($category) && $category > 0 ? ($category / array_sum($catmonument)) * log(19864 / $mixedoccurrences[$key]) : 0;
			//	echo $key.' occurrence: '.$category.', category: '.$cat.', tfidf: '.$cattfidf[$key][$cat].'<br />';
				
			}
		}

		
        // id to insert
        $i = 1;
        // sort by total occurrence
        arsort($totaloccurrences);
        // for each word
        foreach($totaloccurrences as $key => $occ) {

            // check if data is set
            if($key == '' || !isset($mixedoccurrences[$key]) OR !isset($totaloccurrences[$key])) continue;

            // check if really relevant
            $jaartal = preg_match('/^[^a-z]+$/', $key) OR preg_match('/^(?=.)(?i)m*(d?c{0,3}|c[dm])(l?x{0,3}|x[lc])(v?i{0,3}|i[vx])$/',$key);
            if($occ < 2
                OR (strlen($key)<5 AND !$jaartal)
                OR (!preg_match('/^[a-z]+$/',$key) AND !$jaartal)
                OR in_array($key,$stopwords)
            ) continue;


            // term frequency is saved as mean
            $tf = $relativeoccurrences[$key];

            // inverse document frequency = log(D/D(t))
            $idf = log(25500 / (1 + $mixedoccurrences[$key]));

            // the importance of a word is tf*idf calculated
            $tfidf = $tf * $idf;

            // skip irrelevant words
            if($tfidf==0) continue;
			for($j=1; $j < 15; $j++) {
				if(!isset($catmonuments[$key][$j])) $catmonuments[$key][$j] = 0; 
			}
			
			$sql = "insert into dev_tags values(".$i.",'".$originalkeywords[$key]."',".$mixedoccurrences[$key].",".$tfidf.",".$catmonuments[$key][1].",".$catmonuments[$key][2].",".$catmonuments[$key][3].",".$catmonuments[$key][4].",".$catmonuments[$key][5].",".$catmonuments[$key][6].",".$catmonuments[$key][7].",".$catmonuments[$key][8].",".$catmonuments[$key][9].",".$catmonuments[$key][10].",".$catmonuments[$key][11].",".$catmonuments[$key][12].",".$catmonuments[$key][13].",".$catmonuments[$key][14].");";
			DB::query(Database::INSERT, $sql)->execute();

            /*DB::insert("tags", array($i, $originalkeywords[$key], $mixedoccurrences[$key], $tfidf, $catmonuments[$key][1],
																									$catmonuments[$key][2],
																									$catmonuments[$key][3],
																									$catmonuments[$key][4],
																									$catmonuments[$key][5],
																									$catmonuments[$key][6],
																									$catmonuments[$key][7],
																									$catmonuments[$key][8],
																									$catmonuments[$key][9],
																									$catmonuments[$key][10],
																									$catmonuments[$key][11],
																									$catmonuments[$key][12],
																									$catmonuments[$key][13],
																									$catmonuments[$key][14]
																								))->execute();*/

            foreach($inmonuments[$key] as $monumentid => $monumentoccurrence) {
				$sql = "insert into dev_tag_monument values(".$monumentid.",".$i.",".$monumentoccurrence.");";
               	DB::query(Database::INSERT, $sql)->execute();
			 	//DB::insert("tag_monument", array($monumentid, $i, $monumentoccurrence))->execute();
            }
            $i++;
        }

        echo "<h1>KLAAR!</h1><p>".$i." tags toegevoegd...</p>";
        $v = View::factory(static::$entity.'/test');

        $this->template->body = $v;
    }

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
		if (false && isset($session['selection'])) {
			$p = $session['selection'];
		}
		else {
			$p = Arr::overwrite($this->getDefaults(), $this->request->post());
      $p = Arr::overwrite($p, $this->request->query());
		}
		// Get provinces and categories for selection
		$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();

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
    $id = $this->request->param('id');
    $user = Auth::instance()->get_user();
    $monument = ORM::factory('monument', $id);

    if($this->is_json())
    {
      $obj = $monument->object();
      $obj['photoUrl'] = $monument->photoUrl();
      $this->set_json(json_encode($obj));
    }
    else
    {
      $v = View::factory('monument/single');

      $v->bind('monument', $monument);
      $v->bind('user', $user);
      $this->template->body = $v;
    }
	}

	/**
	 * Import Monuments
	*/
	public function action_import() {
		// Set default view
		$v = View::factory('import');

		// Import monuments and save count
		$count = Importer::monuments();

		// Set variables for output
		$v->set('title', 'Monumenten Import');
		$v->set('text', 'Er zijn een '.$count.' monumenten geimporteerd.');

		// Set view to template
		$this->template->body = $v;
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