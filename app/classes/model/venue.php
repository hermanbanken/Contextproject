<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for foursquare venues of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Herman Banken
 */
class Model_Venue extends Model_Abstract_Cultuurorm {

	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 32)),
			),
		);
	}

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
		`ll` varchar(50),
		`city` varchar(100),
		`address` varchar(150),
		`description` text,
		`cachedOn` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  	PRIMARY KEY (`id`),
		KEY `id_monument` (`id_monument`)
	  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
	
	/**
	 * Lookup a venue for a monument
	 */
	public function match($monument)
	{
		if(!isset($monument) || !$monument->loaded())
			return false;

		$this
			->where('id_monument', '=', $monument->pk())
			->find();
		
		$maxage = 30 * 24 * 3600;
		$expired = $this->loaded() ? time() - strtotime($this->cachedOn) > $maxage : true;

		if(!$expired){
			return $this;
		} else {
			// Clean name of all non-alpha chars
			$name_clean = preg_replace("/[^a-zA-Z0-9\s]+/", " ", $monument->name);
			
			$address = $monument->street->name ." " . $monument->streetNumber;
			$max_distance = Kohana::$config->load('4sq.match.distance');
			$query = Kohana::$config->load('4sq.match.name') ? $name_clean : null;
			
			// Get venues from FourSquare
			try {
				$response = file_get_contents(
					"https://api.foursquare.com/v2/venues/search".URL::query( array(
						"intent" => "browse",
						"ll" => $monument->lng . ',' . $monument->lat,
						"radius" => $max_distance,
						"query" => $query,
						"v" => Kohana::$config->load('4sq.v'),
						"client_id" => Kohana::$config->load('4sq.client.id'),
						"client_secret" => Kohana::$config->load('4sq.client.secret'),
					))
				);
			} catch(Exception $e){
				$response = false;
			}	
			$obj = @json_decode($response);
			
 			if(!is_object($response) || $response->status() != 200 || count($obj->response->venues) < 1){
 				return false;
 			} else {
				// Make sure we don't pick a strange result
				while($v = current($obj->response->venues)){
					// Discard if too far away
					if($v->location->distance > $max_distance)
						next($obj->response->venues);
					
					// Match address, if address if the same, we're done
					if(isset($v->location->address) && $v->location->address == $address)
						break;
					
					// Match name
					$distance = levenshtein($v->name, $name_clean);
					if($distance < 5)
						break;
					
					// Ensure we don't while(1)
					next($obj->response->venues);
				}
				
				if(!$v) return false;

				$this->find($v->id);
				$this->fromFourSquare($v, $monument);

				if($this->check())
				{
					$this->save();
					$monument->reload();
				}
				else
				{
					return false;
				}

				return $this;
			}
			
			return false;	
 		}
	}

	/**
	 * Construct new venue from a FourSquare response
	 * @param $venue
	 * @param null $monument
	 */
	public function fromFourSquare($venue, $monument = null)
	{
		$this->id = $venue->id;
		$this->name = substr($venue->name, 0, 32);
		if($monument)
			$this->monument = $monument;

		// Location
		if(isset($venue->location->city))
			$this->city = $venue->location->city;
		elseif($monument)
			$this->city = $monument->town->name;

		if(isset($venue->location->address))
			$this->address = $venue->location->address;
		elseif($monument)
			$this->address = $monument->street->name . " " . $monument->streetNumber;

		$this->location = json_encode($venue->location);
		$this->ll = $venue->location->lat . ';' . $venue->location->lng;
		// Categories
		$this->categories = json_encode($venue->categories);
		// Stats
		$this->checkinsCount = $venue->stats->checkinsCount;
		$this->usersCount = $venue->stats->usersCount;
		$this->tipCount = $venue->stats->tipCount;

		return $this;
	}
}
?>