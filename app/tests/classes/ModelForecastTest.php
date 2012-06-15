<?php
class ModelForecastTest extends Kohana_UnitTest_TestCase
{
	
	
	
	public function test_temperature(){
		
		$model = new Model_Forecast;
		
		$model->low = 10;
		$model->high = 20;
		
		$this->assertEquals(15,$model->temperature());
	}
	
	
	public function test_icon(){
		
		$model = new Model_Forecast;
		
		$model->icon = "icon";
		
		$this->assertEquals("http://icons-ak.wxug.com/i/c/k/icon.gif",$model->icon());
	}
	
	
	public function test_day(){
		
		$model = new Model_Forecast;
		
		$model->date = "2012-06-15";
		
		$this->assertEquals("vr",$model->day());
	}
}

?>