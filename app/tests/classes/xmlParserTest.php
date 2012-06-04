<?php    
class XMLParserTest extends Kohana_UnitTest_TestCase
{   
	public function test_xml2array(){
	  
	  
	   
	  //test1 no attributes priority=tag 
	  $test1 = XMLParser::xml2array(@file_get_contents('testFiles/testFile'.'1'.'.xml'));
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
	  $test2 = XMLParser::xml2array(@file_get_contents('testFiles/testFile'.'1'.'.xml'), 1, 'nottag');
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
}
   
?>   