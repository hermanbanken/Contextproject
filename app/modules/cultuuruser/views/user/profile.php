<div class='row'>
	<div class='span4'>
		<h1>Profiel</h1>
		<table class="table table-bordered table-striped">
			<thead>
			  <tr>
			    <th>Field</th>
			    <th>Value</th>
			  </tr>
			</thead>
		  	<tbody>
				<tr>
				  <th>Username</th>
				  <td><?php echo $user->username; ?></td>
				</tr>
				<tr>
				  <th>E-mail</th>
				  <td><?php echo $user->email; ?></td>
				</tr>
				<tr>
				  <th># of logins</th>
				  <td><?php echo $user->logins; ?></td>
				</tr>
				<tr>
				  <th>Last login</th>
				  <td><?php echo date('Y-m-d H:i:s', $user->last_login); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="span12">
	      <h2>Advised monuments</h2>
	      <p>You might like these monuments. This was determined by comparing your monument history against other users history.</p>
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
      <h2>Visited monuments</h2>
      <p>This are the monuments you visited last on this website or in real life.</p>
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
	
</div>