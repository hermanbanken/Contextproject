<div id="searchdiv">
	<form method="post" action="" id="filter" style="margin-bottom: 0;">
		<input id="search" type="text" name="search" value="<?php echo $post['search']; ?>" placeholder="zoeken" /> 
				<select id="provinces" name="province">
					<option value='-1'>-- Provincie</option>
					<?php 
					$provinces = ORM::factory('province')->order_by('name')->find_all();
					foreach($provinces AS $province) {
						echo '<option value="'.$province->id_province.'"'; if ($post['province'] == $province->id_province) { echo ' selected="selected"'; } echo '>'.$province->name.'</option>';
					}
					?>
				</select>
		<input id="town" type="text" name="town" value="<?php echo $post['town']; ?>" placeholder="stad" /> 
		<select id="categories">
			<option value='-1'>-- Categorie</option>
			<?php 
				$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();
				foreach($categories AS $category) {
					echo '<option value="'.$category->id_category.'"'; if ($post['category'] == $category->id_category) { echo ' selected="selected"'; } echo '>'.$category->name.'</option>';
				}
				?>
		</select>
		<br />
				<label for="nearby">
					<input type="checkbox" id="nearby" name="distance_show" value="1" style="float:left" <?php if ($post['distance_show'] != 0) { echo ' checked="checked"'; } ?> />&nbsp;&nbsp;In de buurt zoeken
				</label>
				<input type="hidden" id="distanceinput" name="distance" value="<?php echo $post['distance']; ?>" />
				<div id="distancecontainer" <?php if ($post['distance_show'] == 0) { echo ' style="display:none"'; } ?>>
					<div class="well" style="padding: 8px;">
						<span>Afstand vanaf huidige locatie</span>
						<div id="distance"></div>
	      				<span id="distanceindicator"><?php echo $post['distance']; ?> kilometer</span>
      				</div>
      			</div>
		<input type="submit" id="filter" value="Filter" />
	</form>
</div>
<div id="kaart"></div>