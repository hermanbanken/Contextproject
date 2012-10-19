<form method="get" action="<?php echo URL::site('event/list') ?>" id="<?php echo $formname; ?>">
	<input id="search" type="text" name="search" value="<?php echo $param['search']; ?>" placeholder="<?php echo __('selection.search'); ?>" /> 
	<input id="town" type="text" name="town" value="<?php echo $param['town']; ?>" placeholder="<?php echo __('city'); ?>" />
	<input type="hidden" name="longitude" id="longitude" value="" /> 
	<input type="hidden" name="latitude" id="latitude" value="" /> 
	<select id="categories" name="category">
		<option value='-1'>-- <?php echo __('selection.category'); ?></option>
		<?php 
		foreach($types AS $type) {
			echo '<option value="'.$type.'"'; if ($param['category'] == $type) { echo ' selected="selected"'; } echo '>'.$type.'</option>';
		}
		?>
	</select>
	<?php if ($formname == 'filter_list') { ?>
	<br />
	<select id="sort" name="sort">
		<option>-- <?php echo __('selection.sort'); ?></option>
		<option value="date" <?php if ($param['sort'] == 'date') { echo ' selected'; } ?> ><?php echo __('selection.date'); ?></option>
		<option value="popularity" <?php if ($param['sort'] == 'popularity') { echo ' selected'; } ?> ><?php echo __('selection.popularity'); ?></option>
		<option value="relevance" <?php if ($param['sort'] == 'relevance') { echo ' selected'; } ?> ><?php echo __('selection.relevance'); ?></option>
		<option value="name" <?php if ($param['sort'] == 'name') { echo ' selected'; } ?> ><?php echo __('selection.name'); ?></option>
	</select>
	<?php } ?>
	<input class="btn btn-primary" id="filter_button" style="width: 100%;" type="submit" value="<?php echo __('selection.filter'); ?>" />
</form>