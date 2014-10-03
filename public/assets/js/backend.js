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

    var title_html = '<a href="'+url_val+'" target="_blank">'+title_val+'</span></a>';

    var well_html = "";
    var well_details = {
      'datasource': {
        'left': 'Data Source',
        'right': title_html
      }
    };

    for (var key in well_details) {
      var obj = well_details[key];
      well_html = well_html + jst_configModal_well(obj.left, obj.right);
    }

    loading_html = '<p class="text-center" id="loading-config">'+
    '<i class="fa fa-circle-o-notch fa-spin"></i><br/>Loading configuration...</p>';

    $("#configModal .well").html(well_html+loading_html);

    $.ajax({
      type: "GET",
      url: base_url+"/api/v1/datasourceconfig/"+edit_id
    }).done(function( response ) {
      var config = response.config;

      well_details = {
        'columns': {
          'left': 'Columns',
          'right': '<small>'+config.data_source_columns+'</small>'
        }
      };

      for (var key in well_details) {
        var obj = well_details[key];
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
    });

  });

  function jst_configModal_well(left, right){
    var html = '<div class="row">'+
      '<div class="col-sm-3">'+
        '<p><b>'+left+'</b></p>'+
      '</div>'+
      '<div class="col-sm-9">'+
        '<p>'+right+'</p>'+
      '</div>'+
    '</div>';
    return html;
  }


});
