<?php defined('SYSPATH') or die('No direct access allowed!');
 
class TextualMagicTest extends Kohana_UnitTest_TestCase
{

   public function test_getTagCloud(){
	   //prepare test database
		PrepareTestDB::prepDB();
		$this->assertEquals(count(TextualMagic::tagcloud(5)),3);
		$this->assertEquals(count(TextualMagic::tagcloud(2)),2);    
   }
   
   
   public function test_getSynonyms()
     {
		 
		 //prepare test database
		PrepareTestDB::prepDB();
		
		
		//$bla = Request::factory('monument');
		
		//$test = new Controller_Monument();
		
		
		//$this->assertInstanceOf(Controller_Monument::getSynonyms("bla"), Array);

		$this->assertEquals(array(array('synoniem'=>'blabla'),array('synoniem'=>'blablabla'),array('synoniem'=>'blablablabla')), TextualMagic::synonyms("bla"));
		$this->assertEquals(array(array('synoniem'=>'bla'),array('synoniem'=>'blablabla'),array('synoniem'=>'blablablabla')),  TextualMagic::synonyms("blabla"));
		$this->assertEquals(array(array('synoniem'=>'bla'),array('synoniem'=>'blabla'),array('synoniem'=>'blablablabla')),  TextualMagic::synonyms("blablabla"));
		$this->assertEquals(array(array('synoniem'=>'bla'),array('synoniem'=>'blabla'),array('synoniem'=>'blablabla')),  TextualMagic::synonyms("blablablabla"));
		$this->assertEquals(array(array('synoniem'=>'bar')),  TextualMagic::synonyms("foo"));
		$this->assertEquals(array(array('synoniem'=>'foo')),  TextualMagic::synonyms("bar"));
		$this->assertEquals(false,  TextualMagic::synonyms("geen"));		

	 }
	 
	 public function test_related(){
		
		//prepare test database
		PrepareTestDB::prepDB();
		
		$monument = ORM::factory('monument')->where('id_monument','=',1)->find();
		//print_r($monument->name);
		
		$related = TextualMagic::related($monument,2)->as_array();
		
		//print_r($related);
		$this->assertCount(2,$related);
		$this->assertEquals(array(2,3), array($related[0]->monument_id, $related[1]->monument_id));
		 
	 }
	 
	 
	 public function test_tags(){
		
		//prepare test database
		PrepareTestDB::prepDB();
		
		$monument = ORM::factory('monument')->where('id_monument','=',1)->find();
		
		$this->assertEquals(array(0=>'tagcontent4',1=>'tagcontent1', 2=>'tagcontent2'),TextualMagic::tags($monument,5));
		$this->assertEquals(array(0=>'tagcontent4',1=>'tagcontent1'),TextualMagic::tags($monument,2));
	 }

	public function test_extractCategory(){
		
		$monument = ORM::factory('monument')->where('id_monument','=',1)->find();
		
		//print_r(TextualMagic::extractCategory($monument));
	}
}
?>