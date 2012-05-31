<div class='row'>
	<div class='span4'>
		<h1><?php echo __('Profile'); ?></h1>
		<table class="table table-bordered table-striped">
			<thead>
			  <tr>
			    <th><?php echo __('profile.field'); ?></th>
			    <th><?php echo __('profile.value'); ?></th>
			  </tr>
			</thead>
		  	<tbody>
				<tr>
				  <th><?php echo __('login.username'); ?></th>
				  <td><?php echo $user->username; ?></td>
				</tr>
				<tr>
				  <th><?php echo __('profile.email'); ?></th>
				  <td><?php echo $user->email; ?></td>
				</tr>
				<tr>
				  <th><?php echo __('profile.no-logins'); ?></th>
				  <td><?php echo $user->logins; ?></td>
				</tr>
				<tr>
				  <th><?php echo __('profile.last-login'); ?></th>
				  <td><?php echo date('Y-m-d H:i:s', $user->last_login); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="span12">
	      <h2><?php echo __('profile.recommendations-title'); ?></h2>
	      <p><?php echo __('profile.recommendations-text'); ?></p>
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
      <h2><?php echo __('profile.last-visited-title'); ?></h2>
      <p><?php echo __('profile.last-visited-text'); ?></p>
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