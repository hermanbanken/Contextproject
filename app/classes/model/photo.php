<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Photo extends Model_Abstract_Cultuurorm {
		
	protected $_rules = array();
	protected $_belongs_to = array(
		'monument'=> array(
			'model' => 'monument',
			'foreign_key' => 'id_monument',
		)
	);
	
	/**
	 * Get url of photo and shortcut lookup in database when 
	 * using the id of the monument model as argument
	 */
	public function url($id = false)
	{
		$url = sprintf("photos/%d.jpg", $id ? $id : $this->id_monument);
		return URL::site($url);
	}
	
	/**
	 * Return all fields that are features extracted by MathLab
	 * @return array of featureName => double value
	 */
	public function features()
	{
		$fields = $this->as_array();
		unset($fields['id']);
		unset($fields['id_monument']);
		return $fields;
	}
	
	protected static $entity = "photo";
	protected static $schema_sql = "CREATE TABLE IF NOT EXISTS `%s` (
		`id` int(10) unsigned AUTO_INCREMENT,
		`id_monument` int(10) unsigned NOT NULL,
		`ACQ_R_16_1` double NOT NULL DEFAULT '0',
		`ACQ_G_16_1` double NOT NULL DEFAULT '0',
		`ACQ_B_16_1` double NOT NULL DEFAULT '0',
		`ACQ_H_16_1` double NOT NULL DEFAULT '0',
		`ACQ_S_16_1` double NOT NULL DEFAULT '0',
		`ACQ_V_16_1` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_1` double NOT NULL DEFAULT '0',
		`ACQ_R_16_2` double NOT NULL DEFAULT '0',
		`ACQ_G_16_2` double NOT NULL DEFAULT '0',
		`ACQ_B_16_2` double NOT NULL DEFAULT '0',
		`ACQ_H_16_2` double NOT NULL DEFAULT '0',
		`ACQ_S_16_2` double NOT NULL DEFAULT '0',
		`ACQ_V_16_2` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_2` double NOT NULL DEFAULT '0',
		`ACQ_R_16_3` double NOT NULL DEFAULT '0',
		`ACQ_G_16_3` double NOT NULL DEFAULT '0',
		`ACQ_B_16_3` double NOT NULL DEFAULT '0',
		`ACQ_H_16_3` double NOT NULL DEFAULT '0',
		`ACQ_S_16_3` double NOT NULL DEFAULT '0',
		`ACQ_V_16_3` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_3` double NOT NULL DEFAULT '0',
		`ACQ_R_16_4` double NOT NULL DEFAULT '0',
		`ACQ_G_16_4` double NOT NULL DEFAULT '0',
		`ACQ_B_16_4` double NOT NULL DEFAULT '0',
		`ACQ_H_16_4` double NOT NULL DEFAULT '0',
		`ACQ_S_16_4` double NOT NULL DEFAULT '0',
		`ACQ_V_16_4` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_4` double NOT NULL DEFAULT '0',
		`ACQ_R_16_5` double NOT NULL DEFAULT '0',
		`ACQ_G_16_5` double NOT NULL DEFAULT '0',
		`ACQ_B_16_5` double NOT NULL DEFAULT '0',
		`ACQ_H_16_5` double NOT NULL DEFAULT '0',
		`ACQ_S_16_5` double NOT NULL DEFAULT '0',
		`ACQ_V_16_5` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_5` double NOT NULL DEFAULT '0',
		`ACQ_R_16_6` double NOT NULL DEFAULT '0',
		`ACQ_G_16_6` double NOT NULL DEFAULT '0',
		`ACQ_B_16_6` double NOT NULL DEFAULT '0',
		`ACQ_H_16_6` double NOT NULL DEFAULT '0',
		`ACQ_S_16_6` double NOT NULL DEFAULT '0',
		`ACQ_V_16_6` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_6` double NOT NULL DEFAULT '0',
		`ACQ_R_16_7` double NOT NULL DEFAULT '0',
		`ACQ_G_16_7` double NOT NULL DEFAULT '0',
		`ACQ_B_16_7` double NOT NULL DEFAULT '0',
		`ACQ_H_16_7` double NOT NULL DEFAULT '0',
		`ACQ_S_16_7` double NOT NULL DEFAULT '0',
		`ACQ_V_16_7` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_7` double NOT NULL DEFAULT '0',
		`ACQ_R_16_8` double NOT NULL DEFAULT '0',
		`ACQ_G_16_8` double NOT NULL DEFAULT '0',
		`ACQ_B_16_8` double NOT NULL DEFAULT '0',
		`ACQ_H_16_8` double NOT NULL DEFAULT '0',
		`ACQ_S_16_8` double NOT NULL DEFAULT '0',
		`ACQ_V_16_8` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_8` double NOT NULL DEFAULT '0',
		`ACQ_R_16_9` double NOT NULL DEFAULT '0',
		`ACQ_G_16_9` double NOT NULL DEFAULT '0',
		`ACQ_B_16_9` double NOT NULL DEFAULT '0',
		`ACQ_H_16_9` double NOT NULL DEFAULT '0',
		`ACQ_S_16_9` double NOT NULL DEFAULT '0',
		`ACQ_V_16_9` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_9` double NOT NULL DEFAULT '0',
		`ACQ_R_16_10` double NOT NULL DEFAULT '0',
		`ACQ_G_16_10` double NOT NULL DEFAULT '0',
		`ACQ_B_16_10` double NOT NULL DEFAULT '0',
		`ACQ_H_16_10` double NOT NULL DEFAULT '0',
		`ACQ_S_16_10` double NOT NULL DEFAULT '0',
		`ACQ_V_16_10` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_10` double NOT NULL DEFAULT '0',
		`ACQ_R_16_11` double NOT NULL DEFAULT '0',
		`ACQ_G_16_11` double NOT NULL DEFAULT '0',
		`ACQ_B_16_11` double NOT NULL DEFAULT '0',
		`ACQ_H_16_11` double NOT NULL DEFAULT '0',
		`ACQ_S_16_11` double NOT NULL DEFAULT '0',
		`ACQ_V_16_11` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_11` double NOT NULL DEFAULT '0',
		`ACQ_R_16_12` double NOT NULL DEFAULT '0',
		`ACQ_G_16_12` double NOT NULL DEFAULT '0',
		`ACQ_B_16_12` double NOT NULL DEFAULT '0',
		`ACQ_H_16_12` double NOT NULL DEFAULT '0',
		`ACQ_S_16_12` double NOT NULL DEFAULT '0',
		`ACQ_V_16_12` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_12` double NOT NULL DEFAULT '0',
		`ACQ_R_16_13` double NOT NULL DEFAULT '0',
		`ACQ_G_16_13` double NOT NULL DEFAULT '0',
		`ACQ_B_16_13` double NOT NULL DEFAULT '0',
		`ACQ_H_16_13` double NOT NULL DEFAULT '0',
		`ACQ_S_16_13` double NOT NULL DEFAULT '0',
		`ACQ_V_16_13` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_13` double NOT NULL DEFAULT '0',
		`ACQ_R_16_14` double NOT NULL DEFAULT '0',
		`ACQ_G_16_14` double NOT NULL DEFAULT '0',
		`ACQ_B_16_14` double NOT NULL DEFAULT '0',
		`ACQ_H_16_14` double NOT NULL DEFAULT '0',
		`ACQ_S_16_14` double NOT NULL DEFAULT '0',
		`ACQ_V_16_14` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_14` double NOT NULL DEFAULT '0',
		`ACQ_R_16_15` double NOT NULL DEFAULT '0',
		`ACQ_G_16_15` double NOT NULL DEFAULT '0',
		`ACQ_B_16_15` double NOT NULL DEFAULT '0',
		`ACQ_H_16_15` double NOT NULL DEFAULT '0',
		`ACQ_S_16_15` double NOT NULL DEFAULT '0',
		`ACQ_V_16_15` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_15` double NOT NULL DEFAULT '0',
		`ACQ_R_16_16` double NOT NULL DEFAULT '0',
		`ACQ_G_16_16` double NOT NULL DEFAULT '0',
		`ACQ_B_16_16` double NOT NULL DEFAULT '0',
		`ACQ_H_16_16` double NOT NULL DEFAULT '0',
		`ACQ_S_16_16` double NOT NULL DEFAULT '0',
		`ACQ_V_16_16` double NOT NULL DEFAULT '0',
		`ACQ_Weight_16_16` double NOT NULL DEFAULT '0',
		`Gabor_1_0` double NOT NULL DEFAULT '0',
		`Gabor_1_45` double NOT NULL DEFAULT '0',
		`Gabor_1_90` double NOT NULL DEFAULT '0',
		`Gabor_1_135` double NOT NULL DEFAULT '0',
		`Gabor_2_0` double NOT NULL DEFAULT '0',
		`Gabor_2_45` double NOT NULL DEFAULT '0',
		`Gabor_2_90` double NOT NULL DEFAULT '0',
		`Gabor_2_135` double NOT NULL DEFAULT '0',
		`Gabor_3_0` double NOT NULL DEFAULT '0',
		`Gabor_3_45` double NOT NULL DEFAULT '0',
		`Gabor_3_90` double NOT NULL DEFAULT '0',
		`Gabor_3_135` double NOT NULL DEFAULT '0',
		`Gabor_4_0` double NOT NULL DEFAULT '0',
		`Gabor_4_45` double NOT NULL DEFAULT '0',
		`Gabor_4_90` double NOT NULL DEFAULT '0',
		`Gabor_4_135` double NOT NULL DEFAULT '0',
		`Hue_Mean` double NOT NULL DEFAULT '0',
		`Hue_Median` double NOT NULL DEFAULT '0',
		`Hue_Std` double NOT NULL DEFAULT '0',
		`Hue_Skewness` double NOT NULL DEFAULT '0',
		`Hue_Kurtosis` double NOT NULL DEFAULT '0',
		`Hue_Min` double NOT NULL DEFAULT '0',
		`Hue_Max` double NOT NULL DEFAULT '0',
		`Sat_Mean` double NOT NULL DEFAULT '0',
		`Sat_Median` double NOT NULL DEFAULT '0',
		`Sat_Std` double NOT NULL DEFAULT '0',
		`Sat_Skewness` double NOT NULL DEFAULT '0',
		`Sat_Kurtosis` double NOT NULL DEFAULT '0',
		`Sat_Min` double NOT NULL DEFAULT '0',
		`Sat_Max` double NOT NULL DEFAULT '0',
		`Value_Mean` double NOT NULL DEFAULT '0',
		`Value_Median` double NOT NULL DEFAULT '0',
		`Value_Std` double NOT NULL DEFAULT '0',
		`Value_Skewness` double NOT NULL DEFAULT '0',
		`Value_Kurtosis` double NOT NULL DEFAULT '0',
		`Value_Min` double NOT NULL DEFAULT '0',
		`Value_Max` double NOT NULL DEFAULT '0',
		`HH_4_1` double NOT NULL DEFAULT '0',
		`HH_4_2` double NOT NULL DEFAULT '0',
		`HH_4_3` double NOT NULL DEFAULT '0',
		`HH_4_4` double NOT NULL DEFAULT '0',
		`SH_4_1` double NOT NULL DEFAULT '0',
		`SH_4_2` double NOT NULL DEFAULT '0',
		`SH_4_3` double NOT NULL DEFAULT '0',
		`SH_4_4` double NOT NULL DEFAULT '0',
		`VH_4_1` double NOT NULL DEFAULT '0',
		`VH_4_2` double NOT NULL DEFAULT '0',
		`VH_4_3` double NOT NULL DEFAULT '0',
		`VH_4_4` double NOT NULL DEFAULT '0',
		`HH_8_1` double NOT NULL DEFAULT '0',
		`HH_8_2` double NOT NULL DEFAULT '0',
		`HH_8_3` double NOT NULL DEFAULT '0',
		`HH_8_4` double NOT NULL DEFAULT '0',
		`HH_8_5` double NOT NULL DEFAULT '0',
		`HH_8_6` double NOT NULL DEFAULT '0',
		`HH_8_7` double NOT NULL DEFAULT '0',
		`HH_8_8` double NOT NULL DEFAULT '0',
		`SH_8_1` double NOT NULL DEFAULT '0',
		`SH_8_2` double NOT NULL DEFAULT '0',
		`SH_8_3` double NOT NULL DEFAULT '0',
		`SH_8_4` double NOT NULL DEFAULT '0',
		`SH_8_5` double NOT NULL DEFAULT '0',
		`SH_8_6` double NOT NULL DEFAULT '0',
		`SH_8_7` double NOT NULL DEFAULT '0',
		`SH_8_8` double NOT NULL DEFAULT '0',
		`VH_8_1` double NOT NULL DEFAULT '0',
		`VH_8_2` double NOT NULL DEFAULT '0',
		`VH_8_3` double NOT NULL DEFAULT '0',
		`VH_8_4` double NOT NULL DEFAULT '0',
		`VH_8_5` double NOT NULL DEFAULT '0',
		`VH_8_6` double NOT NULL DEFAULT '0',
		`VH_8_7` double NOT NULL DEFAULT '0',
		`VH_8_8` double NOT NULL DEFAULT '0',
		`Brightness_Mean` double NOT NULL DEFAULT '0',
		`Brightness_Median` double NOT NULL DEFAULT '0',
		`Brightness_Std` double NOT NULL DEFAULT '0',
		`Brightness_Skewness` double NOT NULL DEFAULT '0',
		`Brightness_Kurtosis` double NOT NULL DEFAULT '0',
		`Brightness_Min` double NOT NULL DEFAULT '0',
		`Brightness_Max` double NOT NULL DEFAULT '0',
		`GrayH_8_1` double NOT NULL DEFAULT '0',
		`GrayH_8_2` double NOT NULL DEFAULT '0',
		`GrayH_8_3` double NOT NULL DEFAULT '0',
		`GrayH_8_4` double NOT NULL DEFAULT '0',
		`GrayH_8_5` double NOT NULL DEFAULT '0',
		`GrayH_8_6` double NOT NULL DEFAULT '0',
		`GrayH_8_7` double NOT NULL DEFAULT '0',
		`GrayH_8_8` double NOT NULL DEFAULT '0',
		`GrayH_32_1` double NOT NULL DEFAULT '0',
		`GrayH_32_2` double NOT NULL DEFAULT '0',
		`GrayH_32_3` double NOT NULL DEFAULT '0',
		`GrayH_32_4` double NOT NULL DEFAULT '0',
		`GrayH_32_5` double NOT NULL DEFAULT '0',
		`GrayH_32_6` double NOT NULL DEFAULT '0',
		`GrayH_32_7` double NOT NULL DEFAULT '0',
		`GrayH_32_8` double NOT NULL DEFAULT '0',
		`GrayH_32_9` double NOT NULL DEFAULT '0',
		`GrayH_32_10` double NOT NULL DEFAULT '0',
		`GrayH_32_11` double NOT NULL DEFAULT '0',
		`GrayH_32_12` double NOT NULL DEFAULT '0',
		`GrayH_32_13` double NOT NULL DEFAULT '0',
		`GrayH_32_14` double NOT NULL DEFAULT '0',
		`GrayH_32_15` double NOT NULL DEFAULT '0',
		`GrayH_32_16` double NOT NULL DEFAULT '0',
		`GrayH_32_17` double NOT NULL DEFAULT '0',
		`GrayH_32_18` double NOT NULL DEFAULT '0',
		`GrayH_32_19` double NOT NULL DEFAULT '0',
		`GrayH_32_20` double NOT NULL DEFAULT '0',
		`GrayH_32_21` double NOT NULL DEFAULT '0',
		`GrayH_32_22` double NOT NULL DEFAULT '0',
		`GrayH_32_23` double NOT NULL DEFAULT '0',
		`GrayH_32_24` double NOT NULL DEFAULT '0',
		`GrayH_32_25` double NOT NULL DEFAULT '0',
		`GrayH_32_26` double NOT NULL DEFAULT '0',
		`GrayH_32_27` double NOT NULL DEFAULT '0',
		`GrayH_32_28` double NOT NULL DEFAULT '0',
		`GrayH_32_29` double NOT NULL DEFAULT '0',
		`GrayH_32_30` double NOT NULL DEFAULT '0',
		`GrayH_32_31` double NOT NULL DEFAULT '0',
		`GrayH_32_32` double NOT NULL DEFAULT '0',
		`Red_Mean` double NOT NULL DEFAULT '0',
		`Red_Median` double NOT NULL DEFAULT '0',
		`Red_Std` double NOT NULL DEFAULT '0',
		`Red_Skewness` double NOT NULL DEFAULT '0',
		`Red_Kurtosis` double NOT NULL DEFAULT '0',
		`Red_Min` double NOT NULL DEFAULT '0',
		`Red_Max` double NOT NULL DEFAULT '0',
		`Green_Mean` double NOT NULL DEFAULT '0',
		`Green_Median` double NOT NULL DEFAULT '0',
		`Green_Std` double NOT NULL DEFAULT '0',
		`Green_Skewness` double NOT NULL DEFAULT '0',
		`Green_Kurtosis` double NOT NULL DEFAULT '0',
		`Green_Min` double NOT NULL DEFAULT '0',
		`Green_Max` double NOT NULL DEFAULT '0',
		`Blue_Mean` double NOT NULL DEFAULT '0',
		`Blue_Median` double NOT NULL DEFAULT '0',
		`Blue_Std` double NOT NULL DEFAULT '0',
		`Blue_Skewness` double NOT NULL DEFAULT '0',
		`Blue_Kurtosis` double NOT NULL DEFAULT '0',
		`Blue_Min` double NOT NULL DEFAULT '0',
		`Blue_Max` double NOT NULL DEFAULT '0',
		`RH_4_1` double NOT NULL DEFAULT '0',
		`RH_4_2` double NOT NULL DEFAULT '0',
		`RH_4_3` double NOT NULL DEFAULT '0',
		`RH_4_4` double NOT NULL DEFAULT '0',
		`GH_4_1` double NOT NULL DEFAULT '0',
		`GH_4_2` double NOT NULL DEFAULT '0',
		`GH_4_3` double NOT NULL DEFAULT '0',
		`GH_4_4` double NOT NULL DEFAULT '0',
		`BH_4_1` double NOT NULL DEFAULT '0',
		`BH_4_2` double NOT NULL DEFAULT '0',
		`BH_4_3` double NOT NULL DEFAULT '0',
		`BH_4_4` double NOT NULL DEFAULT '0',
		`RH_8_1` double NOT NULL DEFAULT '0',
		`RH_8_2` double NOT NULL DEFAULT '0',
		`RH_8_3` double NOT NULL DEFAULT '0',
		`RH_8_4` double NOT NULL DEFAULT '0',
		`RH_8_5` double NOT NULL DEFAULT '0',
		`RH_8_6` double NOT NULL DEFAULT '0',
		`RH_8_7` double NOT NULL DEFAULT '0',
		`RH_8_8` double NOT NULL DEFAULT '0',
		`GH_8_1` double NOT NULL DEFAULT '0',
		`GH_8_2` double NOT NULL DEFAULT '0',
		`GH_8_3` double NOT NULL DEFAULT '0',
		`GH_8_4` double NOT NULL DEFAULT '0',
		`GH_8_5` double NOT NULL DEFAULT '0',
		`GH_8_6` double NOT NULL DEFAULT '0',
		`GH_8_7` double NOT NULL DEFAULT '0',
		`GH_8_8` double NOT NULL DEFAULT '0',
		`BH_8_1` double NOT NULL DEFAULT '0',
		`BH_8_2` double NOT NULL DEFAULT '0',
		`BH_8_3` double NOT NULL DEFAULT '0',
		`BH_8_4` double NOT NULL DEFAULT '0',
		`BH_8_5` double NOT NULL DEFAULT '0',
		`BH_8_6` double NOT NULL DEFAULT '0',
		`BH_8_7` double NOT NULL DEFAULT '0',
		`BH_8_8` double NOT NULL DEFAULT '0',
		`Total_Num_Segments` double NOT NULL DEFAULT '0',
		`Mean_Area` double NOT NULL DEFAULT '0',
		`Min_Area` double NOT NULL DEFAULT '0',
		`Max_Area` double NOT NULL DEFAULT '0',
		`Median_Area` double NOT NULL DEFAULT '0',
		`Num_Segments_2x2_1` double NOT NULL DEFAULT '0',
		`Num_Segments_2x2_2` double NOT NULL DEFAULT '0',
		`Num_Segments_2x2_3` double NOT NULL DEFAULT '0',
		`Num_Segments_2x2_4` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_1` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_2` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_3` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_4` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_5` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_6` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_7` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_8` double NOT NULL DEFAULT '0',
		`Num_Segments_3x3_9` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_1` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_2` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_3` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_4` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_5` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_6` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_7` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_8` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_9` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_10` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_11` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_12` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_13` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_14` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_15` double NOT NULL DEFAULT '0',
		`Num_Segments_4x4_16` double NOT NULL DEFAULT '0',
		`BDIP_2x2_1` double NOT NULL DEFAULT '0',
		`BDIP_2x2_2` double NOT NULL DEFAULT '0',
		`BDIP_2x2_3` double NOT NULL DEFAULT '0',
		`BDIP_2x2_4` double NOT NULL DEFAULT '0',
		`BDIP_3x3_1` double NOT NULL DEFAULT '0',
		`BDIP_3x3_2` double NOT NULL DEFAULT '0',
		`BDIP_3x3_3` double NOT NULL DEFAULT '0',
		`BDIP_3x3_4` double NOT NULL DEFAULT '0',
		`BDIP_3x3_5` double NOT NULL DEFAULT '0',
		`BDIP_3x3_6` double NOT NULL DEFAULT '0',
		`BDIP_3x3_7` double NOT NULL DEFAULT '0',
		`BDIP_3x3_8` double NOT NULL DEFAULT '0',
		`BDIP_3x3_9` double NOT NULL DEFAULT '0',
		`BDIP_4x4_1` double NOT NULL DEFAULT '0',
		`BDIP_4x4_2` double NOT NULL DEFAULT '0',
		`BDIP_4x4_3` double NOT NULL DEFAULT '0',
		`BDIP_4x4_4` double NOT NULL DEFAULT '0',
		`BDIP_4x4_5` double NOT NULL DEFAULT '0',
		`BDIP_4x4_6` double NOT NULL DEFAULT '0',
		`BDIP_4x4_7` double NOT NULL DEFAULT '0',
		`BDIP_4x4_8` double NOT NULL DEFAULT '0',
		`BDIP_4x4_9` double NOT NULL DEFAULT '0',
		`BDIP_4x4_10` double NOT NULL DEFAULT '0',
		`BDIP_4x4_11` double NOT NULL DEFAULT '0',
		`BDIP_4x4_12` double NOT NULL DEFAULT '0',
		`BDIP_4x4_13` double NOT NULL DEFAULT '0',
		`BDIP_4x4_14` double NOT NULL DEFAULT '0',
		`BDIP_4x4_15` double NOT NULL DEFAULT '0',
		`BDIP_4x4_16` double NOT NULL DEFAULT '0',
		`BVLC_1_2x2_1` double NOT NULL DEFAULT '0',
		`BVLC_1_2x2_2` double NOT NULL DEFAULT '0',
		`BVLC_1_2x2_3` double NOT NULL DEFAULT '0',
		`BVLC_1_2x2_4` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_1` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_2` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_3` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_4` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_5` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_6` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_7` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_8` double NOT NULL DEFAULT '0',
		`BVLC_1_3x3_9` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_1` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_2` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_3` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_4` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_5` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_6` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_7` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_8` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_9` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_10` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_11` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_12` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_13` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_14` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_15` double NOT NULL DEFAULT '0',
		`BVLC_1_4x4_16` double NOT NULL DEFAULT '0',
		`Contrast_1` double NOT NULL DEFAULT '0',
		`Correlation_1` double NOT NULL DEFAULT '0',
		`Energy_1` double NOT NULL DEFAULT '0',
		`Homogeneity_1` double NOT NULL DEFAULT '0',
		`Contrast_2` double NOT NULL DEFAULT '0',
		`Correlation_2` double NOT NULL DEFAULT '0',
		`Energy_2` double NOT NULL DEFAULT '0',
		`Homogeneity_2` double NOT NULL DEFAULT '0',
		`Contrast_4` double NOT NULL DEFAULT '0',
		`Correlation_4` double NOT NULL DEFAULT '0',
		`Energy_4` double NOT NULL DEFAULT '0',
		`Homogeneity_4` double NOT NULL DEFAULT '0',
		`Contrast_8` double NOT NULL DEFAULT '0',
		`Correlation_8` double NOT NULL DEFAULT '0',
		`Energy_8` double NOT NULL DEFAULT '0',
		`Homogeneity_8` double NOT NULL DEFAULT '0',
		`Edge_Amount_Sobel` double NOT NULL DEFAULT '0',
		`Entropy` double NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

    protected $_table_columns = array(
        'id'            =>  array('type'=>'int'),
        'id_monument'   =>  array('type'=>'int'),
        "ACQ_R_16_1" => array("type"=>"int"),
        "ACQ_G_16_1" => array("type"=>"int"),
        "ACQ_B_16_1" => array("type"=>"int"),
        "ACQ_H_16_1" => array("type"=>"int"),
        "ACQ_S_16_1" => array("type"=>"int"),
        "ACQ_V_16_1" => array("type"=>"int"),
        "ACQ_Weight_16_1" => array("type"=>"int"),
        "ACQ_R_16_2" => array("type"=>"int"),
        "ACQ_G_16_2" => array("type"=>"int"),
        "ACQ_B_16_2" => array("type"=>"int"),
        "ACQ_H_16_2" => array("type"=>"int"),
        "ACQ_S_16_2" => array("type"=>"int"),
        "ACQ_V_16_2" => array("type"=>"int"),
        "ACQ_Weight_16_2" => array("type"=>"int"),
        "ACQ_R_16_3" => array("type"=>"int"),
        "ACQ_G_16_3" => array("type"=>"int"),
        "ACQ_B_16_3" => array("type"=>"int"),
        "ACQ_H_16_3" => array("type"=>"int"),
        "ACQ_S_16_3" => array("type"=>"int"),
        "ACQ_V_16_3" => array("type"=>"int"),
        "ACQ_Weight_16_3" => array("type"=>"int"),
        "ACQ_R_16_4" => array("type"=>"int"),
        "ACQ_G_16_4" => array("type"=>"int"),
        "ACQ_B_16_4" => array("type"=>"int"),
        "ACQ_H_16_4" => array("type"=>"int"),
        "ACQ_S_16_4" => array("type"=>"int"),
        "ACQ_V_16_4" => array("type"=>"int"),
        "ACQ_Weight_16_4" => array("type"=>"int"),
        "ACQ_R_16_5" => array("type"=>"int"),
        "ACQ_G_16_5" => array("type"=>"int"),
        "ACQ_B_16_5" => array("type"=>"int"),
        "ACQ_H_16_5" => array("type"=>"int"),
        "ACQ_S_16_5" => array("type"=>"int"),
        "ACQ_V_16_5" => array("type"=>"int"),
        "ACQ_Weight_16_5" => array("type"=>"int"),
        "ACQ_R_16_6" => array("type"=>"int"),
        "ACQ_G_16_6" => array("type"=>"int"),
        "ACQ_B_16_6" => array("type"=>"int"),
        "ACQ_H_16_6" => array("type"=>"int"),
        "ACQ_S_16_6" => array("type"=>"int"),
        "ACQ_V_16_6" => array("type"=>"int"),
        "ACQ_Weight_16_6" => array("type"=>"int"),
        "ACQ_R_16_7" => array("type"=>"int"),
        "ACQ_G_16_7" => array("type"=>"int"),
        "ACQ_B_16_7" => array("type"=>"int"),
        "ACQ_H_16_7" => array("type"=>"int"),
        "ACQ_S_16_7" => array("type"=>"int"),
        "ACQ_V_16_7" => array("type"=>"int"),
        "ACQ_Weight_16_7" => array("type"=>"int"),
        "ACQ_R_16_8" => array("type"=>"int"),
        "ACQ_G_16_8" => array("type"=>"int"),
        "ACQ_B_16_8" => array("type"=>"int"),
        "ACQ_H_16_8" => array("type"=>"int"),
        "ACQ_S_16_8" => array("type"=>"int"),
        "ACQ_V_16_8" => array("type"=>"int"),
        "ACQ_Weight_16_8" => array("type"=>"int"),
        "ACQ_R_16_9" => array("type"=>"int"),
        "ACQ_G_16_9" => array("type"=>"int"),
        "ACQ_B_16_9" => array("type"=>"int"),
        "ACQ_H_16_9" => array("type"=>"int"),
        "ACQ_S_16_9" => array("type"=>"int"),
        "ACQ_V_16_9" => array("type"=>"int"),
        "ACQ_Weight_16_9" => array("type"=>"int"),
        "ACQ_R_16_10" => array("type"=>"int"),
        "ACQ_G_16_10" => array("type"=>"int"),
        "ACQ_B_16_10" => array("type"=>"int"),
        "ACQ_H_16_10" => array("type"=>"int"),
        "ACQ_S_16_10" => array("type"=>"int"),
        "ACQ_V_16_10" => array("type"=>"int"),
        "ACQ_Weight_16_10" => array("type"=>"int"),
        "ACQ_R_16_11" => array("type"=>"int"),
        "ACQ_G_16_11" => array("type"=>"int"),
        "ACQ_B_16_11" => array("type"=>"int"),
        "ACQ_H_16_11" => array("type"=>"int"),
        "ACQ_S_16_11" => array("type"=>"int"),
        "ACQ_V_16_11" => array("type"=>"int"),
        "ACQ_Weight_16_11" => array("type"=>"int"),
        "ACQ_R_16_12" => array("type"=>"int"),
        "ACQ_G_16_12" => array("type"=>"int"),
        "ACQ_B_16_12" => array("type"=>"int"),
        "ACQ_H_16_12" => array("type"=>"int"),
        "ACQ_S_16_12" => array("type"=>"int"),
        "ACQ_V_16_12" => array("type"=>"int"),
        "ACQ_Weight_16_12" => array("type"=>"int"),
        "ACQ_R_16_13" => array("type"=>"int"),
        "ACQ_G_16_13" => array("type"=>"int"),
        "ACQ_B_16_13" => array("type"=>"int"),
        "ACQ_H_16_13" => array("type"=>"int"),
        "ACQ_S_16_13" => array("type"=>"int"),
        "ACQ_V_16_13" => array("type"=>"int"),
        "ACQ_Weight_16_13" => array("type"=>"int"),
        "ACQ_R_16_14" => array("type"=>"int"),
        "ACQ_G_16_14" => array("type"=>"int"),
        "ACQ_B_16_14" => array("type"=>"int"),
        "ACQ_H_16_14" => array("type"=>"int"),
        "ACQ_S_16_14" => array("type"=>"int"),
        "ACQ_V_16_14" => array("type"=>"int"),
        "ACQ_Weight_16_14" => array("type"=>"int"),
        "ACQ_R_16_15" => array("type"=>"int"),
        "ACQ_G_16_15" => array("type"=>"int"),
        "ACQ_B_16_15" => array("type"=>"int"),
        "ACQ_H_16_15" => array("type"=>"int"),
        "ACQ_S_16_15" => array("type"=>"int"),
        "ACQ_V_16_15" => array("type"=>"int"),
        "ACQ_Weight_16_15" => array("type"=>"int"),
        "ACQ_R_16_16" => array("type"=>"int"),
        "ACQ_G_16_16" => array("type"=>"int"),
        "ACQ_B_16_16" => array("type"=>"int"),
        "ACQ_H_16_16" => array("type"=>"int"),
        "ACQ_S_16_16" => array("type"=>"int"),
        "ACQ_V_16_16" => array("type"=>"int"),
        "ACQ_Weight_16_16" => array("type"=>"int"),
        "Gabor_1_0" => array("type"=>"int"),
        "Gabor_1_45" => array("type"=>"int"),
        "Gabor_1_90" => array("type"=>"int"),
        "Gabor_1_135" => array("type"=>"int"),
        "Gabor_2_0" => array("type"=>"int"),
        "Gabor_2_45" => array("type"=>"int"),
        "Gabor_2_90" => array("type"=>"int"),
        "Gabor_2_135" => array("type"=>"int"),
        "Gabor_3_0" => array("type"=>"int"),
        "Gabor_3_45" => array("type"=>"int"),
        "Gabor_3_90" => array("type"=>"int"),
        "Gabor_3_135" => array("type"=>"int"),
        "Gabor_4_0" => array("type"=>"int"),
        "Gabor_4_45" => array("type"=>"int"),
        "Gabor_4_90" => array("type"=>"int"),
        "Gabor_4_135" => array("type"=>"int"),
        "Hue_Mean" => array("type"=>"int"),
        "Hue_Median" => array("type"=>"int"),
        "Hue_Std" => array("type"=>"int"),
        "Hue_Skewness" => array("type"=>"int"),
        "Hue_Kurtosis" => array("type"=>"int"),
        "Hue_Min" => array("type"=>"int"),
        "Hue_Max" => array("type"=>"int"),
        "Sat_Mean" => array("type"=>"int"),
        "Sat_Median" => array("type"=>"int"),
        "Sat_Std" => array("type"=>"int"),
        "Sat_Skewness" => array("type"=>"int"),
        "Sat_Kurtosis" => array("type"=>"int"),
        "Sat_Min" => array("type"=>"int"),
        "Sat_Max" => array("type"=>"int"),
        "Value_Mean" => array("type"=>"int"),
        "Value_Median" => array("type"=>"int"),
        "Value_Std" => array("type"=>"int"),
        "Value_Skewness" => array("type"=>"int"),
        "Value_Kurtosis" => array("type"=>"int"),
        "Value_Min" => array("type"=>"int"),
        "Value_Max" => array("type"=>"int"),
        "HH_4_1" => array("type"=>"int"),
        "HH_4_2" => array("type"=>"int"),
        "HH_4_3" => array("type"=>"int"),
        "HH_4_4" => array("type"=>"int"),
        "SH_4_1" => array("type"=>"int"),
        "SH_4_2" => array("type"=>"int"),
        "SH_4_3" => array("type"=>"int"),
        "SH_4_4" => array("type"=>"int"),
        "VH_4_1" => array("type"=>"int"),
        "VH_4_2" => array("type"=>"int"),
        "VH_4_3" => array("type"=>"int"),
        "VH_4_4" => array("type"=>"int"),
        "HH_8_1" => array("type"=>"int"),
        "HH_8_2" => array("type"=>"int"),
        "HH_8_3" => array("type"=>"int"),
        "HH_8_4" => array("type"=>"int"),
        "HH_8_5" => array("type"=>"int"),
        "HH_8_6" => array("type"=>"int"),
        "HH_8_7" => array("type"=>"int"),
        "HH_8_8" => array("type"=>"int"),
        "SH_8_1" => array("type"=>"int"),
        "SH_8_2" => array("type"=>"int"),
        "SH_8_3" => array("type"=>"int"),
        "SH_8_4" => array("type"=>"int"),
        "SH_8_5" => array("type"=>"int"),
        "SH_8_6" => array("type"=>"int"),
        "SH_8_7" => array("type"=>"int"),
        "SH_8_8" => array("type"=>"int"),
        "VH_8_1" => array("type"=>"int"),
        "VH_8_2" => array("type"=>"int"),
        "VH_8_3" => array("type"=>"int"),
        "VH_8_4" => array("type"=>"int"),
        "VH_8_5" => array("type"=>"int"),
        "VH_8_6" => array("type"=>"int"),
        "VH_8_7" => array("type"=>"int"),
        "VH_8_8" => array("type"=>"int"),
        "Brightness_Mean" => array("type"=>"int"),
        "Brightness_Median" => array("type"=>"int"),
        "Brightness_Std" => array("type"=>"int"),
        "Brightness_Skewness" => array("type"=>"int"),
        "Brightness_Kurtosis" => array("type"=>"int"),
        "Brightness_Min" => array("type"=>"int"),
        "Brightness_Max" => array("type"=>"int"),
        "GrayH_8_1" => array("type"=>"int"),
        "GrayH_8_2" => array("type"=>"int"),
        "GrayH_8_3" => array("type"=>"int"),
        "GrayH_8_4" => array("type"=>"int"),
        "GrayH_8_5" => array("type"=>"int"),
        "GrayH_8_6" => array("type"=>"int"),
        "GrayH_8_7" => array("type"=>"int"),
        "GrayH_8_8" => array("type"=>"int"),
        "GrayH_32_1" => array("type"=>"int"),
        "GrayH_32_2" => array("type"=>"int"),
        "GrayH_32_3" => array("type"=>"int"),
        "GrayH_32_4" => array("type"=>"int"),
        "GrayH_32_5" => array("type"=>"int"),
        "GrayH_32_6" => array("type"=>"int"),
        "GrayH_32_7" => array("type"=>"int"),
        "GrayH_32_8" => array("type"=>"int"),
        "GrayH_32_9" => array("type"=>"int"),
        "GrayH_32_10" => array("type"=>"int"),
        "GrayH_32_11" => array("type"=>"int"),
        "GrayH_32_12" => array("type"=>"int"),
        "GrayH_32_13" => array("type"=>"int"),
        "GrayH_32_14" => array("type"=>"int"),
        "GrayH_32_15" => array("type"=>"int"),
        "GrayH_32_16" => array("type"=>"int"),
        "GrayH_32_17" => array("type"=>"int"),
        "GrayH_32_18" => array("type"=>"int"),
        "GrayH_32_19" => array("type"=>"int"),
        "GrayH_32_20" => array("type"=>"int"),
        "GrayH_32_21" => array("type"=>"int"),
        "GrayH_32_22" => array("type"=>"int"),
        "GrayH_32_23" => array("type"=>"int"),
        "GrayH_32_24" => array("type"=>"int"),
        "GrayH_32_25" => array("type"=>"int"),
        "GrayH_32_26" => array("type"=>"int"),
        "GrayH_32_27" => array("type"=>"int"),
        "GrayH_32_28" => array("type"=>"int"),
        "GrayH_32_29" => array("type"=>"int"),
        "GrayH_32_30" => array("type"=>"int"),
        "GrayH_32_31" => array("type"=>"int"),
        "GrayH_32_32" => array("type"=>"int"),
        "Red_Mean" => array("type"=>"int"),
        "Red_Median" => array("type"=>"int"),
        "Red_Std" => array("type"=>"int"),
        "Red_Skewness" => array("type"=>"int"),
        "Red_Kurtosis" => array("type"=>"int"),
        "Red_Min" => array("type"=>"int"),
        "Red_Max" => array("type"=>"int"),
        "Green_Mean" => array("type"=>"int"),
        "Green_Median" => array("type"=>"int"),
        "Green_Std" => array("type"=>"int"),
        "Green_Skewness" => array("type"=>"int"),
        "Green_Kurtosis" => array("type"=>"int"),
        "Green_Min" => array("type"=>"int"),
        "Green_Max" => array("type"=>"int"),
        "Blue_Mean" => array("type"=>"int"),
        "Blue_Median" => array("type"=>"int"),
        "Blue_Std" => array("type"=>"int"),
        "Blue_Skewness" => array("type"=>"int"),
        "Blue_Kurtosis" => array("type"=>"int"),
        "Blue_Min" => array("type"=>"int"),
        "Blue_Max" => array("type"=>"int"),
        "RH_4_1" => array("type"=>"int"),
        "RH_4_2" => array("type"=>"int"),
        "RH_4_3" => array("type"=>"int"),
        "RH_4_4" => array("type"=>"int"),
        "GH_4_1" => array("type"=>"int"),
        "GH_4_2" => array("type"=>"int"),
        "GH_4_3" => array("type"=>"int"),
        "GH_4_4" => array("type"=>"int"),
        "BH_4_1" => array("type"=>"int"),
        "BH_4_2" => array("type"=>"int"),
        "BH_4_3" => array("type"=>"int"),
        "BH_4_4" => array("type"=>"int"),
        "RH_8_1" => array("type"=>"int"),
        "RH_8_2" => array("type"=>"int"),
        "RH_8_3" => array("type"=>"int"),
        "RH_8_4" => array("type"=>"int"),
        "RH_8_5" => array("type"=>"int"),
        "RH_8_6" => array("type"=>"int"),
        "RH_8_7" => array("type"=>"int"),
        "RH_8_8" => array("type"=>"int"),
        "GH_8_1" => array("type"=>"int"),
        "GH_8_2" => array("type"=>"int"),
        "GH_8_3" => array("type"=>"int"),
        "GH_8_4" => array("type"=>"int"),
        "GH_8_5" => array("type"=>"int"),
        "GH_8_6" => array("type"=>"int"),
        "GH_8_7" => array("type"=>"int"),
        "GH_8_8" => array("type"=>"int"),
        "BH_8_1" => array("type"=>"int"),
        "BH_8_2" => array("type"=>"int"),
        "BH_8_3" => array("type"=>"int"),
        "BH_8_4" => array("type"=>"int"),
        "BH_8_5" => array("type"=>"int"),
        "BH_8_6" => array("type"=>"int"),
        "BH_8_7" => array("type"=>"int"),
        "BH_8_8" => array("type"=>"int"),
        "Total_Num_Segments" => array("type"=>"int"),
        "Mean_Area" => array("type"=>"int"),
        "Min_Area" => array("type"=>"int"),
        "Max_Area" => array("type"=>"int"),
        "Median_Area" => array("type"=>"int"),
        "Num_Segments_2x2_1" => array("type"=>"int"),
        "Num_Segments_2x2_2" => array("type"=>"int"),
        "Num_Segments_2x2_3" => array("type"=>"int"),
        "Num_Segments_2x2_4" => array("type"=>"int"),
        "Num_Segments_3x3_1" => array("type"=>"int"),
        "Num_Segments_3x3_2" => array("type"=>"int"),
        "Num_Segments_3x3_3" => array("type"=>"int"),
        "Num_Segments_3x3_4" => array("type"=>"int"),
        "Num_Segments_3x3_5" => array("type"=>"int"),
        "Num_Segments_3x3_6" => array("type"=>"int"),
        "Num_Segments_3x3_7" => array("type"=>"int"),
        "Num_Segments_3x3_8" => array("type"=>"int"),
        "Num_Segments_3x3_9" => array("type"=>"int"),
        "Num_Segments_4x4_1" => array("type"=>"int"),
        "Num_Segments_4x4_2" => array("type"=>"int"),
        "Num_Segments_4x4_3" => array("type"=>"int"),
        "Num_Segments_4x4_4" => array("type"=>"int"),
        "Num_Segments_4x4_5" => array("type"=>"int"),
        "Num_Segments_4x4_6" => array("type"=>"int"),
        "Num_Segments_4x4_7" => array("type"=>"int"),
        "Num_Segments_4x4_8" => array("type"=>"int"),
        "Num_Segments_4x4_9" => array("type"=>"int"),
        "Num_Segments_4x4_10" => array("type"=>"int"),
        "Num_Segments_4x4_11" => array("type"=>"int"),
        "Num_Segments_4x4_12" => array("type"=>"int"),
        "Num_Segments_4x4_13" => array("type"=>"int"),
        "Num_Segments_4x4_14" => array("type"=>"int"),
        "Num_Segments_4x4_15" => array("type"=>"int"),
        "Num_Segments_4x4_16" => array("type"=>"int"),
        "BDIP_2x2_1" => array("type"=>"int"),
        "BDIP_2x2_2" => array("type"=>"int"),
        "BDIP_2x2_3" => array("type"=>"int"),
        "BDIP_2x2_4" => array("type"=>"int"),
        "BDIP_3x3_1" => array("type"=>"int"),
        "BDIP_3x3_2" => array("type"=>"int"),
        "BDIP_3x3_3" => array("type"=>"int"),
        "BDIP_3x3_4" => array("type"=>"int"),
        "BDIP_3x3_5" => array("type"=>"int"),
        "BDIP_3x3_6" => array("type"=>"int"),
        "BDIP_3x3_7" => array("type"=>"int"),
        "BDIP_3x3_8" => array("type"=>"int"),
        "BDIP_3x3_9" => array("type"=>"int"),
        "BDIP_4x4_1" => array("type"=>"int"),
        "BDIP_4x4_2" => array("type"=>"int"),
        "BDIP_4x4_3" => array("type"=>"int"),
        "BDIP_4x4_4" => array("type"=>"int"),
        "BDIP_4x4_5" => array("type"=>"int"),
        "BDIP_4x4_6" => array("type"=>"int"),
        "BDIP_4x4_7" => array("type"=>"int"),
        "BDIP_4x4_8" => array("type"=>"int"),
        "BDIP_4x4_9" => array("type"=>"int"),
        "BDIP_4x4_10" => array("type"=>"int"),
        "BDIP_4x4_11" => array("type"=>"int"),
        "BDIP_4x4_12" => array("type"=>"int"),
        "BDIP_4x4_13" => array("type"=>"int"),
        "BDIP_4x4_14" => array("type"=>"int"),
        "BDIP_4x4_15" => array("type"=>"int"),
        "BDIP_4x4_16" => array("type"=>"int"),
        "BVLC_1_2x2_1" => array("type"=>"int"),
        "BVLC_1_2x2_2" => array("type"=>"int"),
        "BVLC_1_2x2_3" => array("type"=>"int"),
        "BVLC_1_2x2_4" => array("type"=>"int"),
        "BVLC_1_3x3_1" => array("type"=>"int"),
        "BVLC_1_3x3_2" => array("type"=>"int"),
        "BVLC_1_3x3_3" => array("type"=>"int"),
        "BVLC_1_3x3_4" => array("type"=>"int"),
        "BVLC_1_3x3_5" => array("type"=>"int"),
        "BVLC_1_3x3_6" => array("type"=>"int"),
        "BVLC_1_3x3_7" => array("type"=>"int"),
        "BVLC_1_3x3_8" => array("type"=>"int"),
        "BVLC_1_3x3_9" => array("type"=>"int"),
        "BVLC_1_4x4_1" => array("type"=>"int"),
        "BVLC_1_4x4_2" => array("type"=>"int"),
        "BVLC_1_4x4_3" => array("type"=>"int"),
        "BVLC_1_4x4_4" => array("type"=>"int"),
        "BVLC_1_4x4_5" => array("type"=>"int"),
        "BVLC_1_4x4_6" => array("type"=>"int"),
        "BVLC_1_4x4_7" => array("type"=>"int"),
        "BVLC_1_4x4_8" => array("type"=>"int"),
        "BVLC_1_4x4_9" => array("type"=>"int"),
        "BVLC_1_4x4_10" => array("type"=>"int"),
        "BVLC_1_4x4_11" => array("type"=>"int"),
        "BVLC_1_4x4_12" => array("type"=>"int"),
        "BVLC_1_4x4_13" => array("type"=>"int"),
        "BVLC_1_4x4_14" => array("type"=>"int"),
        "BVLC_1_4x4_15" => array("type"=>"int"),
        "BVLC_1_4x4_16" => array("type"=>"int"),
        "Contrast_1" => array("type"=>"int"),
        "Correlation_1" => array("type"=>"int"),
        "Energy_1" => array("type"=>"int"),
        "Homogeneity_1" => array("type"=>"int"),
        "Contrast_2" => array("type"=>"int"),
        "Correlation_2" => array("type"=>"int"),
        "Energy_2" => array("type"=>"int"),
        "Homogeneity_2" => array("type"=>"int"),
        "Contrast_4" => array("type"=>"int"),
        "Correlation_4" => array("type"=>"int"),
        "Energy_4" => array("type"=>"int"),
        "Homogeneity_4" => array("type"=>"int"),
        "Contrast_8" => array("type"=>"int"),
        "Correlation_8" => array("type"=>"int"),
        "Energy_8" => array("type"=>"int"),
        "Homogeneity_8" => array("type"=>"int"),
        "Edge_Amount_Sobel" => array("type"=>"int"),
        "Entropy" => array("type"=>"int"),
    );
}
?>