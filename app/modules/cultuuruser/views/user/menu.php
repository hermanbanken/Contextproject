<div class="nav-collapse pull-right">
	<ul class="nav">
  		<li class="dropdown">
  		<?php if($user){ ?>
  			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
  				<span class='username'><?php echo $user->username; ?></span>
  				<b class="caret"></b>
  			</a>
			<ul class="dropdown-menu pull-right">
				<li><a href="<?php echo URL::site("user/profile"); ?>"><?php echo __('menu.profile'); ?></a></li>
				<li><a href="<?php echo URL::site("user/logout"); ?>""><?php echo __('menu.logout'); ?></a></li>
			</ul>
  		
  		<?php } else { ?>
  			<a href="#user/login" onclick="location.hash='#user/login';return false"><?php echo __('menu.login').'/'.__('menu.register'); ?></a>
		<?php } ?>
		</li>
	</ul>
</div>