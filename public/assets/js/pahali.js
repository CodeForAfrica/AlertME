var Pahali = {
  _name: 'Pahali.JS',
  _description: 'The Pahali.JS',
  map: {
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