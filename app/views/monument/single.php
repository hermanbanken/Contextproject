<div class="container-fluid">
	<div class="row-fluid" style="margin-bottom: 20px;">
		<h1 style="width: 700px; float: left;">
			<?php echo $monument->name; ?>
			<small><a href="javascript:history.back(1);">Terug</a> </small>
		</h1>
		<?php 
		if ($user) {
		$visited = in_array($monument->id_monument, $user->visited_monument_ids()); ?>
		<a style="float: right; margin-top: 10px;" class="btn <?php echo ($visited ? 'btn-success ' : ''); ?>visited"
			href="#"><i
			class="icon-ok <?php echo ($visited ? 'icon-white ' : ''); ?>"></i> <span class="text"><?php echo ($visited ? __('single.visited') : __('single.not-visited')); ?></span>
		</a>
		<?php 
		}
		?>
	</div>
	<div class="row-fluid">
		<div class="span7">
			<table class="table table-bordered table-striped">
				<tr>
					<td><?php echo __('address'); ?>
					</td>
					<td><?php echo $monument->street->name.' '.$monument->streetNumber; ?>
					</td>
				</tr>
				<tr>
					<td><?php echo __('city'); ?>
					</td>
					<td><?php echo $monument->town->name; ?></td>
				</tr>
				<tr>
					<td><?php echo __('municipality'); ?>
					</td>
					<td><?php echo $monument->municipality->name; ?></td>
				</tr>
				<tr>
					<td><?php echo __('province'); ?>
					</td>
					<td><?php echo $monument->province->name; ?></td>
				</tr>
				<tr>
					<td><?php echo __('category'); ?>
					</td>
					<td><?php echo $monument->category->name; ?></td>
				</tr>
				<tr>
					<td><?php echo __('subcategory'); ?>
					</td>
					<td><?php echo $monument->subcategory->name; ?></td>
				</tr>
				<tr>
					<td>Tags</td>
					<td><?php 
							$tags = $monument->tags();
							foreach($tags as $tag)
								echo '<a href="'.URL::site('monument/list/'.$tag['original']).'">'.$tag['content'].'</a>';
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><?php echo $monument->description; ?></td>
				</tr>
			</table>
			<a class="btn" href="monument/visualcomparison/<?php echo $monument->id_monument; ?>">Vergelijk visueel</a>
		</a>
		</div>
		<div class="span5">
			<div class="well" style="text-align: center;">
				<img style="max-width: 100%; max-height: 400px;"
					src="<?php echo $monument->photoUrl(); ?>"
					alt="<?php echo $monument->name; ?>" />
			</div>
		</div>
	</div>
	<ul class="nav nav-tabs single-nav" style="margin-top: 20px;">
		<li><a class="aanbevelingen"
			href="monument/id/<?php echo $monument->id_monument; ?>#aanbevelingen"><?php echo __('single.recommendations'); ?>
		</a></li>
		<li><a class="locatie"
			href="monument/id/<?php echo $monument->id_monument; ?>#locatie"><?php echo __('single.location'); ?>
		</a></li>
		<li><a class="forecast"
			href="monument/id/<?php echo $monument->id_monument; ?>#forecast"><?php echo __('single.forecast'); ?>
		</a></li>
		<li><a class="restaurants"
			href="monument/id/<?php echo $monument->id_monument; ?>#restaurants"><?php echo __('single.restaurants'); ?>
		</a></li>
		<li><a class="cafes"
			href="monument/id/<?php echo $monument->id_monument; ?>#cafes"><?php echo __('single.bars'); ?>
		</a></li>
		<li style="float: right"><img
			src="https://developers.google.com/maps/documentation/places/images/powered-by-google-on-white.png"
			alt="Powered by Google" style="background: none; border: none;" /></li>
	</ul>

	<input id="id_monument" type="hidden"
		value="<?php echo $monument->id_monument; ?>" />

	<div id="ajax_content"></div>
</div>
</div>
