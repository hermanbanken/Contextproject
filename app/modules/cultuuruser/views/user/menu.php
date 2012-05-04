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
				<li><a href="user/logout">Logout</a></li>
			</ul>
  		
  		<?php } else { ?>
  			<a href="user/login" onclick="location.hash='#user/login';return false">Login/register</a>
		<?php } ?>
		</li>
	</ul>
</div>