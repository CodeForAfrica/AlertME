/**
 * Pahali-projects.JS
 * -----------------------------------------------------------------------------
 * 
 * Projects
 *
 */


(function ( $ ) {

  var Pahali_Project = Backbone.Model.extend({

    defaults: {
      'title': '<span class="fa fa-spinner fa-spin"></span> Loading...'
    },

    pull: function(request) {
      data = typeof request.data === 'undefined' ? {} : request.data;
      callback = typeof request.callback === 'undefined'? function () {} : request.callback;
      $.ajax({
        url: "/api/v1/projects/"+this.id,
        data: data,
        callback: callback,
        model: this
      }).done(function(response) {
        var projects = [];
        if (typeof response.project !== 'undefined') {
          project = response.project;
          this.model.set(project);
          this.callback();
        };
      });
    },

    pull_callback: function() {

    }

  });

  var Pahali_Projects = Backbone.Collection.extend({
    model: Pahali_Project,
    url: '/api/v1/projects',

    pull: function(request) {
      data = typeof request.data === 'undefined' ? {} : request.data;
      callback = typeof request.callback === 'undefined'? function () {} : request.callback;
      $.ajax({
        url: "/api/v1/projects",
        data: data,
        callback: callback,
        collection: this
      }).done(function(response) {
        var projects = [];
        if (typeof response.projects !== 'undefined') {
          if (typeof response.projects.current_page !== 'undefined') {
            projects = response.projects.data;
          } else {
            projects = response.projects;
          };

          this.collection.reset();
          this.collection.add(projects);
          this.callback();
        };
        
      });
    },

    parse: function(response) {
      if (typeof response.projects !== 'undefined') {
        if (typeof response.projects.current_page !== 'undefined') {
          var projects = [];
          _.each(response.projects.data, function(project){
            projects.push(project);
          });
          return projects;
        };
        return response.projects;
      };
      return response.projects;
    }
  });

  pahali.project = new Pahali_Project();

  pahali.projects = new Pahali_Projects();


}( jQuery ));

