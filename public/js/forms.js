/**
 * Forms
 */
(function($){
	
	$(window).bind('hashchange', function() {
		var hash = window.location.hash;
		if(hash == '#user/login'){
			$("<div></div>").appendTo(document.body).load(base+'user/login .form-login', function(){
				$('.modal').on('hide', function(){
					location.hash = "";
                    $(this).remove();
				}).modal();
			});
		}
	});
	
	if(window.location.hash !== '')
		$(window).trigger('hashchange');
	
})(jQuery);