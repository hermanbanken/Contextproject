<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven / Rutger Plak / Herman Banken / Tim Eversdijk
 */
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
	public function summary($search = false)
	{
		return self::descToSummary($this->description, $this->id_monument, $search);
	}

	/**
	 * Fetch a piece of text from a text.
	 * @param $text string Text to summarize
	 * @param $search mixed If this parameter is set we search in the description for the matching string and return the surroundings.
	 * @return string Short version of the description
	 */
	public static function descToSummary($text, $id, $search = false)
	{
		$more = " " . HTML::anchor('monument/id/'.$id, __('more'));
		if($search){
			$search = preg_replace(array("~[^a-z0-9\s]+~im", "~\s~"), array("", "|"), $search);
			$success = preg_match("~(^|\s).{0,200}(".$search.").{0,200}(\s|$)~im", $text, $match);

			if($success && !empty($match[0])){
				return preg_replace(array(
					"~^\s~",
					"~\s$~",
					"~(".$search.")~im"
				), array(
					"... ",
					" ...",
					"<b>$1</b>"
				), $match[0]) . $more;
			}
		}

		return Text::limit_chars($text, 200, "...", true) . $more;
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
	public function visuallySimilars($limit, $features = false, $pca = false) {
		if ($pca) {
			$monuments = VisualMagic::similars_pca($this, $limit, $features);
		}
		else {
			$monuments = VisualMagic::similars($this, $limit, $features);
		}

		return $monuments;
	}

	/**
	 * function guessCategory guesses the category of a monument
	 * @return id of guessed category
	 */
	public function extractCategory() {
		$id = TextualMagic::extractCategory($this);

		return $id;
	}

	/**
	 * function getTagRelated gets related monuments based on textual analysis
	 * @param int $limit number of related monuments requested
	 * @return array with monuments
	 */
	public function textuallySimilars($limit) {
		$monuments = TextualMagic::related($this, $limit);

		return $monuments;
	}

	/**
	 * function tags uses TFIDF for text analysis and returns the top x most relevant tags
	 * @param int $limit number of tags requested
	 * @return array with tags
	 */
	public function tags($limit = 5) {
		$tags = TextualMagic::tags($this, $limit);

		return $tags;
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