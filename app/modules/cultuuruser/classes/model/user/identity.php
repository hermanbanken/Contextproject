<?php defined('SYSPATH') or die('No direct script access.');

class Model_User_Identity extends ORM {

	protected $_belongs_to = array(
		'user' => array()
	);

	/**
	 * Rules for the user identity.
	 * @return array Rules
	 */
	public function rules ()
	{
		return array(
			'user_id' => array(
				array('not_empty'), 
				array('numeric')
			), 
			'provider' => array(
				array('not_empty'), 
				array('max_length', array(':value', 255) )
			), 
			'identity' => array(
				array('not_empty'), 
				array('max_length', array(':value', 255) ), 
				array(array($this, 'unique_identity'), array(':validation', ':field') ) 
			)
		);
	}

	/**
	 * Triggers error if identity exists.
	 * Validation callback.
	 *
	 * @param   Validation  Validation object
	 * @param   string    field name
	 * @return  void
	 */
	public function unique_identity (Validation $validation, $field)
	{
		$identity_exists = (bool) DB::select(array('COUNT("*")', 'total_count'))
			->from($this->_table_name)
			->where('identity', '=', $validation['identity'])
			->and_where('provider', '=', $validation['provider'])
			->execute($this->_db)
			->get('total_count');
		if ($identity_exists)
		{
			$validation->error($field, 'identity_available', array($validation[$field]));
		}
	}
	
	public static function schema(){
		return Model_Abstract_Cultuurorm::schema(static::$entity, static::$schema_sql);
	}
	
	protected static $entity = "user_identity";
	protected static $schema_sql = "CREATE TABLE `%s` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) NOT NULL,
	  `provider` varchar(255) NOT NULL,
	  `identity` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;";
}