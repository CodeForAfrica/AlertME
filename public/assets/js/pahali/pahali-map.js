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
      'categories':  {},
      'shareable': true
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
      console.log('Centered');
    },


    // Create Share Link
    share_create_link: function () {
      var loc_bounds = this.get('map').getBounds();
      window.location.hash = setUrlParameters(
          'bounds',
          loc_bounds._southWest.lat+','+loc_bounds._southWest.lng+','+
          loc_bounds._northEast.lat+','+loc_bounds._northEast.lng, '',
          false
        );
      window.location.hash = removeUrlParameters('center', false);
      window.location.hash = removeUrlParameters("zoom", false);
    },

    // Make link shareable
    shareable: function () {
      if (!this.get('shareable')) return;
      
      this.get('map').off('zoomend, moveend', this.share_create_link, this);

      // Map options
      if(getUrlParameters('center', '', true) != false){
        var map_ctr = getUrlParameters('center', '', true).split(',');
        var map_zoom = getUrlParameters('zoom', '', true);
        this.get('map').setView([map_ctr[0], map_ctr[1]], map_zoom);
      }
      if(getUrlParameters('bounds', '', true) != false){
        var map_bounds = getUrlParameters('bounds', '', true).split(',');
        this.get('map').fitBounds([
          [map_bounds[0], map_bounds[1]],[map_bounds[2], map_bounds[3]]
        ]);
      }
      if (getUrlParameters('center', '', true) == false && getUrlParameters('bounds', '', true) == false) {
        this.get('map').setView([-28.4792625, 24.6727135], 5, {animate: false});
        this.center();
      };

      // Category option
      if(getUrlParameters('category', '', true) != false){
        $( '*[data-cat-id="'+getUrlParameters('category', '', true)+'"]' ).trigger( 'click' );
      }

      // Create link
      this.get('map').on('zoomend, moveend', this.share_create_link, this);

      // On window hashchange
      $(window).on('hashchange', $.proxy( this.shareable, this ));
    },


    // Filter by Category
    filter_by_category: function (cat_id) {
      $('.cat-sel').removeClass('active');
      
      window.location.hash = setUrlParameters('category', cat_id, '', true);

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

