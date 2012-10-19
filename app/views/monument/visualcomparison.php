<div class="row" style="padding-bottom: 20px;">
	<div class="span12">
		<h1 style="color: #fff;">
			<?php echo __('visualcomparison.title').' '.$monument->name(); ?>
		</h1>
	</div>
</div>
<div class="row">
	<div class="span4">
		<div class="well" style="text-align: center;">
			<img style="max-width: 100%; max-height: 400px;"
				src="<?php echo $monument->photoUrl(); ?>"
				alt="<?php echo $monument->name; ?>" />
				
				<a style="margin-top: 15px;" href="<?php echo URL::site('monument/id/'.$monument->id_monument); ?>" class="btn btn-primary"><?php echo __('visualcomparison.gotomonument'); ?></a>
		</div>
	</div>
	<div class="span8">
		<form class="well form-inline features" method="post">
			<input type="hidden" name="posted" value="" /> <label
				class="checkbox" style="margin-right: 10px;"> <input type="checkbox"
				name="color"
				<?php if (in_array('color', $selected)) { echo ' checked="checked"'; } ?>>
				<?php echo __('visualcomparison.color'); ?>
			</label> <label class="checkbox" style="margin-right: 10px;"> <input
				type="checkbox" name="composition"
				<?php if (in_array('composition', $selected)) { echo ' checked="checked"'; } ?>>
				<?php echo __('visualcomparison.composition'); ?>
			</label> <label class="checkbox" style="margin-right: 10px;"> <input
				type="checkbox" name="texture"
				<?php if (in_array('texture', $selected)) { echo ' checked="checked"'; } ?>>
				<?php echo __('visualcomparison.texture'); ?>
			</label> <label class="checkbox" style="margin-right: 10px;"> <input
				type="checkbox" name="orientation"
				<?php if (in_array('orientation', $selected)) { echo ' checked="checked"'; } ?>>
				<?php echo __('visualcomparison.orientation'); ?>
			</label> <label style="float: right; margin-top: 5px;" class="checkbox" style="margin-right: 10px;"> <input
				type="checkbox" name="advanced"
				<?php if ($advanced) { echo ' checked="checked"'; } ?>>
				<span id="tooltip" rel="tooltip" title="<?php echo __('visualcomparison.advanced-explain'); ?>"><?php echo __('visualcomparison.advanced'); ?></span>
			</label> <input class="btn btn-primary" type="submit"
				value="<?php echo __('visualcomparison.compare'); ?>" />
		</form>
		<script>
			var server = base+'monument/vc/<?php echo $monument->id_monument; ?>';
			$("form.features").submit(function(){
				$.post(server, $("form.features").serialize(), function(result){
					$("table.similars").html(result);
				});
				return false;
			}).trigger("submit");
		</script>

		<div class="well">
			<table class="similars">
				<tr><td><?php echo '<td>'.__('visualcomparison.searchinstructions').'</td>'; ?></td></tr>
		      	<script>
					$('table.similars').load(server, function(r, s, x){ 
						if(s == 'error'){ 
							$('table.similars').html("<tr><td class='alert alert-error'>Helaas, de aanbevelingen kunnen niet geladen worden. Er ging iets mis.</td></tr>");
						} 
					});
				</script>
			</table>
		</div>
	</div>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#tooltip').tooltip();
});
</script>
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