// global variable map, reference to the google maps
var map = null;

var cache = new Array();
cache['cafes'] = '';
cache['restaurants'] = '';
cache['locatie'] = '';
cache['aanbevelingen'] = '';
/**
 * On document ready, initialize functions and triggers
 */
$(document).ready(
		function() {			
			$(".single-nav li a").click(function (e) {
				e.preventDefault();
				
				// Get tab
				var tab = $(this).html().toLowerCase().replace("'", "");

				$(".single-nav li").removeClass('active');
				$(this).parent().addClass('active');
				

				if (cache[tab] != '') {
					$("#ajax_content").html(cache[tab]);
				}
				else if (tab == 'aanbevelingen') {
					aanbevelingen();
				}
				else if (tab == 'cafes') {
					cafes();
				}
				else if (tab == 'restaurants') {
					restaurants();
				}
				else if (tab == 'locatie') {
					locatie();
				}
			});
			
			aanbevelingen();
		});

function aanbevelingen() {
	$("#ajax_content").html("Laden...");
	$.post('ajax/single_aanbevelingen', {id_monument: $("#id_monument").val()}, succes = function(data) {
		var html = '';
		$.each(data, function(key, monument) {
			html += '<div style="text-align: center; float: left; width: 12.5%; height: 165px; line-height: 150px; vertical-align: middle;">';
			html += '<a href="monument/id/'+monument['id_monument']+'"><img style="max-width: 80%; max-height: 165px;" src="'+monument['photo']+'" alt="'+monument['name']+'" /></a>';
			html += '</div>';
		});
		
		cache['aanbevelingen'] = html;
		
		$("#ajax_content").empty();
		$("#ajax_content").html(html);
	}, "json");
}

function cafes() {
	$("#ajax_content").html("Laden...");
	$.post('ajax/single_places', {id_monument: $("#id_monument").val(), categories: 'bar|cafe'}, succes = function(data) {
		var html = '<table class="table table-bordered table-striped" style="margin-bottom: 0;">';
		$.each(data, function(key, cafe) {
			html += '<tr>';
			html += '<td>'+cafe.rating+'</td><td><a href="'+cafe.website+'">'+cafe.name+'</a></td><td>'+cafe.vicinity+'</td>';
			html += '</tr>';
		});
		
		if (data.length == 0) {
			html += '<tr><td>Er zijn helaas geen cafe\'s in de omgeving gevonden.</td></tr>';
		}
		html += '</table>';
		
		cache['cafes'] = html;
		
		$("#ajax_content").empty();
		$("#ajax_content").html(html);
	}, "json");
}

function restaurants() {
	$("#ajax_content").html("Laden...");
	$.post('ajax/single_places', {id_monument: $("#id_monument").val(), categories: 'food|restaurant'}, succes = function(data) {
		var html = '<table class="table table-bordered table-striped" style="margin-bottom: 0;">';
		$.each(data, function(key, restaurant) {
			html += '<tr>';
			html += '<td>'+restaurant.rating+'</td><td><a href="'+restaurant.website+'">'+restaurant.name+'</a></td><td>'+restaurant.vicinity+'</td>';
			html += '</tr>';
		});
		
		if (data.length == 0) {
			html += '<tr><td>Er zijn helaas geen restaurants in de omgeving gevonden.</td></tr>';
		}
		html += '</table>';
		
		cache['restaurants'] = html;
		
		$("#ajax_content").empty();
		$("#ajax_content").html(html);
	}, "json");
}

function locatie() {
	$("#ajax_content").html("Laden...");
	$.post('ajax/monument', {id_monument: $("#id_monument").val()}, succes = function(monument) {
		$("#ajax_content").html('<div id="kaart-single"></div>');
		
		// If the map is on the page
		if ($('#kaart-single').size() > 0) {
			// initialize options for google maps
			var myOptions = {
				// center of holland
				center : new google.maps.LatLng(monument.lng, monument.lat),
				// default zoomlevel 8
				zoom : 12,
				mapTypeControl : false,
				streetViewControl : false,
				// maptype road
				mapTypeId : google.maps.MapTypeId.ROADMAP
			};
			// create the map
			map = new google.maps.Map(document
					.getElementById("kaart-single"), myOptions);

			var longlat = new google.maps.LatLng(monument.lng, monument.lat);

			marker = new google.maps.Marker({
				position : longlat,
				map : map,
			});
		}
	}, "json");
}