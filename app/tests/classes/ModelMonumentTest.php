<?php
class ModelMonumentTest extends Kohana_UnitTest_TestCase
{
	
	
	
	public function test_photoUrl(){
		
		$model = Model::factory('monument')->where("id_monument", "=", 1)->find();
		print_r($model->as_array());
		
		$model->id_monument = 1;
		
		$this->assertTrue((Boolean) strpos($model->photoUrl(),"photos/1.jpg"));
		
	}	
	
}

?>