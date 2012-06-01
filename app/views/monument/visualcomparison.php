<div class="row" style="padding-bottom: 20px;">
	<div class="span12">
		<h1>
			<?php echo $monument->name; ?>
		</h1>
		<?php 
		if ($user) {
		$visited = in_array($monument->id_monument, $user->visited_monument_ids()); ?>
		<a style="float: right; margin-top: 10px;"
			class="btn <?php echo ($visited ? 'btn-success ' : ''); ?>visited"
			href="#"><i
			class="icon-ok <?php echo ($visited ? 'icon-white ' : ''); ?>"></i> <span
			class="text"><?php echo ($visited ? __('single.visited') : __('single.not-visited')); ?>
		</span> </a>
		<?php 
		}
		?>
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
			<label class="checkbox" style="margin-right: 10px;"> <input
				type="checkbox" name="color"
				<?php if (in_array('color', $selected)) { echo ' checked="checked"'; } ?>>
				Kleur
			</label> <label class="checkbox" style="margin-right: 10px;"> <input
				type="checkbox" name="composition"
				<?php if (in_array('composition', $selected)) { echo ' checked="checked"'; } ?>>
				Compositie
			</label> <label class="checkbox" style="margin-right: 10px;"> <input
				type="checkbox" name="texture"
				<?php if (in_array('texture', $selected)) { echo ' checked="checked"'; } ?>>
				Textuur
			</label> <label class="checkbox" style="margin-right: 10px;"> <input
				type="checkbox" name="orientation"
				<?php if (in_array('orientation', $selected)) { echo ' checked="checked"'; } ?>>
				Orientatie
			</label> <input class="btn btn-primary" type="submit"
				value="Vergelijk" />
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
					<a style="display: block;" href="monument/visualcomparison/'.$similar->id_monument.'">
					<img style="max-height: 100px;" src="'.$similar->getphoto()->url().'" alt="'.$similar->name.'">
					</a>
					</td>';
					$i++;
				}

				?>

			</table>
		</div>
	</div>
</div>
</div>
</div>
