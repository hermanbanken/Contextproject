var map = null;
var markersArray = [];
var bounds = null;
var initialLocation;
var longitude = null;
var latitude = null;

$(document).ready(function() {
			$('#town').click(function() { this.select(); })
			$('#search').click(function() { this.select(); })
		
			if($('#kaart').size()>0) {
				$("#kaart").height($(window).height() - 40);
				var myOptions = {
				          center: new google.maps.LatLng(52.469397, 5.509644),
				          zoom: 8,
				          mapTypeId: google.maps.MapTypeId.ROADMAP
				        };
				        map = new google.maps.Map(document.getElementById("kaart"),
				            myOptions);
				        bounds = new google.maps.LatLngBounds();
				
				updatePins();
				getCoordinates();
				$('#filter').submit(function (e) {
					e.preventDefault();
					updatePins();
				});
			}
			$('#nearby').bind('click', function() {
				if(this.checked) $('#distancecontainer').slideDown();
				else $('#distancecontainer').slideUp();
			});
			
			$('#distance').slider({
				min: 25,
				max: 300,
				value:150
			});
			
			$.post('monument/getsteden', {}, succes = function(towns) {
				$("#town").autocomplete({
					source : towns
				});
			}, "json");

		});

function getCoordinates() {
	//if(longitude != null && latitude != null) return;
	if(navigator.geolocation) {
        browserSupportFlag = true;
        navigator.geolocation.getCurrentPosition(function(position) {
          latitude = position.coords.latitude;
          longitude = position.coords.longitude;
          updatePins();
        });
      // Try Google Gears Geolocation
      } else if (google.gears) {
        browserSupportFlag = true;
        var geo = google.gears.factory.create('beta.geolocation');
        geo.getCurrentPosition(function(position) {
          latitude = position.latitude;
          longitude = position.longitude;
          updatePins();
         });
      }
}

/**
 * functie om de spelden te updaten aan de hand van de selectiecriteria
 * 
 * @returns helemaal niks!
 */

 function updatePins() {
	 var distance = 0;
	 if($('#nearby').is(':checked')) {
		if(longitude==null || latitude==null) {
			getCoordinates();
			return;
		}
		distance = $('#distance').slider('value');
		 
	 }
	 
	 // locaties ophalen met ajax
	 $.post('monument/getmonumenten', {
		 	distance: distance>0?(distance/100):0,
		 	longitude: longitude,
		 	latitude: latitude,
		 	category: $('#categories').val(),
		 	limit: 1000,
		 	search: $('#search').val(),
		 	town: $('#town').val(),
		 	street: $('#street').val()
		 	
		 	}, succes = function(data) {
		 
			 locations = data;
			 
			 placePins(locations);
		 
		 }, "json");
	 
 }
 function initialize() {
	 
 }
 
 function placePins(locations) {
	// alle huidige markers weggooien
	 if (markersArray) {
		    for (i in markersArray) {
		      markersArray[i].setMap(null);
		    }
		    markersArray.length = 0;
		  }
	 
	 bounds = new google.maps.LatLngBounds();
	 // voor alle locaties een nieuwe speld aanmaken
	 for (i = 0; i < locations.length; i++) {
		 var longlat = new google.maps.LatLng(locations[i]["longitude"], locations[i]["latitude"]);
		   marker = new google.maps.Marker({
	        position: longlat,
	        map: map
	      });
		   // pin toevoegen voor de zoom
		   bounds.extend(longlat);
		   // infowindow aanmaken
		   var infowindow = new google.maps.InfoWindow();

		  // marker toevoegen aan array en google maps
	      markersArray.push(marker);
	      google.maps.event.addListener(marker, 'click', (function(marker, i) {
	        return function() {
	        	infowindow.setContent("<a href='monument/id/"+locations[i]["id"]+"'><img src=\"/photos/"+locations[i]["id"]+".jpg\" alt=\"\" style=\"float: left; max-height: 100px; margin-right: 15px; min-height: 100px;\" /></a><h2>"+locations[i]["name"]+"</h2>"
	        							+locations[i]["description"].substring(0,200)
	        							+" <a href='monument/id/"+locations[i]["id"]+"'>Meer</a>");
	        	infowindow.open(map, marker);
	        }
	      })(marker, i));
	    }
	 if($('#nearby').is(':checked')) {
		// huidige pin toevoegen
		 var longlat = new google.maps.LatLng(latitude, longitude);
		   marker = new google.maps.Marker({
	        position: longlat,
	        icon: new google.maps.MarkerImage('http://cdn-img.easyicon.cn/png/5526/552649.png'),
	        map: map
	      });
		   // pin toevoegen voor de zoom
		   bounds.extend(longlat);
		   // infowindow aanmaken
		   var infowindow = new google.maps.InfoWindow();

		  // marker toevoegen aan array en google maps
	      markersArray.push(marker);
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
	        circle.bindTo('center', marker, 'position');
	        markersArray.push(circle);
	 }
	// zoomen!
    if(markersArray.length > 0) map.fitBounds(bounds);
 }
