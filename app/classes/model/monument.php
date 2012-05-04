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
	);

	public function photo(){
		return URL::site('photos/'.$this->id_monument).".jpg";
	}

	public function getphoto() {
		$photo = ORM::factory('photo')->where('id_monument', '=', $this->id_monument)->find();

		return $photo;
	}

	public function similars($limit) {
		// Get photo info
		$photo = $this->getphoto();

		if ($photo->total == NULL) {
			// For now we're searching for random monuments in the same category when no photo is found
			$monuments = ORM::factory('monument')
			->where('id_category', '=', $this->id_category)
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
		// Calculate percentage per category
		$cats = array();

		$monuments = DB::query(Database::SELECT, 'SELECT * FROM dev_monuments NATURAL JOIN dev_photos')->execute();
		foreach ($monuments AS $monument) {
			if ($monument['id_subcategory'] != NULL && $monument['id_subcategory'] != 0) {
				if (!isset($cats[$monument['id_subcategory']])) $cats[$monument['id_subcategory']] = 0;
				$cats[$monument['id_subcategory']]++;
			}
		}

		$cats_perc = array();
		foreach ($cats AS $cat => $value) {
			$cats_perc[$cat] = $value / array_sum($cats);
		}
		
		// Calculate percentage of similar monuments
		$cats_sim = array();

		$similars = $this->similars(400);
		$monuments = $similars['monuments'];

		foreach ($monuments AS $monument) {
			if ($monument->subcategory->id_subcategory != NULL && $monument->subcategory->id_subcategory != 0) {
				if (!isset($cats_sim[$monument->subcategory->id_subcategory])) $cats_sim[$monument->subcategory->id_subcategory] = 0;
				$cats_sim[$monument->subcategory->id_subcategory]++;
			}
		}

		$cats_sim_perc = array();
		foreach ($cats_sim AS $cat_sim => $value) {
			$cats_sim_perc[$cat_sim] = $value / array_sum($cats_sim);
		}
			
		foreach ($cats_sim_perc AS $cat_sim => $value) {
			if (!isset($max)) $max = $cats_perc[$cat_sim] - $value;
			if (!isset($old_max)) $old_max = $cats_perc[$cat_sim] - $value;
			if (!isset($final_cat)) $final_cat = $cat_sim;
			
			$max = max($cats_perc[$cat_sim] - $value, $max);
			
			if ($max > $old_max) $final_cat = $cat_sim;
			
			$old_max = $max;
		}
		
		if (isset($final_cat)) {
			$subcategory = ORM::factory('subcategory')->where('id_subcategory', '=', $final_cat)->find();
			echo 'Gevonden informatie uit visuele analyse:<br />';
			echo $subcategory->category->name.'<br />';
			echo $subcategory->name.'<br />';
		}
	}

	protected static $entity = "monument";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_monument` int(10) unsigned AUTO_INCREMENT,
	`id_category` int(11),
	`id_subcategory` int(11),
	`name` varchar(80) NOT NULL,
	`wiki_url` varchar(200) NOT NULL,
	`province` varchar(12) NOT NULL,
	`municipality` varchar(50) NOT NULL,
	`town` varchar(15) NOT NULL,
	`street` varchar(30) NOT NULL,
	`streetNumber` varchar(4) NULL,
	`zipCode` varchar(7) NOT NULL,
	`function` varchar(50) NULL,
	`description` text NULL,
	`lng` double(10,5) NULL,
	`lat` double(10,5) NULL,
	`category_extracted` tinyint(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id_monument`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
?>