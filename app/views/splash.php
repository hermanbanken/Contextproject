<?php
	echo "Hier komt de SPLASH page!";
	echo "<br>Dit zijn variabelen die gebonden zijn aan deze view:";
	echo "<pre>".print_r(get_defined_vars(), true)."</pre>";

	print_r(Controller_Install::create_tables());
	ORM::factory('monument');
?>