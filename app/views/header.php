<?php echo $css; ?>
<?php echo $js_head; ?>

<meta name="description" content="Vind monumenten in heel het land" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
 
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="" />
<meta name="author" content="" />

<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="apple-touch-startup-image" href="images/startup-full.jpg">
<link rel="apple-touch-icon" href="images/touch-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="72x72" href="images/touch-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="114x114" href="images/touch-icon-114x114.png" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<script type="text/javascript">
   (function(document,navigator,standalone) {
       // prevents links from apps from oppening in mobile safari
       // this javascript must be the first script in your <head>
       if ((standalone in navigator) && navigator[standalone]) {
           var curnode, location=document.location, stop=/^(a|html)$/i;
           document.addEventListener('click', function(e) {
               curnode=e.target;
               while (!(stop).test(curnode.nodeName)) {
                   curnode=curnode.parentNode;
               }
               // Condidions to do this only on links to your own app
               // if you want all links, use if('href' in curnode) instead.
               if('href' in curnode && ( curnode.href.indexOf('http') || ~curnode.href.indexOf(location.host) ) ) {
                   e.preventDefault();
                   location.href = curnode.href;
               }
           },false);
       }
   })(document,window.navigator,'standalone');
</script>

<!-- Le styles -->
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->