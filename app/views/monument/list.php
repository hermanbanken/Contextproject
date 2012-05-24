<div class="container-fluid" id="list">
	<div class="row-fluid">
		<div class="span9">
			<h1 style="margin-bottom: 10px;"><?php echo __('list.monuments'); ?></h1>
			<?php echo $pagination; ?>
			<div class="container-fluid list-container" style="padding: 0;">
				<?php 
				if (count($monuments) == 0) {
					?>
				<div class="row-fluid list-row"
					style="padding: 5px 0; border-bottom: 1px #DDD solid;">
					<span><?php echo __('list.zero-results'); ?></span>
				</div>
				<?php
				}
				foreach ($monuments AS $monument) {
					$monument_s = ORM::factory('monument', $monument['id_monument']);
					?>
				<div class="row-fluid list-row">
					<div class="span2">
						<a href="monument/id/<?php echo $monument_s->id_monument; ?>"> <img
							src="<?php echo $monument_s->photo(); ?>" alt="">
						</a>
					</div>
					<div class="span3">
						<a href="monument/id/<?php echo $monument_s->id_monument; ?>"><?php echo $monument_s->name; ?>
						</a> <span style="display: block;"><?php 
						if (isset($monument['distance'])) {
							$union = 'meter';
							$distance = round($monument['distance'] * 1000);
							if ($distance > 1000) {
								$distance = round($distance / 1000, 2);
								$union = 'km';
							}
							echo $distance.' '.$union;
						}
						?> </span>
					</div>
					<div class="span7">
						<?php echo substr($monument_s->description, 0, 200) ?>
						... <a href="monument/id/<?php echo $monument_s->id_monument; ?>">Meer</a>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			<?php echo $pagination; ?>
		</div>

		<div class="span3">
			<h2 style="margin-bottom: 10px; margin-top: 10px;"><?php echo __('list.selection'); ?></h2>
			<?php echo $selection_form; ?>
			<h2 style="margin-bottom: 10px; margin-top: 10px;">Tags</h2>
			<div class="tagcloud">
				<?php echo $tagcloud; ?>
			</div>
		</div>
	</div>
</div>
