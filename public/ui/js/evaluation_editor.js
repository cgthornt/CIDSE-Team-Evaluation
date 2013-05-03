
function csrfToken() {
  return $("meta[name='CSRF_TOKEN']").attr('content');
}


function updateQuestionOrder(order) {
  notifyUpdating();
  $.ajax({
    type: 'POST',
    url:  $("#evaluation-id").data('sort-url'),
    data: { "YII_CSRF_TOKEN" : csrfToken(), order: order},
    success: function() { notifySuccess(); },
    error: function()   { notifyError(); }
  
  });
}

function updateQuestionOrderAutomagic() {
  var order = [];
  $("#question-list").find("li.question-item").each(function(index, element) {
    order.push($(element).data('id'));
  });
  updateQuestionOrder(order);
}

function makeSortable() {
  $("#question-list").sortable({
    // containment: "parent",
    placeholder: "question-hover-placeholder",
    update: function(event, ui) {
      // Get IDs and convert to an order
      var order = [];
      $("#question-list").find("li.question-item").each(function(index, element) {
        order.push($(element).data('id'));
      });
      updateQuestionOrder(order);
    }
  });
}

// Let the user know changes (AJAX) are being saved. Text defaults to "Saving Changes..."
function notifyUpdating(text) {
  if(!text) text ="Saving Changes...";
  info = $("#status-holder");
  info.removeClass('alert-info alert-success');
  info.html(text);
}

function notifySuccess(text) {
  if(!text) text = "Changes saved successfully.";
  info = $("#status-holder");
  info.removeClass('alert-info alert-success');
  info.addClass('alert-success');
  info.html(text);
}

function notifyError(text) {
  if(!text) text = "Unable to save changes! Please reload the page.";
  info = $("#status-holder");
  info.removeClass('alert-info alert-success');
  info.addClass('alert-error');
  info.html(text);
}

function getEvaluationId() {
  return $("#evaluation-id").data('id');
}

function getQuestionViewUrl(questionId) {
  return $("#evaluation-id").data('view-url').replace(/QUESTION_ID/g, questionId);
}

function getQuestionDeleteUrl(questionId) {
  return $("#evaluation-id").data('delete-url').replace(/QUESTION_ID/g, questionId);
}

function setLoading(selector) {
  selector.html($("#loading-placeholder").html());
}

function updateBox(boxSelector) {
  var requestUrl = getQuestionViewUrl(boxSelector.data('id'));
  var theBox = boxSelector;
  setLoading(boxSelector);
  notifyUpdating();
  $.ajax({
    type: 'GET',
    url: requestUrl,
    success: function(data) {
      theBox.replaceWith(data);
      notifySuccess();
    },
    error: function() { notifyError(); }
  })
}

function createQuestion(questionType) {
  
  // Append something
  var tmpId = new Date().getTime() + "_TMP";
  $("#question-list").append('<li class="question-item" data-id="' + tmpId + '" id="question-' + tmpId + '">' + $("#loading-placeholder").html() + '<li>');
  notifyUpdating();
  $.ajax({
    type: 'post',
    url: $("#evaluation-id").data('new-url'),
    data: {'questionType' : questionType, "YII_CSRF_TOKEN" : csrfToken()},
    success: function(data) {
      $("#question-" + tmpId).replaceWith(data);
      makeSortable();
      updateQuestionOrderAutomagic(); // Make sure to update order!
      notifySuccess();
    }, error: function() { notifyError(); }
  })
}


$(function() {
  
  $(".possible-question").click(function() {
    type = $(this).data('type');
    createQuestion(type);
  });
  
  $("a.editor-close-btn").live('click', function() {
    $(this).closest('.question-config').modal('hide');
    updateBox($(this).closest('.question-item'));
    return false;
  });
  
  
  makeSortable();
  
  $('[rel="tooltip"]').tooltip();
  
  /*
  $(".possible-question").draggable({
    revert: true
  });
  
  $("#question-list").droppable({
    accept: ".possible-question",
    activeClass: "holder-will-accept",
    // hoverClass: "ui-state-highlight",
    drop: function(event, ui) {
      target = $(this);
      sender = $(ui.draggable);
      type   = sender.data('type');
      // Get the placeholder html and add a new item
      placeholder = $("#type-placeholder-" + type);
      target.append(placeholder.html());
      // makeSortable() ;
    }
  });
   */
  
  $('.remove-question-btn').live('click', function() {
    if(confirm("Are you sure you want to remove this question?")) {
      var element = $(this).closest('.question-item');
      deleteUrl  = getQuestionDeleteUrl(element.data('id'));
      
      notifyUpdating();
      $.ajax({
        type: 'POST',
         url: deleteUrl,
        data: {"YII_CSRF_TOKEN" : csrfToken()},
        success: function() { notifySuccess(); },
        error:   function() { notifyError(); }
      });
      
      
      element.fadeOut('slow', function() {
        element.remove();
      });
    }
  });
  
  
  $('.config-question-btn').live('click', function() {
    id = $(this).closest('.question-item').data('id');
    console.log(id);
    $("#question-config-" + id).modal({
      show: true
    });
  });
  
  $('.update-questions-form').live('submit', function() {
    // console.log("Submitting Soon!!!");
    form = $(this);
    var postUrl = form.attr('action');
    var formData = form.serialize();
    var modalBox = $(this).closest('.question-config');
    var originalHtml = modalBox.html();
    var theBox = $(this).closest('.question-item');
    setLoading(modalBox);
    notifyUpdating();
    $.ajax({
      type: 'POST',
      url: postUrl,
      data: formData,
      success: function(data) {
        notifySuccess();
        if(data == "OK") {
          // @todo: alert of success
          modalBox.modal('hide');
          modalBox.html(originalHtml);
          updateBox(theBox);
        } else {
          modalBox.html(data);
        }
      },
      error: function() { notifyError(); }
    });
    
    return false;
  });


});