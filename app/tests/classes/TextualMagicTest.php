<?php defined('SYSPATH') or die('No direct access allowed!');
 
class TextualMagicTest extends Kohana_UnitTest_TestCase
{

   public function test_getTagCloud(){
	   //prepare test database
		PrepareTestDB::prepDB();
		$this->assertEquals(5,count(TextualMagic::tagcloud(5)));
		$this->assertEquals(2,count(TextualMagic::tagcloud(2)));    
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
		//PrepareTestDB::prepDB();
		
		//$monument = ORM::factory('monument')->where('id_monument','=',1)->find();
		//print_r($monument->name);
		
		//$related = TextualMagic::related($monument,2)->as_array();
		
		//print_r($related);
		//$this->assertCount(2,$related);
		//$this->assertEquals(array(2,3), array($related[0]->monument_id, $related[1]->monument_id));
		 
	 }
	 
	 
	 public function test_tags(){
		
		//prepare test database
		PrepareTestDB::prepDB();
		
		$monument = ORM::factory('monument')->where('id_monument','=',1)->find();
		
		//$monument->name = "testnaam"; 
		/*if($monument->loaded()){
			echo "Monumument is loaded!!!";
		}else{
			echo "Monument is not loaded!!!";
		}
		*/
		
		//print_r($monument->);
		//print_r($monument->as_array());
		
		$this->assertEquals(array(
						0 => array('original' => 'tagcontent4', 'content' => 'tagcontent4'), 
						1 => array('original' => 'tagcontent1', 'content' => 'tagcontent1'), 
						2 => array('original' => 'tagcontent2', 'content' => 'tagcontent2') 
						), TextualMagic::tags($monument,5) );
		
				$this->assertEquals(array(
						0 => array('original' => 'tagcontent4', 'content' => 'tagcontent4'), 
						1 => array('original' => 'tagcontent1', 'content' => 'tagcontent1') 
						), TextualMagic::tags($monument,2) );
	 }

	public function test_extractCategory(){
		
		$monument = ORM::factory('monument')->where('id_monument','=',1)->find();
		
		//print_r(TextualMagic::extractCategory($monument));
	}
}
?>