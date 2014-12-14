/**
 * Pahali-map.JS
 * -----------------------------------------------------------------------------
 * 
 * Map
 *
 */


(function ( $ ) {

  var Pahali_Map_Markers = Backbone.Collection.extend({

  });

  var Pahali_Map = Backbone.Model.extend({

    defaults: {
      'categories':  {}
    },

    initialize: function() {
      this.markers = new Pahali_Map_Markers;
    },

    // Center map with list consideration
    center: function () {
      // TODO: This should be independent
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
      if(getUrlParameters("category", "", true) != false){
        $( '*[data-cat-id="'+getUrlParameters("category", "", true)+'"]' ).trigger( "click" );
      }

      // Create link
      this.get('map').on('zoomend, moveend', function(e) {
        var loc_bounds = map.getBounds();
        window.location.hash = setUrlParameters(
            "bounds",
            loc_bounds._southWest.lat+","+loc_bounds._southWest.lng+","+
            loc_bounds._northEast.lat+","+loc_bounds._northEast.lng, "",
            false
          );
        window.location.hash = removeUrlParameters("center", false);
        window.location.hash = removeUrlParameters("zoom", false);
      });

      // On window hashchange
      model = this;
      $(window).on('hashchange', {model: model}, function( event ) {
        event.data.model.shareable();
      });
    },

    // Filter by Category
    filter_by_category: function (cat_id) {
      $('.cat-sel').removeClass('active');
      
      window.location.hash = setUrlParameters("category", cat_id, "", true);

      var markers_sel = [];
      
      if (cat_id == 'all') {
        $.each(markers_arr, function( index, marker_arr ) {
          markers_sel.push(marker_arr);
        });
      } else {
        $.each(this.get('categories'), function( index, projects_category ) {
          if (projects_category.category_id == cat_id) {
            $.each(markers_arr, function( index, marker_arr ) {
              if (marker_arr.id == projects_category.project_id) {
                markers_sel.push(marker_arr);
              };
            });
          };
        });
      }

      this.get('map').removeLayer(markers);
      markers = new L.MarkerClusterGroup({
        showCoverageOnHover: false
      });
      $.each(markers_sel, function( index, marker_sel ) {
        markers.addLayer(marker_sel.marker);
      });
      this.get('map').addLayer(markers);
    }

  });

  pahali.map = new Pahali_Map();

}( jQuery ));

