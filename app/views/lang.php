<div class="nav-collapse pull-right">
	<ul class="nav">
  		<li class="dropdown">
  			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
  				<span class='username'><?php echo HTML::image("images/lang/$lang.png")." ".__($lang); ?></span>
  				<b class="caret"></b>
  			</a>
			<ul class="dropdown-menu pull-right">
			<?php
				foreach($languages as $l){
					$uri = Request::detect_uri() . URL::query(array('lang' => $l));
					$href = "$l/localize?redirect=".urlencode($uri);
					$img = HTML::image("images/lang/$l.png");
					echo "<li><a href='$href'>$img ".__($l)."</a></li>";
				}
			?>
			</ul>
		</li>
	</ul>
</div>