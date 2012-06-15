<?php foreach ($forecasts AS $forecast): ?>
<div class="span1 day">
	<i><img src="<?php echo $forecast->icon(); ?>" alt="" /></i>
	<span class="temperature">
		<?php echo $forecast->temperature(); ?>&deg;C
	</span>
	<span class="dayabbr">
		<?php echo $forecast->day(); ?>
	</span>
</div>
<?php endforeach; ?>