<?php
foreach($tags as $key => $tag) {
	echo "<a style='font-size: ".$tag['fontsize']."px;' href='".URL::site("monument/list")."/".$tag['content']."'>".$tag['content']."</a>\n";
}
?>
