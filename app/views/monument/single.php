    <header class="jumbotron subhead" style="margin-bottom: 30px;" id="overview">
            <h1><?php echo $monument->name; ?></h1>
    </header>
    
    <div class="row">
    	<div class="span9">
		    <div class="row">
		    	<div class="span3">
		   			<img style="width: 100%; margin-right: 20px;" src="<?php echo $monument->photo(); ?>" alt="<?php echo $monument->name; ?>" />	
		   		</div>
		   		<div class="span6">
		    	<?php 
		    		echo $monument->name."<br />";
		    		echo $monument->province."<br />";
		    		echo $monument->town."<br />";
		    		echo $monument->street." ".$monument->streetNumber."<br />";
		    		echo '<br />';
		    		echo $monument->description.'<br />';
		    		echo '<br />';
		    		echo 'Categorie: '.$monument->category->name.'<br />';
		    		echo 'Subcategorie: '.$monument->subcategory->name;
		    	?>
		    	</div>
		    </div>
		    <div class="row">
		    	<div class="span9" style="margin-top: 30px;">  
		<?php 
		$similars = $monument->similars(5);
		
		if ($similars['euclidian']) {
			$monument->extractcategory();
			echo '<h2 style="margin-top: 20px;">Visueel gelijkende monumenten</h2>';
		}
		else {
			echo '<h2>Andere monumenten uit dezelfde categorie</h2>';
			
		}
		
		$monuments = $similars['monuments'];
		foreach ($monuments AS $monument_s) {
			echo '<div style="text-align: center; width: 170px; float: left;">';
			echo '<a href="'.URL::site('monument/id/'.$monument_s->id_monument).'"><img style="max-width: 150px; max-height: 150px; margin-top: 20px;" src="'.$monument_s->photo().'" alt="'.$monument_s->name.'" /></a>';
			echo '</div>';
		}
		?>  		
		    	</div>
		    </div>
		</div>
		<div class="span3" style="text-align: center;">
			<img style="width: 100%;" src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $monument->lng.",".$monument->lat ?>&zoom=14&size=200x900&maptype=road&markers=olor:blue%7Clabel:S%7C<?php echo $monument->lng.",".$monument->lat ?>&sensor=false">
		</div>
	</div>