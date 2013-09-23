<?php /**
 * Description: The complete code for the graph
 * 
 * Date: 2013-09-16
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * 
 * Testdata
 *      var steps = null; //[[1, 7500], [2, 8000], [3, 5600], [4, 14000], [5, 9040], [6, 11500], [7, 13000],]; 
 *      var average = null; //[[1.3, 8100], [2.3, 8500], [3.3, 9200], [4.3, 8700], [5.3, 8000], [6.3, 7500], [7.3, 9400]];
 *      var ticks = null; //[[1.3, "lör 29/12"],[2.3, "sön 30/12"],[3.3, "mån 31/12"], [4.3, "tis 1/1"], [5.3, "ons 2/1"], [6.3, "tor 3/1"], [7.3, "fre 4/1"]];
 *
 */ ?>
<script type="text/javascript">
  jQuery(function($) {
    var mm_url = $("#mm_url").html();
    var mm_id = $("#mm_id").html();
    var steps = null;
    var average = null;
    var ticks = null;
    var stats = null;
    var nbr_days = $("#mm-nbr-days").val();
    getStepData();

function refreshStepdata(){
 alert('apa'); 
}


    function getStepData() {
      nbr_days = $("#mm-nbr-days").val();
      var to_date = $.datepicker.formatDate('yy-mm-dd', new Date());
      var from_date = new Date(new Date().setDate(new Date().getDate() - nbr_days + 1));
      from_date = $.datepicker.formatDate('yy-mm-dd', from_date);

      var data = {
        mm_id: $('#mm_id').html(),
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


    $("#placeholder").bind("plotclick", function(event, pos, item) {
      if (item) {
        $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
        plot.highlight(item.series, item.datapoint);
      }
    });


    /***** catch events *****/
    $("#mm-nbr-days-ok").click(function(event) {
      getStepData();
    });

    $("#mm-nbr-days").change(function(event) {
      getStepData();
    });

  });
</script>  

<style>
  #mm-graph{width:520px; float:left; border-color:#666;}
  #placeholder{width:520px;height:300px;float:left}
  .xAxis{color:#2E4B00;}

  #mm-legend-area{width:500px;background-color: #8AA25A;
                  border-radius: 0 0 10px 10px;
                  border-top: 1px solid #FFFFFF;
                  height: 40px;
                  margin-bottom: 35px;
                  padding: 10px;
                  float:left;
                  color:#fff;
                  font-size: 13px;
  }
  #mm-legend-left{float:left;width:277px;}
  .mm-legend-container-long{float: left;width:175px;}
  .mm-legend-container{float: left;width:100px;}
  .mm-legend{float: left;height: 12px;margin-right: 10px;width: 25px;border:1px solid #fff;}
  .mm-legend-steps{background-color: #46BFE3;}
  .mm-legend-average{background-color: #306EFF;}
  .mm-legend-gold{background-color: #F2D897;}
  .mm-legend-silver{background-color: #CCC;}
  #mm-legend-right{float:right;width:190px;height:50px;}
  #mm-legend-steps{width:90px;float:left;}
  #mm-legend-average{width:90px;float:left;}


  .mmBlueBoxTop {
    background-color: #D0E0C7;
    border-radius: 10px 10px 0 0;
    height: 40px;
    margin: 0;
    padding: 0;
    width: 520px;
  }      
  .BoxTitle {
    color: #FFFFFF;
    font-family: 'Cabin Condensed',sans-serif;
    font-size: 18px;
    font-weight: normal;
    margin: 10px 0 0 15px;
    position: absolute;
  }      
  #mm-nbr-days{width:25px;}
  #mm-nbr-days-ok{padding:3px 12px;}
</style>

<div class="mmBlueBoxTop">
  <h3 class="BoxTitle">Framsteg de senaste <input type="text" id="mm-nbr-days" value="7" /> dagarna <input type="button" value="ok" id="mm-nbr-days-ok"></h3> 
</div>    
<div id="mm-graph">
  <div id="placeholder" ></div>
  <div id="mm-legend-area">
    <div id="mm-legend-left">
      <div class="mm-legend-container-long"><div class="mm-legend mm-legend-steps"></div>Dina steg</div>
      <div class="mm-legend-container"><div class="mm-legend mm-legend-gold"></div>Guldnivå</div>
      <div class="mm-legend-container-long"><div class="mm-legend mm-legend-average"></div>Snitt samtliga deltagare</div>
      <div class="mm-legend-container"><div class="mm-legend mm-legend-silver"></div>Silvernivå</div>
    </div>
    <div id="mm-legend-right">
      <div id="mm-legend-steps"></div>
      <div id="mm-legend-average"></div>
    </div>
  </div>
</div>    