 function initialize() {
        var myOptions = {
          center: new google.maps.LatLng(52.469397, 5.509644),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("kaart"),
            myOptions);
      }