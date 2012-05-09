// is the platform an ipad?
isIpad = navigator.userAgent.match(/iPad/i) != null;

$(document).ready(
		function() {

			// initialize slider for the distance, depending on the platform
			if (isIpad) {
				$('#distance').remove();
				$('#distance_ipad').append(
						"<input id='distanceslider' name='distance' value='"
								+ $('#distanceinput').val()
								+ "' type='range' min='1' max='100' />");
				$('#distanceslider').bind('change', function() {
					$('#distanceinput').val(getDistance());
				});
				$('#distanceinput').change(function() {
					if (!$.isNumeric($('#distanceinput').val())) {
						$('#distanceinput').val(20);
					}
					$('#distanceslider').val($('#distanceinput').val());
				})
			} else {
				$('#distance_ipad').remove();
				$('#distance').slider(
						{
							min : 1,
							max : 100,
							value : $('#distanceinput').val(),
							slide : function(data) {
								$('#distanceindicator').html(
										Math.round(getDistance())
												+ " kilometer");
								$('#distanceinput').val(
										Math.round(getDistance()));
							},
							change : function(data) {
								$('#distanceindicator').html(
										Math.round(getDistance())
												+ " kilometer");
								$('#distanceinput').val(
										Math.round(getDistance()));
							}
						});
				$('#distanceinput').change(
						function() {
							if (!$.isNumeric($('#distanceinput').val())) {
								$('#distanceinput').val(20);
							}
							return $('#distance').slider('value',
									$('#distanceinput').val());
						})
			}

			// Localisation of client
			$('#nearby').bind('click', function() {
				if (this.checked)
					$('#distancecontainer').slideDown();
				else
					$('#distancecontainer').slideUp();
			});

			// The townbar and searchbar have to be completely selected once
			// clicked on
			$('#town, #search').click(function() {
				this.select();
			});

			// autocomplete needs a list of cities
			$.post('monument/getsteden', {}, succes = function(towns) {
				$("#town").autocomplete({
					source : towns
				});
			}, "json");
		});

/**
 * function om de afstand te bepalen, afhankelijk van platform
 */
function getDistance() {
	if (isIpad) {
		return $('#distanceslider').val();
	} else {
		return $('#distance').slider('value');
	}
}