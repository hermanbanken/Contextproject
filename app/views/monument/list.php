<div class="container-fluid">
	<div class="row-fluid">
		<div class="span9">
			<h1 style="margin-bottom: 10px;">Monumenten</h1>
			<?php echo $pagination; ?>
			<div class="container-fluid"
				style="padding: 0; border-top: 1px #DDD solid;">
				<?php 
				if (count($monuments) == 0) {
					?>
				<div class="row-fluid"
					style="padding: 5px 0; border-bottom: 1px #DDD solid;">
					<span>Er zijn helaas geen monumenten gevonden met de opgegeven
						criteria.</span>
				</div>
				<?php
				}
				foreach ($monuments AS $monument) {
					$monument_s = ORM::factory('monument', $monument['id_monument']);
					?>
				<div class="row-fluid"
					style="height: 100px; padding: 5px 0; border-bottom: 1px #DDD solid;">
					<div class="span2">
						<a href="monument/id/<?php echo $monument_s->id_monument; ?>"> <img
							src="<?php echo $monument_s->photo(); ?>"
							style="max-width: 100px; max-height: 100px;" alt="">
						</a>
					</div>
					<div class="span3">
						<a href="monument/id/<?php echo $monument_s->id_monument; ?>"><?php echo $monument_s->name; ?>
						</a> <span style="display: block"><?php echo (isset($monument['distance']) ? round($monument['distance'] * 1000).' meter' : ''); ?>
						</span>
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
			<h2 style="margin-bottom: 10px; margin-top: 10px;">Selectie</h2>
			<?php echo $selection_form; ?>
		</div>
	</div>
</div>
