<div class='row'>
	<div class='span4'>
		<h1><?php echo __('Profile'); ?></h1>
		<table class="table table-bordered table-striped">
			<thead>
			  <tr>
			    <th><?php echo __('Field'); ?></th>
			    <th><?php echo __('Value'); ?></th>
			  </tr>
			</thead>
		  	<tbody>
				<tr>
				  <th><?php echo __('Username'); ?></th>
				  <td><?php echo $user->username; ?></td>
				</tr>
				<tr>
				  <th><?php echo __('Email'); ?></th>
				  <td><?php echo $user->email; ?></td>
				</tr>
				<tr>
				  <th><?php echo __('# of logins'); ?></th>
				  <td><?php echo $user->logins; ?></td>
				</tr>
				<tr>
				  <th><?php echo __('Last login'); ?></th>
				  <td><?php echo date('Y-m-d H:i:s', $user->last_login); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="span12">
	      <h2><?php echo __('Advised monuments'); ?></h2>
	      <p><?php echo __('You might like these monuments. This was determined by comparing your monument history against other users history.'); ?></p>
	      <ul class="thumbnails">
	        <li class="span3">
	          <a href="#" class="thumbnail">
	            <img src="http://placehold.it/260x180" alt="">
	          </a>
	        </li>
	        <li class="span3">
	          <a href="#" class="thumbnail">
	            <img src="http://placehold.it/260x180" alt="">
	          </a>
	        </li>
	        <li class="span3">
	          <a href="#" class="thumbnail">
	            <img src="http://placehold.it/260x180" alt="">
	          </a>
	        </li>
	        <li class="span3">
	          <a href="#" class="thumbnail">
	            <img src="http://placehold.it/260x180" alt="">
	          </a>
	        </li>
	      </ul>
	    </div>
	
	<div class="span12">
      <h2><?php echo __('Visited monuments'); ?></h2>
      <p><?php echo __('This are some monuments you visited last in real life.'); ?></p>
      <ul class="thumbnails">
      <?php 
      $monuments = $user->visited_monuments(4);
      foreach ($monuments AS $monument) {
      	echo '
        <li class="span3">
          <a href="monument/id/'.$monument->id_monument.'" class="thumbnail">
            <img src="'.$monument->photo().'" style="max-width: 260px; max-height: 180px;" alt="">
          </a>
        </li>';
      }
        ?>
      </ul>
    </div>
	
</div>