    <header class="jumbotron subhead" style="margin-bottom: 30px;" id="overview">
            <h1><?php echo $monument->name; ?></h1>
    </header>
    
    <div class="row">
    	<div class="span9">
		    <div class="row">
		    	<div class="span3">
		   			<img style="width: 100%; margin-right: 20px;" src="/public/photos/<?php echo $monument->id_monument; ?>.jpg" alt="<?php echo $monument->name; ?>" />	
		   		</div>
		   		<div class="span6">
		    	<?php 
		    		echo $monument->name."<br />";
		    		echo $monument->province."<br />";
		    		echo $monument->town."<br />";
		    		echo $monument->street." ".$monument->streetNumber."<br />";
		    		echo "<br />";
		    		echo $monument->description;
		    	?>
		    	</div>
		    </div>
		    <div class="row">
		    	<div class="span9" style="margin-top: 30px;">  
		    		<h2>Andere monumenten uit dezelfde categorie</h2>
		<?php 
		$monuments = ORM::factory('monument')
   		->where('id_category', '=', $monument->id_category)
   		->order_by(DB::expr('RAND()'))
        ->limit(4)
		->find_all();
		foreach ($monuments AS $monument) {
			echo '<div style="text-align: center; width: 170px; float: left;">';
			echo '<a href="'.URL::site('monument/id/'.$monument->id_monument).'"><img style="max-width: 150px; max-height: 150px; margin-top: 20px;" src="/public/photos/'.$monument->id_monument.'.jpg" alt="'.$monument->name.'" /></a>';
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