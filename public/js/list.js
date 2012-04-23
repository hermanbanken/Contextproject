
var html = "";

$(document).ready(function() {
	updateList();
	$('#filter_list #categories').bind('change', function() {
		updateList();
	});
	$('#filter_list').submit(function (e) {
		e.preventDefault();
		updateList();
	});
});

 
/**
 * functie om de spelden te updaten aan de hand van de selectiecriteria
 * @returns helemaal niks!
 */
 function updateList() {
	 // alle huidige markers weggooien
	$('#monument_list').html('');
	 // locaties ophalen met ajax
	$.post('getmonumenten', {
	 	category: $('#categories').val(),
	 	limit: 15,
	 	offset: 0,
	 	town: $('#town').val(),
	 	sort: $('#sort').val(),
	 	street: $('#street').val()
	 	
	 	}, succes = function(data) {
		 
		 locations = data;
		 
		 
		 for (i = 0; i < locations.length; i++) {  
			   var tr = '<tr>'+
				   		'<td class="span2">'+
			   				'<div style="height:100px; overflow:hidden;">'+
			   					'<a href="id/'+locations[i]['id']+'">'+locations[i]['name']+'</a>'+
			   				'</div>'+
			   			'</td>'+
			   			'<td class="span5">'+
			   				'<div style="height:100px; overflow:hidden;">'+
			   					locations[i]['description']+
			   				'</div></td><td class="span1">'+
				   			'<div style="height:100px; overflow:hidden;">'+
				   				'<a href="id/'+locations[i]['id']+'"><img src="http://cdn.babble.com/being-pregnant/files/2012/02/church-100x100.jpg" alt=""></a>'+
				   			'</div>'+
				   		'</td>'+
				   	'</tr>';
		       $('#monument_list').append(tr);
		    }
			
			
	 }, "json");
	 
 }