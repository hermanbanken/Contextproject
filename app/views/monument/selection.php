			<form method="post" action="" id="<?php echo $formname; ?>">
				<input class="span3" id="search" type="text" name="search" value="<?php echo $post['search']; ?>" placeholder="zoeken" /> 
				<select class="span3" id="provinces" name="province">
					<option value='-1'>-- Provincie</option>
					<?php 
					$provinces = ORM::factory('province')->order_by('name')->find_all();
					foreach($provinces AS $province) {
						echo '<option value="'.$province->id_province.'"'; if ($post['province'] == $province->id_province) { echo ' selected="selected"'; } echo '>'.$province->name.'</option>';
					}
					?>
				</select>
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
				<div id="distancecontainer" <?php if ($post['distance_show'] == 0) { echo ' style="display:none"'; } ?>>
					<div class="well">
						<div id="distance"></div>
						<div id="distance_ipad"></div>
						<div id="distance_text"><input type="text" id="distanceinput" name="distance" placeholder="km" value="<?php echo $post['distance']; ?>" /></div>
      				</div>
      			</div>
				<input class="btn btn-primary" id="filter_button" style="width: 100%;" type="submit" value="Filter" />
			</form>