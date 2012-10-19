<div class="container-fluid monument-single">
	<div class="row-fluid" style="margin-bottom: 20px;">

		<div class="span8 event-single-overview">

			<h1>
				<?php echo $model->title; ?>
			</h1>
			<p>
			
			<div class="thumbnail span4" style="float: right">
				<a href="<?php echo $model->media; ?>" rel="shadowbox"><img src="<?php echo $model->thumb; ?>"
					alt="<?php echo $model->title; ?>"></a>
			</div>
			<div class='description'>
			<?php echo str_replace("\n\n", "</p><p>", $model->descr); ?>
			</div>

			</p>
			<div class="fb-like" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true" data-font="lucida grande"></div>
		</div>

		<div class="span4 monument-single-details">

			<h2>
				Details <small style="float: right"><a
					href="javascript:history.back(1);">Terug</a>
				</small>
			</h2>
			<div class="column">
				<div class="map-outer">
					<div class="map well" id="single-map"
						data-map="<?php echo $model->lng . ";" . $model->lat; ?>">
					</div>
				</div>
				<table class="table table-bordered table-striped">
					<tr>
						<th><?php echo __('address'); ?>
						</th>
						<td><?php echo $model->address; ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __('city'); ?>
						</th>
						<td><?php echo $model->city; ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __('event.category'); ?>
						</th>
						<td><?php echo $model->types; ?>
						</td>
					</tr>
				</table>

				<div class="forecast">
					<div class="inner well">
					</div>
				</div>
			</div>

		</div>
		<?php
			$conf = Kohana::$config->load("features");
		?>
		<div class="row-fluid">
			<div class="span6">
				<ul class="nav nav-tabs single-photos-nav" style="margin-top: 20px;">
					<li>
						<a class="recommendations" href="#recommendations"><?php echo __('single.recommendations'); ?>
					</a></li>
					<li>
						<a class="flickr" href="#flickr"><?php echo __('single.flickr'); ?>
					</a></li>
				</ul>

				<input id="id_event" type="hidden" value="<?php echo $model->id_event; ?>" />

				<div id="ajax_content_photos" class="<?php if($conf->get("recommendations")) echo "disabled"; ?>"></div>
			</div>
			<div class="span6">
				<ul class="nav nav-tabs single-nav" style="margin-top: 20px;">
					<li class="<?php if(!$conf->get("restaurants")) echo "disabled"; ?>">
						<a class="restaurants" href="#restaurants"><?php echo __('single.restaurants'); ?>
					</a></li>
					<li class="<?php if(!$conf->get("cafes")) echo "disabled"; ?>">
						<a class="cafes" href="#cafes"><?php echo __('single.bars'); ?>
					</a></li>
					<li style="float: right"><img
						src="https://developers.google.com/maps/documentation/places/images/powered-by-google-on-white.png"
						alt="Powered by Google" style="background: none; border: none;" />
					</li>
				</ul>

				<div id="ajax_content"></div>
			</div>
		</div>

	</div>
</div>
</div>
<div class="background-drawing"></div>
<script type="text/javascript">
$(function() {
	$('#category_extracted').tooltip();

	$(".background-drawing").appendTo($(".background").css('display', 'block'));
	var paper = Raphael($(".background-drawing").get(0), "100%", "100%");

	// Draw sky
	paper.add([{
		type: "rect", x: 0, y: 0, width: "100%", height: "100%",
		fill: "80-#4ad4e9-#016ecc", stroke: 0 } ]);
	// Draw grass
	paper.ellipse("50%", "100%", "100%", "40%").attr({ fill: "80-#009945-#81d941", "stroke-width": 4, stroke: "#37B34A"});
});
</script>