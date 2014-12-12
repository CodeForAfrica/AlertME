/**
 * Pahali-categories.JS
 * -----------------------------------------------------------------------------
 * 
 * Categories
 *
 */


(function ( $ ) {
  
  var Pahali_Category = Backbone.Model.extend({

  });

  var Pahali_Categories = Backbone.Collection.extend({
    model: Pahali_Category,
    url: '/api/v1/categories',
    
    parse: function(response) {
      return response.categories;
    }
  });

  pahali.categories = new Pahali_Categories;

}( jQuery ));

