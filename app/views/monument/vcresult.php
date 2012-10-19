<table class="similars">
	<?php 
	// 				foreach ($similars AS $similar) {
	// 					echo '
	// 					<li class="span2"><a href="monument/visualcomparison/'.$similar->id_monument.'" class="thumbnail">
	// 					<img style="max-height: 100px;" src="'.$similar->getphoto()->url().'" alt="'.$similar->name.'"></a></li>';
	// 				}
	$i = 1;
	foreach ($similars AS $similar) {
		if ($i == 5) {
			echo '</tr><tr>';
			$i = 1;
		}
		echo '
		<td style="text-align: center; vertical-align: middle;">
		<a style="display: block;" href="'.URL::site('monument/visualcomparison/'.$similar->id_monument).'?posted">
		<img style="max-height: 100px;" src="'.$similar->getphoto()->url().'" alt="'.$similar->name.'">
		</a>
		</td>';
		$i++;
	}

	if (count($similars) == 0) {
		if ($posted) {
			echo '<td>'.__('visualcomparison.nothingfound').'</td>';
		}
		else {
			echo '<td>'.__('visualcomparison.searchinstructions').'</td>';
		}
	}

?>
</table>