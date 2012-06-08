<?php
class WundergroundTest extends Kohana_UnitTest_TestCase
{
	public function test_forecast(){
	
		$fakemonument = ORM::factory('monument');
		$fakemonument->id_town = 72;
		$fakemonument->lng = 52;
		$fakemonument->lat = 4.37;
		$fakemonument->name = "fake monument1";
		$fakemonument->description = "Dit is fake monument 1";
		$fakemonument->id_monument = 1;
		
		$forecasts = Wunderground::forecast($fakemonument);
	
		print_r($forecasts);
		
	}
	
}

?>