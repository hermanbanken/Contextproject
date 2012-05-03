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

/**
 * On document ready, initialize functions and triggers
 */
$(document).ready(function() {
	
			// The townbar and searchbar have to be completely selected once clicked on
			$('#town, #search').click(function() { 
				this.select(); 
			})
		
			// If the map is on the page
			if($('#kaart').size()>0) {
				// Adjust height
				$("#kaart").height($(window).height() - 40);
				// initialize options for google maps
				var myOptions = {
				  // center of holland
		          center: new google.maps.LatLng(52.469397, 5.509644),
		          // default zoomlevel 8
		          zoom: 8,
		          // maptype road
		          mapTypeId: google.maps.MapTypeId.ROADMAP
		        };
				// create the map
				map = new google.maps.Map(document.getElementById("kaart"), myOptions);
				// get the clients coordinates
				getCoordinates();
				// set pin update on filter submit
				$('#filter').submit(function (e) {
					e.preventDefault();
					updatePins();
				});
			}
			// Localisation of client
			$('#nearby').bind('click', function() {
				if(this.checked) $('#distancecontainer').slideDown();
				else $('#distancecontainer').slideUp();
			});
			
			// initialize slider for the distance
			$('#distance').slider({
				min: 25,
				max: 300,
				value:150
			});
			
			// autocomplete needs a list of cities
			$.post('monument/getsteden', {}, succes = function(towns) {
				$("#town").autocomplete({
					source : towns
				});
			}, "json");

		});

/**
 * function used to get the coordinates of the user
 */
function getCoordinates() {
	// try the navigator
	if(navigator.geolocation) {
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
	 var distance = 0;
	 // if the client wants location based search, the distance have to be calculated
	 // the callback of getCoordinates will re-run this function
	 if($('#nearby').is(':checked')) {
		if(longitude==null || latitude==null) {
			getCoordinates();
			return;
		}
		distance = $('#distance').slider('value');
	 }
	 // get the locations using AJAX with the criteria
	 $.post('monument/getmonumenten', {
			 	// distance from current client location
			 	distance: distance>0?(distance/100):0,
			    // longitude of current client location
			 	longitude: longitude,
			 	// latitude of current client location
			 	latitude: latitude,
			 	// category selected by the client
			 	category: $('#categories').val(),
			 	// maximum number of locations
			 	limit: 50000,
			 	// searchbar value
			 	search: $('#search').val(),
			 	// selected town
			 	town: $('#town').val(),
			 	// selected street 
			 	street: $('#street').val()
			}, succes = function(data) {
			 // on ajax succes, the fetched locations have to be place on the map
			 locations = data;
			 placePins(locations);
		 }, "json");
 }
 
 function initialize() {
	 
 }
 
 /**
  * function to place fetched pins on the map
  */
 function placePins(locations) {
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
	 
	// if no monument is found, notify the user
    if(locations.length == 0) {
    	alert("Er zijn geen monumenten gevonden die voldoen aan uw zoekcriteria.");
    }
	 
	 // add a pin for all locations
	 for (i = 0; i < locations.length; i++) {
		 var longlat = new google.maps.LatLng(locations[i]["longitude"], locations[i]["latitude"]);
		   marker = new google.maps.Marker({
	        position: longlat
	      });
		   // create infowindow for the pin
		   var infowindow = new google.maps.InfoWindow();

		  // add the marker to the markerarray
	      markersArray.push(marker);
	      
	      // make sure the infowindow pops up upon click
	      google.maps.event.addListener(marker, 'click', (function(marker, i) {
	        return function() {
	        	// set the content of the infowindow
	        	infowindow.setContent("<a href='monument/id/"+locations[i]["id"]+"'><img src=\"/photos/"+locations[i]["id"]+".jpg\" alt=\"\" style=\"float: left; max-height: 100px; margin-right: 15px; min-height: 100px;\" /></a><h2>"+locations[i]["name"]+"</h2>"
	        							+locations[i]["description"].substring(0,200)
	        							+" <a href='monument/id/"+locations[i]["id"]+"'>Meer</a>");
	        	infowindow.open(map, marker);
	        }
	      })(marker, i));
	    }
	 
	 // If the client uses location based search, a circle has to be added
	 if($('#nearby').is(':checked')) {
		// add current location
		 var longlat = new google.maps.LatLng(latitude, longitude);
		   marker = new google.maps.Marker({
	        position: longlat,
	        icon: new google.maps.MarkerImage('http://cdn-img.easyicon.cn/png/5526/552649.png'),
	        map: map
	      });
		   // create infowindow for the current location
		   var infowindow = new google.maps.InfoWindow();

		  // add the marker to the array
	      markersArray.push(marker);
	      // make sure the infowindow pops up upon click
	      google.maps.event.addListener(marker, 'click', (function(marker, i) {
	        return function() {
	        	infowindow.setContent("Huidige locatie");
	        	infowindow.open(map, marker);
	        }
	      })(marker, i));
	      // Add a Circle overlay to the map.
	      var circle = new google.maps.Circle({
	          map: map,
	          strokeColor: '#66CCFF',
	          fillColor: '#66CCFF',
	          radius: 1609*($('#distance').slider('value')/100)
	      });
	      // add the circle to the map
	      circle.bindTo('center', marker, 'position');
	      markersArray.push(circle);
	 }
	// create the markercluster
    markerClusterer = new MarkerClusterer(map, markersArray, {
    	// when zoomlevel reaches 16, just show the pins instead of the clusters
    	maxZoom: 16,
    	styles: [
	    	{
	    		height: 100,
	    		width: 47,
	    		opt_anchor: [50, 64],
	    		url: "images/redpin.png"
	    	}
    	]
    });
 }
