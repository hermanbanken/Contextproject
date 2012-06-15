<?php
class GooglePlacesTest extends Kohana_UnitTest_TestCase
{
	public function test_places(){
		//prepare test database
		PrepareTestDB::prepDB();
		
				
		
		
		$query = DB::select('id_place', 'id_monument', 'categories', 'name', 'vicinity', 'lng', 'lat', 'rating')
			->from("places")
			->execute()
			->as_array();
		
		
		$this->assertEquals(array(), $query);
		
		
		$monument1 = ORM::factory('monument')->where('id_monument','=',6)->find();
		
		$places = GooglePlaces::places($monument1, "bar|cafe", "rating", 999999.999, false, 5);
		
		//print_r($places->as_array());
		
		
	}
	
}

?>