			<form method="get" action="<?php echo URL::site('monument/list') ?>" id="<?php echo $formname; ?>">
				<input id="search" type="text" name="search" value="<?php echo $param['search']; ?>" placeholder="<?php echo __('selection.search'); ?>" /> 
				<input id="town" type="text" name="town" value="<?php echo $param['town']; ?>" placeholder="<?php echo __('city'); ?>" />
				<input type="hidden" name="longitude" id="longitude" value="" /> 
				<input type="hidden" name="latitude" id="latitude" value="" /> 
				<select id="categories" name="category">
					<option value='-1'>-- <?php echo __('selection.category'); ?></option>
					<?php 
					foreach($categories AS $category) {
						echo '<option value="'.$category->id_category.'"'; if ($param['category'] == $category->id_category) { echo ' selected="selected"'; } echo '>'.$category->name.'</option>';
					}
					?>
				</select>
				<?php if ($formname == 'filter_list') { ?>
				<br />
				<select id="sort" name="sort">
					<option value="street">-- <?php echo __('selection.sort'); ?></option>
					<option value="popularity" <?php if ($param['sort'] == 'popularity') { echo ' selected="selected"'; } ?> ><?php echo __('selection.popularity'); ?></option>
					<option value="relevance" <?php if ($param['sort'] == 'relevance') { echo ' selected="selected"'; } ?> ><?php echo __('selection.relevance'); ?></option>
					<option value="name" <?php if ($param['sort'] == 'name') { echo ' selected="selected"'; } ?> ><?php echo __('selection.name'); ?></option>
					<option value="distance" <?php if ($param['sort'] == 'distance') { echo ' selected="selected"'; } ?> ><?php echo __('selection.distance'); ?></option>
				</select>
				<?php } ?>
				<label for="nearby">
					<input type="checkbox"name="distance_show" value="1" id="nearby" style="float:left" <?php if ($param['distance_show'] != 0) { echo ' checked="checked"'; } ?> />&nbsp;&nbsp;<?php echo __('selection.search-in-neighbourhood'); ?>
				</label>
				<div id="distancecontainer" <?php if ($param['distance_show'] == 0) { echo ' style="display:none"'; } ?>>
					<div class="well">
						<div id="distance"></div>
						<div id="distance_ipad"></div>
						<div id="distance_text"><input type="text" id="distanceinput" name="distance" value="<?php echo $param['distance']; ?>" /></div>
      				</div>
      			</div>
				<input class="btn btn-primary" id="filter_button" style="width: 100%;" type="submit" value="<?php echo __('selection.filter'); ?>" />
			</form>