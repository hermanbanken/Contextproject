<div class="container-fluid">
	<div class="row-fluid"><h1 id="cultuurapp" style="margin-bottom: 10px;">CultuurApp</h1></div>
	<div class="row-fluid">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a class="btn" href="#cultuurapp">CultuurApp</a>
				<a class="btn" href="#waarom">Waarom</a>
				<a class="btn" href="#databronnen">Databronnen</a>
				<a class="btn" href="#techniek">Technieken</a>
			</div>
		</div>

	</div>
	<div class="row-fluid">
		<div class="span6">
			<p>CultuurApp is een online catalogus voor cultuurliefhebbers. Deze dynamische encyclopedie voorziet de binnen- en buitenlandse toerist van alle informatie die nodig is voor een geslaagd dagje uit. Of het nu gaat om het prachtige culturele erfgoed dat Amsterdam of de rest van ons prachtige land waard is, of een van de vele fantastische evenementen in onze hoofdstad.</p>
		</div>
		<div class="span6"><img style="margin-top:-20px;" src="<?php echo URL::site("images/logo.png"); ?>" /></div>
	</div>
	<div class="row-fluid">
		<div class="span4">
			<img style="margin-top:10px;" src="<?php echo URL::site("images/screenshot.png"); ?>" />
		</div>
		<div class="span4" id="waarom">
			<h2>Wat we doen</h2>
			<p>CultuurApp bevat informatie en foto’s van meer dan 24.000 prachtige monumenten en meer dan 300 evenementen in Amsterdam. Omdat een dagje uit niet altijd alleen om een monument of evenement draait, zijn allerlei faciliteiten en accomodaties in de buurt eenvoudig op te zoeken met CultuurApp. Ook zijn er omgevingsfoto’s en informatie over het weer te vinden. Door volledig gebruik en analyse van bijna 10 databronnen  vormt CultuurApp niet alleen een catalogus, het is tevens een draagbare VVV-folder en dekt door de dynamiek meer informatie dan ooit in een draagbaar boek is te verwerken. Tevens fungeert CultuurApp, door integratie met social media, als sociaal platform voor en door cultuurliefhebbers.</p>
		</div>
		<div class="span4">
			<h2>Waarom</h2>
			<p>CultuurApp is en blijft een leuke gratis manier voor mensen om cultureel Amsterdam te ontdekken. Immers, we moeten ons prachtige land promoten. Vaak wordt vergeten hoe rijk Amsterdam is aan cultureel erfgoed. Niet alleen hebben wij coffeeshops en vrouwen van lichte zeden. Onze cultuur bestaat voor een groot deel uit het prachtige fysieke culturele erfgoed. De duizenden monumenten moeten voor iedereen toegankelijk zijn. Uiteraard zal de doelgroep van CultuurApp adverteerders aanspreken. Ook zullen eventueel tickets gekocht kunnen worden voor evenementen via CultuurApp. De essentie van de applicatie blijft echter een door ons gefinancieerd en onderhouden systeem. Onze trots, onze hobby. </p>	
		</div>
	</div>
	
	<div class="row-fluid"><h1 id="databronnen">Databronnen</h1></div>
	<div class="row-fluid datasources">
		<div class="span6" style="height: 300px">
			<p>CultuurApp gebruikt 9 verschillende databronnen. Deze databronnen bieden data die los vaak niet veel betekenen en/of zeer moeilijk bereikbaar zijn. Zo is de functionaliteit van het rijksregister cultureel erfgoed niet geavanceerd en vrij eenvoudig. Navigeren door deze databron is een verschrikking. Op Flickr staan miljoenen en miljoenen foto’s, vinden wat je zoekt is dan ook erg lastig. Ditzelfde geldt voor data van Google Places, Wunderground en Evenementen Amsterdam. Door al deze data te combineren kan dan de data veel inzichtelijker worden gemaakt. Locatiegebonden filters maken het mogelijk om slechts relevante informatie te gebruiken en te tonen. Bij een monument kunnen zo foto’s uit de omgeving, weersinformatie, foursquare checkins, etc. worden gebruikt. Niet alleen maakt dit de informatie van de overheid voor een gebruiker toegankelijk, het maakt de informatie toegankelijk zonder dat de gebruiker daar expliciet om vraagt. CultuurApp voorziet de gebruiker van allerelevante informatie waarvan een gebruiker van tevoren wellicht helemaal niet weet dat deze opgehaald kunnen worden via de (vaak niet eenvoudig toegankelijke) databronnen. Het combineren van de databronnen maakt CultuurApp een zeer toegankelijke en overzichtelijke informatiebron voor toeristen uit binnen- en buitenland. Zij hoeven met CultuurApp geen andere databronnen meer te gebruiken, CultuurApp is alles in één.</p>
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
		<div class="span6"><h1 id="techniek" style="margin-bottom: 10px;">Uitbreiden data</h1></div>
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
			<p>Ongeveer 6 duizend monumenten in het Rijksregister Cultureel Erfgoed zijn niet gecategoriseerd. Deze monumenten zijn dus niet op categorie te zoeken. Met 18000 wél gecategoriseerde monumenten is echter zeer veel data over de categorieën te analyseren. Zo kan aan de hand van tekstuele analyse worden ‘gegokt’ welke categorie bij een monunment dat niet gecategoriseerd is maar wel een beschrijving heeft. Het door ons ontwikkeld categoriseringssysteem bereikt op een proefset bestaande uit alle wél gecategoriseerde monumenten een slagingspercentage van 87.9 procent. Dit wil zeggen dat van 87.9 procent van de wél gecategoriseerde monumenten het systeem, doend alsof hij de categorie niet weet, de categorie van het monument aan de hand van tekstuele analyse goed gokt. Nog meer data is gehaald uit de afbeeldingen van Wikimedia Commons. De compositie, kleurstelling, oriëntatie en textuur van alle afbeeldingen van de monumenten zijn geanalyseerd. Door de verschillen tussen deze eigenschappen te onderzoeken zijn gelijkende monunmenten te vinden. Stel nu dat een gebruiker een monument erg mooi vindt, dan is het voor hem eenvoudig om in één oogopslag monumenten te zien die er op lijken. Hiervoor bestaat geen enkel systeem in de huidige markt.</p>
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