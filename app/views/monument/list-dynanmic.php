<div class="container-fluid" id="list">
	<div class="row-fluid">
		<div class="span9">
			<h1 style="margin-bottom: 10px;"><?php echo __('list.monuments'); ?></h1>

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
	<script>
		$(function(){
			var paper = spinner($(".loading").get(0), 70, 120, 12, 25, "#fff");

			spinner()
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
