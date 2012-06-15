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
	

	
	public function test_category(){
		//prepare test database
		PrepareTestDB::prepDB();
		
		$query = DB::select('id_log','id_category')
			->from("logs_categories")
			->execute()
			->as_array();
		
		$this->assertEquals(array(), $query);
		
		$logger = Logger::instance();
		
		//$categorie = Model::factory('Category');
		//$categorie->id_category = 1;
		$categorie = ORM::factory('category')->where('id_category', '=', 1)->find();
		
		$logger->category($categorie);
		
		$query = DB::select('id_log','id_category')
			->from("logs_categories")
			->execute()
			->as_array();
		
		$this->assertEquals(array(array('id_log' => 1, 'id_category' => 1)), $query);
		
		
	}
	
	
	public function test_town(){
		//prepare test database
		PrepareTestDB::prepDB();
		
		$query = DB::select('id_log','id_town')
			->from("logs_towns")
			->execute()
			->as_array();
		
		$this->assertEquals(array(), $query);
		
		$logger = Logger::instance();
		
		$town = ORM::factory('town')->where('id_town', '=', 1)->find();
		
		$logger->town($town);
		
		$query = DB::select('id_log','id_town')
			->from("logs_towns")
			->execute()
			->as_array();
		
		$this->assertEquals(array(array('id_log' => 1, 'id_town' => 1)), $query);
	
	}
	
	public function test_monument(){
		//prepare test database
		PrepareTestDB::prepDB();
		
		$query = DB::select('id_log','id_monument')
			->from("logs_monuments")
			->execute()
			->as_array();
		
		$this->assertEquals(array(), $query);
		
		$logger = Logger::instance();
		
		//$categorie = Model::factory('Category');
		//$categorie->id_category = 1;
		$monument = ORM::factory('monument')->where('id_monument', '=', 1)->find();
		
		$logger->monument($monument);
		
		$query = DB::select('id_log','id_monument')
			->from("logs_monuments")
			->execute()
			->as_array();
		
		$this->assertEquals(array(array('id_log' => 1, 'id_monument' => 1)), $query);
	}
	
	
	public function test_keywords(){
		//prepare test database
		PrepareTestDB::prepDB();
		
		$query = DB::select('id_log','value')
			->from("logs_keywords")
			->execute()
			->as_array();
		
		$this->assertEquals(array(), $query);
		
		$logger = Logger::instance();
		
		$logger->keywords("test1 test2 test3");
		
		$query = DB::select('id_log','value')
			->from("logs_keywords")
			->execute()
			->as_array();
		
		
		$this->assertEquals(
			array(
				0=>array(
					'id_log'=>1, 
					'value'=>"test1"
				), 
				1=>array(
					'id_log'=>1, 
					'value'=>"test2"
				), 2=>array(
					'id_log'=>1, 
					'value'=>"test3"
				)
			), $query);
	}
	
	
	
	public function test_bind_user(){
		
		print_r(array(1=>"fuck"));
		echo "hoi";
		
		
		$query = DB::select('id_tracker','id_user')
			->from("trackers")
			->execute()
			->as_array();
		
		$this->assertEquals(array(), $query);
		
		
		$user = ORM::factory("user");
		$user->id = 1;
		
		
		$logger = Logger::instance();

		//$logger->tracker->saved = false;
		
		$logger->bind_user($user);
		
		/*$query = DB::select('id_tracker','id_user')
			->from("trackers")
			->execute()
			->as_array();
		*/
		//print_r($query);
		//$this->assertEquals(array(), $query);
	}
	
}

?>