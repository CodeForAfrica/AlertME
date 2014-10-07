$( document ).ready(function() {

  L.mapbox.accessToken = 'pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
  var map = L.mapbox.map('map', 'codeforafrica.ji193j10');

  map.scrollWheelZoom.disable();

  map.featureLayer.on('click', function(e) {
    map.panTo(e.layer.getLatLng());
  });



});
