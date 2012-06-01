<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Model for PCA-features of photos of monuments
 *
 * @package CultuurApp
 * @category Models
 * @author Sjoerd van Bekhoven
 */
class Model_PCA extends Model_Abstract_Cultuurorm {

	protected $_rules = array(
	);

	protected $_primary_key = "id_category";
	protected $_belongs_to = array(
			'monument'=> array(
					'model' => 'monument',
					'foreign_key' => 'id_monument',
			)
	);
	protected $_translated = array(
			"name" => "nl",
	);

	/**
	 * Return all fields that are features extracted by MATLAB
	 * @return array of featureName => double value
	 */
	public function features()
	{
		$fields = $this->as_array();
		unset($fields['id']);
		unset($fields['id_monument']);
		return $fields;
	}

	public function features_cat($cat) {
		$result = array();
		$features = $this->features();
		foreach ($features AS $feature => $value) {
			 if (preg_match('/'.$cat.'/', $feature)) {
			 	$result[$feature] = $value;
			 }
		}
		
		return $result;
	}

	protected static $entity = "pca";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_monument` int(10) unsigned NOT NULL,
  `color_pca1` double NOT NULL DEFAULT '0',
  `composition_pca1` double NOT NULL DEFAULT '0',
  `orientation_pca1` double NOT NULL DEFAULT '0',
  `texture_pca1` double NOT NULL DEFAULT '0',
  `color_pca2` double NOT NULL DEFAULT '0',
  `composition_pca2` double NOT NULL DEFAULT '0',
  `orientation_pca2` double NOT NULL DEFAULT '0',
  `texture_pca2` double NOT NULL DEFAULT '0',
  `color_pca3` double NOT NULL DEFAULT '0',
  `composition_pca3` double NOT NULL DEFAULT '0',
  `orientation_pca3` double NOT NULL DEFAULT '0',
  `texture_pca3` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

	protected $_table_columns = array(
			'id'            =>  array('type'=>'int'),
			'id_monument'   =>  array('type'=>'int'),
			"color_pca1" => array("type"=>"int"),
			"composition_pca1" => array("type"=>"int"),
			"orientation_pca1" => array("type"=>"int"),
			"texture_pca1" => array("type"=>"int"),
			"color_pca2" => array("type"=>"int"),
			"composition_pca2" => array("type"=>"int"),
			"orientation_pca2" => array("type"=>"int"),
			"texture_pca2" => array("type"=>"int"),
			"color_pca3" => array("type"=>"int"),
			"composition_pca3" => array("type"=>"int"),
			"orientation_pca3" => array("type"=>"int"),
			"texture_pca3" => array("type"=>"int"));
}
?>