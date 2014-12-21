/**
 * Map Categories Javascript File
 * -----------------------------------------------------------------------------
 */

var projects_categories;

 // On Document Ready
$( document ).ready(function() {

  $('.cat-all').addClass('active');

  $('.cat-sel').click(function (){
    pahali.map.filter_by_category( $(this).attr('data-cat-id'), pahali.categories, pahali.projects );
  });
  
});