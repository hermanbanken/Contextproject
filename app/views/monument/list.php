<div class="container-fluid">
	<div class="row-fluid">
		<div class="span9">
			<h1 style="margin-bottom: 10px;">Monumenten</h1>
	<?php echo $pagination; ?>
			<div class="container-fluid" style="padding: 0; border-top: 1px #DDD solid;">
	<?php 
	if (count($monuments) == 0) {
	?>
				<div class="row-fluid" style="padding: 5px 0; border-bottom: 1px #DDD solid;">
					<span>Er zijn helaas geen monumenten gevonden met de opgegeven criteria.</span>
				</div>
	<?php
	}
	foreach ($monuments AS $monument) {
		$monument_s = ORM::factory('monument', $monument['id_monument']);
	?>
				<div class="row-fluid" style="height: 100px; padding: 5px 0; border-bottom: 1px #DDD solid;">
					<div class="span2">				
						<a href="monument/id/<?php echo $monument_s->id_monument; ?>">
							<img src="<?php echo $monument_s->photo(); ?>" style="max-width: 100px; max-height: 100px;" alt="">
						</a>
					</div>
					<div class="span3">
						<a href="monument/id/12043"><?php echo $monument_s->name; ?></a>
						<span style="display:block"><?php echo (isset($monument['distance']) ? round($monument['distance'] * 1000).' meter' : ''); ?></span>
					</div>
					<div class="span7">
						<?php echo substr($monument_s->description, 0, 200) ?>... <a href="monument/id/<?php echo $monument_s->id_monument; ?>">Meer</a>
					</div>
				</div>
	<?php
	}
	?>
			</div>
	<?php echo $pagination; ?>
		</div>
	
		<div class="span3">
			<h2 style="margin-bottom: 10px; margin-top: 10px;">Selectie</h2>
			<form method="post" action="monument/list" id="filter_list">
				<input class="span3" id="search" type="text" name="search" value="<?php echo $post['search']; ?>" placeholder="zoeken" /> 
				<input class="span3" id="town" type="text" name="town" value="<?php echo $post['town']; ?>" placeholder="stad" /> 
				<input type="hidden" name="longitude" id="longitude" value="" /> 
				<input type="hidden" name="latitude" id="latitude" value="" /> 
				<select class="span3" id="categories" name="category">
					<option value='-1'>-- Categorie</option>
					<?php 
					$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();
					foreach($categories AS $category) {
						echo '<option value="'.$category->id_category.'"'; if ($post['category'] == $category->id_category) { echo ' selected="selected"'; } echo '>'.$category->name.'</option>';
					}
					?>
				</select>
				<br /> 
				<select class="span3" id="sort" name="sort">
					<option value="street">--Sorteer</option>
					<option value="relevance" <?php if ($post['sort'] == 'relevance') { echo ' selected="selected"'; } ?> >Relevantie</option>
					<option value="name" <?php if ($post['sort'] == 'name') { echo ' selected="selected"'; } ?> >Naam</option>
					<option value="distance" <?php if ($post['sort'] == 'distance') { echo ' selected="selected"'; } ?> >Afstand tot huidige locatie</option>
				</select> 		
				<label for="nearby">
					<input type="checkbox"name="distance_show" value="1" id="nearby" style="float:left" <?php if ($post['distance_show'] != 0) { echo ' checked="checked"'; } ?> />&nbsp;&nbsp;In de buurt zoeken
				</label>
				<input type="hidden" id="distanceinput" name="distance" value="<?php echo $post['distance']; ?>" />
				<div id="distancecontainer" <?php if ($post['distance_show'] == 0) { echo ' style="display:none"'; } ?>>
					<div class="well" style="padding: 8px;">
						<span>Afstand vanaf huidige locatie</span>
						<div id="distance"></div>
	      				<span id="distanceindicator"><?php echo $post['distance']; ?> kilometer</span>
      				</div>
      			</div>
				<input class="span3" type="submit" value="Filter" />
			</form>
		</div>
	</div>
</div>
