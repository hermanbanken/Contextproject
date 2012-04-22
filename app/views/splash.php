<h1>Installatie completeren</h1>
<ul><li>app/bootstrap.default.php -> app/bootstrap.php</li>
<li>public/default.htaccess -> public/.htaccess</li>
<li>app/config/database.default.php -> app/config/database.php</li></ul>

<h1>Installeren</h1>
<p>De installatie controller kan nu al tabellen maken in een lege database. Hiervoor moeten modellen bestaan die Model_Abstract_Cultuurorm extenden en het schema veld hebben ingevuld.</p>
<pre>Controller_Install::create_tables()</pre>

<h1>Deze pagina</h1>
<?php
	echo "Hier komt de SPLASH page!";
	echo "<br>Deze staat in app/views/splash.php";
	echo "<br>De hoofd layout (buitenkant) staat in app/views/layout.php";
	echo "<br>De views voor modellen staan per soort in mapjes.";
	echo "<br><br>Dit zijn de variabelen die gebonden zijn aan deze view:";
	echo "<pre>".print_r(get_defined_vars(), true)."</pre>";
?>

<h1>Model maken</h1>
<pre>ORM::factory('monument');</pre>
<h1>Nieuwe soort model maken</h1>
Nieuwe bestand .php maken in de map app/classes/model en deze invullen zoals monument.php

