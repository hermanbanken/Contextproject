<!DOCTYPE html>
<?php if(Kohana::$environment == Kohana::PRODUCTION): ?>
<html manifest="<?php echo URL::site("manifest.txt"); ?>">
<?php else: ?>
<html>
<?php endif; ?>
<head>
	<title><?php echo $title; ?> | CultuurApp.nl</title>
	<base href="<?php echo URL::base(); ?>" />
	<?php echo $header; ?>
</head>

<?php

$menu = array(
	'menu.map' => 'monument/map',
	'menu.list' => 'monument/list',
);

?>
<body data-spy="scroll" data-target=".subnav" data-offset="50">
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
			</div>
			<?php echo Request::factory("localize/menu")->execute(); ?>
		</div>
	</div>
</div>
<div class='page'>
	<div class="container">
		<h1><?php echo $title; ?></h1>
		<p class='error'><?php echo $message; ?></p>
	</div>
</div>
</div>
<?php echo $footer; ?>
</body>
</html>