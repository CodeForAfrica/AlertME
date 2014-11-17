/**
 * Pahali-map.JS
 * -----------------------------------------------------------------------------
 * 
 * Map
 *
 */


(function ( $ ) {

  var Pahali_Map = Backbone.Model.extend({

    // Center map with list consideration
    center: function () {
      if ($('.map-list').length) {
        var map_move_x = -0.5 * (
          $('.map-list').width() +
          parseInt($('.map-list').css('padding-top').replace('px', '')) +
          parseInt($('.map-list').css('padding-bottom').replace('px', ''))
        );
         this.get('map').panBy(
          L.point(map_move_x, 0, false),
          {animate: false}
        );
      };
    },

    // Make link shareable
    shareable: function () {
      // React to link
      if(getUrlParameters("center", "", true) != false){
        var map_ctr = getUrlParameters("center", "", true).split(",");
        var map_zoom = getUrlParameters("zoom", "", true);
        this.get('map').setView([map_ctr[0], map_ctr[1]], map_zoom);
      }
      if(getUrlParameters("bounds", "", true) != false){
        var map_bounds = getUrlParameters("bounds", "", true).split(",");
        this.get('map').fitBounds([[map_bounds[0], map_bounds[1]],[map_bounds[2], map_bounds[3]]]);
      }

      // Create link
      this.get('map').on('zoomend, moveend', function(e) {
        var loc_bounds = map.getBounds();
        window.location.hash = "#!/bounds="+
            loc_bounds._southWest.lat+","+loc_bounds._southWest.lng+","+
            loc_bounds._northEast.lat+","+loc_bounds._northEast.lng;
      });
    }

  });

  pahali.map = new Pahali_Map;

}( jQuery ));