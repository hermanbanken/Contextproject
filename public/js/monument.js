function photo(id_monument, id_object) {
	$.post('monument/photo', {
		id : id_monument
	}, succes = function(data) {
		$("#"+id_object).attr('src', data.url);
	}, "json");
}