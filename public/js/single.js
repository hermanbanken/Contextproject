// Global variables
var map = null;
var placemarkers = new Array();
var monumentloc = null;

// Cache recommendations and flickr
var cache = new Array();
cache['recommendations'] = '';
cache['flickr'] = '';

/**
 * On document ready, initialize functions and triggers
 */
$(document).ready(

		function() {
			drawMagic();
			
			// Init photo viewer
			Shadowbox.init();
			
			// Select google maps and get longitude and ltitude
            var m = document.getElementById("single-map"),
            c = m.dataset['map'].split(";"),
            p = new google.maps.LatLng(c[1], c[0]);
            monumentloc = p;

            // Set map options
	        var options = {
	          center : p,
	          zoom : 12,
	          mapTypeControl : false,
	          streetViewControl : false,
	          mapTypeId : google.maps.MapTypeId.ROADMAP
	        };
	        
	        // Create map (global variable)
	        map = new google.maps.Map(m, options);
	        
	        // Add momnument marker
	        new google.maps.Marker({ position : p, map : map});
			
			// Highlight search string
			if(document.location.hash.length>0) {
				var searchstring = document.location.hash.substring(1);
				searchstring = '('+searchstring.replace(/[\s]+/gi,')|(')+')';
				var regex = new RegExp(searchstring,'ig');
				$('.description').html($('.description').html().replace(regex,'<span style="font-weight:bold">$&</span>'));
			}

			// Load weather
			$(".forecast .well").load(base+"weather/monument/"+$("#id_monument").val());
			
			// Click event for places nav
			$(".single-nav li a").click(function (e) {				
				// Get tab
				var tab_places = $(this).attr('class');

				$(".single-nav li").removeClass('active');
				
				show_content_places(tab_places);
			});
			
			// Click event for photos nav
			$(".single-photos-nav li a").click(function (e) {				
				// Get tab
				var tab_photos = $(this).attr('class');

				$(".single-photos-nav li").removeClass('active');
				
				show_content_photos(tab_photos);
			});

			// Standard tabs
			var tab_places = 'restaurants';
			var tab_photos = 'recommendations';
			
			// If monument is set, show content of both tabs
			if($("#id_monument").size() > 0) {
				show_content_places(tab_places);
				show_content_photos(tab_photos);
			}
			
			// Visited button
			$(".visited").click(function(e) {
				e.preventDefault();
				var link = $(this);
				
				$.post(base+'ajax/single_visited', {id_monument: $("#id_monument").val()}, succes = function(data) {
					if (data.success) {
						link.toggleClass('btn-success');
						$(".visited i").toggleClass('icon-white');
						$(".visited .text").html(data.buttonvalue);
					}
					else {
						alert("Something went wrong...");
					}
				}, "json");
			});
		});

// Function to get html string for rating
function rating(r){
	var html = '<div class="classification" title="'+r+'"><div class="cover"></div><div class="percentage" style="width: '+(r * 10)+'%;"></div></div>';
	return html;
}

// Show content for google places
function show_content_places(tab) {
	// Inititate bounds for zooming
	var bounds = new google.maps.LatLngBounds();
	
	// Add monument location to bounds (it should always be visible)
	bounds.extend(monumentloc);
	
	// Activate right tab in menu
	$li = $(".single-nav li ."+tab).parent();
    $li.addClass('active');

    // If it is disabled in config, show message
    if($li.hasClass("disabled")) {
        $("#ajax_content").html("<p>Deze functie is helaas tijdelijk niet beschikbaar.</p>");
        return;
    }
    
	// First show loading message
	$("#ajax_content").html("Laden...");
	
	// Find right categories
	if (tab == 'cafes') {
		// Cafes
		var categories = 'bar|cafe';
	}
	else {
		// Else restaurants
		var categories = 'restaurant';
	}
		
	// Ask for places with ajax
	$.post(base+'ajax/single_places', {id_monument: $("#id_monument").val(), categories: categories}, succes = function(data) {
		// Init table
		var html = '<table class="table table-bordered table-striped" style="margin-bottom: 0;">';
		
		// Remove all markers except monument
		$.each(placemarkers, function(key, marker) {
			marker.setMap(null);
		});
		
		// Loop through places
		$.each(data, function(key, place) {
			// If no rating is found, set to zero
			if (place['rating'] == null) {
				place['rating'] = 0;
			}
			
			// Create row with place information
			html += '<tr id="place'+key+'">';
			html += '	<td>'+(key + 1)+'</td>';
			html += '	<td alt="'+place.rating+'">' + rating(place.rating) + '</td>';
			html += '	<td>'+place.name+'</td>';
			html += '	<td>'+place.vicinity+'</td>';
			html += '	<td><a href="http://maps.google.nl/maps?q='+place.vicinity+'">'+Math.round(place.distance * 1000)+' meter</a></td>';
			html += '</tr>';
			
			// Create location
            var p = new google.maps.LatLng(place.lat, place.lng);
			
            // Add marker to map
	        var marker = new google.maps.Marker({ position : p, map : map, icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+(key + 1)+'|00AEEF|FFFFFF'});
	    	
	        // Extend bounds for zooming
	        bounds.extend(p);
	    	
	        // Add marker to our placemarkers-array
	        placemarkers[key] = marker;
	    });
		
		// Zoom map to right bounds
		map.fitBounds(bounds);
		
		// If no places are found, show message
		if (data.length == 0) {
			html += '<tr><td>Er zijn helaas geen faciliteiten in de omgeving gevonden.</td></tr>';
		}
		
		// End table
		html += '</table>';
		
		// Clear and fill content
		$("#ajax_content").empty();
		$("#ajax_content").html(html);
		
		// Create click-events on the rows
		$.each(placemarkers, function(key, marker) {
			$('#place'+key).click(function(e) {
				// Set zoom and center of map on click
				map.setZoom(18);
				map.setCenter(marker.getPosition());
			});
		});
	}, "json");
}


function show_content_photos(tab) {
	// Activate right tab in menu
	$li = $(".single-photos-nav li ."+tab).parent();
    $li.addClass('active');

    // If it is disabled in config, show message
    if($li.hasClass("disabled")) {
        $("#ajax_content_photos").html("<p>Deze functie is helaas tijdelijk niet beschikbaar.</p>");
        return;
    }
    
    // If cache of tab is available, use it
	if (cache[tab] != '') {
		$("#ajax_content_photos").empty();
		$("#ajax_content_photos").html(cache[tab]);
	}
	else if (tab == 'recommendations') {
		// First show loading message
		$("#ajax_content_photos").html("Laden...");
		
		// Get recommendations from ajax
		$.post(base+'ajax/single_aanbevelingen', {id_monument: $("#id_monument").val()}, succes = function(data) {
			// Init html
			var html = '';
			
			// If no recommendations are found, show message
			if (data.length == 0) {
				html += "<p>Er zijn helaas geen aanbevelingen gevonden.</p>";
			}
			
			// Add recommendations to html
			$.each(data, function(key, monument) {				
				html += '<div style="text-align: center; float: left; width: 20%; height: 165px; line-height: 150px; vertical-align: middle;">';
				html += '<a href="'+base+'monument/id/'+monument['id_monument']+'"><img class="aanbeveling_tooltip" title="'+monument.name+'<br />'+monument.street+' '+monument.streetNumber+'<br />'+monument.town+'" style="max-width: 80%; max-height: 165px;" src="'+monument['photo_url']+'" alt="'+monument['name']+'" /></a>';
				html += '</div>';
			});
			
			// Cache tab
			cache['recommendations'] = html;

			// Clear and fill content
			$("#ajax_content_photos").empty();
			$("#ajax_content_photos").html(html);
			$('.aanbeveling_tooltip').tooltip();
		}, "json");
	}
	else if (tab == 'flickr') {
		$("#ajax_content_photos").html("Laden...");
		
		
		$.post(base+'ajax/single_flickr', {id_monument: $("#id_monument").val()}, succes = function(data) {
			// Init html
			var html = '';
			
			// If no photos are found, show message
			if (data.length == 0) {
				html += "<p>Er zijn helaas geen foto's uit de omgeving gevonden.</p>";
			}
			
			// Add photos to html
			$.each(data, function(key, photo) {				
				html += '<div style="text-align: center; float: left; width: 20%; height: 165px; line-height: 150px; vertical-align: middle;">';
				html += '<a class="flickrphoto" rel="shadowbox[flickr]" href="'+photo.large+'"><img style="max-width: 80%; max-height: 165px;" src="'+photo.thumb+'" alt="" /></a>';
				html += '</div>';
			});
			
			// Cache tab
			cache['flickr'] = html;

			// Clear and fill content
			$("#ajax_content_photos").empty();
			$("#ajax_content_photos").html(html);
			
			// Clear cache of photo-viewer and set it up again so new photos are added
			Shadowbox.clearCache();
			Shadowbox.setup();
		}, "json");
	}
}

function drawMagic(){
    $(".monument-single-overview .thumbnail img").each(function(el, i){
        $magic = $("<a class='magic'></a>");
        $(this).parents(".thumbnail").css("position", "relative").append($magic).find(".caption").remove();
        $magic.css({ width: "100px", height: "100px", position: "absolute", bottom: 0, left: 0, cursor: "pointer"});
        $magic.attr("href", base+"monument/visualcomparison/"+$("#id_monument").val());

        // Create paper and sets
        var paper = new Raphael($magic.get(0), "100px", "100px");
        var wand_bg, wand_fg, wand = paper.set();

        // Convert star polygon to path
        var polygonPoints = '71.805,8.105 70.387,0 64.812,6.055 56.671,4.897 60.704,12.067 57.088,19.459 65.154,17.834 71.068,23.56 72.016,15.384 79.288,11.531';
        var p_star = polygonPoints.replace(/([0-9.]+),([0-9.]+)/g, function($0, x, y) {
            return 'L ' + Math.floor(x) + ',' + Math.floor(y) + ' ';
        }).replace(/^L/, 'M'); // replace first L with M (moveTo)

        var stars = [];
        var pos = [[0, 15, 15], [-10, 50, 20], [-50, 20, 17]];

        // Place stars
        for(var i = 0; i < pos.length; i++){
            var p = paper.path(p_star);
            p.attr("transform", "t" + pos[i][0] + "," + pos[i][1]);
            stars.push(p);
        }

        // Make wand
        wand.push(
            wand_bg = paper.path("M66.325,30.626c-1.142-1.462-2.824-2.927-4.886-4.22c-2.062-1.291-4.109-2.167-5.935-2.555c-1.726-0.314-3.644-0.524-4.995,1.311L-5,113.75l15.75,12l56.566-90.051C68.379,33.68,67.359,32.05,66.325,30.626z"),
            wand_fg = paper.path("M66.325,30.626c-1.141-1.462-2.824-2.927-4.886-4.22c-2.061-1.291-4.109-2.167-5.934-2.555c-1.726-0.314-3.643-0.524-4.995,1.311L-5,113.75l15.75,12l56.567-90.051C68.379,33.68,67.359,32.05,66.325,30.626zM48.743,58.71c-0.89-1.386-2.593-3.003-4.812-4.396c-2.217-1.388-4.412-2.223-6.052-2.417l15.5-24.725c0.224-0.046,0.917-0.074,1.902,0.231c1.215,0.324,2.754,1,4.295,1.971c1.768,1.097,3.185,2.374,3.973,3.401c0.396,0.508,0.623,0.95,0.684,1.174c0.008,0.02,0.015,0.038,0.015,0.053L48.743,58.71z")
        );

        function position(time, w){
            // Move stars
            for(var i = 0; i < stars.length; i++){
                var newX = pos[i][0] + Math.random()*pos[i][2],
                    newY = pos[i][1] + Math.random()*pos[i][2];
                var x = Math.max(Math.min(pos[i][0] + pos[i][2], newX), pos[i][0] - pos[i][2]),
                    y = Math.max(Math.min(pos[i][1] + pos[i][2], newY), pos[i][1] - pos[i][2]),
                    r = Math.random()*180;
                stars[i].animate({
                    "transform": "t"+x+","+y+"r"+r
                }, time, "linear");
            }
            // Move wand
            if(w)
            wand.animate({
                "transform": "t10,-10"
            }, time * .35, "linear", function(){
                wand.animate({"transform":"t20,15"}, time *.35, function(){
                    wand.animate({"transform":"t0,0"}, time*.3);
                });
            });
        }
        // Initial move stars
        position(2000, false);

        (function color(fg, bg, stars_color){
            wand_bg.attr({fill: bg, stroke: fg});
            wand_fg.attr({fill: fg, stroke: "none"});
            for(var i = 0; i < stars.length; i++)
            stars[i].attr({fill: stars_color, stroke: "none"});
        })("#444", "#fff", "#ff0");

        var timeout = false;

        $magic.hover(function(){
            position(2000, true);
            clearTimeout(timeout);
            function tick(){
                timeout = setTimeout(tick, 2000);
                position(2000, false);
            }
            timeout = setTimeout(tick, 2000);
        }, function(){
            clearTimeout(timeout);
        });
    });
}