var map = null;
var markersArray = [];
var bounds = null;
var initialLocation;

$(document).ready(		
		function() {
			$('#town').click(function() { this.select(); })
			$('#search').click(function() { this.select(); })

			if($('#kaart').size()>0) {
				var myOptions = {
				          center: new google.maps.LatLng(52.469397, 5.509644),
				          zoom: 8,
				          mapTypeId: google.maps.MapTypeId.ROADMAP
				        };
				        map = new google.maps.Map(document.getElementById("kaart"),
				            myOptions);
				        bounds = new google.maps.LatLngBounds();
				$("#kaart").height($(window).height() - 40);
				
				updatePins();
				$('#filter').submit(function (e) {
					e.preventDefault();
					updatePins();
				});
			}

			$.post('getsteden', {}, succes = function(towns) {
				$("#town").autocomplete({
					source : towns
				});
			}, "json");

		});

function goLocal() {
	if(navigator.geolocation) {
        browserSupportFlag = true;
        navigator.geolocation.getCurrentPosition(function(position) {
          initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
          map.setCenter(initialLocation);
          map.setZoom(15);
        }, function() {
          handleNoGeolocation(browserSupportFlag);
        });
      // Try Google Gears Geolocation
      } else if (google.gears) {
        browserSupportFlag = true;
        var geo = google.gears.factory.create('beta.geolocation');
        geo.getCurrentPosition(function(position) {
          initialLocation = new google.maps.LatLng(position.latitude,position.longitude);
          map.setCenter(initialLocation);
          map.setZoom(15);
	              }, function() {
          handleNoGeoLocation(browserSupportFlag);
        });
      }
}
function initialize() {
        
      }
 
/**
 * functie om de spelden te updaten aan de hand van de selectiecriteria
 * 
 * @returns helemaal niks!
 */

 function updatePins() {
	 
	 // alle huidige markers weggooien
	 if (markersArray) {
		    for (i in markersArray) {
		      markersArray[i].setMap(null);
		    }
		    markersArray.length = 0;
		  }
	 
	 // locaties ophalen met ajax
	 $.post('getmonumenten', {
		 	category: $('#categories').val(),
		 	limit: 1000,
		 	search: $('#search').val(),
		 	town: $('#town').val(),
		 	street: $('#street').val()
		 	
		 	}, succes = function(data) {
		 
		 locations = data;
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
		        	infowindow.setContent("<a href='id/"+locations[i]["id"]+"'><img src=\"/public/photos/"+locations[i]["id"]+".jpg\" alt=\"\" style=\"float: left; max-height: 100px; margin-right: 15px; min-height: 100px;\" /></a><h2>"+locations[i]["name"]+"</h2>"
		        							+locations[i]["description"].substring(0,200)
		        							+" <a href='id/"+locations[i]["id"]+"'>Meer</a>");
		        	infowindow.open(map, marker);
		        }
		      })(marker, i));
		    }
		 // zoomen!
	      map.fitBounds(bounds);

		 }, "json");
	 
 }
