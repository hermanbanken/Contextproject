	<div class="background-drawing">
		<div class="sky"></div>
		<div class="grass plax" data-xrange="0" data-yrange="5"></div>
		<div class="monuments">
			<div class="house house-1 plax" data-xrange="30" data-yrange="5"></div>
			<div class="house house-2 plax" data-xrange="40" data-yrange="6"></div>
			<div class="castel castel-1 plax" data-xrange="60" data-yrange="8"></div>
			<div class="house house-3 plax" data-xrange="40" data-yrange="6"></div>
			<div class="house house-4 plax" data-xrange="30" data-yrange="5"></div>
		</div>
	</div>
	<script>
		var house = "M94.214,51.025v1.965c-15.819-2.401-21.197-17.111-21.197-34.942c0-0.121,0.009-0.233,0.009-0.354h-2.367h-1.287C69.372,7.922,60.46,0,49.466,0C38.472,0,29.56,7.922,29.56,17.693h-1.296h-2.367c0,0.122,0.009,0.234,0.009,0.354c0,17.832-5.378,32.541-21.197,34.946v-1.969H0v2.35V76.93v33.889h33.646V92.791c0-8.733,7.08-15.814,15.815-15.814c8.734,0,15.814,7.081,15.814,15.814v18.027h33.651V76.93V53.375v-2.35H94.214z M40.614,39.893h0.083c0.423-4.492,4.164-8.022,8.765-8.022c4.604,0,8.342,3.53,8.765,8.022h0.082v17.693H40.614V39.893z";
		var castel = "M248.355,0v9.987h-5.837V0h-11.662v9.987h-5.833V0h-11.662v9.987h-5.832V0h-11.666v19.956h0.059l-0.059,0.058l4.639,4.643v50.986h-5.081v-8.83h-11.328v8.83h-8.587v-8.83h-11.328v8.83h-8.59v-8.83H144.26v8.83h-8.59v-8.83h-11.327v8.83h-8.589v-8.83h-11.328v8.83h-8.588v-8.83H84.51v8.83h-8.589v-8.83H64.593v8.83h-5.08V24.538l4.583-4.582h0.059V0H52.492v9.987h-5.836V0H34.994v9.987H29.16V0H17.498v9.987h-5.832V0H0v19.956h0.059L0,20.014l4.638,4.643v50.986v75.643h54.875h33.62v-18.813h0.355c0-20.167,16.365-36.518,36.525-36.518c20.151,0,36.511,16.351,36.511,36.518h0.36v18.813h33.617h54.874V75.643V24.538l4.583-4.582h0.059V0H248.355z M42.367,133.34h-20.58v-27.438h0.343c0-5.496,4.455-9.947,9.948-9.947c5.49,0,9.946,4.451,9.946,9.947h0.343V133.34z M238.23,133.34h-20.58v-27.438h0.343c0-5.496,4.456-9.947,9.948-9.947c5.491,0,9.945,4.451,9.945,9.947h0.344V133.34z";
		var colors = {"blue": {fill: "#00ADEE", stroke: "#1B75BB"}, "orange": {stroke: "#8A5D3B", fill: "#C3996B"}, "brown": {fill: "#716558", stroke: "#603813"}};

		$(function(){
			$(".background-drawing").appendTo($(".page .background").css('display', 'block'));
			var sky = Raphael($(".sky").get(0), "100%", "100%");
			var grs = Raphael($(".grass").get(0), "100%", "100%");

			// Draw sky
			sky.add([{
				type: "rect", x: 0, y: 0, width: "100%", height: "100%",
				fill: "80-#4ad4e9-#016ecc", stroke: 0 } ]);
			// Draw grass
			sky.ellipse("50%", "100%", "100%", "40%").attr({ fill: "80-#009945-#81d941", "stroke-width": 4, stroke: "#37B34A"});

			$(".monuments .house").each(function(i){
				var paper = Raphael(this, "100%", "100%");
				paper.path(house).attr(colors[i % 2 == 0 ? "orange" : "blue"]).attr("stroke-width", "3").translate("3", "3");
			});

			$(".monuments .castel").each(function(i){
				var paper = Raphael(this, "100%", "100%");
				paper.path(castel).attr(colors['brown']).attr("stroke-width", 4).translate("3", "3");
			});

			$(".plax").plaxify();
			$.plax.enable();
		});
	</script>
	<header class="jumbotron masthead">
		<div class="inner">
			<h1><img alt="CultuurApp" src="images/logo.png" /></h1>
			<p style="color:black"><?php echo __('splash.text'); ?></p>
			<p class="download-info">
				<a href="<?php echo URL::site("monument/map"); ?>" class="btn btn-primary btn-large"><?php echo __('splash.show-map'); ?></a>
				<a href="<?php echo URL::site("user/register"); ?>" class="btn btn-large"><?php echo __('splash.create-account'); ?> <small>(<?php echo __('splash.free'); ?>)</small></a>
			</p>
		</div>
	</header>
	<div class='background'></div>
	<div class="fb-like" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true" data-font="lucida grande"></div>
	<a class='torn' href='<?php echo URL::site("monument/map"); ?>'></a>