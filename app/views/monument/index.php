<div class="container-fluid monument-single">
	<div class="row-fluid" style="margin-bottom: 20px;">
		<div class="btn-toolbar">
			<div class="btn-group">
				<?php
				for ($i=65; $i<=90; $i++) {
					$x = chr($i);
					echo HTML::anchor("monument/town/".$x, $x, array('class'=>'btn'));
				}
				?>
			</div>
		</div>
		<ul>
		<?php foreach($cities as $city): ?>
		<li class="span4 city">
			<h2><?php echo $city->name; ?></h2>
			<dl>
			<?php foreach($city->monuments as $monument): ?>
				<dt><?php echo HTML::anchor("monument/id/".$monument->id_monument, $monument->name); ?></dt>
				<dd><?php echo $monument->name(); ?></dd>
			<?php endforeach; ?>
			</dl>
		</li>
		<?php endforeach; ?>
		</ul>

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