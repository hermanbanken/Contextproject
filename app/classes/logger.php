<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Logging
 *
 * Log users activity
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class Logger {
	private $tracker;
	private $log;
	private $saved = false;

	/**
	 * Create instance of logger
	 */
	public static function instance() {
		return new Logger();
	}
	
	/**
	 * Constructor of logger
	 */
	public function __construct() {
		$this->tracker = ORM::factory('tracker')->tracker();

		$this->log = ORM::factory('log');
		$this->log->id_tracker = $this->tracker->id_tracker;
	}

	/**
	 * Log category
	 * @param category $category
	 */
	public function category($category) {
		// Check if category is set
		if ($category->loaded()) {
			$this->savelog();

			$log_category = DB::insert('logs_categories', array('id_log', 'id_category'))->values(array($this->log->id_log, $category->id_category))->execute();
		}
	}

	/**
	 * Log town
	 * @param town $town
	 */
	public function town($town) {
		if ($town->loaded()) {
			$this->savelog();

			$log_category = DB::insert('logs_towns', array('id_log', 'id_town'))->values(array($this->log->id_log, $town->id_town))->execute();
		}
	}

	/**
	 * Log monument
	 * @param monument $monument
	 */
	public function monument($monument) {
		if ($monument->loaded()) {
			$this->savelog();
				
			$log_category = DB::insert('logs_monuments', array('id_log', 'id_monument'))->values(array($this->log->id_log, $monument->id_monument))->execute();
		}
	}

	/**
	 * Log keywords
	 * @param string $keywords
	 */
	public function keywords($keywords) {
		if ($keywords != '') {
			$this->savelog();

			$explode = explode(' ', $keywords);

			foreach ($explode AS $keyword) {
				$log_category = DB::insert('logs_keywords', array('id_log', 'value'))->values(array($this->log->id_log, $keyword))->execute();
			}
		}
	}
	
	/**
	 * Function which is called by every logging action
	 * saves the created log if it isn't saved yet
	 */
	public function savelog() {
		if (!$this->saved) {
			$this->log->save();

			$this->saved = true;
		}
	}

	/**
	 * Function to bind user to tracker if he logs in
	 * @param user $user
	 */
	public function bind_user($user) {
		// Check if there already is a tracker for this user
		$tracker = ORM::factory('tracker')->where('id_user', '=', $user->id)->find();

		// If there indeed is a tracker, update logs to existing tracker and delete old tracker
		if ($tracker->loaded()) {
			DB::update('logs')->set(array('id_tracker' => $tracker->id_tracker))->where('id_tracker', '=', $this->tracker->id_tracker)->execute();
			DB::delete('trackers')->where('id_tracker', '=', $this->tracker->id_tracker)->execute();

			// Set existing tracker to tracker to be used
			$this->tracker = $tracker;
			
			// Update session and cookies to right tracker
			$this->tracker->set_session_and_cookie();
		}
		else {
			// Just add a user id to the tracker
			$this->tracker->id_user = $user->id;
			$this->tracker->save();
		}
	}

	/**
	 * Create random string
	 * @param int $length
	 */
	public static function randomstring($length) {
		$randomstring = '';
		$chars = array_merge(range('A','Z'), range('a','z'), range(0, 9), array('!','#','@','$','%','^','&','*'));

		for ($i = 0; $i < $length; $i++) {
			$randomstring .= $chars[array_rand($chars)];
		}

		return $randomstring;
	}
}
?>