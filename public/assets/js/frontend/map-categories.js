/**
 * Map Categories Javascript File
 * -----------------------------------------------------------------------------
 */


$('.cat-all').addClass('active');

$('.cat-sel').click(function (){
  pahali.map.filter_by_category(
    $(this).attr('data-cat-id'), pahali.categories, pahali.projects
  );
});