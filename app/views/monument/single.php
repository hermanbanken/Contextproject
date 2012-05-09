<div class="container-fluid">
	<div class="row-fluid" style="margin-bottom: 20px;">
		<h1>
			<?php echo $monument->name; ?>
			<small><a href="javascript:history.back(1);">Terug</a> </small>
		</h1>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<table class="table table-bordered table-striped">
				<tr>
					<td>Adres</td>
					<td><?php echo $monument->street->name.' '.$monument->streetNumber; ?>
					</td>
				</tr>
				<tr>
					<td>Stad</td>
					<td><?php echo $monument->town->name; ?></td>
				</tr>
				<tr>
					<td>Gemeente</td>
					<td><?php echo $monument->municipality->name; ?></td>
				</tr>
				<tr>
					<td>Provincie</td>
					<td><?php echo $monument->province->name; ?></td>
				</tr>
				<tr>
					<td>Hoofdcategorie</td>
					<td><?php echo $monument->category->name; ?></td>
				</tr>
				<tr>
					<td>Subcategorie</td>
					<td><?php echo $monument->subcategory->name; ?></td>
				</tr>
				<tr>
					<td colspan="2"><?php echo $monument->description; ?></td>
				</tr>
			</table>
		</div>
		<div class="span6">
			<div class="well" style="text-align: center;">
			<img style="max-width: 100%; max-height: 400px;" src="<?php echo $monument->photo(); ?>"
				alt="<?php echo $monument->name; ?>" />
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="well">
				<?php
				$similars = $monument->similars400(4);
	
				if ($similars['euclidian']) {
					echo '<h2 style="margin-bottom: 15px;">Visueel gelijkende monumenten</h2>';
				}
				else {
					echo '<h2 style="margin-bottom: 15px;">Andere monumenten uit dezelfde categorie</h2>';
	
				}
	
				$monuments = $similars['monuments'];
				foreach ($monuments AS $monument_s) {
					echo '<div style="text-align: center; float: left; width: 25%; height: 165px; line-height: 150px; vertical-align: middle;">';
					echo '<a href="'.URL::site('monument/id/'.$monument_s->id_monument).'"><img style="max-width: 80%; max-height: 165px;" src="'.$monument_s->photo().'" alt="'.$monument_s->name.'" /></a>';
					echo '</div>';
				}
				?>
				<br style="clear: both;" />
			</div>
		</div>
		<div class="span6">
			<div class="well">
				<h2>Locatie</h2>
				<input id="latitude" type="hidden" value="<?php echo $monument->lat; ?>" />
				<input id="longitude" type="hidden" value="<?php echo $monument->lng; ?>" />
				<input id="id_monument" type="hidden" value="<?php echo $monument->id_monument; ?>" />
				<div id="kaart-single"></div>
			</div>
		</div>
	</div>
</div>
