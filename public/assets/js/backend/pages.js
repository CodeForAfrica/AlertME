$( document ).ready(function() {
  $('.pages-desc').elastic();
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $('.pages-desc').elastic();
  })
});
