<?php defined('SYSPATH') or die('No direct access allowed!');
 
class ListTest extends Kohana_UnitTest_TestCase
{
    public function test_startPage()
    {
		$this->assertEquals(Request::factory('monument')->execute()->send_headers()->body(), Request::factory('monument/list')->execute()->send_headers()->body());
    }
	
	
	 public function test_MenuButtons()
    {
		
		include_once(Kohana::find_file('vendor/simplehtmldom', 'simple_html_dom'));
		//echo "bla: " . Kohana::find_file('vendor/simplehtmldom', 'simple_html_dom');
		$pageDOM = str_get_html(Request::factory('monument/list')->execute()->send_headers()->body());
		$buttons = $pageDOM->find('.nav-collapse',0)->find('.nav',0)->find('li');
		
		$this->assertCount(2, $buttons);
		$this->assertEquals(trim($buttons[0]->plaintext), "Kaart");
		$this->assertEquals(trim($buttons[1]->plaintext), "Lijst");
		
		
	}
	
	public function test_SelectieItems(){
		
		$pageDOM = str_get_html(Request::factory('monument/list')->execute()->send_headers()->body());
		$filter = $pageDOM->find('#filter_list',0);
		
		
		$this->assertCount(1,$filter->find('[id=search]'));
		$this->assertCount(1,$filter->find('[id=categories]'));
		$this->assertCount(1,$filter->find('[id=town]'));
		$this->assertCount(1,$filter->find('[id=popularity]'));
		$this->assertCount(1,$filter->find('[id=year]'));
		
		//$this->assertContains('id="search"',$inputs[0]->outertext);
		//$this->assertContains('id="town"',$inputs[1]->outertext);
		//$this->assertContains('type="submit"',$inputs[2]->outertext);
		
		//$this->assertCount(3, $inputs);
		//$this->assertCount(2, $selects);
	}
}