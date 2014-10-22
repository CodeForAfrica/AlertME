/**
 * Map Categories Javascript File
 * -----------------------------------------------------------------------------
 */

var projects_categories;

 // On Document Ready
$( document ).ready(function() {

  $('.cat-all').addClass('active');

  $('.cat-sel').click(function (){
    category_select( $(this).attr('data-cat-id') );
  });

  function category_select (id) {
    $('.cat-sel').removeClass('active');

    var markers_sel = [];
    
    if (id == 'all') {
      $.each(markers_arr, function( index, marker_arr ) {
        markers_sel.push(marker_arr);
      });
    } else {
      $.each(projects_categories, function( index, projects_category ) {
        if (projects_category.category_id == id) {
          $.each(markers_arr, function( index, marker_arr ) {
            if (marker_arr.id == projects_category.project_id) {
              markers_sel.push(marker_arr);
            };
          });
        };
      });
    }

    map.removeLayer(markers);
    markers = new L.MarkerClusterGroup({
      showCoverageOnHover: false
    });
    $.each(markers_sel, function( index, marker_sel ) {
      markers.addLayer(marker_sel.marker);
    });
    map.addLayer(markers);
  }
  
});