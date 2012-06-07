// global variable map, reference to the google maps
var map = null;

var cache = new Array();
cache['cafes'] = '';
cache['restaurants'] = '';
cache['locatie'] = '';
cache['aanbevelingen'] = '';
cache['forecast'] = '';
/**
 * On document ready, initialize functions and triggers
 */
$(document).ready(
		function() {			
			$(".single-nav li a").click(function (e) {				
				// Get tab
				var tab = $(this).attr('class');

				$(".single-nav li").removeClass('active');
				
				show_content(tab);
			});
			
			var tab = document.location.hash.replace('#', '');
			if (tab == '') {
				tab = 'restaurants';
			}
			
			if($("#id_monument").size() > 0)
				show_content(tab);
			
			// Visited functionality
			$(".visited").click(function(e) {
				e.preventDefault();
				var link = $(this);
				
				$.post(base+'ajax/single_visited', {id_monument: $("#id_monument").val()}, succes = function(data) {
					if (data.success) {
						link.toggleClass('btn-success');
						$(".visited i").toggleClass('icon-white');
						$(".visited .text").html(data.buttonvalue);
					}
					else {
						alert("Something went wrong...");
					}
				}, "json");
			});
			

			$("#aanbevelingen").html("Laden...");
			$.post(base+'ajax/single_aanbevelingen', {id_monument: $("#id_monument").val()}, succes = function(data) {
				var html = '';
				
				if (data.length == 0) {
					html += "<p>Er zijn helaas geen aanbevelingen gevonden.</p>";
				}
				
				$.each(data, function(key, monument) {
					html += '<div style="text-align: center; float: left; width: 20%; height: 165px; line-height: 150px; vertical-align: middle;">';
					html += '<a href="'+base+'monument/id/'+monument['id_monument']+'"><img style="max-width: 80%; max-height: 165px;" src="'+monument['photo_url']+'" alt="'+monument['name']+'" /></a>';
					html += '</div>';
				});
				
				$("#aanbevelingen").empty();
				$("#aanbevelingen").html(html);
			}, "json");
		});

function rating(r){
	var html = '<div class="classification"><div class="cover"></div><div class="percentage" style="width: '+(r * 10)+'%;"></div></div>';
	return html;
}

function show_content(tab) {
	$(".single-nav li ."+tab).parent().addClass('active');
	
	if (cache[tab] != '') {
		$("#ajax_content").empty();
		$("#ajax_content").html(cache[tab]);
	}
	else if (tab == 'cafes') {
		$("#ajax_content").html("Laden...");
		$.post(base+'ajax/single_places', {id_monument: $("#id_monument").val(), categories: 'bar|cafe'}, succes = function(data) {
			var html = '<table class="table table-bordered table-striped" style="margin-bottom: 0;">';
			$.each(data, function(key, cafe) {
				if (cafe['rating'] == null) {
					cafe['rating'] = 0;
				}
				html += '<tr>';
				html += '	<td alt="'+cafe.rating+'">' + rating(cafe.rating) + '</td>';
				html += '	<td>'+cafe.name+'</td>';
				html += '	<td>'+cafe.vicinity+'</td>';
				html += '	<td><a href="http://maps.google.nl/maps?q='+cafe.vicinity+'">'+Math.round(cafe.distance * 1000)+' meter</a></td>';
				html += '</tr>';
			});
			
			if (data.length == 0) {
				html += '<tr><td>Er zijn helaas geen cafe\'s in de omgeving gevonden.</td></tr>';
			}
			html += '</table>';
			
			cache['cafes'] = html;
			
			$("#ajax_content").empty();
			$("#ajax_content").html(html);
		}, "json");
	}
	else if (tab == 'restaurants') {
		$("#ajax_content").html("Laden...");
		$.post(base+'ajax/single_places', {id_monument: $("#id_monument").val(), categories: 'restaurant'}, succes = function(data) {
			var html = '<table class="table table-bordered table-striped" style="margin-bottom: 0;">';
			$.each(data, function(key, restaurant) {
				if (restaurant['rating'] == null) {
					restaurant['rating'] = 0;
				}
				html += '<tr>';
				html += '	<td alt="'+restaurant.rating+'">' + rating(restaurant.rating) + '</td>';
				html += '	<td>'+restaurant.name+'</td>';
				html += '	<td>'+restaurant.vicinity+'</td>';
				html += '	<td><a href="http://maps.google.nl/maps?q='+restaurant.vicinity+'">'+Math.round(restaurant.distance * 1000)+' meter</a></td>';
				html += '</tr>';
			});
			
			if (data.length == 0) {
				html += '<tr><td>Er zijn helaas geen restaurants in de omgeving gevonden.</td></tr>';
			}
			html += '</table>';
			
			cache['restaurants'] = html;
			
			$("#ajax_content").empty();
			$("#ajax_content").html(html);
		}, "json");
	}
}