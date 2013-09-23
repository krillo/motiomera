<?php /**
 * Description: The complete code for report stepa
 * 
 * Date: 2013-09-16
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * 
 *
 */ ?>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    var mm_url = $("#mm_url").html();
    var mm_id = $("#mm_id").html();

/*
    //refresh the stepdata
    function refreshData() {
      $.ajax({
        type: "POST",
        url: "user/refreshstepdata/",
        data: '',
        success: function(data) {
          //$('#step-data').html(data).show();
        }
      });
      return false;
    }
*/

    //open report steps dialog on click
    //when dialog closes update stuff..
    $("#mm-report-steps").click(function() {
      //event.preventDefault();
      $("#mm-dialog").dialog({
        title: "Rapportera steg",
        height: 420,
        width: 680,
        close: function() {
          
        //alert('apa');
        //getStepData();
        refreshStepdata();
          if ($.isFunction(window.refreshstepdata)){
          //if ($.isFunction($.fn.getStepData)) {
            refreshStepdata();
          }
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

    function clearInputField(){
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

  });
</script>
<style>

  .ui-widget-header{background-color: #5B7A19;background-image: none;border-color: #384A0E;}
  .ui-widget {font-family: 'Cabin Condensed';font-size: 1.1em;}
  #mm-datepicker{float:left;}
  .ui-datepicker-calendar thead tr th span{color:#333;text-decoration: none;}
  .ui-datepicker {width: 14em;}

  #mm-count{width:3em;margin:0 0.3em 0 0;}
  #submit-steps{margin-bottom: 10px;}
  #submit-steps input, #submit-steps img{display:inline;}
  #mm-activity-link{margin:0 1em;}
  #mm-activity-list, #step-severity{font-size: 14px;margin-top:5px;}
  #preview-step-list{maring-top:15px;font-size: 14px;}
  #motiomera_steg_preview_header {color: #333;background-color: #fff;}
  #motiomera_steg_preview_header td{padding:0 10px 0 10px;}

  #mm-dialog{font-size: 16px; background-color: #fff;}      
  #mm-step-data-area{float:left;margin-left: 15px;width:400px;}

  #mm-step-list{min-width:400px;width:400px;font-size: 12px;}
  #mm-step-list-table{margin:0;width:100%;border-spacing: 0;}
  #mm-step-list-table th{color:#333;text-decoration: none;}
  .mm-activities {
    background-color: #D0E0C7;
    border-radius: 10px 10px 0 0;
    height: 34px;
    margin-top: 2px;
    padding: 0;
    width: 400px;
  }
  .mm-boxtitle {
    color: #FFFFFF;
    font-family: 'Cabin Condensed',sans-serif;
    font-size: 18px;
    font-weight: normal;
    margin: 5px 0 0 15px;
    position: absolute;
  }
  .mm-activities-box {
    background-color: #E0EDD9;
    border-radius: 0 0 10px 10px;
    border-top: medium none;
    padding: 10px 0;
    width: 400px;
    margin-bottom: 10px;
  }     

  .mm-odd {background-color: #D0E0C7;}
  .mm-even {background-color: #E0EDD9;}
  .mm-sum td{border-top:3px solid #5B7A19;height: 2em;vertical-align: middle;}
  th.mm-first-cell, td.mm-first-cell{padding-left: 10px;width:75px;}
  .mm-activity-cell{width:185px;color:#333;}
  #mm-activity-link{text-decoration: underline;}
  #mm-comment{margin:0 0 5px 10px;width:375px; height:40px;}
  .mm-smiley{margin-left:40px;}
  .mm-smiley-first{margin-left:14px;}
  #mm-diary-save{float: right;margin-right: 10px;}
  #mm-progress{margin-left:10px;}
  .mm-delete{background-image: url("/wp-content/themes/motiomera/css/ui-lightness/images/ui-icons_ffffff_256x240.png");width:15px;height:15px;}
  .mm-delete:hover{background-image: url("/wp-content/themes/motiomera/css/ui-lightness/images/ui-icons_ef8c08_256x240.png");width:15px;height:15px;}

  .mm-delete-x{background-image: url("/wp-content/themes/motiomera/css/ui-lightness/images/ui-icons_ffffff_256x240.png");width:15px;height:15px;}
  .mm-delete-x:hover{background-image: url("/wp-content/themes/motiomera/css/ui-lightness/images/ui-icons_ef8c08_256x240.png");width:15px;height:15px;}

  html input[type="button"]{
    border: 1px solid #ccc;
    border-color: #CEE596 #555555 #555555 #CEE596;
    border-radius: 5px;
    background-color: #5A7919;
    cursor: pointer;
    font-size: 14px;
    padding: 0.6em 0.5em 0.6em 0.5em;
    color: #fff;  
  }
  html input[type="button"]:hover{border-color: #555 #CEE596 #CEE596 #555;} /*padding: 0.55em 0.55em 0.65em 0.45em; } */
  
</style>


<div id="mm-dialog" title="Rapportera steg" style="display:none;">
  <div id="mm-datepicker" ></div>
  <div id="mm-step-data-area">
    <form id="submit-steps" method="post">
      <input type="hidden" name="mm-step-date-today" id="mm-step-date-today" value="<?php echo date("Y-m-d"); ?>" />
      <input type="hidden" name="mm-step-date" id="mm-step-date" value="<?php echo date("Y-m-d"); ?>" />
      <input type="text"  name="mm-count" id="mm-count" value="" />steg<a href="#" title="Annan aktivitet" id="mm-activity-link">Annan aktivitet?</a>
      <input type="button" name="mm-submit" id="mm-submit" value="Lägg till" />
      <img id="mm-progress" src="/wp-content/themes/motiomera/img/ajax-loader.gif" alt="" />
      <div id="mm-activity-list" style=""></div>
      <div id="step-severity" style="display:none;"></div>
    </form>
    <div id="mm-step-list" >
      <div class="mm-activities">
        <h3 class="mm-boxtitle">Dina aktiviteter <span id="mm-step-list-date">idag</span></h3>
      </div>  
      <div class="mm-activities-box">
        <table id="mm-step-list-table">
          <thead>
            <tr>
              <th class="mm-first-cell">Datum</th>
              <th class="mm-activity-cell">Aktivitet</th>
              <th class="">Tid</th>
              <th colspan="2" class="">Antal</th>
              <th class=""></th>
            </tr>
          </thead>
          <tbody id="">
          </tbody>
        </table> 
      </div>
    </div>
    <div id="mm-diary" >
      <div class="mm-activities">
        <h3 class="mm-boxtitle">Kommentera dagens motion &nbsp;<span></span></h3>
      </div>  
      <div class="mm-activities-box" style="float:left;">
        <textarea cols="40" rows="2" name="mm-comment" id="mm-comment"></textarea>
        <div style="float:left;width:285px;">
          <img alt="" src="/wp-content/themes/motiomera/img/smileys.png">
          <input type="radio" class="mm-smiley-first" value="1" name="mm-smiley" id="mm-smiley1">
          <input type="radio" class="mm-smiley"       value="2" name="mm-smiley" id="mm-smiley2">
          <input type="radio" class="mm-smiley"       value="3" name="mm-smiley" id="mm-smiley3">
          <input type="radio" class="mm-smiley"       value="4" name="mm-smiley" id="mm-smiley4">
          <input type="radio" class="mm-smiley"       value="5" name="mm-smiley" id="mm-smiley5">
          <input type="hidden" value="" id="mm-diary-id">              
        </div>
        <input type="button" value="Spara" id="mm-diary-save">
      </div>
    </div>
  </div>      
</div>