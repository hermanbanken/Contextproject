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
    var keys = {"page": 1, "search": "", "town": "", "province": -1, "category": -1, "sort": "street", "latitude": null, "longitude": null, "distance": null, "distance_show": null};
    var page = getParameter('page');
    var empty = $(".monument-list .empty").hide();
    var $template = $(".monument-list .list-row.monument").remove();

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
                var field = ["INPUT", "SELECT", "CHECKBOX"].indexOf(el.nodeName) >= 0 && "value" || el.nodeName == "TEXTAREA" && "innerHTML";
                if(field)
                    el[field] = getParameter(el.name);
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
                console.log("Success, starting display");
                empty.hide();

                $(".pagination").html(response.pagination);
                $(".tagcloud").html(response.tagcloud);

                $(".monument-list .list-row.monument").remove();
                $.map(response.monuments, function(monument, i){
                    $html = $template.clone();

                    $html.find("a").attr("href", base+"monument/id/"+monument.id_monument);
                    $html.find("img").attr("src", monument.photoUrl);
                    $html.find(".summary").text(monument.description);
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
                console.log("Done, display");
            },
            error: function(){
                empty.show();
                $(".monument-list .list-row.monument").remove();
            }
        });
    };

    exports.loadList = setState;
    exports.param = getParameter;
    exports.setParam = setParameter;
    exports.paramByName = getParameterByName;
    setState();
});

/*
 * var html = ""; var mpp = 8; var page = 1; var total = 0; var longitude =
 * null; var latitude = null;
 * 
 * $(document).ready(function() { updateList(true); $('#filter_list
 * #town').click(function() { this.select(); })
 * 
 * $('#filter_list #search').click(function() { this.select(); })
 * 
 * $('.prev').click(function(e) { e.preventDefault(); if (page != 1) { page--;
 * updateList(false); } });
 * 
 * $('.next').click(function(e) { e.preventDefault(); if (page !=
 * Math.ceil(total / mpp)) { page++; updateList(false); } });
 * 
 * $('#filter_list').submit(function(e) { e.preventDefault(); updateList(true);
 * }); });
 * 
 * function updateArrows() { if (page == Math.ceil(total / mpp)) {
 * $('.next').fadeTo(0, 0.5); } else { $('.next').fadeTo(0, 1); }
 * 
 * if (page == 1) { $('.prev').fadeTo(0, 0.5); } else { $('.prev').fadeTo(0, 1); } }
 * 
 * /** functie om de entries te updaten aan de hand van de selectiecriteria
 * 
 * @returns helemaal niks!
 * 
 * function updateList(datachanged) { if (datachanged) { total = 0; page = 1; }
 * if(longitude == null || latitude == null) { if(navigator.geolocation) {
 * browserSupportFlag = true;
 * navigator.geolocation.getCurrentPosition(function(position) { latitude =
 * position.coords.latitude; longitude = position.coords.longitude;
 * updateList(true); }); // Try Google Gears Geolocation } else if
 * (google.gears) { browserSupportFlag = true; var geo =
 * google.gears.factory.create('beta.geolocation');
 * geo.getCurrentPosition(function(position) { latitude = position.latitude;
 * longitude = position.longitude; updateList(true); }); } return; } // locaties
 * ophalen met ajax $.post('monument/getmonumenten',{ longitude: longitude,
 * latitude: latitude, category : $('#categories').val(), limit : mpp, search:
 * $('#search').val(), offset : (mpp * (page - 1)), town : $('#town').val(),
 * sort : $('#sort').val() }, succes = function(data) {
 * 
 * locations = data; updateEntries(locations); }, "json"); }
 * 
 * 
 * function updateEntries(locations) { // checken hoeveel pagina's er nodig zijn
 * $.post('monument/getmonumenten', { search: $('#search').val(), category :
 * $('#categories').val(), town : $('#town').val(), sort : $('#sort').val(),
 * findtotal : true }, succes = function(count) { total = count; updateArrows();
 * }); // alle huidige markers weggooien $('#monument_list').fadeOut(function() {
 * $('#monument_list').html('');
 * 
 * 
 * if (locations.length == 0) { var tr = '<tr><td class="span12">Er zijn geen
 * monumenten gevonden met deze selectie.</td></tr>';
 * $('#monument_list').append(tr); }
 * 
 * for (i = 0; i < locations.length; i++) { var tr = '<tr>' + '<td class="span2">' + '<div
 * style="height:100px; overflow:hidden;">' + '<a href="monument/id/' +
 * locations[i]['id'] + '">' + locations[i]['name'] + '</a>' + '<span
 * style="display:block">'+(locations[i]['distance']==0?'':Math.round(locations[i]['distance']*1000)+'
 * meter')+'</span>' + '</div>' + '</td>' + '<td class="span5">' + '<div
 * style="height:100px; overflow:hidden;">' +
 * locations[i]['description'].substring(0, 300) + '... <a href="monument/id/' +
 * locations[i]['id'] + '">Lees meer</a>' + '</div></td><td class="span1">' + '<div
 * style="height:100px; overflow:hidden;">' + '<a style="display: block;
 * text-align: center;" href="monument/id/' + locations[i]['id'] + '">' + '<img
 * src="photos/' + locations[i]['id'] + '.jpg" style="max-width: 100px;
 * max-height: 100px;" alt="">' + '</a>' + '</div>' + '</td>' + '</tr>';
 * $('#monument_list').append(tr); } $('#monument_list').fadeIn(); }); // alle
 * huidige markers weggooien $('#monument_list').hide(); }
 */