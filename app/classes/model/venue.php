<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Venue extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array(
	);
	
	protected $_primary_key = "id";
	protected $_belongs_to = array(
			'monument'=> array(
					'model' => 'monument',
					'foreign_key' => 'id_monument',
			),
	);
	
	protected static $entity = "venue";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	  	`id` varchar(32) NOT NULL,
	  	`id_monument` int(10) unsigned NOT NULL,
	  	`name` varchar(100) NOT NULL,
		`location` text,
		`categories` text,
		`checkinsCount` int(8),
		`usersCount` int(8),
		`tipCount` int(4),
		`photos` text,
		`cachedOn` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  	PRIMARY KEY (`id`),
		KEY `id_monument` (`id_monument`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
	
	/**
	 * Lookup a venue for a monument
	 */
	public function match($monument){
		$this
			->where('id_monument', '=', $monument->pk())
			->find();
		
		$maxage = 30 * 24 * 3600;
		$expired = $this->loaded() ? time() - strtotime($this->cachedOn) > $maxage : true;

		if(!$expired){
			return $this;
		} else {
			$response = Request::factory(
					"https://api.foursquare.com/v2/venues/search".URL::query( array(
						"intent" => "match",
						"ll" => $monument->lng . ',' . $monument->lat,
						"query" => $monument->name,
						"v" => Kohana::$config->load('4sq.v'),
						"client_id" => Kohana::$config->load('4sq.client.id'),
						"client_secret" => Kohana::$config->load('4sq.client.secret'),
					))
				)->execute();
			$obj = @json_decode($response->body());
			
			if($response->status() != 200 || count($obj->response->venues) < 1){
				return false;
			} else {
				$v = current($obj->response->venues);
				$this->id = $v->id;
				$this->name = $v->name;
				$this->monument = $monument;
				$this->location = json_encode($v->location);
				$this->categories = json_encode($v->categories);
				$this->checkinsCount = $v->stats->checkinsCount;
				$this->usersCount = $v->stats->usersCount;
				$this->tipCount = $v->stats->tipCount;
				$this->save();
				return $this;
			}
			
			return false;	
		}
	}	
}
?>