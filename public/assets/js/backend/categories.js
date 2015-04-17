$( document ).ready(function() {

  $('.close-modal').click(function() {
    $('.categories #editModal #error').hide();
  });

  /**
   * ---------------------------------------------------------------------------
   * CATEGORIES
   * ---------------------------------------------------------------------------
   */


  /**
   * Category ADD
   * ------------
   */

  $( ".category-add" ).click(function() {
    $("#editModalLabel").html('New Category');
    edit_id = 0;

    $("#editModal #title").val('');
    $("#editModal #desc").val('');
    $("#editModal #keywords").val('');
    $("#editModal #keywords-span").html('<input id="keywords" class="tagsinput" data-role="tagsinput"/>');
    $(".tagsinput").tagsinput();
    $("#editModal #icon_url").val('');
  });

  /**
   * Category EDIT
   * ----------------
   */

  $('[id^=edit-category-]').click(function() {
    $("#editModalLabel").html('Edit Category');
    edit_id = $(this).attr('alt');

    var ds_sel = '#category-' + edit_id;
    var title_sel =  ds_sel + ' #title';
    var desc_sel = ds_sel + ' #desc';
    var keywords_sel = ds_sel + ' #keywords';
    var icon_url_sel = ds_sel + ' #icon_url';

    var title_val = $(title_sel).html().trim();
    var desc_val = $(desc_sel).html().trim();
    var keywords_val = $(keywords_sel).html().trim();
    var icon_url_val = $(icon_url_sel).html().trim();

    if (desc_val == '[No Description]') {
      desc_val = '';
    }

    if (keywords_val == '[No Keywords]') {
      keywords_val = '';
    }

    $("#editModal #title").val(title_val);
    $("#editModal #desc").val(desc_val);
    $("#editModal #keywords").val(keywords_val.trim());
    $("#editModal #keywords-span").html(
      '<input id="keywords" class="tagsinput" data-role="tagsinput" value="'+
      keywords_val+'"/>'
    );
    $(".tagsinput").tagsinput();
    $("#editModal #icon_url").val(icon_url_val.trim());

  });

  /**
   * Category SAVE
   * -------------
   */
  $('#category-save').click(function() {
    var title_val = $("#editModal #title").val();
    var desc_val = $("#editModal #desc").val();
    var keywords_val = $("#editModal #keywords").val();
    var icon_url_val = $("#editModal #icon_url").val();

    if (title_val.trim() == '') {
      $('.categories #editModal .alert #error').html(
        '<b>Oops:</b> The title is required.');
      $('.categories #editModal .alert').show();
      return;
    }

    var data = {
      id: edit_id,
      title: title_val,
      desc: desc_val,
      keywords: keywords_val,
      icon_url: icon_url_val,
      '_token': pahali.csrf_token
    };

    var ajaxurl = "/api/v1/categories/" + edit_id;
    var ajaxtype = "PUT";

    if (edit_id == 0) {
      ajaxurl = "/api/v1/categories";
      ajaxtype = "POST";
    }

    $.ajax({
      type: ajaxtype,
      url: pahali.base_url+ajaxurl,
      data: data
    }).done(function( response ) {
      window.location.replace("/dashboard/categories");
    });

  });


  /**
   * Category DELETE
   * ---------------
   */

  $('[id^=del-category-]').click(function() {
    var ds_sel = '#category-' + $(this).attr('alt');
    var title_sel =  ds_sel + ' #title';
    var desc_sel = ds_sel + ' #desc';
    var keywords_sel = ds_sel + ' #keywords';
    var icon_url_sel = ds_sel + ' #icon_url';

    var title_val = $(title_sel).html();
    var desc_val = $(desc_sel).html();
    var keywords_val = $(keywords_sel).html();
    var icon_url_val = $(icon_url_sel).html();
    var img_url_val = icon_url_val;

    if(img_url_val.trim() == '') img_url_val = '/assets/img/icons/svg/retina.svg';

    edit_id = $(this).attr('alt');

    $("#deleteModal #title").html(title_val);
    $("#deleteModal #desc").html(desc_val);
    $("#deleteModal #keywords").html(keywords_val);
    $("#deleteModal #icon_url").html(icon_url_val);
    $("#deleteModal #img_icon_url").attr("src", img_url_val);

  });

  $('#category-delete').click(function() {

    $.ajax({
      type: "DELETE",
      url: pahali.base_url+"/api/v1/categories/"+edit_id,
      data: {'_token': pahali.csrf_token}
    }).done(function( response ) {
      window.location.replace("/dashboard/categories");
    });

  });


});
