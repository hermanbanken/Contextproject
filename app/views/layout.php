<!DOCTYPE html>
<html manifest="manifest.txt">
<head>
  <title>CultuurApp.nl</title>
  <base href="<?php echo URL::base(); ?>" />
	<?php echo $header; ?>
</head>

<body data-spy="scroll" data-target=".subnav" data-offset="50" onLoad="initialize()">
	<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo URL::site(''); ?>"><span style="color: #716658;">Cultuur</span><span style="color: #00AEEF;">App</span></a>
          <div class="nav-collapse">
            <ul class="nav">
              <li <?php if(preg_match('/map/i',Request::detect_uri())) echo 'class="active"'?>>
                <a href="<?php echo URL::site('monument/map'); ?>">Kaart</a>
              </li>
              <li <?php if(preg_match('/list/i',Request::detect_uri())) echo 'class="active"'?>>
                <a href="<?php echo URL::site('monument/list'); ?>">Lijst</a>
              </li>
            </ul>
          </div>
          <?php echo Request::factory("user/menu")->execute(); ?>
      	</div>
	  </div>
    </div>
	<div class='page'>
    <div class="container">
	<?php
	/*
	$messages = Message::pull();
	foreach($messages as $m){
		echo "<div class='alert alert-$m[type]'>$m[message]</div>";
	}
	*/
	?>
	<?php 
	echo $body;
 	?>
    </div>
	</div>
</div>	
<?php echo $footer; ?>
</body>
</html>