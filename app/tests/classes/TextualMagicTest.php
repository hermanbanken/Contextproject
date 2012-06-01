<?php defined('SYSPATH') or die('No direct access allowed!');
 
class TextualMagicTest extends Kohana_UnitTest_TestCase
{

   public function test_getTagCloud(){
	   PrepareTestDB::prepDB();
		$this->assertEquals(count(TextualMagic::tagcloud(5)),3);
		$this->assertEquals(count(TextualMagic::tagcloud(2)),2);    
   }
   
   
   public function test_getSynonyms()
     {
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

}
?>