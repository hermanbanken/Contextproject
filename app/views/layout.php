<!DOCTYPE html>
<?php if(false && Kohana::$environment == Kohana::PRODUCTION): ?>
<html manifest="<?php echo URL::site("manifest.txt"); ?>">
<?php else: ?>
<html>
<?php endif; ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# cultuurapp: http://ogp.me/ns/fb/cultuurapp#">
  <title>CultuurApp.nl</title>
  <script>var base = "<?php echo URL::base(); ?>";</script>
  <?php echo $header; ?>
</head>

<?php

	$menu = array(
		'menu.map' => 'monument/map?town=Amsterdam',
		'menu.list' => 'monument/list?town=Amsterdam&category=2&sort=name',
		'menu.events' => 'event/list?town=Amsterdam&sort=date',
		'menu.index' => 'monument/town/A',
		'menu.about' => 'welcome/about'
	);

?>
<body data-spy="scroll" data-target=".subnav" data-offset="50" class="<?php echo $class; ?>">
	<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" style="padding-top: 12px; padding-bottom: 9px;" href="<?php echo URL::site(''); ?>"><span class="brand_logo" style="background: url(<?php echo URL::site('images/logo-klein.png'); ?>) no-repeat;">CultuurApp</span></a>
          <div class="nav-collapse">
            <ul class="nav">
				<?php
				foreach($menu as $label => $href){
					$regex = "/".str_replace("/", "\/", $href)."/i";
					$active = preg_match($regex,Request::detect_uri());
					$class =  $active ? 'class="active"' : '';
					echo "<li $class><a href='".URL::site($href)."'>".__($label)."</a></li>";
				}
				?>
            </ul>
			<form style="float: right;" class="navbar-search" method="get" <?php if(!preg_match('/\/list|\/map/i', Request::detect_uri())) echo 'action="'.URL::site('monument/list').'"';?>>
                <input type="text" name="search" class="search-query span3" placeholder="<?php echo __('selection.search')?>">
                <div class="icon-search"></div>
            </form>
          </div>
	      <?php echo Request::factory("localize/menu")->execute(); ?>
          <?php echo Request::factory("user/menu")->execute(); ?>
      	</div>
	  </div>
    </div>
	<div class='page page-<?php echo Request::$initial->action(); ?>'>
    <div class="container">
	<?php
		$messages = Message::pull();
		foreach($messages as $m){
			echo "<div class='alert alert-$m[type]'>$m[message]</div>";
		}

		echo $body;
 	?>
    </div>
	</div>
</div>	
<?php
    echo $footer;

    if(Kohana::$environment == Kohana::STAGING && Request::current()->query("bench") != null)
    {
       echo View::factory('profiler/stats');
    }
?>
<div class='background'></div>
</body>
</html>