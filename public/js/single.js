// global variable map, reference to the google maps
var map = null;
// global variable markersArray holds all markers currently visible
var markersArray = [];
// bounds for auto-zooming on search
var bounds = null;
/**
 * On document ready, initialize functions and triggers
 */
$(document).ready(
		function() {
			// If the map is on the page
			if ($('#kaart-single').size() > 0) {
				// initialize options for google maps
				var myOptions = {
					// center of holland
					center : new google.maps.LatLng($("#longitude").val(), $(
							"#latitude").val()),
					// default zoomlevel 8
					zoom : 12,  
					  mapTypeControl: false,
					  streetViewControl: false,
					// maptype road
					mapTypeId : google.maps.MapTypeId.ROADMAP
				};
				// create the map
				map = new google.maps.Map(document
						.getElementById("kaart-single"), myOptions);

				var longlat = new google.maps.LatLng($("#longitude").val(), $(
						"#latitude").val());

				marker = new google.maps.Marker({
					position : longlat,
					map : map,
				});
			}
		});
