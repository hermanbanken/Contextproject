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

	public function __construct() {
		$this->tracker = ORM::factory('tracker');
		$session = Session::instance();

		// If tracker is in session, use session
		$s_tracker = $session->get('tracker');
		if (isset($s_tracker)) {
			// Find tracker
			$this->tracker = ORM::factory('tracker')->where('hash', '=', $s_tracker)->find();
		}
		else {
			// If tracker is in cookie, use cookie
			$c_tracker = Cookie::get('tracker');
			if (isset($c_tracker)) {
				// Put tracker in session
				$session->set('tracker', $c_tracker);

				// Find tracker
				$this->tracker = ORM::factory('tracker')->where('hash', '=', $c_tracker)->find();
			}
		}

		// If not tracker is found, create new one and create session and cookie
		if ($this->tracker->id_tracker == NULL) {
			$this->tracker->hash = Logger::randomstring(30);
			$this->tracker->dateCreated = date('Y-m-d H:i:s');
			$this->tracker->save();

			// Create session
			$session->set('tracker', $this->tracker->hash);

			// Create cookie
			Cookie::set('tracker', $this->tracker->hash);
		}

		$this->log = ORM::factory('log');
		$this->log->id_tracker = $this->tracker->id_tracker;
	}

	public function category($category) {
		if ($category->loaded()) {
			$this->savelog();

			$log_category = DB::insert('logs_categories', array('id_log', 'id_category'))->values(array($this->log->id_log, $category->id_category))->execute();
		}
	}

	public function town($town) {
		if ($town->loaded()) {
			$this->savelog();

			$log_category = DB::insert('logs_towns', array('id_log', 'id_town'))->values(array($this->log->id_log, $town->id_town))->execute();
		}
	}

	public function monument($monument) {
		if ($monument->loaded()) {
			$this->savelog();
				
			$log_category = DB::insert('logs_monuments', array('id_log', 'id_monument'))->values(array($this->log->id_log, $monument->id_monument))->execute();
		}
	}

	public function keywords($keywords) {
		if ($keywords != '') {
			$this->savelog();

			$explode = explode(' ', $keywords);

			foreach ($explode AS $keyword) {
				$log_category = DB::insert('logs_keywords', array('id_log', 'value'))->values(array($this->log->id_log, $keyword))->execute();
			}
		}
	}

	public function savelog() {
		if (!$this->saved) {
			$this->log->save();

			$this->saved = true;
		}
	}

	public function bind_user($user) {
		// Check if there already is a tracker for this user
		$tracker = ORM::factory('tracker')->where('id_user', '=', $user->id)->find();

		// If there indeed is a tracker, update logs to existing tracker and delete old tracker
		if ($tracker->loaded()) {
			DB::update('logs')->set(array('id_tracker' => $tracker->id_tracker))->where('id_tracker', '=', $this->tracker->id_tracker)->execute();
			DB::delete('trackers')->where('id_tracker', '=', $this->tracker->id_tracker)->execute();

			// Set existing tracker to tracker to be used
			$this->tracker = $tracker;
		}
		else {
			// Just add a user id to the tracker
			$this->tracker->id_user = $user->id;
			$this->tracker->save();
		}
	}

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