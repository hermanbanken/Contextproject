
var html = "";

$(document).ready(function() {
	//updatePins();
	$('#categories').bind('change', function() {
		updatePins();
	});
});

 
/**
 * functie om de spelden te updaten aan de hand van de selectiecriteria
 * @returns helemaal niks!
 */
 function updatePins() {
	 // alle huidige markers weggooien
	html = "";
	
	 /*
	 // uit de selectiecriteria een array bouwen met geselecteerde opties
	 var options = [];
	 var elements = document.getElementById('selectie').elements;
	 for(i=0; i < elements.length; i++) {
		if(elements[i].value != '') options.push([elements[i].name,elements[i].value]); 
	 }*/
	 
	 // locaties ophalen met ajax
	 $.post('getmonumenten', {category: $('#categories').val()}, succes = function(data) {
		 
		 locations = data;
		 
		 /*
		  zo moet de ajax data geintepreteerd worden om dit te laten werken
		  var locations = [
		                  ['Test1', 52.469397, 5.509644],
		                  ['Test1', 52.569397, 5.609644],
		                  ...
		                  ['Testn', long, lat]
			            ];
		 */
		 // voor alle locaties een nieuwe speld aanmaken
		 for (i = 0; i < locations.length; i++) {  
			   			   
			   //alert(locations[i]["description"]);
			   html = html+'<tr><td class="span2"><div style="height:100px; overflow:hidden;">' + locations[i]['name'] + '</div></td><td class="span5"><div style="height:100px; overflow:hidden;">' + locations[i]['description'] + '</div></td><td class="span1"><div style="height:100px; overflow:hidden;"><img src="http://placehold.it/100x100" alt=""></div></td></tr>';
		      
		      
		    }
			
			$('#monument_list').append(html);
	 }, "json");
	 
 }