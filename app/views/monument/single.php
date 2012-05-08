    <header class="jumbotron subhead" style="margin-bottom: 30px;" id="overview">
            <h1><?php echo $monument->name; ?> <small><a href="javascript:history.back(1);">Terug</a></small></h1>
    </header>
    
    <div class="row">
    	<div class="span9">
		    <div class="row">
		    	<div class="span3">
		   			<img style="width: 100%; margin-right: 20px;" src="<?php echo $monument->photo(); ?>" alt="<?php echo $monument->name; ?>" />	
		   		</div>
		   		<div class="span6">
		   			<h2>Informatie</h2>
		   			<table class="table table-bordered table-striped">
		   				<tr>
		   					<td>Adres</td><td><?php echo $monument->street->name.' '.$monument->streetNumber; ?></td>
		   				</tr>
		   				<tr>
		   					<td>Stad</td><td><?php echo $monument->town->name; ?></td>
		   				</tr>
		   				<tr>
		   					<td>Gemeente</td><td><?php echo $monument->municipality->name; ?></td>
		   				</tr>
		   				<tr>
		   					<td>Provincie</td><td><?php echo $monument->province->name; ?></td>
		   				</tr>
		   				<tr>
		   					<td>Hoofdcategorie</td><td><?php echo $monument->category->name; ?></td>
		   				</tr>
		   				<tr>
		   					<td>Subcategorie</td><td><?php echo $monument->subcategory->name; ?></td>
		   				</tr>
		   				<tr>
		   					<td colspan="2"><?php echo $monument->description; ?></td>
		   				</tr>
		   			</table>
		    	<?php 
// 		    		echo $monument->name."<br />";
// 		    		echo $monument->province->name."<br />";
// 		    		echo $monument->town->name."<br />";
// 		    		echo $monument->street->name." ".$monument->streetNumber."<br />";
// 		    		echo '<br />';
// 		    		echo $monument->description.'<br />';
// 		    		echo '<br />';
// 		    		echo 'Categorie: '.$monument->category->name.'<br />';
// 		    		echo 'Subcategorie: '.$monument->subcategory->name.'<br />';
// 		    		echo '<br />';
// 		    		$links = ORM::factory('link')->join('monument_link')->on('monument_link.id_link', '=', 'link.id_link')->where('monument_link.id_monument', '=', $monument->id_monument)->and_where('link.name', '=', 'wiki')->find_all();
// 		    		foreach ($links AS $link) {
// 		    			echo '<a href="'.$link->url.'">'.$link->url.'</a><br />';
// 		    		}
		    	?>
		    	</div>
		    </div>
		    <div class="row">
		    	<div class="span9" style="margin-top: 30px;">  
		<?php
		$similars = $monument->similars400(4);
		
		if ($similars['euclidian']) {
			echo '<h2 style="margin-top: 20px;">Visueel gelijkende monumenten</h2>';
		}
		else {
			echo '<h2>Andere monumenten uit dezelfde categorie</h2>';
			
		}
		
		$monuments = $similars['monuments'];
		foreach ($monuments AS $monument_s) {
			echo '<div style="text-align: center; float: left; width: 25%;">';
			echo '<a href="'.URL::site('monument/id/'.$monument_s->id_monument).'"><img style="max-width: 80%; max-height: 150px; margin-top: 20px;" src="'.$monument_s->photo().'" alt="'.$monument_s->name.'" /></a>';
			echo '</div>';
		}
		?>
		    	</div>
		    </div>
		</div>
		<div class="span3" style="text-align: center;">
			<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $monument->lng.",".$monument->lat ?>&zoom=14&size=200x900&maptype=road&markers=olor:blue%7Clabel:S%7C<?php echo $monument->lng.",".$monument->lat ?>&sensor=false">
		</div>
	</div>