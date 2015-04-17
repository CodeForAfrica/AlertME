/**
* Data Source CONFIGURE
* ---------------------
*/



$('[id^=btn-configure-]').click(function() {
  edit_id = $(this).attr('alt');
  configShow();
});


// Modal alert buttons
$('.btn-config-refresh').click(function() {
  configShow();
});
$('#btn-config-new, #btn-config-edit').click(function() {
  configEditShow();
});

// Modal footer buttons
$('#btn-config-save').click(function() {
  configSave();
});


function configShow(){

  $('#config-loading').fadeIn();

  $('.config-columns .alert-info').hide();

  $('.config-edit').hide();
  $('.config-screen').hide();
  $('#config-columns-list').hide();
  $('#btn-config-save').hide();

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

  $('#config-title').html('<a href="'+url_val+'" target="_blank">'+title_val+'</a>');

  $.ajax({
    type: "GET",
    url: pahali.base_url+"/api/v1/datasources/"+edit_id
  }).done(function( response ) {
    pahali.datasource__ = $.extend({}, pahali.datasource);
    pahali.datasource = $.extend({}, response.datasource);
    if (response.datasource.config == null ||
          response.datasource.config == "" ) {
      pahali.datasource.config = $.extend({}, pahali.datasource__.config);
    };

    config_data = response.datasource;

    ds_config = pahali.datasource.config;


    // Columns Alerts

    // Columns being fetched
    if (pahali.datasource.config_status == 3) {
      $('.config-columns .alert-info').fadeIn();
    }

    // Data error
    if (pahali.datasource.config_status == 0) {
      $('.config-columns .alert-danger').fadeIn();
      $('#btn-config-edit').hide();
    }

    // Ready to configure
    if (pahali.datasource.config_status == 2) {
      $('.config-screen .alert-success').fadeIn();
    }


    // Display columns list
    if ( pahali.datasource.config_status != 0 &&
        pahali.datasource.config_status != 3 ) {
      var columns_list_html = "";
      $.each(pahali.datasource.columns, function( index, value ) {
        columns_list_html = columns_list_html +
            '<span class="label label-primary">'+value+'</span> ';
      });
      
      $('.config-screen').fadeIn();

      $('.config-columns-list').html(columns_list_html);
      $('.config-columns-list').show();
      $('#btn-config-edit').show();
    } else {
      $('#config-loading').fadeOut();
      return ;
    }


    // Configured
    if (pahali.datasource.config_status == 1) {

      var ds_cols = pahali.datasource.columns;

      var config_list_geo_html = '';
      if (pahali.datasource.config.geo.type == 'address'){
        config_list_geo_html = '<tr><td>Geo Type</td><td>Address</td></tr>'+
        '<tr><td>Geo Address</td><td>'+ds_cols[pahali.datasource.config.geo.address.col]+'</td></tr>';
      }
      if (pahali.datasource.config.geo.type == 'lat_lng'){
        config_list_geo_html = '<tr><td>Geo Type</td><td>Lat + Lng</td></tr>'+
        '<tr><td>Geo Lat</td><td>'+ds_cols[pahali.datasource.config.geo.lat_lng.lat.col]+'</td></tr>'+
        '<tr><td>Geo Lat</td><td>'+ds_cols[pahali.datasource.config.geo.lat_lng.lng.col]+'</td></tr>';
      }
      

      var config_list_html = '';
      $.each(pahali.datasource.config, function( index, config ) {
        if (index == 'geo') {
          config_list_html += config_list_geo_html;
          return true;
        };
        config_list_html += '<tr><td>' + config.title +
            '</td><td>' + ds_cols[config.col] + '</td></tr>';
      });

      $('.config-list table tbody').html(config_list_html);
      $('.config-list').fadeIn();


    }

    $('#config-loading').fadeOut();
    return;
    
  });
}


function configEditShow() {
  $('.config-screen .alert-ready').fadeOut();

  $('.config-list').fadeOut();
  $('.config-edit').fadeIn();

  $('#config-edit-error').hide();

  $('#btn-config-edit').hide();
  $('#btn-config-save').show();

  var config_sel_cols_html = '<option value="-1">Select column</option>';
  $.each(pahali.datasource.columns, function( index, value ) {
    config_sel_cols_html += '<option value="' + index + '">' + value + '</option>';
  });

  $('.config-edit select').html(config_sel_cols_html);

  configEditSetSelect();

  configEditOnSelect();

  $('select').select2({dropdownCssClass: 'dropdown-inverse'});
  $(':radio').radiocheck();

}


function configEditSetSelect () {
  $("#config-sel-id").val(pahali.datasource.config.id.col);
  $("#config-sel-title").val(pahali.datasource.config.title.col);
  $("#config-sel-desc").val(pahali.datasource.config.desc.col);

  $(".config-radio-geo-type").val(pahali.datasource.config.geo.type);
  if (pahali.datasource.config.geo.type == 'lat_lng') {
    $('.config-edit-geo-type-add').hide();
    $('.config-edit-geo-type-lat-lng').show();
    $('#config-sel-geo-type-lat-lng').prop('checked',true);
  }
  if (pahali.datasource.config.geo.type == 'address') {
    $('.config-edit-geo-type-add').show();
    $('.config-edit-geo-type-lat-lng').hide();
    $('#config-sel-geo-type-add').prop('checked',true);
  }
  $("#config-sel-geo-lat").val(pahali.datasource.config.geo.lat_lng.lat.col);
  $("#config-sel-geo-lng").val(pahali.datasource.config.geo.lat_lng.lng.col);
  $("#config-sel-geo-add").val(pahali.datasource.config.geo.address.col);

  $("#config-sel-status").val(pahali.datasource.config.status.col);
}


function configEditOnSelect () {

  // ID
  $( "#config-sel-id" ).change(function() {
    pahali.datasource.config.id.col = $("#config-sel-id option:selected").val();
  });

  // Title
  $( "#config-sel-title" ).change(function() {
    pahali.datasource.config.title.col = $("#config-sel-title option:selected").val();
  });

  // Description
  $( "#config-sel-desc" ).change(function() {
    pahali.datasource.config.desc.col = $("#config-sel-desc option:selected").val();
  });

  // Geolocation
  $('.config-radio-geo-type :radio').on('change.radiocheck', function() {
    // Do something
    if ($(this).val() == 'lat_lng') {
      $('.config-edit-geo-type-add').hide();
      $('.config-edit-geo-type-lat-lng').show();
      pahali.datasource.config.geo.type = 'lat_lng';
    }
    if ($(this).val() == 'address') {
      $('.config-edit-geo-type-add').show();
      $('.config-edit-geo-type-lat-lng').hide();
      pahali.datasource.config.geo.type = 'address';
    }
  });
  $( "#config-sel-geo-lat, #config-sel-geo-lng, #config-sel-geo-add" ).change(function() {
    if(pahali.datasource.config.geo.type == 'lat_lng') {
      pahali.datasource.config.geo.lat_lng.lat.col = $("#config-sel-geo-lat option:selected").val();
      pahali.datasource.config.geo.lat_lng.lng.col = $("#config-sel-geo-lng option:selected").val();
    }
    if(pahali.datasource.config.geo.type == 'address') {
      pahali.datasource.config.geo.address.col = $("#config-sel-geo-add option:selected").val();
    }
  });

  // Status
  $( "#config-sel-status" ).change(function() {
    pahali.datasource.config.status.col = $("#config-sel-status option:selected").val();
  });

}


function configSave() {

  if(pahali.datasource.config.id.col == -1 ||
      pahali.datasource.config.title.col == -1 ||
      pahali.datasource.config.desc.col == -1 ||
      pahali.datasource.config.status.col == -1){
    $('#config-edit-error').show();
    return;
  }
  if(pahali.datasource.config.geo.type == 'lat_lng') {
    if(pahali.datasource.config.geo.lat_lng.lat.col == -1 ||
        pahali.datasource.config.lat_lng.lng.col == -1){
      $('#config-edit-error').show();
      return;
    }
  }
  if(pahali.datasource.config.geo.type == 'address') {
    if(pahali.datasource.config.geo.address.col == -1){
      $('#config-edit-error').show();
      return;
    }
  }

  pahali.datasource.config_status = 1;

  pahali.datasource._token = pahali.csrf_token;

  $.ajax({
    type: "PUT",
    async: false,
    url: pahali.base_url + "/api/v1/datasources/" + pahali.datasource.id,
    data: pahali.datasource
  }).done(function( response ) {

  });

  $('#btn-config-save').hide();
  $('#btn-config-edit').show();

  configShow();
}
