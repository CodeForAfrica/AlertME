$( document ).ready(function() {

  $('.close-modal').click(function() {
    $('.data-sources #editModal #error').hide();
    $('#edit-config-btn').html(
      '<button type="button" class="btn btn-info btn-embossed btn-wide" id="edit-config">Edit</button>'
    );
    $("#sync-data-sources").show();
  });

  /**
   * ---------------------------------------------------------------------------
   * DATA SOURCE
   * ---------------------------------------------------------------------------
   */


  /**
   * Data Source SYNC
   * ----------------
   */

  $( "#data-source-sync" ).click(function() {

    $.ajax({
      type: "GET",
      async: false,
      url: pahali.base_url + "/api/v1/datasources"
    }).done(function( response ) {
      data_sources = response.datasources;
    });

    var ds_sync_html = '';

    if (Object.keys(data_sources).length == 0) {
      ds_sync_html = '<div class="alert alert-warning">'+
        '<p><span class="fui-alert-circle"></span> It seems you don\'t have any data sources yet. '+
          '<button type="button" class="btn btn-primary btn-sm">'+
            '<span class="fui-plus"></span> Add</button> '+
          'some now to get started.'+
        '</p>'+
      '</div>';
      $("#sync-data-sources").hide();
      $("#data-sources-sync").html(ds_sync_html);
      return;
    }

    var ds_configs = new Object();
    $.ajax({
      type: "GET",
      async: false,
      url: pahali.base_url+"/api/v1/datasourceconfig"
    }).done(function( response ) {
      ds_configs = response.configs;
    });

    var ds_sync = new Object();
    $.each(data_sources, function( ds_i, ds ) {
      $.each(ds_configs, function( cfg_i, cfg ) {
        if (cfg.data_source_id == ds.id && cfg.config_status == 1) {
          ds_sync_html = ds_sync_html +
            '<li><b><a href="'+ds.url+'" target="_blank">'+ds.title+'</a></b></li>';
        }
      });
    });

    if (ds_sync_html == ''){
      ds_sync_html = '<div class="alert alert-warning">'+
        '<p><span class="fui-alert-circle"></span> <b>Oops</b>: There doesn\'t seem to be any data sources to sync '+
        'at this moment. Please <span class="text-primary"><span class="fui-cmd"></span> '+
        'Configure</span> some to be able to sync data.</p>'+
      '</div>';

      $("#sync-data-sources").hide();
    } else {
      ds_sync_html = '<ol>'+ds_sync_html+'</ol>';
    }

    $("#data-sources-sync").html(ds_sync_html);
  });

  $( "#sync-data-sources" ).click(function() {
    window.location.replace("/dashboard/datasources/sync");
  });



  /**
   * Data Source DISPLAY
   * -------------------
   */

  $( "#data-source-add, #data-source-add-first" ).click(function() {
    $("#editModalLabel").html('New Data Source');
    edit_id = 0;
  });

  $('#save-data-source').click(function() {
    var title_val = $("#editModal #title").val();
    var desc_val = $("#editModal #desc").val();
    var url_val = $("#editModal #url").val();

    if (title_val.trim() == '') {
      $('.data-sources #editModal #error').html('Error: Title is required');
      $('.data-sources #editModal #error').show();
      return;
    }

    if (url_val.trim() == '') {
      $('.data-sources #editModal #error').html('Error: Url is required');
      $('.data-sources #editModal #error').show();
      return;
    }

    var data = {
      id: edit_id,
      title: title_val,
      desc: desc_val,
      url: url_val
    };

    var ajaxurl = "/api/v1/datasources/" + edit_id;
    var ajaxtype = "PUT";

    if (edit_id == 0) {
      ajaxurl = "/api/v1/datasources";
      ajaxtype = "POST";
    }

    $.ajax({
      type: ajaxtype,
      url: pahali.base_url+ajaxurl,
      data: data
    }).done(function( response ) {
      window.location.replace("/dashboard/datasources");
    });

  });

  /**
   * Data Source EDIT
   * ----------------
   */

  $('[id^=edit-data-source-]').click(function() {
    $("#editModalLabel").html('Edit Data Source');
    edit_id = $(this).attr('alt');

    var ds_sel = '#' + 'data-source-' + edit_id;
    var title_sel =  ds_sel + ' #title';
    var desc_sel = ds_sel + ' #desc';
    var url_sel = ds_sel + ' #url';

    var title_val = $(title_sel).html();
    var desc_val = $(desc_sel).html();
    var url_val = $(url_sel).html();

    if (desc_val == '[No Description]') {
      desc_val = '';
    }

    $("#editModal #title").val(title_val);
    $("#editModal #desc").val(desc_val);
    $("#editModal #url").val(url_val);

  });

  /**
   * Data Source DELETE
   * ------------------
   */

  $('[id^=del-data-source-]').click(function() {
    var ds_sel = '#data-source-' + $(this).attr('alt');
    var title_sel =  ds_sel + ' #title';
    var desc_sel = ds_sel + ' #desc';
    var url_sel = ds_sel + ' #url';

    var title_val = $(title_sel).html();
    var desc_val = $(desc_sel).html();
    var url_val = '<a target="_blank" href="'+$(url_sel).html()+'">'+$(url_sel).html()+'</a>';

    edit_id = $(this).attr('alt');

    $("#deleteModal #del_title").html(title_val);
    $("#deleteModal #del_desc").html(desc_val);
    $("#deleteModal #del_url").html(url_val);

  });

  $('#delete-data-source').click(function() {

    $.ajax({
      type: "DELETE",
      url: pahali.base_url+"/api/v1/datasources/"+edit_id,
    }).done(function( response ) {
      window.location.replace("/dashboard/datasources");
    });

  });


  

  function jst_configModal_well(left, right){
    var html = '<div class="row">'+
      '<div class="col-sm-3 text-right">'+left+'</div>'+
      '<div class="col-sm-9">'+right+'</div>'+
    '</div>';
    return html;
  }


});
