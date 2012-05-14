			<form method="post" action="" id="<?php echo $formname; ?>">
				<input id="search" type="text" name="search" value="<?php echo $post['search']; ?>" placeholder="<?php echo __('selection.search'); ?>" /> 
				<select id="provinces" name="province">
					<option value='-1'>-- <?php echo __('province'); ?></option>
					<?php 
					$provinces = ORM::factory('province')->order_by('name')->find_all();
					foreach($provinces AS $province) {
						echo '<option value="'.$province->id_province.'"'; if ($post['province'] == $province->id_province) { echo ' selected="selected"'; } echo '>'.$province->name.'</option>';
					}
					?>
				</select>
				<input id="town" type="text" name="town" value="<?php echo $post['town']; ?>" placeholder="<?php echo __('city'); ?>" />
				<input type="hidden" name="longitude" id="longitude" value="" /> 
				<input type="hidden" name="latitude" id="latitude" value="" /> 
				<select id="categories" name="category">
					<option value='-1'>-- <?php echo __('selection.category'); ?></option>
					<?php 
					$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();
					foreach($categories AS $category) {
						echo '<option value="'.$category->id_category.'"'; if ($post['category'] == $category->id_category) { echo ' selected="selected"'; } echo '>'.$category->name.'</option>';
					}
					?>
				</select>
				<?php if ($formname == 'filter_list') { ?>
				<br />
				<select id="sort" name="sort">
					<option value="street">-- <?php echo __('selection.sort'); ?></option>
					<option value="relevance" <?php if ($post['sort'] == 'relevance') { echo ' selected="selected"'; } ?> ><?php echo __('selection.relevance'); ?></option>
					<option value="name" <?php if ($post['sort'] == 'name') { echo ' selected="selected"'; } ?> ><?php echo __('selection.name'); ?></option>
					<option value="distance" <?php if ($post['sort'] == 'distance') { echo ' selected="selected"'; } ?> ><?php echo __('selection.sistance'); ?></option>
				</select>
				<?php } ?>
				<label for="nearby">
					<input type="checkbox"name="distance_show" value="1" id="nearby" style="float:left" <?php if ($post['distance_show'] != 0) { echo ' checked="checked"'; } ?> />&nbsp;&nbsp;<?php echo __('selection.search-in-neighbourhood'); ?>
				</label>
				<div id="distancecontainer" <?php if ($post['distance_show'] == 0) { echo ' style="display:none"'; } ?>>
					<div class="well">
						<div id="distance"></div>
						<div id="distance_ipad"></div>
						<div id="distance_text"><input type="text" id="distanceinput" name="distance" value="<?php echo $post['distance']; ?>" /></div>
      				</div>
      			</div>
				<input class="btn btn-primary" id="filter_button" style="width: 100%;" type="submit" value="<?php echo __('selection.filter'); ?>" />
			</form>