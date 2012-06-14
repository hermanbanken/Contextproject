<?php
class LoggerTest extends Kohana_UnitTest_TestCase
{
	public function test_randomstring(){
		//prepare test database
		//PrepareTestDB::prepDB();
		
				
		$string1 = Logger::randomstring(8);	
		
		$string2 = Logger::randomstring(8);	
		
		$string3 = Logger::randomstring(3);
		
		$string4 = Logger::randomstring(0);
		
		$this->assertEquals(8,strlen($string1));
		$this->assertEquals(8,strlen($string2));
		$this->assertEquals(3,strlen($string3));
		
		$this->assertEquals("",$string4);
		
		$this->assertnotEquals($string1, $string2);
				
		//$monument1 = ORM::factory('monument')->where('id_monument','=',6)->find();
		
		//$info = Rijksmonumenten::monument($monument1);
		
		//print_r($info);
		
		
	}
	
}

?>