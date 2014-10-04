$( document ).ready(function() {

  $('.close-modal').click(function() {
    $('.data-sources #editModal #error').hide();
    $('#edit-config-btn').html(
      '<button type="button" class="btn btn-info btn-embossed btn-wide" id="edit-config">Edit</button>'
    );
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

  //  $( "#data-source-sync" ).click(function() {
  //    window.location.replace("/dashboard/datasources");
  //  });



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
      url: base_url+ajaxurl,
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
      url: base_url+"/api/v1/datasources/"+edit_id,
    }).done(function( response ) {
      window.location.replace("/dashboard/datasources");
    });

  });


  /**
   * Data Source Configure
   * ---------------------
   */

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
      data_source_columns = JSON.parse(config_data.data_source_columns);
      ds_config = JSON.parse(config_data.config);

      var data_source_columns_html = "";
      $.each(data_source_columns, function( index, value ) {
        data_source_columns_html = data_source_columns_html +
          '<span class="label label-primary">'+value+'</span> ';
      });

      var config_status_3_html = '<div class="alert alert-info" role="alert">'+
        '<span class="fui-alert-circle"></span> We are still fetching this data source\'s details...<br/>'+
        '<button class="btn btn-sm btn-link" id="refresh-config">'+
        '<i class="fa fa-refresh"></i> Refresh to check for update</button>'+
      '</div>';
      var config_status_2_html = '<div id="config-screen">'+
        '<div class="alert alert-success" role="alert">'+
          '<span class="fui-alert-circle"></span> This data source is ready for configuriation.<br/>'+
          '<button class="btn btn-sm btn-link" id="new-config">'+
          '<span class="fui-cmd"></i> Configure now</button>'+
        '</div></div>';
      var config_status_0_html = '<div id="config-screen">'+
        '<div class="alert alert-danger" role="alert">'+
          '<span class="fui-alert-circle"></span> This data source has an error. '+
          'Please check that the data is well structured, delete it and add it again.<br/>'+
          '<button class="btn btn-sm btn-link" id="config-del">'+
          '<span class="fui-cmd"></i> Delete data source now</button>'+
          '<button class="btn btn-sm btn-link" id="config-help">'+
          '<span class="fui-question-circle"></i> Check out the help</button>'+
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
          'right': '<p>'+data_source_columns_html+'</p>'
        };
      }

      // Ready to configure
      if (config_data.config_status == 2){
        pre_html['config'] = {
          'left': '<p><b>Configuration</b></p>',
          'right': config_status_2_html
        };
      }

      // Data error
      if (config_data.config_status == 0){
        pre_html['config'] = {
          'left': '<p><b>Configuration</b></p>',
          'right': config_status_0_html
        };
      }

      // Configured
      if (config_data.config_status == 1){

        var ds_cols = data_source_columns;
        config_id = ds_config.config_id;
        config_title = ds_config.config_title;
        config_desc = ds_config.config_desc;
        config_geo_type = ds_config.config_geo_type;
        config_geo_lat = ds_config.config_geo_lat;
        config_geo_lng = ds_config.config_geo_lng;
        config_geo_add = ds_config.config_geo_add;
        config_status = ds_config.config_status;

        var config_data_geo_html = '';
        if (config_geo_type == 'address'){
          config_data_geo_html = '<tr><td>Geo Type</td><td>Address</td></tr>'+
          '<tr><td>Geo Address</td><td>'+ds_cols[config_geo_add]+'</td></tr>';
        }
        if (config_geo_type == 'lat_lng'){
          config_data_geo_html = '<tr><td>Geo Type</td><td>Lat + Lng</td></tr>'+
          '<tr><td>Geo Lat</td><td>'+ds_cols[config_geo_lat]+'</td></tr>'+
          '<tr><td>Geo Lat</td><td>'+ds_cols[config_geo_lng]+'</td></tr>';
        }

        var config_data_html = '<p>Platform required columns and related data source columns:</p>'+
        '<table class="table">'+
          '<thead><tr><th>Platform</th><th>Data Source</th></tr></thead>'+
          '<tbody>'+
            '<tr><td>ID</td><td>'+ds_cols[config_id]+'</td></tr>'+
            '<tr><td>Title</td><td>'+ds_cols[config_title]+'</td></tr>'+
            '<tr><td>Description</td><td>'+ds_cols[config_desc]+'</td></tr>'+
            config_data_geo_html +
            '<tr><td>Status</td><td>'+ds_cols[config_status]+'</td></tr>'+
          '</tbody>'+
        '</table>';

        pre_html['config'] = {
          'left': '<p><b>Configuration</b></p>',
          'right': '<div id="config-screen">'+config_data_html+'</div>'
        };
      }


      var well_html = '';
      for (var key in pre_html) {
        var obj = pre_html[key];
        well_html = well_html + jst_configModal_well(obj.left, obj.right);
      }

      $("#configModal #loading-config").hide();
      $("#configModal .well").html(well_html);

      // $(".well .row .col-sm-9 p").readmore({
      //   speed: 75,
      //   maxHeight: 100,
      //   moreLink: '<a href="#"><small>More</small></a>',
      //   lessLink: '<a href="#"><small>Less</small></a>',
      //   sectionCSS: 'margin-bottom:0;'
      // });

      $('#refresh-config').click(function() {
        displayConfig();
      });
      $('#new-config, #edit-config').click(function() {
        displayConfigEdit();
      });
    });
  }

  function displayConfigEdit() {
    $('#config-edit-error').hide();
    $('#edit-config-btn').html(
      '<button type="button" class="btn btn-primary btn-embossed btn-wide" id="save-config">Save</button>'
    );

    config_sel_cols_html = '<option value="-1">Select column</option>';
    $.each(data_source_columns, function( index, value ) {
      config_sel_cols_html = config_sel_cols_html + '<option value="' + index + '">' + value + '</option>';
    });

    var edit_html = '<div class="alert alert-success">'+
      '<p>Columns configuration for each project \\ row of data.</p>'+
      '<p><b>ID</b> <small><em>(Important! Unique identifier)</em></small><br/>'+
      '<select id="sel_config_id" class="form-control select select-primary select-block mbl">'+
        config_sel_cols_html+
      '</select></p>'+
      '<p><b>Title</b><br/>'+
      '<select id="sel_config_title" class="form-control select select-primary select-block mbl">'+
        config_sel_cols_html+
      '</select></p>'+
      '<p><b>Description</b><br/>'+
      '<select id="sel_config_desc" class="form-control select select-primary select-block mbl">'+
        config_sel_cols_html+
      '</select></p>'+
      '<p><b>Geolocation</b><br/>'+
      'Type: '+
      '<label class="radio radio-geo-type">'+
        '<input type="radio" name="sel_config_geo_type" id="sel_config_geo_type_lat_lng" value="lat_lng" data-toggle="radio" checked="">'+
          'Lat + Lng'+
      '</label>'+
      '<label class="radio radio-geo-type">'+
        '<input type="radio" name="sel_config_geo_type" id="sel_config_geo_type_add" value="address" data-toggle="radio">'+
          'Address'+
      '</label><br/>'+
      '<div id="geo_type">'+
        '<div class="row div_config_geo_lat">'+
          '<div class="col-xs-3">Lat:</div>'+
          '<div class="col-xs-9">'+
            '<select id="sel_config_geo_lat" class="form-control select select-primary mbl">'+
              config_sel_cols_html+
            '</select>'+
        '</div></div><div class="row div_config_geo_lng">'+
          '<div class="col-xs-3">Long:</div>'+
          '<div class="col-xs-9">'+
            '<select id="sel_config_geo_lng" class="form-control select select-primary mbl">'+
              config_sel_cols_html+
            '</select>'+
        '</div></div>'+
        '<div class="row div_config_geo_add" style="display:none;">'+
          '<div class="col-xs-3">Address:</div>'+
          '<div class="col-xs-9">'+
            '<select id="sel_config_geo_add" class="form-control select select-primary mbl">'+
              config_sel_cols_html+
            '</select>'+
        '</div></div>'+
      '</div></p>'+
      '<p><b>Status</b><br/>'+
      '<select id="sel_config_status" class="form-control select select-primary select-block mbl">'+
        config_sel_cols_html+
      '</select></p>'+
      '<p id="config-edit-error" class="text-danger alert alert-danger" style="display:none;"></p>'+
    '</div>';

    $('#config-screen').html(edit_html);

    configSetSelect();

    configOnSelect();

    $("select").select2({dropdownCssClass: 'dropdown-inverse'});
    $(':radio').radiocheck();

    $('#save-config').click(function() {
      saveConfig();
    });
  }

  function configSetSelect () {
    $("#sel_config_id").val(config_id);
    $("#sel_config_title").val(config_title);
    $("#sel_config_desc").val(config_desc);
    $(".radio-geo-type").val(config_geo_type);
    if (config_geo_type== 'lat_lng') {
      $('.div_config_geo_add').hide();
      $('.div_config_geo_lat').show();
      $('.div_config_geo_lng').show();
      $('#sel_config_geo_type_lat_lng').prop('checked',true);
    }
    if (config_geo_type == 'address') {
      $('.div_config_geo_lat').hide();
      $('.div_config_geo_lng').hide();
      $('.div_config_geo_add').show();
      $('#sel_config_geo_type_add').prop('checked',true);
    }

    $("#sel_config_geo_lat").val(config_geo_lat);
    $("#sel_config_geo_lng").val(config_geo_lng);
    $("#sel_config_geo_add").val(config_geo_add);
    $("#sel_config_status").val(config_status);
  }

  function configOnSelect () {

    // ID
    $( "#sel_config_id" ).change(function() {
      config_id = $("#sel_config_id option:selected").val();
    });

    // Title
    $( "#sel_config_title" ).change(function() {
      config_title = $("#sel_config_title option:selected").val();
    });

    // Description
    $( "#sel_config_desc" ).change(function() {
      config_desc = $("#sel_config_desc option:selected").val();
    });

    // Geolocation
    $('.radio-geo-type :radio').on('change.radiocheck', function() {
      // Do something
      if ($(this).val() == 'lat_lng') {
        $('.div_config_geo_add').hide();
        $('.div_config_geo_lat').show();
        $('.div_config_geo_lng').show();
        config_geo_type = 'lat_lng';
      }
      if ($(this).val() == 'address') {
        $('.div_config_geo_lat').hide();
        $('.div_config_geo_lng').hide();
        $('.div_config_geo_add').show();
        config_geo_type = 'address';
      }
    });
    $( "#sel_config_geo_lat, #sel_config_geo_lng, #sel_config_geo_add" ).change(function() {
      if(config_geo_type == 'lat_lng') {
        config_geo_lat = $("#sel_config_geo_lat option:selected").val();
        config_geo_lng = $("#sel_config_geo_lng option:selected").val();
      }
      if(config_geo_type == 'address') {
        config_geo_add = $("#sel_config_geo_add option:selected").val();
      }
    });

    // Status
    $( "#sel_config_status" ).change(function() {
      config_status = $("#sel_config_status option:selected").val();
    });

  }

  function saveConfig() {

    if(config_id == -1 || config_title == -1 || config_desc == -1 || config_status == -1){
      $('#config-edit-error').html('<small><b>Error:</b> Please define all columns.</small>');
      $('#config-edit-error').show();
      return;
    }
    if(config_geo_type == 'lat_lng') {
      if(config_geo_lat == -1 || config_geo_lng == -1){
        $('#config-edit-error').html('<small><b>Error:</b> Please define all columns.</small>');
        $('#config-edit-error').show();
        return;
      }
    }
    if(config_geo_type == 'address') {
      if(config_geo_add == -1){
        $('#config-edit-error').html('<small><b>Error:</b> Please define all columns.</small>');
        $('#config-edit-error').show();
        return;
      }
    }

    var config_final = {
      config_id: config_id,
      config_title: config_title,
      config_desc: config_desc,
      config_geo_type: config_geo_type,
      config_geo_lat: config_geo_lat,
      config_geo_lng: config_geo_lng,
      config_geo_add: config_geo_add,
      config_status: config_status
    };

    var data = {
      id: config_data.id,
      data_source_columns: config_data.data_source_columns,
      config_status: 1,
      config: config_final
    };

    $.ajax({
      type: "PUT",
      async: false,
      url: base_url+"/api/v1/datasourceconfig/"+data.id,
      data: data
    }).done(function( response ) {

    });

    $('#edit-config-btn').html(
      '<button type="button" class="btn btn-info btn-embossed btn-wide" id="edit-config">Edit</button>'
    );

    displayConfig();
  }

  function jst_configModal_well(left, right){
    var html = '<div class="row">'+
      '<div class="col-sm-3 text-right">'+left+'</div>'+
      '<div class="col-sm-9">'+right+'</div>'+
    '</div>';
    return html;
  }


});
