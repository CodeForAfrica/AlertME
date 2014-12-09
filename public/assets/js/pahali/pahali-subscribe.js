/**
 * Pahali-subscribe.JS
 * -----------------------------------------------------------------------------
 * 
 * Subscription for alerts
 *
 */


(function ( $ ) {
  
  var Pahali_Subscribe = Backbone.Model.extend({

    project: function (project_id, email) {

      this.set({
        'type': 'project',
        'email': email,
        'project_id': project_id,
        'geojson': geojson
      });

      if (!this.validate_email()) return false;

      this.subscribe();

      return true;
    },


    subscribe: function () {
      var data = {
        type: this.get('type'),
        email: this.get('email'),
        geojson: this.get('geojson'),
        
        // Project
        project_id: this.get('project_id'),

        // Map
        bounds: this.get('bounds'),
        center: this.get('center'),
        zoom: this.get('zoom'),
        
        _token: pahali.csrf_token
      };

      $.ajax({
        type: "POST",
        url: "/api/v1/subscriptions",
        data: data
      }).done(function(response) {
        // console.log('SUCCESSFUL: Subscription');
      }).fail(function(response) {
        // console.log('FAILED: Subscription');
      }).always(function(response) {
        pahali.subscribe.set({
          'response': response,
          'subscribed': true
        });
        if (typeof pahali.subscribe.callback === 'function') {
          pahali.subscribe.callback();
        }
      });

    },


    validate_email: function () {
      if( this.get('email').trim() == '' ) {
        return false;
      }
      if(!isEmail(this.get('email'))) {
        return false;
      }
      return true;
    }

  });

  pahali.subscribe = new Pahali_Subscribe({
    'response': {},
    'subscribed': false
  });


}( jQuery ));

