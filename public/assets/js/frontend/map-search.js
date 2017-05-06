var user_lat = 0;
var user_lng = 0;


$( document ).ready(function() {

  $('.search-geo .btn').tooltip('hide');

  /**
   * Google Geocoder Search Box
   * ---------------------------------------------------------------------------
   */

  if (typeof google !== 'undefined') {
    var input = document.getElementById('search-geo');
    var options = {
      componentRestrictions: { country: pahali.country.code.toLowerCase() }
    };
    searchBox = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(searchBox, 'place_changed', function() {

      $('#loading-geo').fadeIn('slow');
      var place = searchBox.getPlace();
      window.location.href = "/map/#!/center="+place.geometry.location.lat()+","+
      place.geometry.location.lng()+"&zoom=11";

    });
  };
  

  /**
   * User's Location
   * ---------------------------------------------------------------------------
   */

  $('#search-my-geo').on('click', function () {
    $('#search-my-geo-alert').fadeOut();
    $('#search-my-geo-alert-denied').fadeOut();

    var $btn = $(this).button('loading');

    if (navigator.geolocation) {
      // Geolocation can work

      var options_my_geo = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      };

      function success_my_geo (position) {
        user_lat = position.coords.latitude;
        user_lng = position.coords.longitude;
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
                    itemCountry = address_component.short_name;
                }
              });

              if(itemCountry == pahali.country.code.toUpperCase()){
                $('#search-geo').val(results[1].formatted_address);
                window.location.href = "/map/#!/center=" + user_lat + "," +
                  user_lng + "&zoom=11";
              } else {
                $('#search-my-geo-alert').fadeIn();
              }

            } else {
              $('#search-my-geo-alert').fadeIn();
            }
          } else {
            $('#search-my-geo-alert').fadeIn();
          }
          $btn.button('reset');
        });
      };

      function error_my_geo (err) {
        $('#search-my-geo-alert-denied').fadeIn();
        $btn.button('reset');
      };

      navigator.geolocation.getCurrentPosition(
        success_my_geo, error_my_geo, options_my_geo );

    } else {
      // Geolocation doesn't work
      $btn.button('reset');
      $('#search-my-geo-alert-denied').fadeIn();
    }
  });


});
