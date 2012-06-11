var longitude = null;
var latitude = null;

$(document).ready(function() {
	if (navigator.geolocation) {
		browserSupportFlag = true;
		navigator.geolocation.getCurrentPosition(function(position) {
			latitude = position.coords.latitude;
			longitude = position.coords.longitude;
			
			update_position();
		});
		// Try Google Gears Geolocation
	} else if (google.gears) {
		browserSupportFlag = true;
		var geo = google.gears.factory.create('beta.geolocation');
		geo.getCurrentPosition(function(position) {
			latitude = position.latitude;
			longitude = position.longitude;
			
			update_position();
		});
	}
});

function update_position() {
	$('#longitude').val(longitude);
	$('#latitude').val(latitude);
}

var exports = exports || {};
$(function(){
    var keys = {"page": 1, "search": "", "town": "", "province": -1, "category": -1, "sort": "street", "latitude": null, "longitude": null, "distance": null, "distance_show": null, "lang": null};
    var page = getParameter('page');
    var empty = $(".monument-list .empty").hide();
    var $template = $(".monument-list .list-row.monument").remove();
    var coords = false;

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

    $(".pagination").on("click", "a", function(event){
        var page = getParameterByName("page", this.href) || keys['page'];
        setParameter('page', page);
        event.preventDefault();
        setState();

        return false;
    });
    $(".tagcloud").on("click", "a", function(event){
        var search = getParameterByName("search", this.href) || keys['search'];
        setParameter({search: search, page: keys['page']});
        event.preventDefault();
        setState();
    });
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

    // Update list
    function load(filters){

        $.ajax({
            url: base+"search/list",
            dataType: "json",
            data: filters,
            success: function(response, status, xhr){
                empty.hide();

                $(".pagination").html(response.pagination);
                $(".tagcloud").load(base+"search/cloud").ajaxStart(function(){ $(this).animate({opacity:.05}, 300); }).ajaxStop(function(){ $(this).animate({opacity:1}, 300); });
                $(".bench").html(response.bench);

                $(".monument-list .list-row.monument").remove();
                $.map(response.monuments, function(monument, i){
                    $html = $template.clone();

                    $html.find("a").attr("href", base+"monument/id/"+monument.id_monument);
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

    exports.loadList = setState;
    exports.param = getParameter;
    exports.setParam = setParameter;
    exports.paramByName = getParameterByName;

    geo(function(coords){
        setParameter({ latitude: coords.latitude, longitude: coords.longitude });
        setState();
    });

    setState();
});