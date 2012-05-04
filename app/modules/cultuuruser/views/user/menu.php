<div class="nav-collapse pull-right">
	<ul class="nav">
  		<li class="dropdown">
  		<?php if($user){ ?>
  			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
  				<span class='username'><?php echo $user->username; ?></span>
  				<b class="caret"></b>
  			</a>
			<ul class="dropdown-menu pull-right">
				<li><a href="user/profile">Profiel</a></li>
				<li><a href="monument/fav">Favorieten</a></li>
				<li><a href="user/logout">Logout</a></li>
			</ul>
  		
  		<?php } else { ?>
  			<a href="#" class="dropdown-toggle" data-toggle="dropdown">Account
  			<b class="caret"></b></a>
			<ul class="dropdown-menu pull-right">
				<li><a href="user/login">Login</a></li>
				<li><a href="user/create">Register</a></li>
			</ul>
		<?php } ?>
		</li>
	</ul>
</div>