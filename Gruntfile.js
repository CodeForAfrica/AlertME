module.exports = function(grunt) {

  //Initializing the configuration object
  grunt.initConfig({

    // Task configuration
    bower_concat: {
      all: {
        dest: 'public/assets/js/_bower.js',
        cssDest: 'public/assets/css/_bower.css',
        exclude: [
          'modernizr'
        ],
        dependencies: {
          'underscore': 'jquery',
          'backbone': 'underscore',
          'jquery-mousewheel': 'jquery',
          'flat-ui': 'bootstrap',
          'font-awesome': 'bootstrap'
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