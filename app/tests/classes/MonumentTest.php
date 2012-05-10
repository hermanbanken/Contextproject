<?php defined('SYSPATH') or die('No direct access allowed!');
 
class MonumentTest extends Kohana_UnitTest_TestCase
{
   
	
	
	 public function test_getSynonyms()
     {
		//$bla = Request::factory('monument');
		
		//$test = new Controller_Monument();
		
		
		//$this->assertInstanceOf(Controller_Monument::getSynonyms("bla"), Array);
		
		$this->assertTrue( (Bool)( (Controller_Monument::getSynonyms("bla") == false)  ^ (is_array(Controller_Monument::getSynonyms("bla")))) );

	 }

	public function test_buildQuery()
	{
		$testCases = array(
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array()),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('' => 'zoeken', '' => 'town', '' => '-1')),
		array("SELECT * FROM dev_monuments HAVING 1 AND CONCAT(name,description) LIKE '%nothesaurus%' ORDER BY RAND() ;", array('search' => 'nothesaurus')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('distance' => '0')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('distance' => '1')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('distance' => '1', 'longitude' => '1')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('distance' => '1', 'latitude' => '1')),
		array("SELECT * ,((ACOS(SIN(31 * PI() / 180) * SIN(lat * PI() / 180) + COS(31 * PI() / 180) * COS(lat * PI() / 180) * COS((32 - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)*1.6 AS distance FROM dev_monuments HAVING 1 AND distance < 1 ORDER BY RAND() ;", array('distance' => '1', 'longitude' => '31', 'latitude' => '32', 'distance_show' => '1')),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() ;", array('category' => '1'))
		);
		
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
		
		$num++;
		$request = Request::factory('monument')->method(Request::POST)->post($testCases[$num][1]);
		$controller = (new Controller_Monument($request, new Response()));
		try{$query = $controller->buildQuery();}
		catch(ErrorException $expected){$query = "Error!";}	
		$this->assertEquals($testCases[$num][0], $query);
		
		
		//echo $controller->buildQuery();
		
		//echo "COUNT:".count($testCases);
		
		
	}

}