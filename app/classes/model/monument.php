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

	/**
	 * @return mixed url of photo
	 */
	public function photoUrl(){
		return ORM::factory('photo')->url($this->id_monument);
	}

	/**
	 * Fetch a piece of text from the description.
	 * @param mixed If this parameter is set we search in the description for the matching string and return the surroundings.
	 * @return string Short version of the description
	 */
	public function summary($search = false){
		if($search){
		}else{
			$match = preg_match("/^(.{140,200})\s/ims", $this->description, $matches);
			if(strlen($this->description) <= 200-3){
				return substr($this->description, 0, 200-3);
			} else {
				return ($match ? $matches[1] : substr($this->description, 0, 200)) . "...";
			}
		}
	}

	/**
	 * Get photo of monument
	 * @return photo of monument
	 */
	public function getphoto() {
		return $this->photos->find();
	}

	/**
	 * Accessor method for the venue field but also lookup on FourSquare if its not yet set
	 * @author Herman
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
	 * Function to get 4-day forecast
	 * @return array with forecast-objects
	 */
	public function forecast() {
		$forecast = Wunderground::forecast($this);
		
		return $forecast;
	}
	
	/**
	 * Function to get surrounding places
	 * @param string $categories (https://developers.google.com/maps/documentation/places/supported_types)
	 * @param int $limit
	 * @return array with place-objects
	 */
	public function places($categories, $limit) {
		$places = GooglePlaces::places($this, $categories, 'distance', false, false, $limit);
		
		return $places;
	}

	/**
	 * Compare this monument against others by the features extracted
	 * in MATLAB (in database) and return the monuments that have the
	 * smallest Euclidian distance to the given monument.
	 * @param int $limit
	 * @param array with features (created by "features_filter" or "features_cat" $features
	 * @return array with monument objects
	 */
	public function similars($limit, $features = false) {
		$monuments = VisualMagic::similars($this, $limit, $features);
		
		return $monuments;
	}

	/**
	 * function guessCategory guesses the category of a monument
	 * @return id of guessed category
	 */
	public function guessCategory() {
		// use the first 5 keywords of the monument
		$keywords = $this->getKeywords(5);
		$category = null;
		$probabilities = array();
		// calculate the maximum probability of an occurrence of a tag in a category
		foreach($keywords as $keyword) {
			$tags = DB::select('*')->from('tags')->where('content', '=', $keyword)->execute();
			foreach($tags as $tag) {
				for($i=1;$i<15;$i++) {
					$probabilities[$i]=isset($probabilities[$i])?$probabilities[$i]+$tag['importance']*$tag['cat'.$i.'tfidf']:$tag['importance']*$tag['cat'.$i.'tfidf'];
				}
			}
		}
		$max = 0;
		$sum = 0;
		// check which category has highest probability
		foreach($probabilities as $key=>$probability) {
			if($probability>$max) {
				$category = $key;
				$max = $probability;
			}
			$sum+=$probability;
		}
		return $category;
	}
	
	/**
	 * function getTagRelated gets related monuments based on textual analysis
	 * @param int $limit number of related monuments requested
	 * @return array with monuments
	 */
	public function getTagRelated($limit) {
		$keywords = $this->getKeywords(5);
		$prefix = Database::instance()->table_prefix();
		// match monuments with > 2 tags the same
		$results = DB::query(Database::SELECT,"SELECT COUNT(DISTINCT(tag.content)), monument.id_monument
		FROM {$prefix}monuments monument
		INNER JOIN {$prefix}tag_monument link
		  ON link.monument = monument.id_monument
		INNER JOIN dev_tags tag
		  ON tag.id = link.tag
		WHERE tag.content IN('".implode("','",$keywords)."')
		AND monument.id_monument != ".$this->id_monument."
		GROUP BY monument.id_monument
		HAVING COUNT(DISTINCT(tag.content)) >= 2 
		limit ".$limit.";",TRUE)->execute();
		// create monumentset from result
		$monuments = ORM::factory('monument');
		foreach($results as $result) {
			$monuments = $monuments->or_where('id_monument','=',$result['id_monument']);
		}
		return $monuments;
	}
	
	/**
	 * function getKeywords uses TFIDF for text analysis and returns the top x most relevant tags
	 * @param int $limit number of keywords requested
	 * @return array with keywords
	 */
	public function getKeywords($limit = 5) {

		$prefix = Database::instance()->table_prefix();
		$tags = DB::select(
				array("tags.content", "tag"),
				array(DB::expr("({$prefix}link.occurrences * LOG(24500 / (1 + {$prefix}tags.occurrences)))"), "tfidf"),
				array("tags.id", "id"))
				->from(array("tag_monument","link"))
				->join("tags")->on("tags.id", "=", "link.tag")
				->where(DB::expr("LENGTH(tag)"), ">", 4)
				->and_where("link.monument", "=", $this->id_monument)
				->order_by("tfidf", "desc")
				->limit($limit)->execute();

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