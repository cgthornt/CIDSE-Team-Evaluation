// Google Charts
google.load('visualization', '1.0', {'packages':['corechart']});


function changeAlert(container, message, type) {
  alert = $(container.find('.pie-alert'));
  alert.removeClass('alert-info alert-error alert-success');
  if(type) alert.addClass("alert-" + type);
  alert.html(message);
}


function calculateStatistics(container) {
  var statistics = []
  pieBody   = container.find(".pie-body");
  pieBody.find("tr").each(function(index, element) {
    elements = $(element).children();
    personName = elements[0].innerText;
    value      = parseInt($(elements[1]).find('input.spinner-pie').val());
    if(isNaN(value)) value = 0;
    statistics.push([personName, value]);
  });
  
  return statistics;
}


function validateStatistics(container, statistics) {
  precision = 1; // 1 percent percision
  sum = 0;
  for(i = 0; i < statistics.length; i++)
    sum += statistics[i][1];
  
  totalBox = container.find('.total-percent');
  totalBox.html(sum + "%");
    
  if(sum > 100) return "Total percentage cannot be greater than 100%";
  if(sum < 100 - precision) return "Total percentage cannot be less than 100% (with " + precision + "% precision)";
  return true;
}

function drawChart(chartId, statistics) {
  
  if(!statistics) statistics = calculateStatistics($("#" + chartId).closest(".pie-container"));


  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Teammate');
  data.addColumn('number', 'Percent');
  
  data.addRows(statistics);

  // Set chart options
  var options = {
    'title' : 'Graph',
    'width':390,
    'height':250};

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.PieChart(document.getElementById(chartId));
  chart.draw(data, options);
}

function loadAllPieInfo() {
  $("input.spinner-pie").spinner({
    min: 0,
    max: 100,
    change: function(event, ui) {
      container = $(this).closest('div.pie-container');
      chart = container.find('div.percentpie-chart:first')[0];
      stats = calculateStatistics(container);
      message = validateStatistics(container, stats);
      if(message == true) {
        changeAlert(container, "Percentages are correct", "success");
        drawChart(chart.id);
      } else {
        changeAlert(container, message, "error");
      }
    }
  });
  
  $("div.percentpie-chart").each(function(index, element) {
    drawChart(element.id);
  });
}


$(function() {
  loadAllPieInfo();
});

