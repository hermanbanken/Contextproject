<html>

<head>
    <meta charset="utf-8">
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../../../public/lib/bootstrap/docs/assets/css/bootstrap.css" rel="stylesheet">
    <link href="../../../public/lib/bootstrap/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../../../public/lib/bootstrap/docs/assets/css/docs.css" rel="stylesheet">
    <link href="../../../public/lib/bootstrap/docs/assets/js/google-code-prettify/prettify.css" rel="stylesheet">

	<script type="text/javascript" src="../../../public/js/googlemaps.js" ></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBx79ayF-rofhhNDBFW6633FcLWFuEItHk&sensor=true">
    </script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../../../public/lib/bootstrap/docs/assets/ico/favicon.ico">
    <link rel="apple-touch-icon" href="../../../public/lib/bootstrap/docs/assets/ico/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../../public/lib/bootstrap/docs/assets/ico/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../../public/lib/bootstrap/docs/assets/ico/apple-touch-icon-114x114.png">
  </head>

  <body data-spy="scroll" data-target=".subnav" data-offset="50" onload="initialize()">
<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="./index.html">Demonstratie eerste iteratie 23/04/2012</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active">
                <a href="map">Kaart</a>
              </li>
              <li class="">
                <a href="list">Lijst</a>
              </li>
              <li class="">
                <a href="contact">Contact</a>
              </li>
              
            </ul>
          </div>
        </div>
      </div>
    </div>
<div class="container">

<header class="jumbotron subhead" id="overview">
        <h1>Kaartweergave</h1>
        <p class="lead">Llorem ipsum blablablalablablablablablablabla</p>
</header>


<div class="row"></div>
<div class="span8" id="kaart" style="width: 700px; height: 800px;">

</div>
<div class="span4" id="filter">
<h1>Selecteren</h1>
<input id="search" type="text" value="zoeken" />
<input id="stad" type="text" value="stad" />
<input id="straat" type="text" value="straat" />
<select id="categories">
<option value='-1'>- Categorie -</option>
<?php 
$categorien = ORM::factory('category');
foreach($categorien as $key=>$categorie) {
	echo "<option value=".$categorie->id.">".$categorie->description."</option>";
}
?>
</select>
</form>

</div>
</div>
</div>
</body>

</html>