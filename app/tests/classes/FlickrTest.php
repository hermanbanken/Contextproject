<?php
class FlickrTest extends Kohana_UnitTest_TestCase
{
	public function test_photos(){
		//prepare test database
		PrepareTestDB::prepDB();
		
				
		
		
		$query = DB::select('id_flickrphoto', 'id_monument', 'url')
			->from("flickrphotos")
			->execute()
			->as_array();
		
		
		$this->assertEquals(array(), $query);
		
		
		$monument1 = ORM::factory('monument')->where('id_monument','=',1)->find();
		
		$photos = Flickr::photos($monument1, 2);
		
		//print_r($photos);
		
		
	}
	
}

?>