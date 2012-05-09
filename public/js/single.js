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
					zoom : 15,
					// maptype road
					mapTypeId : google.maps.MapTypeId.ROADMAP
				};
				// create the map
				map = new google.maps.Map(document
						.getElementById("kaart-single"), myOptions);

				// get the locations using AJAX with the criteria
				$.post('monument/getmonumenten', {
					// distance from current client location
					distance : 0.05,
					// distance show
					distance_show : 1,
					// longitude of current client location
					latitude : $("#longitude").val(),
					// latitude of current client location
					longitude : $("#latitude").val(),
					// category selected by the client
					category : -1,
					// category selected by the client
					province : -1,
					// maximum number of locations
					limit : 50,
					// searchbar value
					search : '',
					// selected town
					town : '',
					// selected street
					street : '',
					// not save into session
					not_in_session : true
				}, succes = function(data) {
					// on ajax succes, the fetched locations have to be place on
					// the map
					locations = data;
					placePinsSingle(locations);
				}, "json");
			}
		});

/**
 * function to place fetched pins on the map
 */
function placePinsSingle(locations) {
	if (locations.length == 0) {
		alert("Er zijn geen monumenten gevonden die voldoen aan uw zoekcriteria.");
	}
	// Create a new viewpoint bound for location based search
	bounds = new google.maps.LatLngBounds();
	// add a pin for all locations
	for (i = 0; i < locations.length; i++) {
		var longlat = new google.maps.LatLng(locations[i]["longitude"],
				locations[i]["latitude"]);
		if (locations[i]['id'] == $('#id_monument').val()) {
			marker = new google.maps.Marker({
				position : longlat,
				map : map,
			});
			marker.setZIndex(1);
		}

		// And increase the bounds to take this point
		bounds.extend(longlat);
	}
	map.fitBounds(bounds);
	map.setZoom(11);
}
