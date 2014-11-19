$( document ).ready(function() {
  var input = document.getElementById('search-geo');
  var options = {
    componentRestrictions: {country: 'za'}
  };
  searchBox = new google.maps.places.Autocomplete(input, options);

  /**
   * Google Geocoder Search Box
   * ---------------------------------------------------------------------------
   */
  google.maps.event.addListener(searchBox, 'place_changed', function() {

    $('#loading-geo').fadeIn('slow');
    var place = searchBox.getPlace();
    window.location.href = "/map/#!/center="+place.geometry.location.k+","+
    place.geometry.location.B+"&zoom=11";

  });


  /**
   * User's Location
   * ---------------------------------------------------------------------------
   */

  $('#search-my-geo').click(function () {
    $('#search-my-geo-alert').fadeOut();
    $('#loading-my-geo').fadeIn();
    if (navigator.geolocation) {
      // Geolocation can work

      var options_my_geo = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
      };

      function success_my_geo (position) {
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

              if(itemCountry == "ZA"){
                $('#search-geo').val(results[1].formatted_address);
              } else {
                $('#search-my-geo-alert').fadeIn();
              }
              $('#loading-my-geo').fadeOut();

            } else {
              $('#loading-my-geo').fadeOut();
              $('#search-my-geo-alert').fadeIn();
            }
          } else {
            $('#loading-my-geo').fadeOut();
            $('#search-my-geo-alert').fadeIn();
          }
        });
      };

      function error_my_geo (err) {
        // console.warn('ERROR(' + err.code + '): ' + err.message);
        $('#loading-my-geo').fadeOut();
        $('#search-my-geo-alert').fadeIn();
      };

      navigator.geolocation.getCurrentPosition(
        success_my_geo, error_my_geo, options_my_geo );
    } else {
      // Geolocation doesn't work
      $('#loading-my-geo').fadeOut();
      $('#search-my-geo-alert').fadeIn();
    }
  });


});
