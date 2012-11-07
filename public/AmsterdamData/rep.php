<?php /*
$dat = file_get_contents("dump.sql");
$dat = preg_replace("/(\d{2})\/(\d{2})\/(\d{4})/", "$3-$2-$1", $dat, -1, $count);
echo $count;
file_put_contents("dump2.sql", html_entity_decode($dat)); */
?>
<?php
$dat = file_get_contents("dump.sql");
$date = "\d{4}\-\d{2}\-\d{2}";
$pattern = "/^.*'(($date,)+($date))'.*$/m";
preg_match_all($pattern, $dat, $matches);

$multies = $matches[0];
$seq = $matches[1];

foreach($multies as $key => $line){
	$line2 = str_replace($seq[$key], "seq-".($key+1), $line);
	$ds = split(",", $seq[$key]);
	
	$extra = array();
	foreach($ds as $d){
		$row = trim(str_replace("NULL,NULL,'seq", "'$d',NULL,'seq", $line2), " ;,");
		$extra[] = $row;
	}
	
	$lastchar = substr($line, -1);
	$dat = str_replace($line, implode(",\n", $extra).$lastchar, $dat);
}

$dat = preg_replace("/\(\d+,/", "(NULL,", $dat);
file_put_contents("dump3.sql", $dat);
?>