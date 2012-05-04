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
			->order_by('p')
			->where('monument.id_monument', '!=', $this->id_monument)
			->limit($limit)
			->find_all();

			$euclidian = true;
		}

		// Return monumentlist
		return array('monuments' => $monuments, 'euclidian' => $euclidian);
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
	PRIMARY KEY (`id_monument`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
?>