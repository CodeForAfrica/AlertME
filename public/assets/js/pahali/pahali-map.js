/**
 * Pahali-map.JS
 * -----------------------------------------------------------------------------
 *
 * Map
 *
 */


(function ($) {

  var Pahali_Map = Backbone.Model.extend({

    defaults: {
      'categories': {},
      'shareable': true,
      'pahali_changed_hash': false,
      'share_on_hashchange_count': 0,
      'share_on_hashchange_count_max': 1
    },

    initialize: function () {

      // Make Shareable
      this.on('change:map', function (event) {
        if (typeof this.get('map') === 'undefined') return;
        if (!this.get('shareable')) return;

        if (typeof this.get('markers') !== 'undefined') {
          if (!this.get('map').hasLayer(this.get('markers'))) {
            this.get('map').addLayer(this.get('markers'));
          }
          ;
        }
        ;

        this.share_link_process();
        this.share_link_create_enable();

        // On window hashchange e.g back button
        $(window).on('hashchange', $.proxy(this.share_on_hashchange, this));
      });
    },


    /* Shareable Map
     * -------------------------------------------------------------------------
     */

    // Shareable: Process link present
    share_link_process: function () {
      // Map options
      if (getUrlParameters('center', '', true) == false && getUrlParameters('bounds', '', true) == false) {
        this.get('map').setView([-28.4792625, 24.6727135], 5, {animate: false});
        this.center();
      }
      ;
      if (getUrlParameters('center', '', true) != false) {
        var map_ctr = getUrlParameters('center', '', true).split(',');
        var map_zoom = getUrlParameters('zoom', '', true);
        this.get('map').setView([map_ctr[0], map_ctr[1]], map_zoom);
      }
      ;
      if (getUrlParameters('bounds', '', true) != false) {
        var map_bounds = getUrlParameters('bounds', '', true).split(',');
        this.get('map').fitBounds([
          [map_bounds[0], map_bounds[1]], [map_bounds[2], map_bounds[3]]
        ]);
      }
      ;

      // Category option
      if (getUrlParameters('category', '', true) != false) {
        $('*[data-cat-id="' + getUrlParameters('category', '', true) + '"]').trigger('click');
      } else {
        $('*[data-cat-id="all_not_set"]').trigger('click');
      }
      ;
    },

    // Shareable: Create link to enable share
    share_link_create: function () {
      this.set('pahali_changed_hash', true);
      var loc_bounds = this.get('map').getBounds();
      window.location.hash = setUrlParameters(
          'bounds',
          loc_bounds._southWest.lat + ',' + loc_bounds._southWest.lng + ',' +
          loc_bounds._northEast.lat + ',' + loc_bounds._northEast.lng, '',
          false
      );
      window.location.hash = removeUrlParameters('center', false);
      window.location.hash = removeUrlParameters('zoom', false);
    },
    share_link_create_enable: function () {
      this.get('map').on('zoomend, moveend', this.share_link_create, this);
    },
    share_link_create_disable: function () {
      this.get('map').off('zoomend, moveend', this.share_link_create, this);
    },

    // Shareable: When back button pressed
    share_on_hashchange: function () {
      // Making sure it isn't hash change from share_link_create      
      if (this.get('pahali_changed_hash')) return this.set('pahali_changed_hash', false);

      if (getUrlParameters('center', '', true) == false && getUrlParameters('bounds', '', true) == false) {
        this.set('share_on_hashchange_count_max', 1);
      } else {
        this.set('share_on_hashchange_count_max', 0);
      }

      this.share_link_create_disable();

      this.get('map').on('zoomend, moveend', this.share_on_hashchange_end, this);
      this.share_link_process();
    },
    share_on_hashchange_end: function () {
      var count = this.get('share_on_hashchange_count');
      if (count < this.get('share_on_hashchange_count_max')) {
        return this.set('share_on_hashchange_count', count + 1);
      }
      ;
      this.set('share_on_hashchange_count', 0);
      this.get('map').off('zoomend, moveend', this.share_on_hashchange_end, this);
      this.share_link_create_enable();
    },


    // Center map with list consideration
    center: function () {
      // TODO: This should be independent
      if ($('.map-list').length) {
        var map_move_x = -0.5 * (
            $('.map-list').width() +
            parseInt($('.map-list').css('padding-left').replace('px', '')) +
            parseInt($('.map-list').css('padding-right').replace('px', ''))
            );
        this.get('map').panBy(
            L.point(map_move_x, 0, false),
            {animate: false}
        );
      }
      ;
    },


    // Filter by Category
    filter_by_category: function (cat_id, categories, projects) {
      $('.cat-sel').removeClass('active');

      if (cat_id != 'all_not_set') {
        this.set('pahali_changed_hash', true);
        window.location.hash = setUrlParameters('category', cat_id, '', true);
      }
      ;

      var map_markers = this.get('markers');
      map_markers.clearLayers();

      if (cat_id == 'all' || cat_id == 'all_not_set') {
        projects.each(function (project) {
          map_markers.addLayer(project.get('marker'));
        });
      } else {
        $.each(categories.get(cat_id).get('projects_pivot'), function (index, project_id) {
          var project = projects.get(project_id);
          if (typeof project !== 'undefined') {
            map_markers.addLayer(project.get('marker'));
          }
          ;
        });
      }

    }

  });

  pahali.map = new Pahali_Map();

}(jQuery));

