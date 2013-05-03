

/**
 * Creates a confirmation modal
 */
function confirmModal(text, confirmCallback, cancelCallback) {
  $("#confirm-modal-body").html(text);
  
  var cancelC = cancelCallback;
  
  // Set Callbacks
  $("#confirm-modal-ok").click(confirmCallback);
  $("#confirm-modal-cancel").click(function() {
    $("#confirm-modal").modal('hide');
    if(cancelCallback) cancelCallback();
  });
  
  $("#confirm-modal").modal('show');
}

$(function() {
  $("#flashes").delay(7000).fadeOut();
  
  $("a[data-confirm]").live('click', function() {
    var link = $(this);
    var text = link.data('confirm');
    console.log('Doing Something!');
    if(text) {
      console.log('Clicking!');
      confirmModal(text, function() {
        link.data('confirm', false);
        link.click();
      });
    
      return false;
    }
    
    return true;
  });
});