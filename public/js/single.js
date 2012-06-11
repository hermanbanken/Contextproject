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
				html += '<a href="'+base+'monument/id/'+monument['id_monument']+'"><img style="max-width: 80%; max-height: 165px;" src="'+monument['photo_url']+'" alt="'+monument['name']+'" /></a>';
				html += '</div>';
			});
			
			// Cache tab
			cache['recommendations'] = html;

			// Clear and fill content
			$("#ajax_content_photos").empty();
			$("#ajax_content_photos").html(html);
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