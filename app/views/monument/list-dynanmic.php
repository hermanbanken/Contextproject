<div class="container-fluid" id="list">
	<div class="row-fluid">
		<div class="span9">
			<h1 style="margin-bottom: 10px;"><?php echo __('list.monuments'); ?></h1>

			<div class='pagination'></div>
			<div class="monument-list container-fluid list-container" style="padding: 0;">
				<div class="empty">
					<div class="row-fluid list-row" style="padding: 5px 0; border-bottom: 1px #DDD solid;">
						<span><?php echo __('list.zero-results'); ?></span>
					</div>
				</div>

				<div class="row-fluid list-row monument">
					<div class="span2 photo">
						<a href=""><img src="" alt="" /></a>
					</div>
					<div class="span3 name">
						<a href=""></a>
						<span class="distance" style="display: block;"></span>
					</div>
					<div class="span7">
						<span class="summary"></span>
						<a href="">Meer</a>
					</div>
				</div>
			</div>
		</div>

		<div class="span3">
			<h2 style="margin-bottom: 10px; margin-top: 10px;"><?php echo __('list.selection'); ?></h2>
			<?php echo $selection_form; ?>
			<h2 style="margin-bottom: 10px; margin-top: 10px;">Tags</h2>
			<div class="tagcloud">
			</div>
		</div>
	</div>
</div>
