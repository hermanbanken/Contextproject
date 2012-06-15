<?php
class RijksmonumentenTest extends Kohana_UnitTest_TestCase
{
	public function test_monument(){
		//prepare test database
		PrepareTestDB::prepDB();
		
				
		$monument1 = ORM::factory('monument')->where('id_monument','=',6)->find();
		$monument2 = ORM::factory('monument')->where('id_monument','=',-1)->find();
		
		$info1 = Rijksmonumenten::monument($monument1);
		
		$info2 = Rijksmonumenten::monument($monument2);
		
		
		$this->assertEquals('rce:' . $monument1->id_monument, $info1->Id);
		
		$this->assertFalse($info2);
		//print_r($info);
		
		
	}
	
}

?>