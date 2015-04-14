/**
 * Project Alert Subscription
 * ---------------------------------------------------------------------------
 */


// On Document Ready
$(document).ready(function () {

  /**
   * ActNOW
   * -----------------------------------------------------------------------------------------------------------------*/


  // Modal Controls
  function alertsReset() {
    $('.subscription-email').removeClass('has-error');
    $('#subscription-email').attr('disabled', false);

    $('#subscriptionModal .alert-info').fadeOut();
    $('#subscriptionModal .alert-success').fadeOut();
    $('#subscriptionModal .alert-warning').fadeOut();
    $('#subscriptionModal .alert-danger').fadeOut();

    $('.subscribe-btn').removeClass('disabled');
  };

  $('#subscriptionModal').on('shown.bs.modal', function () {
    alertsReset();
  });

  // Modal View Controls
  $('.subscribe-btn').click(function () {
    alertsReset();
    // Check e-mail
    if ($('#subscription-email').val().trim() == '') {
      $('.subscription-email').addClass('has-error');
      $('#subscriptionModal .alert-danger').fadeIn();
      $('#subscriptionModal .alert-danger .msg-error.email').fadeIn();
      return;
    }
    if (!isEmail($('#subscription-email').val())) {
      $('.subscription-email').addClass('has-error');
      $('#subscriptionModal .alert-danger').fadeIn();
      $('#subscriptionModal .alert-danger .msg-error.email').fadeIn();
      return;
    }
    // Subscription start
    $('#subscriptionModal .alert-info').fadeIn();
    $('.subscribe-btn').addClass('disabled');
    $('#subscription-email').attr('disabled', true);

    pahali.subscribe.callback = function () {

      response = pahali.subscribe.get('response');
      if (typeof response.error === 'function') {
        response = pahali.subscribe.get('response').responseJSON;
      }
      ;

      if (response.status == 'OK') {
        $('#subscriptionModal .alert-success').fadeIn();
      }
      ;
      if (response.status == 'OVER_LIMIT') {
        $('#subscriptionModal .alert-danger').fadeIn();
        $('#subscriptionModal .alert-danger .msg-error.limit').fadeIn();
      }
      ;
      if (response.error) {
        var validator = response.validator;
        if (validator.email) {
          $('#subscriptionModal .alert-danger').fadeIn();
          $('#subscriptionModal .alert-danger .msg-error.email').fadeIn();
          $('#map-alert-email').attr('disabled', false);
          $('.map-alert-email').addClass('has-error');
          $('.create-alert-btn').removeClass('disabled');
        }
        ;
        if (validator.bounds) {
          $('#subscriptionModal .alert-danger').fadeIn();
          $('#subscriptionModal .alert-danger .msg-error.reload').fadeIn();
        }
        ;
        if (validator.confirm_token) {
          $('#subscriptionModal .alert-warning').fadeIn();
          $('#subscriptionModal .alert-warning .msg-error.duplicate').fadeIn();
        }
        ;
      }
      ;

      $('#subscriptionModal .alert-info').fadeOut();
    };

    pahali.subscribe.project(project_id, $('#subscription-email').val());

  });

});
