<!DOCTYPE html>
<html>
<head>
  <title>CultuurApp.nl</title>
  <base href="<?php echo URL::base(); ?>" />

  <meta name="description" content="Vind monumenten in heel het land" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="" />
  <meta name="author" content="" />

    <!-- Le styles -->
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
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
          <a class="brand" href="<?php echo URL::site(''); ?>">CultuurApp</a>
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
	<div class="modal" id="loginModal">
	  <div class="modal-header">
	    <button class="close" data-dismiss="modal">×</button>
	    <h3>Login to CultuurApp.nl</h3>
	  </div>
	  <div class="modal-body">
	    <p>One fine body…</p>
	  </div>
	  <div class="modal-footer">
	    <a href="#" class="">Forgot password</a> |
	    <a href="#" class="">Signup</a>
	  </div>
	</div>
</div>	
<?php echo $footer; ?>
</body>
</html>