    <header class="jumbotron subhead" style="margin-bottom: 30px;" id="overview">
            <h1><?php echo $monument->name; ?></h1>
    </header>
    
    <div class="row">
    	<div class="span3">
   			<img style="width: 100%; margin-right: 20px;" src="/public/photos/<?php echo $monument->id_monument; ?>.JPG" alt="<?php echo $monument->name; ?>" />	
   		</div>
   		<div class="span9">
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
    <div class="row" style="padding-top: 20px;">
    	<div class="span12">    		
    		<img style="width: 100%;" src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $monument->lng.",".$monument->lat ?>&zoom=14&size=900x200&maptype=road&markers=olor:blue%7Clabel:S%7C<?php echo $monument->lng.",".$monument->lat ?>&sensor=false">
    	</div>