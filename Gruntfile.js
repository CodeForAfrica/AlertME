module.exports = function(grunt) {

  //Initializing the configuration object
  grunt.initConfig({

    // Task configuration
    bower_concat: {
      all: {
        dest: 'public/assets/js/_bower.js',
        cssDest: 'public/assets/css/_bower.css',
        exclude: [
          'modernizr', 'leaflet.markercluster'
        ],
        dependencies: {
          'underscore': 'jquery',
          'backbone': 'underscore',
          'jquery-mousewheel': 'jquery',
          'jquery-ui': 'jquery',
          'jquery-ui-touch-punch': ['jquery', 'jquery-ui'],
          'bootstrap': ['jquery', 'jquery-ui', 'jquery-ui-touch-punch'],
          'flat-ui': ['bootstrap', 'jquery', 'jquery-ui', 'jquery-ui-touch-punch'],
          'font-awesome': 'flat-ui'
        },
        bowerOptions: {
          relative: false
        }
      },
      leaflet: {
        dest: 'public/assets/js/_bower.leaflet.js',
        cssDest: 'public/assets/css/_bower.leaflet.css',
        include: [
          'leaflet.markercluster'
        ],
        mainFiles: {
          'leaflet.markercluster': [
            'dist/leaflet.markercluster-src.js',
            'dist/MarkerCluster.css',
            'dist/MarkerCluster.Default.css'
          ]
        },
        bowerOptions: {
          relative: false
        }
      }
    }
  });

  // Plugin loading
  grunt.loadNpmTasks('grunt-bower-concat');

  // Task definition

};