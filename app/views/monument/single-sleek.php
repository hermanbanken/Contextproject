<div class="container-fluid monument-single">
	<div class="row-fluid" style="margin-bottom: 20px;">

		<div class="span8 monument-single-overview">

			<h1>
				<?php echo $monument->name(); ?>
			</h1>
			<p>
			
			
			<div class="thumbnail span4" style="float: right">
				<a href="<?php echo $monument->photoUrl(); ?>" rel="shadowbox"><img src="<?php echo $monument->photoUrl(); ?>"
					alt="<?php echo $monument->name; ?>"></a>
				<div class="caption">
					<p>
						<a class="btn"
							href="<?php echo URL::site('monument/visualcomparison/'.$monument->id_monument); ?>">Vergelijk
							visueel</a>
						<?php
						if ($user) {
                $visited = in_array($monument->id_monument, $user->visited_monument_ids()); ?>
						<a style="margin-top: 10px;"
							class="btn <?php echo ($visited ? 'btn-success ' : ''); ?>visited"
							href="#"><i
							class="icon-ok <?php echo ($visited ? 'icon-white ' : ''); ?>"></i>
							<span class="text"><?php echo ($visited ? __('single.visited') : __('single.not-visited')); ?>
						</span> </a>
						<?php
						}
						?>
					</p>
				</div>
			</div>
			<div class='description'>
			<?php echo str_replace("\n\n", "</p><p>", $monument->description); ?>
			</div>
			</p>

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
						data-map="<?php echo $monument->lat . ";" . $monument->lng; ?>">
					</div>
				</div>
				<table class="table table-bordered table-striped">
					<tr>
						<th><?php echo __('address'); ?>
						</th>
						<td><?php echo $monument->street->name.' '.$monument->streetNumber; ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __('city'); ?>
						</th>
						<td><?php echo $monument->town->name; ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __('municipality'); ?>
						</th>
						<td><?php echo $monument->municipality->name; ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __('province'); ?>
						</th>
						<td><?php echo $monument->province->name; ?>
						</td>
					</tr>
					<tr>
						<th><?php 
						if($monument->category_extracted == 1) {
							echo '<span rel="tooltip" id="category_extracted" title="'.__('category_extracted').'">'.__('category').'*</span>';
						} else {
							echo __('category');
						} 
						?>
						</th>
						<td><?php echo $monument->category->name; ?>
						</td>
					</tr>
					<?php
						if($monument->category_extracted == 0 && $monument->id_subcategory > 0) {
							echo '<tr>
								<th>'.__('subcategory').'
								</th>
								<td>'.$monument->subcategory->name.'
								</td>
								</tr>';
						}?>
					<tr>
						<th>Tags</th>
						<td><?php
						$tags = $monument->tags();
						foreach($tags as $tag)
							echo '<a href="'.URL::site('monument/list?search='.$tag['original']).'">'.$tag['content'].'</a> ';
						?></td>
					</tr>
					<tr>
						<th>FourSquare</th>
						<td><?php
						$venue = $monument->venue();
						if(isset($venue) && isset($venue->id))
						{
							echo sprintf(
									"<a href='%s' title='%s'>%s</a>",
									"https://foursquare.com/v/".$venue->id,
									__("foursquare.outboundlink"),
									__("foursquare.checkins", array(":d" => (int) $monument->venue()->checkinsCount))
							);
						} else {
							echo sprintf(
									"<a href='%s' title='%s'>%s</a>",
									URL::site('4sq/create/'.$monument->id_monument),
									__("foursquare.venue.create"),
									__("foursquare.venue.create")
							);
						}
						?></td>
					</tr>
				</table>

				<div class="forecast">
					<div class="inner well">
						<?php
						foreach ($forecasts AS $forecast) {
							?>
						<div class="span1 day">
							<i><img src="<?php echo $forecast->icon(); ?>" alt="" />
							</i> <span class="temperature"><?php echo $forecast->temperature(); ?>
								&deg;C</span> <span class="dayabbr"><?php echo $forecast->day(); ?>
							</span>
						</div>
						<?php
						}
						?>
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

				<input id="id_monument" type="hidden" value="<?php echo $monument->id_monument; ?>" />

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

	$(".background-drawing").appendTo($(".background"));
	var paper = Raphael($(".background-drawing").get(0), "100%", "100%");

	// Draw sky
	paper.add([{
		type: "rect", x: 0, y: 0, width: "100%", height: "100%",
		fill: "80-#4ad4e9-#016ecc", stroke: 0 } ]);
	// Draw grass
	paper.ellipse("50%", "100%", "100%", "40%").attr({ fill: "80-#009945-#81d941", "stroke-width": 4, stroke: "#37B34A"});
});
</script>