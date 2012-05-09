<div class="container-fluid">
	<div class="row-fluid" style="margin-bottom: 20px;">
		<h1>
			<?php echo $monument->name; ?>
			<small><a href="javascript:history.back(1);">Terug</a> </small>
		</h1>
	</div>
	<div class="row-fluid">
		<div class="span7">
			<table class="table table-bordered table-striped">
				<tr>
					<td>Extracted Name</td>
					<td><?php echo $monument->extract_name(); ?>
					</td>
				</tr>
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
		<div class="span5">
			<div class="well" style="text-align: center;">
				<img style="max-width: 100%; max-height: 400px;"
					src="<?php echo $monument->photo(); ?>"
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
				<input id="latitude" type="hidden"
					value="<?php echo $monument->lat; ?>" /> <input id="longitude"
					type="hidden" value="<?php echo $monument->lng; ?>" /> <input
					id="id_monument" type="hidden"
					value="<?php echo $monument->id_monument; ?>" />
				<div id="kaart-single"></div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="well">
				<h2>Restaurants in de omgeving</h2>
				<table class="table table-bordered table-striped">
					<?php 
					$key = 'AIzaSyC3jT6jPdf1JhAwSBWzde1RFuq21HYKExo';
					$restaurants = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/search/json?location='.$monument->lng.','.$monument->lat.'&radius=5000&types=restaurant|food&sensor=false&key='.$key));
						
					$i = 1;
					foreach ($restaurants->results AS $place) {
						$details = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?reference='.$place->reference.'&sensor=true&key='.$key));
						$details = $details->result;
						if (isset($place->name) && isset($place->rating) && isset($place->vicinity) && isset($details->formatted_phone_number) && isset($details->website)) {
							echo '
							<tr>
								<td>'.$place->rating.'</td><td><a href="'.$details->website.'">'.$place->name.'</a></td><td>'.$place->vicinity.'</td>
							</tr>';

							$i++;
						}
							
						if ($i > 5) {
							break;
						}
					}
					if ($i == 1) {
						echo '<tr><td>Er zijn helaas geen restaurants in de omgeving gevonden.</td></tr>';
					}
					?>
				</table>
			</div>
		</div>
		<div class="span6">
			<div class="well">
				<h2>Cafe's in de omgeving</h2>
				<table class="table table-bordered table-striped">
					<?php 
					$key = 'AIzaSyC3jT6jPdf1JhAwSBWzde1RFuq21HYKExo';
					$restaurants = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/search/json?location='.$monument->lng.','.$monument->lat.'&radius=5000&types=bar|cafe&sensor=false&key='.$key));

					$i = 1;
					foreach ($restaurants->results AS $place) {
						$details = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?reference='.$place->reference.'&sensor=true&key='.$key));
						$details = $details->result;
						if (isset($place->name) && isset($place->rating) && isset($place->vicinity) && isset($details->formatted_phone_number) && isset($details->website)) {
							echo '
							<tr>
								<td>'.$place->rating.'</td><td><a href="'.$details->website.'">'.$place->name.'</a></td><td>'.$place->vicinity.'</td>
							</tr>';

							$i++;
						}
							
						if ($i > 5) {
							break;
						}
					}
					if ($i == 1) {
						echo '<tr><td>Er zijn helaas geen cafe\'s in de omgeving gevonden.</td></tr>';
					}
					?>
				</table>
			</div>
		</div>
	</div>
</div>
