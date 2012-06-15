<?php
class PrepareTestDB {   
    
	public static function prepDB(){
		   ini_set('memory_limit', '12800M');
		   
		   $db = Database::instance();
			
			$tables = $db->list_tables();
			$foreignKeyTables = array($db->table_prefix() . "roles", $db->table_prefix() . "users");
			$tables = array_diff($tables,$foreignKeyTables);
			
			//Leeg alle tables behalve roles en users vanwege foreign keys
			foreach($tables as $table){
				DB::query('TRUNCATE', 'TRUNCATE ' . $table)->execute();	
			}
			
			//Leeg alle tables die niet getruncate konden worden
			foreach($foreignKeyTables AS $table){
				DB::delete($table);
			}
			
			
			//Vul de database
			 
			 //categories
			 DB::insert("categories", array("id_category", "name"))
			 ->values( array(intval(1), "category1"))
			 ->values( array(intval(2), "category2"))
			 ->values( array(intval(3), "category3"))
			 ->execute();
			
			 //functions
			 DB::insert("functions", array("id_function", "name"))
			 ->values( array(intval(1), "function1"))
			 ->values( array(intval(2), "function2"))
			 ->values( array(intval(3), "function3"))
			 ->execute();		 
			 
			 //tags
			 DB::insert("tags", array("id", "content", "occurrences", "importance"))
			 ->values( array(1, "tagcontent1", 10, 0.3))
			 ->values( array(2, "tagcontent2", 200, 0.22))
			 ->values( array(3, "tag3", 3000, 0.13))
			 ->values( array(4, "tagcontent4", 10, 0.31))
			 ->values( array(5, "tagcontent5", 10, 0.32))
			 ->values( array(6, "tagcontent6", 10, 0.33))
			 ->values( array(7, "tagcontent7", 10, 0.34))
			 ->values( array(8, "tagcontent8", 10, 0.35))
			 ->values( array(9, "tagcontent9", 10, 0.36))
			 ->execute();

			 //tag monument links
			 DB::insert("tag_monument", array("monument", "tag", "occurrences"))
			 ->values( array(1,1,2) )
			 ->values( array(1,2,2) )
			 ->values( array(1,3,2) )
			 ->values( array(1,4,2) )
			 ->values( array(2,1,2) )
			 ->values( array(2,2,2) )
			 ->values( array(3,1,2) )
			 ->values( array(3,2,2) )
			 ->execute();			 
			
			 //thesaurus words
			 DB::insert("thesaurus_words", array("id", "word"))
			 ->values( array(intval(1), "bla"))
			 ->values( array(intval(2), "blabla"))
			 ->values( array(intval(3), "blablabla"))
			 ->values( array(intval(4), "blablablabla"))
			 ->values( array(intval(5), "foo"))
			 ->values( array(intval(6), "bar"))
			 ->values( array(intval(7), "geen"))
			 ->execute(); 
			 
			 
			 //thesaurus links
			 DB::insert("thesaurus_links", array("id", "word", "synonym"))
			 ->values( array('',1,2))
			 ->values( array('',1,3))
			 ->values( array('',1,4))
			 ->values( array('',2,1))
			 ->values( array('',2,3))
			 ->values( array('',2,4))
			 ->values( array('',3,1))
			 ->values( array('',3,2))
			 ->values( array('',3,4))
			 ->values( array('',4,1))
			 ->values( array('',4,2))
			 ->values( array('',4,3))
			 ->values( array('',5,6))
			 ->values( array('',6,5))
			 ->execute();		 
			 
			 //monuments
			 DB::insert("monuments", array('id_monument', 'id_category', 'id_subcategory', 'name', 'id_province', 'id_municipality', 'id_town', 'id_street', 'streetNumber', 'zipCode', 'id_function', 'description_commons', 'description', 'lng', 'lat', 'category_extracted'))
			 ->values(array(1,1,1,"test monument", 1, 1, 1, 1, '31', '1234AB', 1, "description commons", "description", 50.5, 50.5, 0))
			 ->values(array(2,1,1,"test monument2", 1, 1, 1, 1, '32', '1234CD', 1, "description commons2", "description2", 100.2, 100.2, 0))
			 ->values(array(3,1,1,"test monument3", 1, 1, 3, 1, '32', '1234EF', 1, "description commons3", "description3", 10.3, 10.3, 0))
			 ->values(array(4,1,1,"test monument4", 1, 1, 3, 1, '32', '1234GH', 1, "description commons4", "vertaling", 10.3, 10.3, 0))
			 ->values(array(5,1,1,"test monument5", 1, 1, '', 1, '32', '1234IJ', 1, "description commons5", "description5", '', '', 0))
			 ->values(array(6,1,1,"test monument6", 1, 1, '', 1, '32', '1234IJ', 1, "description commons6", "description6", '52', '4.5', 0))
			 ->values(array(-1,1,1,"test monument-1", 1, 1, '', 1, '32', '1234IJ', 1, "description commons-1", "description-1", '52', '4.5', 0))
			 ->execute();
			 
			 
			 
			 //towns
			 DB::insert("towns", array("id_town", "id_municipality", "name"))
			 ->values(array(1,1,"town1"))
			 ->values(array(2,2,"town2"))
			 ->values(array(3,3,"town3"))
			 ->execute();
			 
			 
			
			 
			 
	}
			
			
			
			
			
	   
}
?>