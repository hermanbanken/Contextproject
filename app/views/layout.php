<!DOCTYPE html>
<html>
<head>
  <title></title>
  <base href="<?php echo URL::base(); ?>" />

  <meta name="description" content="Vind monumenten in heel het land" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  <!--[if lt IE 8]>
  <![endif]-->
  
  <meta charset="utf-8">
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo URL::site('lib/bootstrap/docs/assets/css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo URL::site('lib/bootstrap/docs/assets/css/bootstrap-responsive.css'); ?>" rel="stylesheet">
    <link href="<?php echo URL::site('lib/bootstrap/docs/assets/css/docs.css'); ?>" rel="stylesheet">
    <link href="<?php echo URL::site('lib/bootstrap/docs/assets/css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo URL::site('css/jquery-ui-1.8.19.custom.css'); ?>" rel="stylesheet">
	<script type="text/javascript" src="<?php echo URL::site('js/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo URL::site('js/jquery-ui-1.8.19.custom.min.js'); ?>" ></script>
	<script type="text/javascript" src="<?php echo URL::site('js/googlemaps.js'); ?>" ></script>
    <script type="text/javascript" src="<?php echo URL::site('js/list.js'); ?>" ></script>
    <script type="text/javascript" src="<?php echo URL::site('lib/bootstrap/js/bootstrap-alert.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo URL::site('lib/bootstrap/js/bootstrap-dropdown.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo URL::site('lib/bootstrap/js/bootstrap-collapse.js'); ?>"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBx79ayF-rofhhNDBFW6633FcLWFuEItHk&sensor=true"></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo URL::site('lib/bootstrap/docs/assets/ico/favicon.ico'); ?>">
    <link rel="apple-touch-icon" href="<?php URL::site('lib/bootstrap/docs/assets/ico/apple-touch-icon.png'); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php URL::site('lib/bootstrap/docs/assets/ico/apple-touch-icon-72x72.png'); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo URL::site('lib/bootstrap/docs/assets/ico/apple-touch-icon-114x114.png'); ?>">
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
	<div class='page'>
    <div class="container">
	<?php 
	echo $body;
 	?>
    </div>
	</div>
</div>
</body>
</html>