var exports = exports | {};
(function($, exports){

    // global variable map, reference to the google maps
    var map = null;
    // global variable markersArray holds all markers currently visible
    var markersArray = [];
    var markersLookup = {};
    // initiallocation holds the location on load
    var initialLocation;
    // longitude of client
    var longitude = null;
    // latitude of client
    var latitude = null;
    // markerclusterer which clusters the markers
    var markerClusterer = null;
    // bounds for auto-zooming on search
    var bounds = null;
    // keep track of the distance circle (for removing
    var circle = null;
    // platform
    var isIpad = null;
    // locations
    var locations = false;
    // dom ready
    var ready = false;
    // Google Map options
    var mapOptions = {
        // center of holland
        center:new google.maps.LatLng(52.003695, 4.361444),
        // default zoomlevel 8
        zoom:15,
        // maptype road
        mapTypeId:google.maps.MapTypeId.ROADMAP
    };

	function CultuurApp()
    {
        this.initialize();
        $(function(){
            domready = true;
        });
	}

    var domready = false;
	CultuurApp.prototype.pages = {"monument/map": "search", "monument/list": "search", "profile": "profile"};
	CultuurApp.prototype.initialize = function()
	{
        for(n in this.pages)
            if(window.location.href.match(n) && typeof this[this.pages[n]] == 'function')
                this[this.pages[n]]();
	};

	CultuurApp.prototype.search = function(){
        var self = this;

        console.log("Search");

        $(function(){
            // catch submitting of filter
            $("#filter").on("submit", function (e) {
                e.preventDefault();
                self.map();
            });

            map = new google.maps.Map(document.getElementById("kaart"), mapOptions);
            self.map();
        });
	};

    CultuurApp.prototype.list = function(monuments, options)
    {
        var self = this;
        var extractedMarker = false;
        this.page = this.page || 0;

        if(!monuments)
        {
            var query = this.parameter(false, function(){ self.list(); });

            if(this.page > 0) query.page = this.page;
            $.getJSON(base+"search/list", query, function(data)
            {
                if(options && options.replace)
                    data.replace = options.replace;
                self.list(data.monuments, data);
            });
        }else{
            if( options && options.replace )
                $(this).remove();
            else
                $("#list").html("");

            $.map(monuments, function(monument)
            {
                var id = monument.id_monument;
                var desc = monument.description.replace(/^(.{170,200})[\.\,\s](.*)/, "$1... ");
                var style = "float: left; margin-right: 10px; margin-top: 3px; ";//max-height: 100px; min-height: 100px;";
                $("#list").append(
                    $(
                        "<div class='monument'>"+
                        "<a href='"+base+"monument/id/"+id+"' >" +
                        "<h2>" + monument.name + "</h2>" +
                        "<img class='map-info-photo' id='photo'"+id+"' src='"+monument.photoUrl+"' style='"+style+"' />" +
                        "</a><p>"+desc+"<a href='"+base+"monument/id/"+id+"'>Meer</a></p>" +
                        "</div>"
                    ).click(function(){
                        $(this).addClass("selected").siblings().removeClass("selected");
                        var point = new google.maps.LatLng( monument.lng, monument.lat );
                        map.panTo(point);
                        if(map.getZoom() < 16) map.setZoom(16);

                        if(extractedMarker){
                            markerClusterer.addMarkers([extractedMarker]);
                        }
                        extractedMarker = markersLookup[monument.id_monument];
                        marker.setMap(map);
                        //marker.setVisible(true);
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                        //setTimeout(function(){ marker.setAnimation(null); }, 750);
                    })
                );
            });

            if(options.more) $("#list").append(
                $("<div class='monument more'>Load more monuments</div>").click(
                    function()
                    {
                        self.page++;
                        self.list(false, { replace: this });
                    }
                )
            );
        }

    };

    CultuurApp.prototype.map = function(locations)
    {
        var self = this;

        if(!locations){
            var query = this.parameter(false, function(){ self.map(); });

            $.getJSON(base+"search/map", query, function(data){
                self.map(data.monuments);
                console.log("Debug", data.debug);
            });
        } else {
            this.locations = locations;
            this.placePins();
            this.list();
        }

    };

    /**
     * Make single marker and return it
     * @param location
     * @param map
     * @param bounds
     * @return {google.maps.Marker}
     */
    CultuurApp.prototype.addMarker = function(location, map, bounds){
        var point = new google.maps.LatLng( location[1],  location[2]);
        var marker = new google.maps.Marker({ position: point, zIndex: 40 });
        var self = this;
        bounds.extend(point);

        // make sure the infowindow pops up upon click
        google.maps.event.addListener(
            marker,
            'click',
            (function (marker) {
                return function () {
                    // Close other open window
                    if(self.infowindow)
                        self.infowindow.close();

                    // create infowindow for the pin
                    var infowindow = self.infowindow = new google.maps.InfoWindow();

                    // Add right source to image
                    $.getJSON(base+'monument/id/'+location[0], function (data) {
                        var id = location[0];
                        var desc = data.description.replace(/^(.{170,200})[\.\,\s](.*)/, "$1... ");
                        var style = "float: left; max-height: 100px; margin-right: 15px; min-height: 100px;";
                        infowindow.setContent(
                            "<a href='"+base+"monument/id/"+id+"' >" +
                                "<img class='map-info-photo' id='photo'"+id+"' src='"+data.photoUrl+"' style='"+style+"' />" +
                                "<h2>" + data.name + "</h2></a>" +
                                "<p>"+desc+"<a href='"+base+"monument/id/"+id+"'>Meer</a></p>"
                        );
                        infowindow.open(map, marker);
                    });

                }
            })(marker)
        );

        return marker;
    };

    CultuurApp.prototype.drawLocation = function(point, map, bounds){
        // add current location
        var longlat = new google.maps.LatLng(point.latitude, point.longitude);
        marker = new google.maps.Marker({
            position: longlat,
            icon: new google.maps.MarkerImage(
                'https://maps.gstatic.com/mapfiles/mobile/mobileimgs2.png',
                new google.maps.Size(22, 22),
                new google.maps.Point(0, 18)
            ),
            map: map,
            zIndex: 70
        });
        marker.setZIndex(10);
        // And increase the bounds to take this point
        bounds.extend(longlat);
        // create infowindow for the current location
        var infowindow = new google.maps.InfoWindow();

        // add the marker to the array

        // make sure the infowindow pops up upon click
        google.maps.event.addListener(marker, 'click',
            (function (marker, i) {
                return function () {
                    infowindow.setContent("Huidige locatie");
                    infowindow.open(map, marker);
                }
            })(marker, i));
        // Add a Circle overlay to the map.
        circle = new google.maps.Circle({
            map:map,
            strokeColor:'#66CCFF',
            fillColor:'#66CCFF',
            radius:1000 * getDistance()
        });
        // add the circle to the map
        circle.bindTo('center', marker, 'position');
        // markersArray.push(circle);

        return marker;
    };

    CultuurApp.prototype.placePins = function placePins() {
        var locations = this.locations;
        console.log("Placing pins");

        // if no monument is found, notify the user
        if (locations.length == 0) {
            alert("Er zijn geen monumenten gevonden die voldoen aan uw zoekcriteria.");
            $("#searchdiv").fadeTo('fast', 1);
            $("#filter_button").val("Filter");
        } else {

            // remove all current markers
            if (markersArray) {
                for (i in markersArray) {
                    markersArray[i].setMap(null);
                }
                markersArray.length = 0;
            }
            if (markerClusterer) {
                markerClusterer.clearMarkers();
            }
            if (circle) {
                circle.setMap(null);
            }

            // Create a new viewpoint bound for location based search
            bounds = new google.maps.LatLngBounds();
            this.infowindow = false;
            // add a pin for all locations
            for (i = 0; i < locations.length; i++) {

                marker = this.addMarker(locations[i], map, bounds);
                markersArray.push(marker);
                markersLookup[locations[i][0]] = marker;
            }

            // store if the user wants to search nearby
            var nearby = $('#nearby').is(':checked');
            // If the client uses location based search, a circle has to be added
            if (nearby) {
                var coord = this.getCoordinates();
                var userloc = this.drawLocation(coord, map, bounds);
            }
            // if the client wants location based search there's no need for late
            // clustering
            var maxZoom = nearby ? 14 : 16;
            // autozoom
            map.fitBounds(bounds);


            // create the markercluster
            if (true || !nearby)
                markerClusterer = new MarkerClusterer(map, markersArray, {
                    // when zoomlevel reaches 16, just show the pins instead of the
                    // clusters
                    maxZoom: maxZoom
                });

            $("#searchdiv").fadeTo('fast', 1);
            $("#filter_button").val("Filter");
        }
    };

    function precondition(total){
        this.conditions = arguments.slice(1);
        this.done = [total];
    };
    precondition.prototype.add = function(name){
        this.conditions.push(name);
        return this;
    };
    precondition.prototype.done = function(name){
        for(n in this.conditions)
            if(this.conditions[n] == name)
                delete this.conditions[n];
        // If all conditions are gone, callback
        if(this.conditions.length == 0)
            for(n in this.done)
                var c = this.done[n]();
    };

    /**
     * Get parameters
     *
     * If callback is given and we need to update the parameters, we call callback.
     * This happens when we find out the coordinates of the navigator when these
     * weren't known before.
     * @param key
     * @param callback
     * @return {*}
     */
    CultuurApp.prototype.parameter = function(key, callback){
        var serialize = $('#filter').serializeArray();
        var query = {};
        $.each(serialize, function(i, o){ query[o.name] = o.value; });

        if ($('#nearby').is(':checked')) {

            if (!(coord = this.getCoordinates()))
            {
                this.getCoordinates(function(){
                    callback();
                });
                return;
            }
            query.distance_show = 1;
            query.distance = getDistance();

            query.longitude = coord.longitude;
            query.latitude = coord.latitude;
        }

        if(key)
            return query[key];
        else
            return query;
    };

	/**
	 * function used to get the coordinates of the user
	 */
	CultuurApp.prototype.setCoordinates = function(self, callback)
	{
        return function(position)
        {
            var c = position.coords || position;
            self._location = {latitude: c.latitude, longitude: c.longitude};
            if(typeof callback == 'function')
                callback(self._location);
        }
	};
	CultuurApp.prototype.getCoordinates = function(callback)
	{
		if(this._location)
		{
			if(callback) callback(this._location);
            return this._location;
		}

        if (navigator.geolocation)
		{
			navigator.geolocation.getCurrentPosition(this.setCoordinates(this, callback));
		}
		else if (google.gears)
		{
			var geo = google.gears.factory.create('beta.geolocation');
			geo.getCurrentPosition(this.setCoordinates(this, callback));
		}
	};

	exports.app = new CultuurApp();

})(jQuery, exports);