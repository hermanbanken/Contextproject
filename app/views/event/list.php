<div class="container-fluid" id="list">
	<div class="row-fluid">
		<div class="span9">
			<h1 style="margin-bottom: 10px;"><?php echo __('list.events'); ?> <span class="total"></span></h1>
			
			<div class='pagination'></div>
			<div class="monument-list container-fluid list-container" style="padding: 0;">
				<div class="loading"></div>
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
					</div>
				</div>
			</div>
			<div class="bench"></div>
		</div>

		<div class="span3">
			<h2 style="margin-bottom: 10px; margin-top: 10px;"><?php echo __('list.selection'); ?></h2>
			<?php echo $selection_form; ?>
			</div>
		</div>
	</div>
	<script>
		$(function(){
			var paper = spinner($(".loading").get(0), 70, 120, 12, 25, "#000");

			function spinner(holder, R1, R2, count, stroke_width, colour) {
				var sectorsCount = count || 12,
						color = colour || "#fff",
						width = stroke_width || 15,
						r1 = Math.min(R1, R2) || 35,
						r2 = Math.max(R1, R2) || 60,
						cx = r2 + width,
						cy = r2 + width,
						r = Raphael(holder, r2 * 2 + width * 2, r2 * 2 + width * 2),

						sectors = [],
						opacity = [],
						beta = 2 * Math.PI / sectorsCount,

						pathParams = {stroke: color, "stroke-width": width, "stroke-linecap": "round"};
				Raphael.getColor.reset();
				for (var i = 0; i < sectorsCount; i++) {
					var alpha = beta * i - Math.PI / 2,
							cos = Math.cos(alpha),
							sin = Math.sin(alpha);
					opacity[i] = 1 / sectorsCount * i;
					sectors[i] = r.path([["M", cx + r1 * cos, cy + r1 * sin], ["L", cx + r2 * cos, cy + r2 * sin]]).attr(pathParams);
					if (color == "rainbow") {
						sectors[i].attr("stroke", Raphael.getColor());
					}
				}
				var tick;
				(function ticker() {
					opacity.unshift(opacity.pop());
					for (var i = 0; i < sectorsCount; i++) {
						sectors[i].attr("opacity", opacity[i]);
					}
					r.safari();
					tick = setTimeout(ticker, 1000 / sectorsCount);
				})();
				return function () {
					clearTimeout(tick);
					r.remove();
				};
			}
		});
	</script>
</div>

<div class="background-drawing"></div>
<script>
	$(function(){
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