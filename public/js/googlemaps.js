// global variable map, reference to the google maps
var map = null;
// global variable markersArray holds all markers currently visible
var markersArray = [];
// initiallocation holds the location on load
var initialLocation;
// longitude of client
var longitude = null;
// latitude of client
var latitude = null;
// markerclusterer which clusters the markers
var markerClusterer = null;
// bounds for auto-zooming on search
var bounds = null;
// keep track of the distance circle (for removing
var circle = null;
// platform
var isIpad = null;
/**
 * On document ready, initialize functions and triggers
 */
$(document).ready(function() {
	// If the map is on the page
	if ($('#kaart').size() > 0) {
		// Adjust height
		// $("#kaart").height($(window).height() - 40);
		// initialize options for google maps
		var myOptions = {
			// center of holland
			center : new google.maps.LatLng(52.003695, 4.361444),
			// default zoomlevel 8
			zoom : 15,
			// maptype road
			mapTypeId : google.maps.MapTypeId.ROADMAP
		};
		// create the map
		map = new google.maps.Map(document.getElementById("kaart"), myOptions);
		// get the clients coordinates
		getCoordinates();
		// set pin update on filter submit
		$('#filter').submit(function(e) {
			e.preventDefault();
			updatePins();
		});
	}
});

/**
 * function used to get the coordinates of the user
 */
function getCoordinates() {
	// try the navigator
	if (navigator.geolocation) {
		browserSupportFlag = true;
		navigator.geolocation.getCurrentPosition(function(position) {
			latitude = position.coords.latitude;
			longitude = position.coords.longitude;
			// update pins upon completion
			updatePins();
		});
		// try google gears
	} else if (google.gears) {
		browserSupportFlag = true;
		var geo = google.gears.factory.create('beta.geolocation');
		geo.getCurrentPosition(function(position) {
			latitude = position.latitude;
			longitude = position.longitude;
			// update pins upon completion
			updatePins();
		});
	}
}

/**
 * function to place the pins on the map
 */
function updatePins() {
	$("#searchdiv").fadeTo('fast', 0.8);
	$("#filter_button").val("Laden...");

	var distance = 0;
	// if the client wants location based search, the distance have to be
	// calculated
	// the callback of getCoordinates will re-run this function
	var distance_show = 0;
	if ($('#nearby').is(':checked')) {
		distance_show = 1;

		if (longitude == null || latitude == null) {
			getCoordinates();
			return;
		}
	}
	distance = getDistance();

	// get the locations using AJAX with the criteria
	$.post('monument/getmonumenten', 'longitude=' + longitude + '&latitude='
			+ latitude + '&' + $('#filter').serialize(),
			succes = function(data) {
				// on ajax succes, the fetched locations have to be place on the
				// map
				locations = data;
				placePins(locations);
			}, "json");
}

/**
 * function to place fetched pins on the map
 */
function placePins(locations) {
	// if no monument is found, notify the user
	if (locations.length == 0) {
		alert("Er zijn geen monumenten gevonden die voldoen aan uw zoekcriteria.");
		$("#searchdiv").fadeTo('fast', 1);
		$("#filter_button").val("Filter");
	} else {

		// store if the user wants to search nearby
		var nearby = $('#nearby').is(':checked');
		// remove all current markers
		if (markersArray) {
			for (i in markersArray) {
				markersArray[i].setMap(null);
			}
			markersArray.length = 0;
		}
		if (markerClusterer) {
			markerClusterer.clearMarkers();
		}
		if (circle) {
			circle.setMap(null);
		}

		// Create a new viewpoint bound for location based search
		bounds = new google.maps.LatLngBounds();
		// add a pin for all locations
		for (i = 0; i < locations.length; i++) {
			var longlat = new google.maps.LatLng(locations[i]["longitude"],
					locations[i]["latitude"]);
			marker = new google.maps.Marker({
				position : longlat
			});
			// And increase the bounds to take this point
			bounds.extend(longlat);
			// add the marker to the markerarray
			markersArray.push(marker);

			// create infowindow for the pin
			var infowindow = new google.maps.InfoWindow();
			// make sure the infowindow pops up upon click
			google.maps.event
					.addListener(
							marker,
							'click',
							(function(marker, i) {
								return function() {
									// Add right source to image
									$
											.post(
													'ajax/map_photo',
													{
														id_monument : locations[i]["id"]
													},
													succes = function(data) {
														// set the content of
														// the
														// infowindow
														infowindow
																.setContent("<a href='monument/id/"
																		+ locations[i]["id"]
																		+ "'><img src=\""
																		+ data.url
																		+ "\" alt=\"\" style=\"float: left; max-height: 100px; margin-right: 15px; min-height: 100px;\" /></a><h2>"
																		+ locations[i]["name"]
																		+ "</h2>"
																		+ locations[i]["description"]
																				.substring(
																						0,
																						200)
																		+ " <a href='monument/id/"
																		+ locations[i]["id"]
																		+ "'>Meer</a>");
													}, "json");

									infowindow.open(map, marker);
								}
							})(marker, i));
			// if the client uses location based search, there's no need for
			// clustering
		}

		// If the client uses location based search, a circle has to be added
		if (nearby) {
			// add current location
			var longlat = new google.maps.LatLng(latitude, longitude);
			marker = new google.maps.Marker({
				position : longlat,
				icon : new google.maps.MarkerImage(
						'http://cdn-img.easyicon.cn/png/5526/552649.png'),
				map : map
			});
			// And increase the bounds to take this point
			bounds.extend(longlat);
			// create infowindow for the current location
			var infowindow = new google.maps.InfoWindow();

			// add the marker to the array
			markersArray.push(marker);
			// make sure the infowindow pops up upon click
			google.maps.event.addListener(marker, 'click',
					(function(marker, i) {
						return function() {
							infowindow.setContent("Huidige locatie");
							infowindow.open(map, marker);
						}
					})(marker, i));
			// Add a Circle overlay to the map.
			circle = new google.maps.Circle({
				map : map,
				strokeColor : '#66CCFF',
				fillColor : '#66CCFF',
				radius : 1000 * getDistance()
			});
			// add the circle to the map
			circle.bindTo('center', marker, 'position');
			// markersArray.push(circle);
		}
		// if the client wants location based search there's no need for late
		// clustering
		var maxZoom = nearby ? 14 : 16;
		// autozoom
		map.fitBounds(bounds);

		// create the markercluster
		if (true || !nearby)
			markerClusterer = new MarkerClusterer(map, markersArray, {
				// when zoomlevel reaches 16, just show the pins instead of the
				// clusters
				maxZoom : maxZoom
			});

		$("#searchdiv").fadeTo('fast', 1);
		$("#filter_button").val("Filter");
	}
}