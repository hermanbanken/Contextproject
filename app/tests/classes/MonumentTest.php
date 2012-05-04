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
	
	public function test_iets()
	{
		$testCases = array(
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() LIMIT 500 OFFSET 0;", array()),
		array("SELECT * FROM dev_monuments HAVING 1 ORDER BY RAND() LIMIT 500 OFFSET 0;", array('' => 'zoeken', '' => 'stad', '' => '-1'))
	
		);
		
		foreach($testCases as $testCase){
			$response = $testCase[0];
			$post = $testCase[1];
			$request = Request::factory('monument')->method(Request::POST)->post($post);
			//$test = Request::factory('monument')->method(Request::POST)->post(array('limit' => '31'));
			$controller = (new Controller_Monument($request, new Response()));
			
			echo "response:".$controller->buildQuery();
			$this->assertEquals($response, $controller->buildQuery());
		}
		
		echo "COUNT:".count($testCases);
		
		
	}

}