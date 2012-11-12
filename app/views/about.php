<div class="container-fluid">
	<div class="row-fluid"><h1 id="cultuurapp" style="margin-bottom: 10px;">CultuurApp</h1></div>
	<div class="row-fluid">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a class="btn" href="#cultuurapp">CultuurApp</a>
				<a class="btn" href="#waarom"><?php echo __("about.why.title"); ?></a>
				<a class="btn" href="#databronnen"><?php echo __("about.data.title"); ?></a>
				<a class="btn" href="#techniek"><?php echo __("about.extend.title"); ?></a>
			</div>
		</div>

	</div>
	<div class="row-fluid">
		<div class="span6">
			<p><?php echo __("about.cultuurapp"); ?></p>
		</div>
		<div class="span6"><img style="margin-top:-20px;" src="<?php echo URL::site("images/logo.png"); ?>" /></div>
	</div>
	<div class="row-fluid">
		<div class="span4">
			<img style="margin-top:10px;" src="<?php echo URL::site("images/screenshot.png"); ?>" />
		</div>
		<div class="span4" id="waarom">
			<h2><?php echo __("about.what.title"); ?></h2>
			<p><?php echo __("about.what"); ?></p>
		</div>
		<div class="span4">
			<h2><?php echo __("about.why.title"); ?></h2>
			<p><?php echo __("about.why"); ?></p>	
		</div>
	</div>
	
	<div class="row-fluid"><h1 id="databronnen"><?php echo __("about.data.title"); ?></h1></div>
	<div class="row-fluid datasources">
		<div class="span6" style="height: 300px">
			<p><?php echo __("about.data"); ?></p>
		</div>
		<div class="span6 row">
			<a class="span4" href="http://monumentenregister.cultureelerfgoed.nl/">
				<img alt="Rijksregister Cultureel Erfgoed" src="<?php echo URL::site("images/datasource/register.gif"); ?>" />
			</a>
			<a class="span4" href="http://www.flickr.com/">
				<img alt="Flickr" src="<?php echo URL::site("images/datasource/flickr.png"); ?>" />
			</a>
			<a class="span4" href="http://www.4sq.com">
				<img alt="FourSquare" src="<?php echo URL::site("images/datasource/4sq.png"); ?>" />
			</a>
		</div>
		<div class="span6 row">
			<a class="span4" href="http://www.google.com">
				<img alt="Google Places / Maps" src="<?php echo URL::site("images/datasource/google.png"); ?>" />
			</a>
			<a class="span4" href="http://www.facebook.com">
				<img alt="Facebook" src="<?php echo URL::site("images/datasource/facebook.jpeg"); ?>" />
			</a>
			<a class="span4" href="http://www.twitter.com">
				<img alt="Twitter" src="<?php echo URL::site("images/datasource/twitter.jpeg"); ?>" />
			</a>
		</div>
		<div class="span6 row">
			<a class="span4" href="http://www.wikimedia.org">
				<img alt="Wikimedia Commons" src="<?php echo URL::site("images/datasource/wikimedia.jpg"); ?>" />
			</a>
			<a class="span4" href="http://www.iamsterdam.nl">
				<img alt="Amsterdam" src="<?php echo URL::site("images/datasource/amsterdam.jpg"); ?>" />
			</a>
			<a class="span4" href="http://www.wunderground.com">
				<img alt="Wunderground" src="<?php echo URL::site("images/datasource/wunderground.jpeg"); ?>" />
			</a>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span6"></div>
		<div class="span6"><h1 id="techniek" style="margin-bottom: 10px;"><?php echo __("about.extend.title"); ?></h1></div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div id="illustration"></div>
			<script>
				var house = "M94.214,51.025v1.965c-15.819-2.401-21.197-17.111-21.197-34.942c0-0.121,0.009-0.233,0.009-0.354h-2.367h-1.287C69.372,7.922,60.46,0,49.466,0C38.472,0,29.56,7.922,29.56,17.693h-1.296h-2.367c0,0.122,0.009,0.234,0.009,0.354c0,17.832-5.378,32.541-21.197,34.946v-1.969H0v2.35V76.93v33.889h33.646V92.791c0-8.733,7.08-15.814,15.815-15.814c8.734,0,15.814,7.081,15.814,15.814v18.027h33.651V76.93V53.375v-2.35H94.214z M40.614,39.893h0.083c0.423-4.492,4.164-8.022,8.765-8.022c4.604,0,8.342,3.53,8.765,8.022h0.082v17.693H40.614V39.893z";
				var castel = "M248.355,0v9.987h-5.837V0h-11.662v9.987h-5.833V0h-11.662v9.987h-5.832V0h-11.666v19.956h0.059l-0.059,0.058l4.639,4.643v50.986h-5.081v-8.83h-11.328v8.83h-8.587v-8.83h-11.328v8.83h-8.59v-8.83H144.26v8.83h-8.59v-8.83h-11.327v8.83h-8.589v-8.83h-11.328v8.83h-8.588v-8.83H84.51v8.83h-8.589v-8.83H64.593v8.83h-5.08V24.538l4.583-4.582h0.059V0H52.492v9.987h-5.836V0H34.994v9.987H29.16V0H17.498v9.987h-5.832V0H0v19.956h0.059L0,20.014l4.638,4.643v50.986v75.643h54.875h33.62v-18.813h0.355c0-20.167,16.365-36.518,36.525-36.518c20.151,0,36.511,16.351,36.511,36.518h0.36v18.813h33.617h54.874V75.643V24.538l4.583-4.582h0.059V0H248.355z M42.367,133.34h-20.58v-27.438h0.343c0-5.496,4.455-9.947,9.948-9.947c5.49,0,9.946,4.451,9.946,9.947h0.343V133.34z M238.23,133.34h-20.58v-27.438h0.343c0-5.496,4.456-9.947,9.948-9.947c5.491,0,9.945,4.451,9.945,9.947h0.344V133.34z";
				var colors = {"blue": {fill: "#00ADEE", stroke: "#1B75BB"}, "orange": {stroke: "#8A5D3B", fill: "#C3996B"}, "brown": {fill: "#716558", stroke: "#603813"}};
				
				$(function() {
					var paper = Raphael($("#illustration").css({"margin": "auto", "width": 480, "height": 250}).get(0));
					var fader;
					var c, b, a = paper.set();
					a.push(
							b = paper.ellipse(413, 70, 50, 65), 
							c = paper.ellipse(413, 70, 50, 65)
					).attr({"stroke-width": 3, "stroke": "#ffff00", "fill": "#fffacd", "opacity": 0});

					function fin(){
						fader.animate(Raphael.animation({ opacity: 1 }, 1500));
						b.animate(Raphael.animation({ cx: 173, opacity: 1 }, 1000));
						c.animate(Raphael.animation({
							opacity: 1
						}, 1500, "<", function(){
							fader.animate(Raphael.animation({
								y: 140
							}, 2000, "elastic", function(){
								fader.attr("stroke-dasharray", "");
							}));
							a.animate(Raphael.animation({ opacity: 0}, 1500));
							setTimeout(fout, 5000);
						}));
					}
					function fout(){
						b.attr({ cx: 413 });
						fader.animate(Raphael.animation({
							opacity: 0
						}, 1000, 2e3, function(){
							fader.attr({ y: 200, opacity: 0, "stroke-dasharray": "-" });
						}));
						setTimeout(fin, 3000);
					}

					for(var i = 0; i<4; i++){
						paper.path(house).attr(colors[i % 2 == 0 ? "orange" : "blue"]).attr("stroke-width", "3").translate(i*120+3, "3");
						if(i != 1){
							paper.rect(i*120+3, 140, 100, 30, 10).attr(colors[i % 2 == 0 ? "orange" : "blue"]).attr({"stroke-width": "3"});
						} else {
							fader = paper.rect(i*120+3, 200, 100, 30, 10).attr(colors[i % 2 == 0 ? "orange" : "blue"]).attr({"stroke-dasharray": "-", "stroke-width": "3", "opacity": 0});
							setTimeout(fin,10);
						}				
					}
				});
			</script>
		</div>
		<div class="span6">
			<p><?php echo __("about.data"); ?></p>
		</div>
	</div>
	
	<!--<div class="row-fluid"><h1 style="margin-bottom: 10px;">Hoe we doen we wat we doen</h1></div>
	<div class="row-fluid" id="techniek">
		
	</div>
	
	<div class="row-fluid">
		<div class="thumbnail"><img src="<?php echo URL::site("images/castel.jpg"); ?>" /></div>
	</div>
	-->
	<div style="margin-bottom: 20px;"></div>
</div>
<div class="background-drawing"></div>
<script type="text/javascript">
	$(function() {
		$('#category_extracted').tooltip();

		$(".background-drawing").appendTo($(".background").css('display', 'block'));
		var paper = Raphael($(".background-drawing").get(0), "100%", "100%");

		// Draw sky
		paper.add([{
			type: "rect", x: 0, y: 0, width: "100%", height: "100%",
			fill: "80-#4ad4e9-#016ecc", stroke: 0 } ]);
		// Draw grass
		paper.ellipse("50%", "100%", "100%", "40%").attr({ fill: "80-#009945-#81d941", "stroke-width": 4, stroke: "#37B34A"});
	});
</script>