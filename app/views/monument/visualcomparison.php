<div class="row" style="padding-bottom: 20px;">
	<div class="span12">
		<h1>
			<?php echo __('visualcomparison.title').' '.$monument->name; ?>
		</h1>
	</div>
</div>
<div class="row">
	<div class="span4">
		<div class="well" style="text-align: center;">
			<img style="max-width: 100%; max-height: 400px;"
				src="<?php echo $monument->photoUrl(); ?>"
				alt="<?php echo $monument->name; ?>" />
		</div>
	</div>
	<div class="span8">
		<form class="well form-inline" method="post">
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

		<div class="well">
			<table class="similars">
				<?php 
				// 				foreach ($similars AS $similar) {
				// 					echo '
				// 					<li class="span2"><a href="monument/visualcomparison/'.$similar->id_monument.'" class="thumbnail">
				// 					<img style="max-height: 100px;" src="'.$similar->getphoto()->url().'" alt="'.$similar->name.'"></a></li>';
				// 				}
				$i = 1;
				foreach ($similars AS $similar) {
					if ($i == 5) {
						echo '</tr><tr>';
						$i = 1;
					}
					echo '
					<td style="text-align: center; vertical-align: middle;">
					<a style="display: block;" href="monument/visualcomparison/'.$similar->id_monument.'?posted">
					<img style="max-height: 100px;" src="'.$similar->getphoto()->url().'" alt="'.$similar->name.'">
					</a>
					</td>';
					$i++;
				}

				if (count($similars) == 0) {
					if ($posted) {
						echo '<td>'.__('visualcomparison.nothingfound').'</td>';
					}
					else {
						echo '<td>'.__('visualcomparison.searchinstructions').'</td>';
					}
				}

				?>

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