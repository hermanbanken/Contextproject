<?php defined('SYSPATH') or die('No direct access allowed!');
 
class MonumentTest extends Kohana_UnitTest_TestCase
{
   
   
   
  public function test_iets(){
	  
	$monument = ORM::factory('monument')->where('id_monument','=',1)->find();
	
		$request = Request::factory('monument');
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->action_map();}
		catch(ErrorException $expected){$query = "Error!";}	
		echo "test:";
		print_r($query);
		//$this->assertEquals($testCases[$num][0], $query);

   }
   
	

   
   
   public function test_monumentsToJSON(){
		
		$fm1 = ORM::factory('monument');
		$fm1->lng = 0;
		$fm1->lat = 0;
		$fm1->name = "fake monument1";
		$fm1->description = "Dit is fake monument 1";
		$fm1->id_monument = 1;
		
		$fm2 = ORM::factory('monument');
		$fm2->lng = 0;
		$fm2->lat = 0;
		$fm2->name = "fake monument2";
		$fm2->description = "Dit is fake monument 2";
		$fm2->id_monument = 2;
		
		$fm3 = ORM::factory('monument');
		$fm3->lng = 0;
		$fm3->lat = 0;
		$fm3->name = "fake monument3";
		$fm3->description = "Dit is fake monument 3";
		$fm3->id_monument = 3;
		
		
		
		
		$fakeMonuments = array($fm1, $fm2, $fm3);
		
		//$test = ::factory('monument');
		//$result = $test->monumentsToJSON($fakeMonuments);
		//$result = Controller_Monument::monumentsToJSON($fakeMonuments);
		//$this->assertEquals($result, " ");
		
		//$testObject = $this->getMock('Monument');
		
		//$testObject->expects($this->once())
      		//->method('monumentsToJSON')
      		//->with($testMonuments)
      		//->will($this->returnValue($eventRow));
		
		   
   }
   
   
   
   
   
   
	
		
	 

	public function test_buildQuery()
	{
		$testCases = array(
		array("SELECT *, dev_monuments.id_monument AS id_monument, COUNT(dev_visits.id) AS popularity FROM dev_monuments LEFT JOIN dev_visits ON dev_visits.id_monument = dev_monuments.id_monument GROUP BY dev_monuments.id_monument HAVING 1 ORDER BY RAND() ;", array()),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('search'=>"bla")) );
		
		/*array("SELECT * FROM dev_monuments HAVING 1 AND CONCAT(name,description) LIKE '%nothesaurus%' ORDER BY RAND() ;", array('search' => 'nothesaurus')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('distance' => '0')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('distance' => 1, 'distance_show'=>1)),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('distance' => '1', 'longitude' => '1')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('distance' => '1', 'latitude' => '1')),
		array("SELECT * ,((ACOS(SIN(31 * PI() / 180) * SIN(lat * PI() / 180) + COS(31 * PI() / 180) * COS(lat * PI() / 180) * COS((32 - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)*1.6 AS distance FROM dev_monuments HAVING 1 AND distance < 1 ORDER BY RAND() ;", array('distance' => '1', 'longitude' => '31', 'latitude' => '32', 'distance_show' => '1')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('category' => '1'))
		);
		*/
		
		/*foreach($testCases as $testCase){
			$response = $testCase[0];
			$post = $testCase[1];
			$request = Request::factory('monument')->method(Request::POST)->post($post);
			//$test = Request::factory('monument')->method(Request::POST)->post(array('limit' => '31'));
			$controller = (new Controller_Monument($request, new Response()));
			
			echo "response:".$controller->buildQuery();
			$this->assertEquals($response, $controller->buildQuery());
		}
		
		*/
		
		$num = 0;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}	
		$this->assertEquals($testCases[$num][0], $query);
		
		$num++;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}
		$this->assertEquals($testCases[$num][0], $query);
		
		/*$num++;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}	
		$this->assertEquals($testCases[$num][0], $query);
		
		$num++;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}	
		$this->assertEquals($testCases[$num][0], $query);
		
		$num++;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}	
		$this->assertEquals($testCases[$num][0], $query);
		
		$num++;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}	
		$this->assertEquals($testCases[$num][0], $query);
		
		$num++;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}	
		$this->assertEquals($testCases[$num][0], $query);
		
		$num++;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}	
		$this->assertEquals($testCases[$num][0], $query);
		
		*/
		//echo $controller->buildQuery();
		
		//echo "COUNT:".count($testCases);
		
		
	}

}