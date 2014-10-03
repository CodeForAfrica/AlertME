$( document ).ready(function() {

  // Data Sources

  $( "#add-data-source, #add-data-source-first" ).click(function() {
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
      url: base_url+ajaxurl,
      data: data
    }).done(function( response ) {
      window.location.replace("/dashboard/datasources");
    });

  });

  $('#close-editModal, #close_x-editModal').click(function() {
    $('.data-sources #editModal #error').hide();
  });

  // Data Source Edit

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

  // Data Source Delete

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
      url: base_url+"/api/v1/datasources/"+edit_id,
    }).done(function( response ) {
      window.location.replace("/dashboard/datasources");
    });

  });


  // Data source configure

  $('[id^=config-data-source-]').click(function() {
    edit_id = $(this).attr('alt');
    displayConfig();
  });


  function displayConfig(){

    var ds_sel = '#data-source-' + edit_id;
    var title_sel =  ds_sel + ' #title';
    var desc_sel = ds_sel + ' #desc';
    var url_sel = ds_sel + ' #url';

    var title_val = $(title_sel).html();
    var desc_val = $(desc_sel).html();
    var url_val = $(url_sel).html();

    if (desc_val == '[No Description]') {
      desc_val = '';
    }

    var title_html = '<p><a href="'+url_val+'" target="_blank">'+title_val+'</span></a></p>';

    var pre_html = {
      'datasource': {
        'left': '<p><b>Data Source</b></p>',
        'right': title_html
      }
    };

    var datasource_html = jst_configModal_well(
      pre_html['datasource'].left,
      pre_html['datasource'].right
    );

    loading_html = '<p class="text-center" id="loading-config">'+
    '<i class="fa fa-circle-o-notch fa-spin"></i><br/>Loading configuration...</p>';

    $("#configModal .well").html( datasource_html + loading_html );

    $.ajax({
      type: "GET",
      url: base_url+"/api/v1/datasourceconfig/"+edit_id
    }).done(function( response ) {
      config_data = response.config;

      var config_status_3_html = '<div class="alert alert-info" role="alert">'+
        '<span class="fui-alert-circle"></span> We are still fetching this data source\'s details...<br/>'+
        '<button class="btn btn-sm btn-link" id="refresh-config">'+
        '<i class="fa fa-refresh"></i> Refresh to check for update</button>'+
      '</div>';
      var config_status_2_html = '<div class="config-screen">'+
        '<div class="alert alert-success" role="alert">'+
          '<span class="fui-alert-circle"></span> This data source is ready for configuriation.<br/>'+
          '<button class="btn btn-sm btn-link" id="new-config">'+
          '<span class="fui-cmd"></i> Configure now</button>'+
        '</div></div>';

      // Columns being fetched
      if (config_data.config_status == 3){
        pre_html['columns'] = {
          'left': '<p><b>Columns</b></p>',
          'right': config_status_3_html
        };
      } else {
        pre_html['columns'] = {
          'left': '<p><b>Columns</b></p>',
          'right': '<p><small>'+config_data.data_source_columns+'</small></p>'
        };
      }

      // Ready to configure
      if (config_data.config_status == 2){
        pre_html['config'] = {
          'left': '<p><b>Configuration</b></p>',
          'right': config_status_2_html
        };
      }

      var well_html = '';
      for (var key in pre_html) {
        var obj = pre_html[key];
        well_html = well_html + jst_configModal_well(obj.left, obj.right);
      }

      $("#configModal #loading-config").hide();
      $("#configModal .well").html(well_html);

      $(".well .row .col-sm-9 p").readmore({
        speed: 75,
        maxHeight: 100,
        moreLink: '<a href="#"><small>More</small></a>',
        lessLink: '<a href="#"><small>Less</small></a>',
        sectionCSS: 'margin-bottom:0;'
      });

      $('#refresh-config').click(function() {
        displayConfig();
      });
      $('#new-config, #edit-config').click(function() {
        displayConfigEdit();
      });
    });
  }

  function displayConfigEdit(){

  }

  function jst_configModal_well(left, right){
    var html = '<div class="row">'+
      '<div class="col-sm-3 text-right">'+left+'</div>'+
      '<div class="col-sm-9">'+right+'</div>'+
    '</div>';
    return html;
  }


});
