<?php
class WundergroundTest extends Kohana_UnitTest_TestCase
{
	public function test_forecast(){
		//prepare test database
		PrepareTestDB::prepDB();
		
				
		$today = date("Y-m-d");
		$tomorrow  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")) );
		$dayAfterTomorrow  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+2, date("Y")) );
		$dayAfterTheDayAfterTomorrow  = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+3, date("Y")) );
		
		$query = DB::select('id_forecast', 'icon', 'date', 'low', 'high')
			->from("forecasts")
			->execute()
			->as_array();
		
		
		$this->assertEquals(array(), $query);
		
		
		$monument1 = ORM::factory('monument')->where('id_monument','=',3)->find();
		$forecastsm1 = Wunderground::forecast($monument1);
		
		$forecast1m1 = $forecastsm1[0]->as_array();
		$forecast2m1 = $forecastsm1[1]->as_array();
		$forecast3m1 = $forecastsm1[2]->as_array();
		$forecast4m1 = $forecastsm1[3]->as_array();
		
		
		$this->assertEquals($today, $forecast1m1['date']);
		$this->assertEquals($tomorrow, $forecast2m1['date']);
		$this->assertEquals($dayAfterTomorrow, $forecast3m1['date']);
		$this->assertEquals($dayAfterTheDayAfterTomorrow, $forecast4m1['date']);
		
		
		$query = DB::select('id_forecast', 'icon', 'date', 'low', 'high')
			->from("forecasts")
			->execute()
			->as_array();
		
		$this->assertEquals(array(
			array(
				'id_forecast' => 1, 
				'icon' => $forecast1m1['icon'], 
				'date' => $today, 
				'low' => $forecast1m1['low'], 
				'high' => $forecast1m1['high']
				), 
			array(
			'id_forecast' => 2, 
				'icon' => $forecast2m1['icon'], 
				'date' => $tomorrow, 
				'low' => $forecast2m1['low'], 
				'high' => $forecast2m1['high']
				), 
			array(
				'id_forecast' => 3, 
				'icon' => $forecast3m1['icon'], 
				'date' => $dayAfterTomorrow, 
				'low' => $forecast3m1['low'], 
				'high' => $forecast3m1['high']
				), 
			array(
				'id_forecast' => 4, 
				'icon' => $forecast4m1['icon'], 
				'date' => $dayAfterTheDayAfterTomorrow, 
				'low' => $forecast4m1['low'], 
				'high' => $forecast4m1['high']
				)
		), 
		$query);
		
		
		$monument2 = ORM::factory('monument')->where('id_monument','=',4)->find();
		$forecastsm2 = Wunderground::forecast($monument2);
		
		$forecast1m2 = $forecastsm1[0]->as_array();
		$forecast2m2 = $forecastsm1[1]->as_array();
		$forecast3m2 = $forecastsm1[2]->as_array();
		$forecast4m2 = $forecastsm1[3]->as_array();		
	
		
		$this->assertEquals($today, $forecast1m2['date']);
		$this->assertEquals($tomorrow, $forecast2m2['date']);
		$this->assertEquals($dayAfterTomorrow, $forecast3m2['date']);
		$this->assertEquals($dayAfterTheDayAfterTomorrow, $forecast4m2['date']);
		
		
		
		
		$this->assertEquals($forecast1m1['icon'], $forecast1m2['icon']);
		$this->assertEquals($forecast1m1['low'], $forecast1m2['low']);
		$this->assertEquals($forecast1m1['high'], $forecast1m2['high']);
		
		
		
		$monument3 = ORM::factory('monument')->where('id_monument','=',1)->find();
		$forecastsm3 = Wunderground::forecast($monument3);
		
		
		
		$forecast1m3 = $forecastsm3[0]->as_array();
		$forecast2m3 = $forecastsm3[1]->as_array();
		$forecast3m3 = $forecastsm3[2]->as_array();
		$forecast4m3 = $forecastsm3[3]->as_array();
		
		$this->assertEquals($today, $forecast1m3['date']);
		$this->assertEquals($tomorrow, $forecast2m3['date']);
		$this->assertEquals($dayAfterTomorrow, $forecast3m3['date']);
		$this->assertEquals($dayAfterTheDayAfterTomorrow, $forecast4m3['date']);
		
		
		
		$query = DB::select('id_forecast', 'icon', 'date', 'low', 'high')
			->from("forecasts")
			->execute()
			->as_array();
		
		$this->assertEquals(array(
			array(
				'id_forecast' => 1, 
				'icon' => $forecast1m1['icon'], 
				'date' => $today, 
				'low' => $forecast1m1['low'], 
				'high' => $forecast1m1['high']
				), 
			array(
			'id_forecast' => 2, 
				'icon' => $forecast2m1['icon'], 
				'date' => $tomorrow, 
				'low' => $forecast2m1['low'], 
				'high' => $forecast2m1['high']
				), 
			array(
				'id_forecast' => 3, 
				'icon' => $forecast3m1['icon'], 
				'date' => $dayAfterTomorrow, 
				'low' => $forecast3m1['low'], 
				'high' => $forecast3m1['high']
				), 
			array(
				'id_forecast' => 4, 
				'icon' => $forecast4m1['icon'], 
				'date' => $dayAfterTheDayAfterTomorrow, 
				'low' => $forecast4m1['low'], 
				'high' => $forecast4m1['high']
				),
			array(
				'id_forecast' => 5, 
				'icon' => $forecast1m3['icon'], 
				'date' => $today, 
				'low' => $forecast1m3['low'], 
				'high' => $forecast1m3['high']
				), 
			array(
				'id_forecast' => 6, 
				'icon' => $forecast2m3['icon'], 
				'date' => $tomorrow, 
				'low' => $forecast2m3['low'], 
				'high' => $forecast2m3['high']
				), 
			array(
				'id_forecast' => 7, 
				'icon' => $forecast3m3['icon'], 
				'date' => $dayAfterTomorrow, 
				'low' => $forecast3m3['low'], 
				'high' => $forecast3m3['high']
				), 
			array(
				'id_forecast' => 8, 
				'icon' => $forecast4m3['icon'], 
				'date' => $dayAfterTheDayAfterTomorrow, 
				'low' => $forecast4m3['low'], 
				'high' => $forecast4m3['high']
				)		
		), 
		$query);
		
		//echo 'test';
		
		$monument4 = ORM::factory('monument')->where('id_monument','=',5)->find();
		$forecastsm4 = Wunderground::forecast($monument4);
		
		$this->assertEquals(array(), $forecastsm4->as_array());
		
		
	}
	
}

?>