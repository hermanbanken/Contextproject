var exports = exports || {};
$(function(){

    // Default parameters for selection
    var keys = {"page": 1, "search": "", "town": "", "province": -1, "category": -1, "sort": "street", "latitude": null, "longitude": null, "distance": null, "distance_show": null, "lang": null};
    var page = getParameter('page');
    var empty = $(".monument-list .empty").hide();
    var $template = $(".monument-list .list-row.monument").remove();
    var coords = false;

    // Add navigation with arrow keys
    $(document).keyup(function(event){
        if(event.target == document.body)
        {
            var prev = parseInt(getParameter('page'));
            if(event.keyCode == 39)
                setParameter('page', prev+1);
            else if(event.keyCode == 37)
                setParameter('page', prev-1 || 1);

            if(event.keyCode == 39 || event.keyCode == 37)
                setState();
        }
    });

    // Function to get the GPS-coordinates
    function geo(callback) {
        // Cache location
        if ( coords ) callback( coords );

        var geo = navigator.geolocation || google.gears.factory.create('beta.geolocation');
        if ( geo )
        {
            geo.getCurrentPosition(function(position){
                position = coords = position.coords || position;
                callback(position);
            });
        }
    }

    // Fetch the parameter from the URI
    function getParameterByName(name, source)
    {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(source || window.location.search);
        if(results == null)
            return false;
        else
            return decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    // Fetch the parameter key or parameters given in key and if not set return the default value from keys
    function getParameter(key)
    {
        if(typeof key == 'object' || typeof key == 'undefined'){
            var data = {};
            for(n in keys){
                var val = getParameterByName(n) || keys[n];
                if(val !== keys[n])
                    data[n] = val;
            }
            return data;
        } else {
            return getParameterByName(key) || keys[key];
        }
    }

    // Set a parameter if it isn't de default value
    function setParameter(key, value)
    {
        var data = getParameter();

        if(typeof value !== 'undefined')
        {
            if(value === keys[key])
                delete data[key];
            else
                data[key] = value;

        }
        else
        {
            for(n in key){
                if(key[n] === keys[key])
                    delete data[key];
                else
                    data[n] = key[n];
            }
        }

        history.pushState(data, window.title, location.origin + location.pathname + "?" + $.param(data));
    }

    /**
     * Update the state
     * - load new monuments
     * - update the form
     * @param state
     */
    function setState(state){
        state = state || getParameter() || {};
        load(state);

        $("input, textarea, select").each(function(i, el){
            if(el.name in keys){
                var field = "checkbox" == el.type && "checked" || ["INPUT", "SELECT"].indexOf(el.nodeName) >= 0 && "value" || el.nodeName == "TEXTAREA" && "innerHTML";
                if(field)
                    el[field] = getParameter(el.name);
                if("checkbox" == el.type)
                    $(el).triggerHandler('click');
            }
        });
    }

    /**
     * Make sure the pagination keeps on the page, handle with AJAX
     */
    $(".pagination").on("click", "a", function(event){
        var page = getParameterByName("page", this.href) || keys['page'];
        setParameter('page', page);
        event.preventDefault();
        setState();

        return false;
    });

    /**
     * Make sure the tagcloud keeps on the page, handle with AJAX
     */
    $(".tagcloud").on("click", "a", function(event){
        var search = getParameterByName("search", this.href) || keys['search'];
        setParameter({search: search, page: keys['page']});
        event.preventDefault();
        setState();
    });

    /**
     * Make sure the form doesn't submit, handle with AJAX
     */
    $(".page").on("submit", "form", function(event){
        var data = $(this).serializeArray(),
            mod = {};
        $.each(data, function(i, el){
            if(el.value !== getParameter(el.name))
                mod[el.name] = el.value;
        });
        mod.page = keys['page'];
        setParameter(mod);
        event.preventDefault();
        setState();
        return false;
    });

    /**
     * Load the current state from the server
     * @param filters
     */
    function load(filters){

        $.ajax({
            url: base+"search/list",
            dataType: "json",
            data: filters,
            success: function(response, status, xhr){
                // Hide 404 message
                empty.hide();

                // Update side elements like pagination and benchmarks, start load of tagcloud
                if (response.pagination == '') {
                	$(".pagination").hide();
                }
                else {
                    $(".pagination").html(response.pagination);
                	$(".pagination").show();
                }
                $(".total").html('('+response.total+')');
                $(".tagcloud").load(base+"search/cloud").ajaxStart(function(){ $(this).animate({opacity:.05}, 300); }).ajaxStop(function(){ $(this).animate({opacity:1}, 300); });
                $(".bench").html(response.bench);
                
                if (response.keywordrecommend.length != 0) {
	                var keywords = '';
	                $.each(response.keywordrecommend, function (key, keyword) {
	                	keywords += '<a href="'+base+'monument/list?search='+keyword+'">'+keyword+'</a> ';
	                });
	                
                	$(".keywords").html(keywords);
                	$(".recommendations").show();
            	}
		        else {
		        	$(".recommendations").hide();
		        }	

                // Clear old monuments
                $(".monument-list .list-row.monument").remove();
                // Add new monuments
                $.map(response.monuments, function(monument, i){
                    $html = $template.clone();

					$html.find("a").attr("href", base+"monument/id/"+monument.id_monument + "#" + getParameter('search'));
                    $html.find("img").attr("src", monument.photoUrl);
                    $html.find(".summary").html(monument.summary);
                    $html.find(".name a").text(monument.name);
                    if(monument.distance)
                    {
                        $html.find(".distance").text(
                            monument.distance > 2 ?
                            Math.round(monument.distance) + " km" :
                            Math.round(monument.distance * 1000) + " m");
                    }

                    $(".monument-list").append($html);
                });
            },
            error: function(){
                // show error by 404 message
                empty.show();
                $(".monument-list .list-row.monument").remove();
            },
            beforeSend: function() {
                $(".loading").css({opacity: 0, display: "block"}).animate({ opacity: 1}, 200);
                $(".monument-list .monument").animate({ opacity:.7});
            },
            complete: function() {
                $(".loading").animate({ opacity: 0}, 200).animate({opacity: 0, display: "none"}, 1);
                $(".monument-list .monument").animate({ opacity: 1});
            }
        });
    };

    // Export to global scope
    exports.setParam = setParameter;

    // Start by getting the coordinates
    geo(function(coords){
        setParameter({ latitude: coords.latitude, longitude: coords.longitude });
        setState();
    });

    // Load first monuments
    setState();
});