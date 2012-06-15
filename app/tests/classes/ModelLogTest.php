<?php
class ModelLogTest extends Kohana_UnitTest_TestCase
{
	
	
	
	public function test_schema(){
		
		$schema = Model_Log::schema();
		
		$this->assertEquals("a8b2167db4a61cbb68f6f79c6547c5a3", md5($schema));
		
	}	
	
}

?>