

var changesMade = false;



function markChangesMade() {
  changesMade = true;
}

function markNoChangesMade() {
   changesMade = false;
}

function makeSortable() {
  $(".group-accept").sortable({
    connectWith: ".group-accept",
    placeholder: "ui-state-highlight",
    
    // Handles changing hidden elements!
    receive: function(event, ui) {
      // Hint at changes made
      markChangesMade();
      
      // The item that was dragged
      item = $(ui.item);
      
      // The new group ID
      newGroupId = item.closest('.group-accept').data('group-id');
      
      // Replaces any form HTML fields, like such,
      //    <input type="hidden" name="[GR_SOMETHING][ab]..." >
      // With
      //    <input type="hidden" name="[GR_123][ab]...">
      item.html(item.html().replace(/GR_[a-zA-Z0-9]*/g, "GR_" + newGroupId));
    }
  });
}

$(function() {
  
  $(window).bind('beforeunload', function() {
    if(changesMade) return "You have unsaved changes. Navigating away from this page will cause you to lose your changes.";
    return null;
  })
  
  makeSortable();
  
  $("#new-group").click(function() {
    template = $("#group-placeholder").html();
    newId = "NEW_" + new Date().getTime();
    template = template.replace(/GR_PLACEHOLDER/g, "GR_" + newId).replace(/id="PLACEHOLDER"/g, 'id="' + newId + '"');
    $("#group-new-group").append(template);
    makeSortable();
    markChangesMade();
    return false;
  });
  
  $(".remove-group-btn").live('click', function() {
    if(confirm("DELETING THIS TEAM WILL DELETE EXISTING REPORTS ASSOCIATED WITH THIS TEAM.\n\nAre you sure you want to delete this team?")) {
      var box = $(this).closest('.student-group-box');
      box.fadeOut('slow', function() {
        box.remove();
      })
      markChangesMade();
    }
  });
  
  $(".remove-student-icon").live('click', function() {
    if(confirm("Are you sure you want to remove this person from this team?\n\nYou will be able to view previous evaluations by this user.")) {
      var box = $(this).closest('.group-student');
      box.fadeOut('slow', function() {
        box.remove();
      });
      markChangesMade();
    }
  });
  
  $("#enroll-form").submit(function() {
    markNoChangesMade();
    // return confirm("Commit changes?");
    return true;
  });
});


// Box only functions
$(function() {
  $(".box-collapsable .box-title i.box-collapse-icon").addClass('icon-chevron-down');
  $(".box-collapsable .box-title i.box-drag-icon").addClass('icon-move');
  $(".box-collapsable .box-title i.box-collapse-icon").live('click', function() {
    parent = $($(this).closest('.box-collapsable'));
    parent.toggleClass('box-collapsed');
    chevron = $(parent.find("i.box-collapse-icon"));
    chevron.toggleClass('icon-chevron-down');
    chevron.toggleClass('icon-chevron-right');
  });
});