<?php
class MessageTest extends Kohana_UnitTest_TestCase
{
	public function test_add(){
		//prepare test database
		PrepareTestDB::prepDB();
		
				
		$monument1 = ORM::factory('monument')->where('id_monument','=',6)->find();
		
		$info = Rijksmonumenten::monument($monument1);
		
		//print_r($info);
		
		
	}
	
}

?>