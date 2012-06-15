<?php defined('SYSPATH') or die('No direct access allowed!');
 
class TranslatorTest extends Kohana_UnitTest_TestCase
{
	public function test_translate(){
		//prepare test database
		PrepareTestDB::prepDB();
		
		
		Session::instance()->set('lang', 'en');
		
		$this->assertEquals("translation", Translator::translate('monuments', '4', "description", "vertaling") );
		
		$query = DB::select('translation', 'lang')
            ->from("translation")
            ->where('table', '=', 'monuments')
            ->and_where('field', '=', 'description')
            ->and_where('pk', '=', '4')
            ->execute()
			->as_array();
			
		$this->assertCount(1,$query);
		$this->assertEquals(array('translation'=>'translation', 'lang'=>'en'), $query[0]);	
		
		$this->assertEquals("translation", Translator::translate('monuments', '4', "description", "vertaling") );
	}


}

?>