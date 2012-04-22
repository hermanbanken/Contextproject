
var html = "";

$(document).ready(function() {
	updatePins();
	$('#filter_list #categories').bind('change', function() {
		updateList();
	});
});

 
/**
 * functie om de spelden te updaten aan de hand van de selectiecriteria
 * @returns helemaal niks!
 */
 function updateList() {
	 // alle huidige markers weggooien
	$('#monument_list').val('');
	
	 /*
	 // uit de selectiecriteria een array bouwen met geselecteerde opties
	 var options = [];
	 var elements = document.getElementById('selectie').elements;
	 for(i=0; i < elements.length; i++) {
		if(elements[i].value != '') options.push([elements[i].name,elements[i].value]); 
	 }*/
	 
	 // locaties ophalen met ajax
	 $.post('getmonumenten', {category: $('#filter_list #categories').val()}, succes = function(data) {
		 
		 locations = data;
		 
		 
		 for (i = 0; i < locations.length; i++) {  
			   var tr = '<tr><td class="span2"><div style="height:100px; overflow:hidden;">' + locations[i]['name'] + '</div></td><td class="span5"><div style="height:100px; overflow:hidden;">' + locations[i]['description'] + '</div></td><td class="span1"><div style="height:100px; overflow:hidden;"><img src="http://placehold.it/100x100" alt=""></div></td></tr>';
		       $('#monument_list').append(html);
		    }
			
			
	 }, "json");
	 
 }