var html = "";
var mpp = 8;
var page = 1;
var total = 0;

$(document).ready(function() {
	updateList(true);
	$('#filter_list #town').click(function() {
		this.select();
	})

	$('#filter_list #search').click(function() {
		this.select();
	})

	$('.prev').click(function(e) {
		e.preventDefault();
		if (page != 1) {
			page--;
			updateList(false);
		}
	});

	$('.next').click(function(e) {
		e.preventDefault();
		if (page != Math.ceil(total / mpp)) {
			page++;
			updateList(false);
		}
	});

	$('#filter_list').submit(function(e) {
		e.preventDefault();
		updateList(true);
	});
});

function updateArrows() {
	if (page == Math.ceil(total / mpp)) {
		$('.next').fadeTo(0, 0.5);
	} else {
		$('.next').fadeTo(0, 1);
	}

	if (page == 1) {
		$('.prev').fadeTo(0, 0.5);
	} else {
		$('.prev').fadeTo(0, 1);
	}

}

/**
 * functie om de spelden te updaten aan de hand van de selectiecriteria
 * 
 * @returns helemaal niks!
 */
function updateList(datachanged) {
	if (datachanged) {
		total = 0;
		page = 1;
	}

	// alle huidige markers weggooien
	$('#monument_list').html('');
	// locaties ophalen met ajax
	$
			.post(
					'getmonumenten',
					{
						category : $('#categories').val(),
						limit : mpp,
						search: $('#search').val(),
						offset : (mpp * (page - 1)),
						town : $('#town').val(),
						sort : $('#sort').val()
					},
					succes = function(data) {

						locations = data;

						$.post('getmonumenten', {
							search: $('#search').val(),
								category : $('#categories').val(),
							town : $('#town').val(),
							sort : $('#sort').val(),
							findtotal : true
						}, succes = function(count) {
							total = count;
							updateArrows();
						});

						if (locations.length == 0) {
							var tr = '<tr><td class="span12">Er zijn geen monumenten gevonden met deze selectie.</td></tr>';
							$('#monument_list').append(tr);
						}

						for (i = 0; i < locations.length; i++) {
							var tr = '<tr>'
									+ '<td class="span2">'
									+ '<div style="height:100px; overflow:hidden;">'
									+ '<a href="id/'
									+ locations[i]['id']
									+ '">'
									+ locations[i]['name']
									+ '</a>'
									+ '</div>'
									+ '</td>'
									+ '<td class="span5">'
									+ '<div style="height:100px; overflow:hidden;">'
									+ locations[i]['description'].substring(0,
											300)
									+ '... <a href="id/'
									+ locations[i]['id']
									+ '">Lees meer</a>'
									+ '</div></td><td class="span1">'
									+ '<div style="height:100px; overflow:hidden;">'
									+ '<a style="display: block; text-align: center;" href="id/'
									+ locations[i]['id']
									+ '">'
									+ '<img src="/public/photos/'
									+ locations[i]['id']
									+ '.jpg" style="max-width: 100px; max-height: 100px;" alt="">'
									+ '</a>' + '</div>' + '</td>' + '</tr>';
							$('#monument_list').append(tr);
						}
					}, "json");

}