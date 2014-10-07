$( document ).ready(function() {

  L.mapbox.accessToken = 'pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
  var map = L.mapbox.map('map', 'codeforafrica.ji193j10')
    .setView([-28.4792625, 24.6727135], 5);

  map.scrollWheelZoom.disable();


  var featureLayer = L.mapbox.featureLayer()
    .loadURL('/api/v1/projectsgeojson')
    .addTo(map);

  featureLayer.on('ready', function() {
    map.fitBounds(featureLayer.getBounds());
  });

  featureLayer.on('click', function(e) {
    map.panTo(e.layer.getLatLng());
  });


});
