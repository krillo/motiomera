/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function($) {
  var mm_url = $("#mm_url").html();
  var mm_id = $("#mm_id").html();

  //open report steps dialog on click
  //when dialog closes update stuff..
  $("#mm-report-steps").click(function() {
    $("#mm-dialog").dialog({
      title: "Rapportera steg",
      height: 420,
      width: 680,
      close: function() {
        getStepData();
      }
    });
  });


  $("#mm-progress").hide();
  var table = null;
  var diary = null;
  loadActivityList()
  getUserData();

  $("#mm-datepicker").datepicker({
    firstDay: 1,
    monthNames: ['jan', 'feb', 'mar', 'apr', 'maj', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec'],
    dayNamesMin: ['sön', 'mån', 'tis', 'ons', 'tor', 'fre', 'lör'],
    altField: "#mm-step-date",
    dateFormat: 'yy-mm-dd',
    minDate: new Date(2011, 1, 15), //todo set register date
    maxDate: new Date(),
    onSelect: function(dateText, inst) {  //display steps matrix for selected date
      getUserData();
    }
  });

  function loadActivityList() {
    $.ajax({
      type: "POST",
      url: mm_url + "/ajax/includes/display_activities.php",
      success: function(data) {
        $('#mm-activity-list').html(data);
        $('#mm-activity-list').hide();
      }
    });
  }

  function getUserData() {
    $("#mm-progress").show();
    var data = {
      mm_id: mm_id,
      date: $('#mm-step-date').attr('value')
    };
    $.ajax({
      type: "POST",
      url: mm_url + "/ajax/data/getUserSteps.php",
      data: data,
      async: false,
      dataType: "json",
      success: function(data) {
        clearInputField();
        printTable(data.table);
        printDiary(data.diary);
      }
    });
  }

  function clearInputField() {
    $('#mm-count').val('');
  }

  function printDiary(diary) {
    $("#mm-comment").val(diary.kommentar);
    $("#mm-diary-id").val(diary.id);
    $("#mm-diary h3 span").html(diary.datum);
    if (diary.betyg === 0) {
      for (var i = 1; i < 6; i++) {
        $("#mm-smiley" + i).removeAttr('checked');
      }
    } else {
      $("#mm-smiley" + diary.betyg).attr('checked', 'checked');
    }
    return false;
  }

  function printTable(table) {
    var rows = '';
    $(table).each(function(index, value) {
      var row = '<tr class="' + value[0] + '">';
      row += '<td class="mm-first-cell">' + value[1] + '</td>';
      row += '<td class="mm-activity-cell">' + value[3] + '</td>';
      row += '<td class="">' + value[4] + '</td>';
      row += '<td class="">' + value[5] + '</td>';
      if (value[0].indexOf('mm-sum') >= 0) {
        row += '<td class="">Steg</td>';
      } else {
        row += '<td class=""><div class="mm-delete ui-icon-closethick" id="' + value[6] + '"></div></td>';
      }
      row += '</tr>';
      rows += row;
    });
    $('#mm-step-list-table tbody').html(rows).fadeIn();
    if ($("#mm-step-date-today").val() === $("#mm-step-date").val()) {
      $("#mm-step-list-date").html('idag');
    } else {
      $("#mm-step-list-date").html($("#mm-step-date").val());
    }
    $("#mm-progress").hide();
    return false;
  }


  function submitSteps() {
    $("#mm-progress").show();
    activity_id = $('#mm-activity-cat-id option:selected').val();
    if (activity_id === undefined) {
      activity_id = 5;
    }
    var data = {
      mm_id: mm_id,
      count: $('#mm-count').attr('value'),
      date: $('#mm-step-date').attr('value'),
      activity_id: activity_id
    };
    $.ajax({
      type: "POST",
      url: mm_url + "/ajax/actions/savesteps.php",
      data: data,
      success: function(data) {
        $('#mm-activity-cat-id').val(5);
        $('#mm-activity-list').hide();
        $('#step-severity').hide();
        printTable(data.table);
        printDiary(data.diary);
      }
    });
    return false;
  }



  /************ catch events ********************/

  $('#mm-count').keyup(function(e) {
    e.stopPropagation();
    e.preventDefault();
    if (e.keyCode === $.ui.keyCode.ENTER) {
      submitSteps();
      return false;
    }
    return false;
  });





  //delete activity row
  $(".mm-delete").live('click', function() {
    $("#mm-progress").show();
    var rowId = $(this).attr('id');
    var data = {
      row_id: rowId,
      mm_id: mm_id,
      date: jQuery('#mm-step-date').attr('value')
    };
    jQuery.ajax({
      type: "POST",
      url: mm_url + "/ajax/actions/deletesteps.php",
      data: data,
      success: function(data) {
        printTable(data.table);
        printDiary(data.diary);
      }
    });
    return false;
  });


  //submit steps to db via ajax
  $("#mm-submit").click(function(event) {
    submitSteps();
    return false;
  });


  //save diary to db via ajax
  $("#mm-diary-save").click(function(event) {
    $("#mm-progress").show();
    var data = {
      mm_id: mm_id,
      comment: jQuery('#mm-comment').attr('value'),
      smiley: jQuery('input:radio:checked').attr('value'),
      date: jQuery('#mm-step-date').attr('value')
    };
    $.ajax({
      type: "POST",
      url: mm_url + "/ajax/actions/savediary.php",
      data: data,
      success: function(data) {
        printTable(data.table);
        printDiary(data.diary);
      }
    });
    return false;
  });



  //show other actions dropdown
  $("#mm-activity-link").click(function(event) {
    event.preventDefault();
    $('#mm-activity-list').fadeIn();
  });


  //get the activity serverities as a dropdown and display it
  $("#mm-activity-list").change(function() {
    var data = {
      activity_id: $('#mm-activity-cat-id').attr('value')
    };
    $.ajax({
      type: "POST",
      data: data,
      url: mm_url + "/ajax/includes/display_severity.php",
      success: function(data) {
        $('#step-severity').html(data).fadeIn();
      }
    });
    return false;
  });


  $(".mm-delete-x").click(function(event) {
    var rowId = $(this).attr('id');
    alert(rowId);
  });



  /******************** graph ***********************  
   * Testdata
   * 
   * var steps = null; //[[1, 7500], [2, 8000], [3, 5600], [4, 14000], [5, 9040], [6, 11500], [7, 13000],]; 
   * var average = null; //[[1.3, 8100], [2.3, 8500], [3.3, 9200], [4.3, 8700], [5.3, 8000], [6.3, 7500], [7.3, 9400]];
   * var ticks = null; //[[1.3, "lör 29/12"],[2.3, "sön 30/12"],[3.3, "mån 31/12"], [4.3, "tis 1/1"], [5.3, "ons 2/1"], [6.3, "tor 3/1"], [7.3, "fre 4/1"]];
   * 
   */

  var mm_url = $("#mm_url").html();
  var mm_id = $("#mm_id").html();
  var steps = null;
  var average = null;
  var ticks = null;
  var stats = null;
  var nbr_days = $("#mm-nbr-days").val();
  getStepData();


  function getStepData() {
    nbr_days = $("#mm-nbr-days").val();
    var to_date = $.datepicker.formatDate('yy-mm-dd', new Date());
    var from_date = new Date(new Date().setDate(new Date().getDate() - nbr_days + 1));
    from_date = $.datepicker.formatDate('yy-mm-dd', from_date);
    //is it a profil page listing?
    if ($("title").html() === "Profilsida" && $("#profil_id").html() > 0) {
      mm_id = $("#profil_id").html();
    }
    var data = {
      mm_id: mm_id,
      from_date: from_date,
      to_date: to_date
    };
    $.ajax({
      type: "POST",
      url: mm_url + "/ajax/data/getStepSumPerDay.php",
      data: data,
      dataType: "json",
      async: false,
      success: function(data) {
        steps = data.steps;
        average = data.average;
        ticks = data.ticks;
        stats = data.stats;
        $("#mm-legend-steps").html(stats.steps + ' <strong>steg</strong><br/>' + stats.steps_kcal + ' <strong>kcal</stong>');
        $("#mm-legend-average").html(stats.average + ' <strong>snitt/dag</strong><br/>' + stats.average_kcal + ' <strong>kcal</strong>');
        onDataReceived();
      }
    });
  }


  function onDataReceived() {
    if (nbr_days > 10) {
      ticks = null;
    }
    var plot = $.plot($("#placeholder"),
            [
              {data: steps, bars: {show: true, barWidth: 0.6}, color: "rgba(0, 173, 223, 0.7)"},
              {data: average, lines: {show: true}, points: {show: true}, color: "#306EFF"}
            ],
            {
              xaxis: {ticks: ticks
              },
              grid: {hoverable: true, clickable: true, markings: [
                  {xaxis: {from: 0, to: 1000},
                    yaxis: {from: 7000, to: 11000}, color: "#CCC"},
                  {xaxis: {from: 0, to: 1000},
                    yaxis: {from: 11000, to: 99000}, color: "rgba(251, 185, 23, 0.4)"}]}
            });
  }


  function showTooltip(x, y, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
      position: 'absolute',
      display: 'none',
      top: y + 5,
      left: x + 5,
      border: '1px solid #fdd',
      padding: '2px',
      'background-color': '#fee',
      opacity: 0.80
    }).appendTo("body").fadeIn(200);
  }
  var previousPoint = null;
  $("#placeholder").bind("plothover", function(event, pos, item) {
    $("#x").text(pos.x.toFixed(2));
    $("#y").text(pos.y.toFixed(2));
    if (item) {
      if (previousPoint != item.dataIndex) {
        previousPoint = item.dataIndex;

        $("#tooltip").remove();
        var x = item.datapoint[0].toFixed(2),
                y = item.datapoint[1];//.toFixed(2);

        showTooltip(item.pageX, item.pageY, y);
      }
    }
  });



  /***** catch events *****/
  $("#mm-nbr-days-ok").click(function(event) {
    getStepData();
  });

  $("#mm-nbr-days").change(function(event) {
    getStepData();
  });

  $("#placeholder").bind("plotclick", function(event, pos, item) {
    if (item) {
      $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
      plot.highlight(item.series, item.datapoint);
    }
  });



});
