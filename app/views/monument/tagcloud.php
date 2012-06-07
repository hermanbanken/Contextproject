<?php
foreach($tags as $key => $tag) {
	$query = Request::initial()->query();
	$query['search'] = $tag['content'];
	unset($query['page']);
	$href = URL::site("monument/list".URL::query($query));
	echo "<a style='font-size: ".$tag['fontsize']."px;' href='".$href."'>".$tag['content']."</a>\n";
}
?>
