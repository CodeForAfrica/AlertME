/**
 * Data Source SYNC
 * -----------------------------------------------------------------------------
 */


$( document ).ready(function() {

  var PahaliModat = function () {
    if (pahali.datasources[0] == undefined) {
      $("#btn-sync").hide();
      $(".sync-screen .alert-danger").fadeIn();
      return;
    }

    var sync_list_html = '';
    $.each(pahali.datasources, function( index, datasource ) {
      if (!isNaN(parseInt(index))) {
        if (datasource.config_status == 1){
          sync_list_html += '<li><b><a href="'+datasource.url+'" target="_blank">'+
              datasource.title+'</a></b></li>';
        };
      };
    });

    if (sync_list_html == ''){
      $("#btn-sync").hide();
      $(".sync-screen .alert-warning").fadeIn();
    } else {
      $('.sync-list').html(sync_list_html);
      $('.sync-list').fadeIn();
    }
  };

  $( "#btn-sync-modal" ).click(function() {
    pahali.datasources.pull( PahaliModat() );
  });

  $( "#btn-sync" ).click(function() {
    window.location.replace("/dashboard/datasources/sync");
  });

});

