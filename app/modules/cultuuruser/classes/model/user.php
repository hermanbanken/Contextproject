<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	/**
	 * A user has many tokens and roles
	 *
	 * @var array Relationhips
	 */
	protected $_has_many = array(
			// auth
			'roles' => array('through' => 'roles_users'),
			'user_tokens' => array(),
			// for facebook / twitter / google / yahoo identities
			'user_identity' => array(),
			'visits' => array(
					'model' => 'visit',
					'foreign_key' => 'id_user',
			),
	);

	protected $_table_columns = array(
		"id" => array(
			'type' => 'int',
			'key' => 'PRI',
		),
		"email" => array(
			'type' => 'string',
			'key' => 'UNI'
		),
		"username" => array(
			'type' => 'string',
			'key' => 'UNI'
		),
		"password" => array(
			'type' => 'string',
			'character_maximum_length' => '64'
		),
		"logins" => array(
			'type' => 'int',
		),
		"last_login" => array(
			'type' => 'int',
		)
	);

	/**
	 * Returns id's of visited monuments
	 */
	public function visited_monument_ids() {
		$list = array();
		
		foreach ($this->visits->find_all() AS $visit) {
			$list[] = $visit->id_monument;
		}
		
		return $list;
	}

	/**
	 * Returns id's of visited monuments
	 */
	public function visited_monuments($limit = NULL) {
		$list = array();
		$visits = $this->visits->order_by('date', 'desc')->limit($limit)->find_all();
		
		foreach ($visits AS $visit) {
			$list[] = ORM::factory('monument', $visit->id_monument);
		}
		
		return $list;
	}
	
	/**
	 * Generates a password of given length using mt_rand.
	 *
	 * @param int $length
	 * @return string
	 */
	public function generate_password($length = 8)
	{
		// start with a blank password
		$password = "";
		// define possible characters (does not include l, number relatively likely)
		$possible = "123456789abcdefghjkmnpqrstuvwxyz123456789^$%&@_^$%&@_";
		// add random characters to $password until $length is reached
		for ($i = 0; $i < $length; $i++)
		{
			// pick a random character from the possible ones
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$password .= $char;
		}
		return $password;
	}

	/**
	 * Transcribe name to ASCII
	 *
	 * @param string $string
	 * @return string
	 */
	function transcribe($string)
	{
		$string = strtr($string,
				"\xA1\xAA\xBA\xBF\xC0\xC1\xC2\xC3\xC5\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD8\xD9\xDA\xDB\xDD\xE0\xE1\xE2\xE3\xE5\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF8\xF9\xFA\xFB\xFD\xFF\xC4\xD6\xE4\xF6",
				"_ao_AAAAACEEEEIIIIDNOOOOOUUUYaaaaaceeeeiiiidnooooouuuyyAOao"
		);
		$string = strtr($string, array("\xC6"=>"AE", "\xDC"=>"Ue", "\xDE"=>"TH", "\xDF"=>"ss",	"\xE6"=>"ae", "\xFC"=>"ue", "\xFE"=>"th"));
		$string = preg_replace("/([^a-z0-9\\.]+)/", "", strtolower($string));
		return($string);
	}

	/**
	 * Given a string, this function will try to find an unused username by appending a number.
	 * Ex. username2, username3, username4 ...
	 *
	 * @param string $base
	 */
	function generate_username($base = '')
	{
		$base = $this->transcribe($base);
		$username = $base;
		$i = 2;
		// check for existent username
		while( $this->username_exist($username) )
		{
			$username = $base.$i;
			$i++;
		}
		return $username;
	}

	/**
	 * Check whether a username exists.
	 * @param string $username
	 * @return boolean
	 */
	public function username_exist($username)
	{
		return ( (bool) $this->unique_key_exists( $username, "username") ) ;
	}

}