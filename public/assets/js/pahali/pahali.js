/**
 * Pahali.JS
 * -----------------------------------------------------------------------------
 * 
 * The Pahali.JS extension.
 *
 */

var pahali = {
  _name: 'Pahali.JS',
  _description: 'The Pahali.JS extension'
};

(function ( $ ) {

  if (typeof map === 'undefined') {
    // map is undefined
  } else {
    pahali.map = {
      id: 'map',
      center: function () {
        if ($('.map-list').length) {
          map_move_x = -0.5 * (
            $('.map-list').width() +
            parseInt($('.map-list').css('padding-top').replace('px', '')) +
            parseInt($('.map-list').css('padding-bottom').replace('px', ''))
          );
           map.panBy(
            L.point(map_move_x, 0, false),
            {animate: false}
          );
        };
      }
    }
  }

}( jQuery ));

document.write('<script src="/assets/js/pahali/pahali-datasource.js"><\/script>');
