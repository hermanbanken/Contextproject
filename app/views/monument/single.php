<div class="container-fluid">
	<div class="row-fluid" style="margin-bottom: 20px;">
		<h1>
			<?php echo $monument->extract_name(); ?>
			<small><a href="javascript:history.back(1);">Terug</a> </small>
		</h1>
	</div>
	<div class="row-fluid">
		<div class="span7">
			<table class="table table-bordered table-striped">
				<tr>
					<td>Adres</td>
					<td><?php echo $monument->street->name.' '.$monument->streetNumber; ?>
					</td>
				</tr>
				<tr>
					<td>Stad</td>
					<td><?php echo $monument->town->name; ?>
					</td>
				</tr>
				<tr>
					<td>Gemeente</td>
					<td><?php echo $monument->municipality->name; ?>
					</td>
				</tr>
				<tr>
					<td>Provincie</td>
					<td><?php echo $monument->province->name; ?>
					</td>
				</tr>
				<tr>
					<td>Hoofdcategorie</td>
					<td><?php echo $monument->category->name; ?>
					</td>
				</tr>
				<tr>
					<td>Subcategorie</td>
					<td><?php echo $monument->subcategory->name; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><?php echo $monument->description; ?>
					</td>
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
	<ul class="nav nav-tabs">
		<li class="active"><a href="#">Aanbevelingen</a>
		</li>
		<li><a href="#">Locatie</a>
		</li>
		<li><a href="#">Restaurants</a>
		</li>
		<li><a href="#">Cafe's</a>
		</li>
	</ul>

	<input id="id_monument" type="hidden"
		value="<?php echo $monument->id_monument; ?>" />

	<div id="ajax_content"></div>
</div>
</div>
