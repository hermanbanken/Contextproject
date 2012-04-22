var map = null; 
var markersArray = [];

function initialize() {
        var myOptions = {
          center: new google.maps.LatLng(52.469397, 5.509644),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("kaart"),
            myOptions);
      }
 
/**
 * functie om de spelden te updaten aan de hand van de selectiecriteria
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
	 /*
	 // uit de selectiecriteria een array bouwen met geselecteerde opties
	 var options = [];
	 var elements = document.getElementById('selectie').elements;
	 for(i=0; i < elements.length; i++) {
		if(elements[i].value != '') options.push([elements[i].name,elements[i].value]); 
	 }*/
	 
	 // locaties ophalen met ajax
	 $.get('getpins', {categorie: '123'}, succes = function(data) {
		 alert(data);
		 /*locations = data;
		 
		 
		  zo moet de ajax data geintepreteerd worden om dit te laten werken
		  var locations = [
		                  ['Test1', 52.469397, 5.509644],
		                  ['Test1', 52.569397, 5.609644],
		                  ...
		                  ['Testn', long, lat]
			            ];
		 
		 // voor alle locaties een nieuwe speld aanmaken
		 for (i = 0; i < locations.length; i++) {  
		      marker = new google.maps.Marker({
		        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		        map: map
		      });
		      markersArray.push(marker);
		      google.maps.event.addListener(marker, 'click', (function(marker, i) {
		        return function() {
		          infowindow.setContent(locations[i][0]);
		          infowindow.open(map, marker);
		        }
		      })(marker, i));
		    }*/
	 });
	 
 }