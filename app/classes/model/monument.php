<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Monument extends Model_Abstract_Cultuurorm {

	protected $_rules = array(
        'province' => array(
            'not_empty'  => NULL,
            'min_length' => array(3),
            'max_length' => array(50),
        ),
	);
	protected $_primary_key = 'id_monument';
	protected $_belongs_to = array(
        'category'=> array(
            'model' => 'category',
            'foreign_key' => 'id_category',
        ),
        'subcategory'=> array(
            'model' => 'subcategory',
            'foreign_key' => 'id_subcategory',
        ),
        'town'=> array(
            'model' => 'town',
            'foreign_key' => 'id_town',
        ),
        'municipality'=> array(
            'model' => 'municipality',
            'foreign_key' => 'id_municipality',
        ),
        'province'=> array(
            'model' => 'province',
            'foreign_key' => 'id_province',
        ),
        'street'=> array(
            'model' => 'street',
            'foreign_key' => 'id_street',
        ),
        'function'=> array(
            'model' => 'function',
            'foreign_key' => 'id_function',
        ),
	);

	protected $_has_one = array(
        'venue' => array(
            'model' => 'venue',
            'foreign_key' => 'id_monument',
        )
	);

	protected $_has_many = array(
        'visits' => array(
            'model' => 'visit',
            'foreign_key' => 'id_monument',
        ),
        'links' => array(
            'model' => 'link',
            'foreign_key' => 'id_monument',
        ),
        'photos' => array(
            'model' => 'photo',
            'foreign_key' => 'id_monument',
        )
	);

	protected $_translated = array(
		"description" => "nl",
	);

	public function photoUrl(){
		return ORM::factory('photo')->url($this->id_monument);
	}

	public function getphoto() {
		return $this->photos->find();
	}

	/**
	 * Accessor method for the venue field but also lookup on FourSquare if its not yet set
	 */
	public function venue() {
		// Not yet looked up? Do it now
		if(!$this->venue || !$this->venue->loaded())
		{
			if( $sq = ORM::factory('venue')->match($this) )
				$this->_related['venue'] = $sq;
		}

		// Return cached venue
		return $this->venue;
	}

	/**
	 * Make sure that if the name is the name of the street of a town that the town name is included as well.
	 */
	public function extract_name() {
		$name = $this->name;

		// Woningen
		if ($this->category->id_category == 1 || preg_match('/('.strtolower($this->street->name).'|flickr)/', strtolower($name))) {
			// Check if city and street are set, since there
            // are monuments which don't have these, dunno why
            if(isset($this->town->name) && isset($this->street->name))
                $name = $this->town->name.' - '.$this->street->name.' '.$this->streetNumber;
		}

		return $name;
	}

	/**
	 * Compare this monument against others by the features extracted 
	 * in MathLad (in database) and return the monuments that have the 
	 * smallest Euclidian distance to the given monument.
	 */
	public function similars400($limit) {
		// Get photo info
		$photo = $this->getphoto();

		if ($photo->id_monument == NULL) {
			// For now we're searching for random monuments in the same category when no photo is found
			$monuments = ORM::factory('monument')
			->where('id_subcategory', '=', $this->id_subcategory)
			->order_by(DB::expr('RAND()'))
			->limit($limit)
			->find_all();

			$euclidian = false;
		}
		else {
			$euclidian = 'sqrt(';
			$i = 0;
			foreach ($photo->_original_values AS $key => $value) {
				if ($key != 'id' && $key != 'id_monument') {
					if ($i != 0) $euclidian .= ' + ';
					$euclidian .= 'POW("'.$key.'" - '.$photo->$key.', 2)';
					$i++;
				}
			}
			$euclidian .= ')';

			// Find monuments based on photos (Euclidian distance!)
			$monuments = ORM::factory('monument')
			->select('*', array($euclidian, 'p'))
			->join('photos')->on('photos.id_monument', '=', 'monument.id_monument')
			->order_by('p', 'asc')
			->where('monument.id_monument', '!=', $this->id_monument)
			->limit($limit)
			->find_all();

			$euclidian = true;
		}

		// Return monumentlist
		return array('monuments' => $monuments, 'euclidian' => $euclidian);
	}

	public function similars4($limit) {
		// Get photo info
		$photo = $this->getphoto();

		if ($photo->total == NULL) {
			// For now we're searching for random monuments in the same category when no photo is found
			$monuments = ORM::factory('monument')
			->where('id_subcategory', '=', $this->id_subcategory)
			->order_by(DB::expr('RAND()'))
			->limit($limit)
			->find_all();

			$euclidian = false;
		}
		else {
			// Find monuments based on photos (Euclidian distance!)
			$monuments = ORM::factory('monument')
			->select('*', array('sqrt(POW("color" - '.$photo->color.', 2) + POW("composition" - '.$photo->composition.', 2) + POW("orientation" - '.$photo->orientation.', 2) + POW("texture" - '.$photo->texture.', 2))', 'p'))
			->join('photos')->on('photos.id_monument', '=', 'monument.id_monument')
			->order_by('p', 'asc')
			->where('monument.id_monument', '!=', $this->id_monument)
			->limit($limit)
			->find_all();

			$euclidian = true;
		}

		// Return monumentlist
		return array('monuments' => $monuments, 'euclidian' => $euclidian);
	}

	public function extractcategory() {
		$cats = array();

		// Count numbers of monuments in each category
		$monuments = DB::query(Database::SELECT, 'SELECT * FROM dev_monuments NATURAL JOIN dev_photos WHERE id_monument != '.$this->id_monument)->execute();
		foreach ($monuments AS $monument) {
			if ($monument['id_subcategory'] != NULL && $monument['id_subcategory'] != 0) {
				if (!isset($cats[$monument['id_subcategory']])) $cats[$monument['id_subcategory']] = 0;
				$cats[$monument['id_subcategory']]++;
			}
		}

		// Calculate percentages per category
		$cats_perc = array();
		foreach ($cats AS $cat => $value) {
			$cats_perc[$cat] = $value / array_sum($cats);
		}

		$cats_sim = array();

		// Get 400 similar monuments
		$similars = $this->similars400(5);
		$monuments = $similars['monuments'];

		// Count numbers of monuments in each category from the similars
		foreach ($monuments AS $monument) {
			if ($monument->subcategory->id_subcategory != NULL && $monument->subcategory->id_subcategory != 0) {
				if (!isset($cats_sim[$monument->subcategory->id_subcategory])) $cats_sim[$monument->subcategory->id_subcategory] = 0;
				$cats_sim[$monument->subcategory->id_subcategory]++;
			}
		}

		// Calculate percentages per category
		$cats_sim_perc = array();
		foreach ($cats_sim AS $cat_sim => $value) {
			$cats_sim_perc[$cat_sim] = $value / array_sum($cats_sim);
		}
			
		echo '<style>table.hallo tr td { padding-right: 10px; }</style><table class="hallo">';
		// Compare percentages
		foreach ($cats_sim_perc AS $cat_sim => $value) {
			if (!isset($max)) $max = $value - $cats_perc[$cat_sim];
			if (!isset($old_max)) $old_max = $value - $cats_perc[$cat_sim];
			if (!isset($final_cat)) $final_cat = $cat_sim;

			// Compare difference between percentages of normal and compared monuments
			$max = max($value - $cats_perc[$cat_sim], $max);
			echo '<tr><td>'.$cat_sim.'</td><td>'.$cats_sim[$cat_sim].'</td><td>'.$value.'</td><td>'.$cats_perc[$cat_sim].'</td><td>'.($value - $cats_perc[$cat_sim]).'</td></tr>';
			// If the difference is bigger then previous, update final categorization
			if ($max > $old_max) $final_cat = $cat_sim;

			$old_max = $max;
		}
		echo '</table>';

		// If no final cat is set final categorization to false
		if (!isset($final_cat)) $final_cat = false;

		return $final_cat;
	}

	public function euclidian_distance($v1, $v2) {
		$total = 0;
		foreach ($v1 AS $key => $val1) {
			$total += pow($val1 - $v2[$key], 2);
		}

		return sqrt($total);
	}

	public function extractcategory2() {
		$cats = array();

		// Average of each category
		$monuments = DB::query(Database::SELECT, 'SELECT dev_photos.*, id_subcategory FROM dev_monuments NATURAL JOIN dev_photos WHERE id_monument != '.$this->id_monument)->execute();
		foreach ($monuments AS $monument) {
			if ($monument['id_subcategory'] != NULL && $monument['id_subcategory'] != 0) {
				if (!isset($cats[$monument['id_subcategory']])) $cats[$monument['id_subcategory']] = array();

				$id_subcategory = $monument['id_subcategory'];
				unset($monument['id_monument']);
				unset($monument['id_subcategory']);
				unset($monument['id']);
				$cats[$id_subcategory][] = $monument;
			}
		}

		$cats_rev = array();
		foreach ($cats AS $id_subcategory => $photos) {
			foreach ($photos AS $photo => $features) {
				foreach ($features AS $feature => $value) {
					$cats_rev[$id_subcategory][$feature][$photo] = $value;
				}
			}
		}

		$cats_avg = array();
		foreach ($cats_rev AS $id_subcategory => $features) {
			foreach ($features AS $feature => $values) {
				rsort($values);
				$middle = round(count($values) / 2);
				$total = $values[$middle-1];

				$cats_avg[$id_subcategory][$feature] = array_sum($values) / count($values);
			}
		}

		$features = $this->getphoto()->features();

		foreach ($cats_avg AS $id_subcategory => $values) {
			$euclidian = $this->euclidian_distance($features, $values);

			if (!isset($min)) $min = $euclidian;
			if (!isset($cid)) $cid = $id_subcategory;

			if ($min > $euclidian) {
				$min = $euclidian;
				$cid = $id_subcategory;
			}
		}

		return $cid;
	}

	/**
	 * function getKeywords uses TFIDF for text analysis and returns the top x most relevant tags
	 * @param int $limit number of keywords requested
	 * @return array with keywords
	 */
	public function getKeywords($limit = 5) {

        $sql = "SELECT tags.content as tag, (link.occurrences * LOG(24500 / (1 + tags.occurrences))) as tfidf, tags.id as id FROM dev_tag_monument link LEFT JOIN dev_tags tags ON tags.id = link.tag WHERE LENGTH(tags.content) > 4 AND link.monument = ".$this->id_monument." ORDER BY tfidf desc LIMIT ".$limit.";";
        $tags = DB::query(Database::SELECT, $sql)->execute();
        $keywords = array();
        foreach ($tags AS $keyword) {
            $keywords[] = Translator::translate('tag',$keyword['id'],'tag',$keyword['tag']);
        }
		
		
		
        return $keywords;
	}

	protected static $entity = "monument";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_monument` int(10) unsigned AUTO_INCREMENT,
	`id_category` int(10) NULL,
	`id_subcategory` int(10) NULL,
	`name` varchar(200) NULL,
	`id_street` int(10) NULL,
	`id_town` int(10) NULL,
	`id_municipality` int(10) NULL,
	`id_province` int(10) NULL,
	`streetNumber` varchar(4) NULL,
	`zipCode` varchar(7) NULL,
	`id_function` int(10) NULL,
	`description_commons` text NULL,
	`description` text NULL,
	`lng` double(10,5) NULL,
	`lat` double(10,5) NULL,
	`category_extracted` tinyint(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id_monument`),
	KEY `id_category` (`id_category`),
	KEY `id_subcategory` (`id_subcategory`),
	KEY `id_street` (`id_street`),
	KEY `id_function` (`id_function`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
?>