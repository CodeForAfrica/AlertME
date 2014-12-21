/**
 * Map.js
 * -----------------------------------------------------------------------------
 */

var map;


// Resize the page
function resizeMap () {
  var footerHeight = $('footer').height() +
  parseInt($('footer').css('padding-top').replace('px', '')) +
  parseInt($('footer').css('padding-bottom').replace('px', ''));
  if($('body').height() > (500 +  footerHeight)) {
    // Map loading
    $('.home-map, .map-loading, .home-map .map-wrapper, .home-map .map-list').height($('body').height() - footerHeight );
  } else {
    $('.home-map, .map-loading, .home-map .map-wrapper, .home-map .map-list').height(500);
  }
}
resizeMap();
$(window).resize(function(){
  resizeMap();
});


// Create map
L.mapbox.accessToken = 'pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
map = L.mapbox.map('map', 'codeforafrica.ji193j10',{
  zoomAnimationThreshold: 10,
  maxZoom: 15,
  zoomControl: false,
  attributionControl: false
}).setView([-28.4792625, 24.6727135], 5);
map.scrollWheelZoom.disable();

pahali.map.set({'map': map});


// Markers
var markers;
var markers_arr = [];


// On Document Ready
$( document ).ready(function() {
  
  // Map controls
  $('#map-ctrl-zoom-in').click(function () {
    pahali.map.get('map').zoomIn();
  });
  $('#map-ctrl-zoom-out').click(function () {
    pahali.map.get('map').zoomOut();
  });

  // Overlapping markers
  markers = new L.MarkerClusterGroup({
    showCoverageOnHover: false
  });
  pahali.map.set({'markers': markers});
  pahali.map.get('map').addLayer(pahali.map.get('markers'));



  /**
   * Load Markers
   * ---------------------------------------------------------------------------
   */

  var bounds = pahali.map.get('map').getBounds();
  var bound = bounds._southWest.lat + "," + bounds._northEast.lat + "," +
    bounds._southWest.lat + "," + bounds._northEast.lng;

  function addMarker (project) {
    var loc = new L.LatLng(
      project.get('geo_lat'),
      project.get('geo_lng')
    );
    var marker = new L.Marker(loc);
    var marker_html = '<h6>'+project.get('title')+'</h6>'+
        '<small><a href="/project/'+project.get('id')+'" target="_blank">'+
        'Learn more <span class="fui-arrow-right"></span></a></small>';

    marker.title = project.get('title');
    marker.alt = project.get('id');

    marker.on('popupopen', function () {
      if (typeof project.get('data') === 'undefined') {
        project.pull({
          callback: function() {
            // Update the marker pop up
            marker_html = '<h6>'+project.get('title')+'</h6>'+
              '<small><a href="/project/'+project.get('id')+'" target="_blank">'+
              'Learn more <span class="fui-arrow-right"></span></a></small>';
            marker.setPopupContent(marker_html);
          }
        });
      };
    });

    marker.bindPopup(marker_html);

    marker.on('mouseover', function () {
      this.openPopup();
    });

    project.set({'marker': marker});

    pahali.map.get('markers').addLayer(project.get('marker'));
    
  }

  _.each(pahali.projects.models, function (project) {
    addMarker(project);
  });

});
