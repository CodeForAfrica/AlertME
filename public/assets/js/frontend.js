$( document ).ready(function() {

  L.mapbox.accessToken = 'pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
  var map = L.mapbox.map('map', 'codeforafrica.ji193j10',{
      zoomAnimationThreshold: 10,
      zoomControl: false
    }).setView([-28.4792625, 24.6727135], 5);


  // Overlapping markers
  var markers = new L.MarkerClusterGroup({
    showCoverageOnHover: false
  });

  // var featureLayer = L.mapbox.featureLayer();

  map.scrollWheelZoom.disable();


  // Create Alert Map

  var map_alert = L.mapbox.map('map-alert', 'codeforafrica.ji193j10',{
    zoomAnimationThreshold: 10,
    maxZoom: 20,
    zoomControl: false,
    attributionControl: false
  }).setView([-28.4792625, 24.6727135], 5);

  // Disable drag and zoom handlers.
  map_alert.dragging.disable();
  map_alert.touchZoom.disable();
  map_alert.doubleClickZoom.disable();
  map_alert.scrollWheelZoom.disable();

  // Disable tap handler, if present.
  if (map_alert.tap) map_alert.tap.disable();


  // On search click
  $('.map-ctrl-search').click(function(){
    $('.home-search').fadeIn('fast');
    $('.leaflet-control-mapbox-geocoder-wrap').hide();
    $('.leaflet-control-mapbox-geocoder-results').hide();
    $('#loading-geo').hide();
  });



  // // Initialize the geocoder control and add it to the map.
  // var geocoderControl = L.mapbox.geocoderControl('mapbox.places-v1',{
  //   autocomplete: true
  // });
  // geocoderControl.addTo(map);

  var input = document.getElementById('search-geo');
  var options = {
    componentRestrictions: {country: 'za'}
  };

  searchBox = new google.maps.places.Autocomplete(input, options);


  // Users location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var latlng = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[1]) {
            var arrAddress = results[1].address_components;
            var itemCountry='';

            // iterate through address_component array
            $.each(arrAddress, function (i, address_component) {
              if (address_component.types[0] == "country"){
                  console.log("country:"+address_component.short_name);
                  itemCountry = address_component.short_name;
              }
              //return false; // break the loop
            });
            console.log(results[1].formatted_address);
            if(itemCountry == "ZA"){
              $('#search-geo').val(results[1].formatted_address);
            }

          } else {
            console.log('No results found');
          }
        } else {
          console.log('Geocoder failed due to: ' + status);
        }
      });
    }, null, {
      enableHighAccuracy: true,
      timeout: 5000,
      maximumAge: 0
    } );
  }

  var load = false;

  initializeMap ();

  // Listen for the event fired when the user selects an item from the
  // pick list. Retrieve the matching places for that item.
  google.maps.event.addListener(searchBox, 'place_changed', function() {

    $('#loading-geo').fadeIn('slow');

    load = true;

    initializeMap ();

    var place = searchBox.getPlace();
    map.setView([place.geometry.location.k, place.geometry.location.B], 10);

    loadMarkers();

  });

  function initializeMap () {
    map.remove();
    markers = new L.MarkerClusterGroup({
      showCoverageOnHover: false,
    });

    map = L.mapbox.map('map', 'codeforafrica.ji193j10',{
      zoomAnimationThreshold: 10,
      maxZoom: 15,
      zoomControl: false
    }).setView([-28.4792625, 24.6727135], 5);

    map.scrollWheelZoom.disable();

    $('#map-ctrl-zoom-in').click(function () {
      map.zoomIn();
    });
    $('#map-ctrl-zoom-out').click(function () {
      map.zoomOut();
    });

    map.on('zoomend', function(e) {
      if(map.getZoom() > 9) {
        $('#map-ctrl-alert').attr('class', 'btn btn-sm btn-embossed btn-primary');
      } else {
        $('#map-ctrl-alert').attr('class', 'btn btn-sm btn-embossed btn-primary disabled');
      }
      // Assign route
      var loc_center = map.getCenter();
      window.location.hash = "#!/map=home"+
      "&ctr_lat="+loc_center.lat+"&ctr_lng="+loc_center.lng+
      "&zoom="+map.getZoom();
    });

    /* Alerts
     * ------- */
    $('#alertModal').on('shown.bs.modal', function () {
      map_alert.invalidateSize();
      map_alert.fitBounds(map.getBounds());
      // Reset
      $('.map-alert-email').removeClass('has-error');
      $('#alertModal .alert-danger').fadeOut();
      $('#alertModal .alert-danger .msg-error.email').fadeOut();
    });
    $('.create-alert-btn').click(function () {
      if( $('#map-alert-email').val().trim() == '' ) {
        $('.map-alert-email').addClass('has-error');
        $('#alertModal .alert-danger').fadeIn();
        $('#alertModal .alert-danger .msg-error.email').fadeIn();
        return;
      }
      if(!isEmail($('#map-alert-email').val())) {
        $('.map-alert-email').addClass('has-error');
        $('#alertModal .alert-danger').fadeIn();
        $('#alertModal .alert-danger .msg-error.email').fadeIn();
        return;
      }
    });


    if(getUrlParameters("map", "", true) != false){
      var ctr_lat = getUrlParameters("ctr_lat", "", true);
      var ctr_lng = getUrlParameters("ctr_lng", "", true);
      var map_zoom = getUrlParameters("zoom", "", true);

      map.setView([ctr_lat, ctr_lng], map_zoom);
      load = true;
      loadMarkers();
    }

    // geocoderControl = L.mapbox.geocoderControl('mapbox.places-v1',{
    //   autocomplete: true
    // });
    // geocoderControl.addTo(map);
  }

  function loadMarkers () {

    if ( map.getZoom() > 9 && load) {

      load = false;

      var bounds = map.getBounds();
      var bound = bounds._southWest.lat + "," + bounds._northEast.lat + "," +
        bounds._southWest.lat + "," + bounds._northEast.lng;

      $.ajax({
        type: "GET",
        url: '/api/v1/projectsgeojson?bounds='+bound
      }).done(function(response) {

        for (var i = 0; i < response.features.length; i ++) {
          var datum = response.features[i];
          var loc = new L.LatLng(
            datum.geometry.coordinates[1],
            datum.geometry.coordinates[0]
          );
          var marker = new L.Marker(loc);
          marker.title = datum.properties.title;
          marker.bindPopup(marker.title);
          markers.addLayer(marker);
        }

        map.addLayer(markers);

        $('#loading-geo').fadeOut('fast');
        $('.home-search').fadeOut('slow');

      });

      // featureLayer = L.mapbox.featureLayer()
      //   .loadURL('/api/v1/projectsgeojson?bounds='+bound)
      //   .addTo(map);
      //
      // featureLayer.on('ready', function() {
      //   // map.fitBounds(featureLayer.getBounds());
      //
      //   $('#loading-geo').fadeOut('fast');
      //   $('.home-search').fadeOut('slow');
      // });
      //
      // featureLayer.on('click', function(e) {
      //   map.panTo(e.layer.getLatLng());
      // });
    }

  }


});
