<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for municipalities of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_Tracker extends Model_Abstract_Cultuurorm {

	protected $_rules = array(
	);

	protected $_primary_key = "id_tracker";
	protected $_belongs_to = array(
	);

	/**
	 * Get tracker from session, cookies or create new one
	 */
	public function tracker() {
		$session = Session::instance();

		// If tracker is in session, use session
		$s_tracker = $session->get('tracker');
		if (isset($s_tracker)) {
			// Find tracker
			$tracker = ORM::factory('tracker')->where('hash', '=', $s_tracker)->find()->as_array();
			$this->_load_values($tracker);
		}
		else {
			// If tracker is in cookie, use cookie
			$c_tracker = Cookie::get('tracker');
			if (isset($c_tracker)) {
				// Put tracker in session
				$session->set('tracker', $c_tracker);

				// Find tracker
				$tracker = ORM::factory('tracker')->where('hash', '=', $c_tracker)->find()->as_array();
				$this->_load_values($tracker);
			}
		}

		// If not tracker is found, create new one and create session and cookie
		if ($this->id_tracker == NULL) {
			$this->hash = Logger::randomstring(30);
			$this->dateCreated = date('Y-m-d H:i:s');
			$this->save();

			$this->set_session_and_cookie();
		}

		return $this;
	}

	/**
	 * Set session and cookie from tracker
	 */
	public function set_session_and_cookie() {
		// Init session
		$session = Session::instance();

		// Create session
		$session->set('tracker', $this->hash);

		// Create cookie
		Cookie::set('tracker', $this->hash);
	}
	
	/**
	 * Get array of monument_ids visited by tracker
	 * @return array monuments
	 */
	public function monuments() {
		$monuments = DB::select('monuments.id_monument')
		->distinct(true)
		->from('logs_monuments')
		->join('logs')->on('logs.id_log', '=', 'logs_monuments.id_log')
		->join('monuments')->on('monuments.id_monument', '=', 'logs_monuments.id_monument')
		->where('id_tracker', '=', $this->id_tracker)
		->order_by(DB::expr('RAND()'))
		->execute();
		
		$ids = array();
		foreach ($monuments AS $monument) {
			$ids[] = $monument['id_monument'];
		}
		
		return $ids;
	}
	
	/**
	 * Get array of monument_ids visited by tracker
	 * @return array monuments
	 */
	public function keywords() {
		$query = DB::select('logs_keywords.value')
		->distinct(true)
		->from('logs_keywords')
		->join('logs')->on('logs.id_log', '=', 'logs_keywords.id_log')
		->where('id_tracker', '=', $this->id_tracker)
		->order_by(DB::expr('RAND()'))
		->execute();
		
		$keywords = array();
		foreach ($query AS $keyword) {
			$keywords[] = $keyword['value'];
		}
		
		return $keywords;
	}

	protected $_table_columns = array(
		"id_tracker" => array( "type" => "int", "key" => "PRI", "extra" => "auto_increment" ),
		"id_user" 	 =>	array( "type" => "int", "key" => "MUL" ),
		"hash"		 => array( "type" => "string", "character_maximum_length" => 30 ),
		"dateCreated" => array( "type" => "string", "data_type" => "timestamp" ),
		"dateLastUpdated" => array( "type" => "string", "column_default" => "CURRENT_TIMESTAMP", "data_type" => "timestamp" ),
	);

	protected static $entity = "tracker";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
	`id_tracker` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id_user` int(10) unsigned NULL,
	`hash` varchar(30) NOT NULL,
	`dateCreated` datetime NOT NULL,
	`dateLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id_tracker`),
	KEY `id_user` (`id_user`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
?>