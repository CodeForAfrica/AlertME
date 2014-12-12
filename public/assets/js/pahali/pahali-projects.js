/**
 * Pahali-projects.JS
 * -----------------------------------------------------------------------------
 * 
 * Projects
 *
 */


(function ( $ ) {

  var Pahali_Project = Backbone.Model.extend({

  });

  var Pahali_Projects = Backbone.Collection.extend({
    model: Pahali_Project,
    url: '/api/v1/projects',

    parse: function(response) {
      if (typeof response.projects.current === undefined) {
        return response.projects;
      };
      return response.projects.data;
    }
  });

  pahali.project = new Pahali_Project();

  pahali.projects = new Pahali_Projects();


}( jQuery ));

