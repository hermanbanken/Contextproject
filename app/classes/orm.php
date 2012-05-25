<?php defined('SYSPATH') or die('No direct access allowed.');

class ORM extends Kohana_ORM {

	/**
	 * Fields that need translation by the Translator
	 */
	protected $_translated;

	/**
	 * Handles translation of fields as a extension to the
     * retrieval of all model values, relationships, and metadata from the parent class.
	 *
	 * @param   string Column name
	 * @return  mixed
	 */
	public function __get($column)
	{	
		$val = parent::__get($column);
		
		if (isset($this->_translated[$column]) && $this->loaded())
		{
			return Translator::translate(
				$this->_object_name,
				$this->pk(),
				$column,
				$val
			);
		}
		
		return $val;
	}

}