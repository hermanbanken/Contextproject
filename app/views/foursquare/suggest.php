<h1><?php echo __('foursquare.header.duplicates'); ?></h1>
<p><?php echo __('foursquare.legend.duplicates'); ?></p>
<ul class='row'>
<?php foreach($venues as $venue): ?>
	<form class='span3' method='post'>
		<input type='hidden' name='action' value='link' />
		<table class='table table-bordered table-striped'>
			<tbody>
				<tr>
					<th><?php echo __('foursquare.form.legend.name'); ?></th>
					<td><?php echo sprintf(
						"<a href='%s' title='%s'>%s</a>",
						"https://foursquare.com/v/".$venue->id,
						__("foursquare.outboundlink"),
						$venue->name
					); ?></td>
				</tr>
				<tr>
					<th><?php echo __('foursquare.form.legend.city'); ?></th>
					<td><?php echo $venue->city; ?></td>
				</tr>
				<tr>
					<th><?php echo __('foursquare.form.legend.description'); ?></th>
					<td><?php echo $venue->address; ?></td>
				</tr>
				<tr>
					<th>Checkins</th>
					<td><?php echo $venue->checkinsCount; ?></td>
				</tr>
				<tr>
					<th><input type='hidden' name='id' value='<?php echo $venue->id; ?>' /></th>
					<td><button class='btn btn-primary'><?php echo __("foursquare.form.linkbutton"); ?></button></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php endforeach; ?>
</ul>