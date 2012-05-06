/**
 * Forms
 */
(function($){
	
	$(window).bind('hashchange', function() {
		var hash = window.location.hash;
		if(hash == '#user/login'){
			$("<div></div>").appendTo(document.body).load('user/login .form-login', function(){
				$('.modal').on('hide', function(){
					location.hash = "";
				}).modal();
			});
		}
	});
	
	if(window.location.hash !== '')
		$(window).trigger('hashchange');
	
})(jQuery);