<?php defined('SYSPATH') or die('No direct access allowed!');
 
class MonumentTest extends Kohana_UnitTest_TestCase
{
   
   public function test_xml2array(){
	   
	  //test1 no attributes priority=tag 
	  $test1 = Controller_Monument::xml2array(@file_get_contents('testFiles/testFile'.'1'.'.xml'));
	  $wikimediaData = ($test1['Monument']['wikimediaCommons']);
	  $monumentRegisterData = ($test1['Monument']['monumentRegistry']); 
	   	   
	  $this->assertEquals($wikimediaData['monumentNumber'], "1");
	  $this->assertEquals($wikimediaData['title'], "title");
	  $this->assertEquals($wikimediaData['url'], "url");
	  $this->assertEquals($wikimediaData['description'], "description");
	  $this->assertEquals($wikimediaData['authorUrl'], "authorUrl");
	  $this->assertEquals($wikimediaData['date'], "date");
	  $this->assertEquals($wikimediaData['fullResolutionImageUrl'], "fullResolutionImageUrl");
	  $this->assertEquals($wikimediaData['longitude'], "1"); 
	  $this->assertEquals($wikimediaData['latitude'], "2");
	  $this->assertEquals($wikimediaData['usageOnOtherWikis']['string'], "string1");
	  $this->assertEquals($wikimediaData['usageOnWikiMediaCommons']['string'], "string1");
		   
	  $this->assertEquals($monumentRegisterData['monumentNumber'], "1");
	  $this->assertEquals($monumentRegisterData['province'], "province");
	  $this->assertEquals($monumentRegisterData['municipality'], "municipality");
	  $this->assertEquals($monumentRegisterData['town'], "town");
	  $this->assertEquals($monumentRegisterData['street'], "street");
	  $this->assertEquals($monumentRegisterData['streetNumber'], "3");
	  $this->assertEquals($monumentRegisterData['zipCode'], "1234AB");
	  $this->assertEquals($monumentRegisterData['mainCategory'], "mainCategory");
	  $this->assertEquals($monumentRegisterData['subCategory'], "subCategory");
	  $this->assertEquals($monumentRegisterData['function'], "function");
	  $this->assertEquals($monumentRegisterData['description'], "description");
	  $this->assertEquals($monumentRegisterData['xCoordinates'], "4");
	  $this->assertEquals($monumentRegisterData['yCoordinates'], "5");
	  //end test1
	   
	   
	   
	  //test2 attributes=1 priority!=tag 
	  $test2 = Controller_Monument::xml2array(@file_get_contents('testFiles/testFile'.'1'.'.xml'), 1, 'nottag');
	  $wikimediaData = ($test2['Monument']['wikimediaCommons']);
	  $monumentRegisterData = ($test2['Monument']['monumentRegistry']); 
	  print_r($wikimediaData['monumentNumber']); 	   
	  $this->assertEquals($wikimediaData['monumentNumber']['value'], "1");
	  $this->assertEquals($wikimediaData['title']['value'], "title");
	  $this->assertEquals($wikimediaData['url']['value'], "url");
	  $this->assertEquals($wikimediaData['description']['value'], "description");
	  $this->assertEquals($wikimediaData['authorUrl']['value'], "authorUrl");
	  $this->assertEquals($wikimediaData['date']['value'], "date");
	  $this->assertEquals($wikimediaData['fullResolutionImageUrl']['value'], "fullResolutionImageUrl");
	  $this->assertEquals($wikimediaData['longitude']['value'], "1"); 
	  $this->assertEquals($wikimediaData['latitude']['value'], "2");
	  $this->assertEquals($wikimediaData['usageOnOtherWikis']['string']['value'], "string1");
	  $this->assertEquals($wikimediaData['usageOnWikiMediaCommons']['string']['value'], "string1");
		   
	  $this->assertEquals($monumentRegisterData['monumentNumber']['value'], "1");
	  $this->assertEquals($monumentRegisterData['province']['value'], "province");
	  $this->assertEquals($monumentRegisterData['municipality']['value'], "municipality");
	  $this->assertEquals($monumentRegisterData['town']['value'], "town");
	  $this->assertEquals($monumentRegisterData['street']['value'], "street");
	  $this->assertEquals($monumentRegisterData['streetNumber']['value'], "3");
	  $this->assertEquals($monumentRegisterData['zipCode']['value'], "1234AB");
	  $this->assertEquals($monumentRegisterData['mainCategory']['value'], "mainCategory");
	  $this->assertEquals($monumentRegisterData['subCategory']['value'], "subCategory");
	  $this->assertEquals($monumentRegisterData['function']['value'], "function");
	  $this->assertEquals($monumentRegisterData['description']['value'], "description");
	  $this->assertEquals($monumentRegisterData['xCoordinates']['value'], "4");
	  $this->assertEquals($monumentRegisterData['yCoordinates']['value'], "5");
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