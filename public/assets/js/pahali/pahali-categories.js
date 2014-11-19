/**
 * Pahali-map.JS
 * -----------------------------------------------------------------------------
 * 
 * Map
 *
 */


(function ( $ ) {
  
  var Pahali_Category = Backbone.Model.extend({

  });

  var Pahali_Categories = Backbone.Collection.extend({
    model: Pahali_Category
  });

  pahali.categories = new Pahali_Categories;

}( jQuery ));

