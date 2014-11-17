/**
 * Pahali-map.JS
 * -----------------------------------------------------------------------------
 * 
 * Map
 *
 */

(function ( $ ) {

  if (typeof pahali.map.options.map === 'undefined') {
    // map is undefined
  } else {

    // Center map with list consideration
    pahali.map.center = function () {
      if ($('.map-list').length) {
        var map_move_x = -0.5 * (
          $('.map-list').width() +
          parseInt($('.map-list').css('padding-top').replace('px', '')) +
          parseInt($('.map-list').css('padding-bottom').replace('px', ''))
        );
         this.options.map.panBy(
          L.point(map_move_x, 0, false),
          {animate: false}
        );
      };
    }

    // Make link shareable
    pahali.map.shareable = function () {
      // React to link
      if(getUrlParameters("center", "", true) != false){
        var map_ctr = getUrlParameters("center", "", true).split(",");
        var map_zoom = getUrlParameters("zoom", "", true);
        this.options.map.setView([map_ctr[0], map_ctr[1]], map_zoom);
      }
      if(getUrlParameters("bounds", "", true) != false){
        var map_bounds = getUrlParameters("bounds", "", true).split(",");
        this.options.map.fitBounds([[map_bounds[0], map_bounds[1]],[map_bounds[2], map_bounds[3]]]);
      }

      // Create link
      this.options.map.on('zoomend, moveend', function(e) {
        var loc_bounds = map.getBounds();
        window.location.hash = "#!/bounds="+
            loc_bounds._southWest.lat+","+loc_bounds._southWest.lng+","+
            loc_bounds._northEast.lat+","+loc_bounds._northEast.lng;
      });
    }
  }

}( jQuery ));