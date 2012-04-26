<?php

$rewrite = array(
	'bootstrap' => array(
		'default'=>'./app/bootstrap.default.php', 
		'custom' =>'./app/bootstrap.php', 
		'fields'=>array("baseurl"),
	),
	'htaccess' => array(
		'default' => './public/default.htaccess',
		'custom'  => './public/.htaccess',
		'fields'  => array('baseurl'),
	),
	'database' => array(
		'default' => './app/config/database.default.php',
		'custom'  => './app/config/database.php',
		'fields' => array("hostname", "database", "username", "password"),
	),
);

$store = array("store"=>array("custom"=>"./custom_settings.json"));
$proceed = true;
$suggest = array();
foreach(array_merge($rewrite, $store) as $name => $config){
	if(!is_writable($config['custom'])){
	 	print "Configuration file ". $config['custom'] ." is not writable!<br />";
		$suggest[] = "touch ".$config['custom']." && chmod o+w ".$config['custom']."\n";
		$proceed = false;
	}
}
if(!$proceed) die("You need to fix these permissions first before we can proceed. Try: <pre>".implode('', $suggest)."</pre>");

if(isset($_POST['install']))
{
	foreach($rewrite as $name => $config){
		if(!file_exists($config['default'])) die("Default config for $config not found");
		$file = file_get_contents($config['default']);
		foreach($config['fields'] as $key){
			$file = str_replace(":$key", value($name, $key), $file);
		}
		file_put_contents($config['custom'], $file);
	}

}

/*if(isset($_POST['file'])){
	$f = realpath($_POST['file']);
	$t = realpath(dirname(__FILE__));
	if(strpos($f, $t) != 0) die('0');

	echo (file_exists($_POST['file']) ? '1' : '0');
}*/

function load(){
	global $config_loaded;
	if($config_loaded) return $config_loaded;
	else {
		$f = @file_get_contents('custom_settings.json');
		if(!$f) return $config_loaded = (object) array();
		else return $config_loaded = json_decode($f);
	}
}

function value($name, $key){
	$config = load();
	
	// Make config
	if(!isset($config->$name)) $config->$name = (object) array();
	
	// Return POST
	if(isset($_POST[$name][$key])){
		$config->$name->$key = $_POST[$name][$key];
		file_put_contents('custom_settings.json', json_encode($config));
	}
	
	// Return saved
	return isset($config->$name->$key) ? $config->$name->$key : '';
}

?>
<h1>Install</h1>
<form method="post"><table>
<?php
	foreach($rewrite as $name => $config){
		echo "<tr><th colspan=2><h2>$name</h2></th></tr>";
		foreach($config['fields'] as $key){
			echo "<tr><th>$key</th><td><input type='text' name='".$name."[$key]' value='".value($name, $key)."' /></td></tr>";
		}
	}
?></table>
<input type='hidden' name='install' value='1'>
<input type='submit' value='Install' />
</form>
