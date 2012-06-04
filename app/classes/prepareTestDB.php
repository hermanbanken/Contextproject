<?php
class PrepareTestDB {   
    
	public static function prepDB(){
		   
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
			 ->values( array(intval(1), "tagcontent1", 10, 0.3))
			 ->values( array(intval(2), "tag2", 200, 0.22))
			 ->values( array(intval(3), "tag3", 3000, 0.13))
			 ->values( array(intval(4), "tagcontent4", 10, 0.31))
			 ->values( array(intval(5), "tagcontent5", 10, 0.31))
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
			 
			 
			 DB::insert("monument_link", array("id_monument", "id_link"))->values( array(intval(0), intval(0)))->execute();
			
			
			
			
	   }
}
?>