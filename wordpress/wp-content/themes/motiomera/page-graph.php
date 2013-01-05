<?php
/**
  Template Name: Motiomera-graph
 */
/*
  $d = new JDate();
  $d->subDays(6);
  $data['graph'] = $this->m_step->getStepSumPerDayByUserId($user_id, 'VALID', $d->getDate(), date('Y-m-d') );
  $data['average'] = $this->m_step->getAverageStepSumPerDay('VALID', $d->getDate(), date('Y-m-d') );
  $this->load->view('snippets/v_graph', $data);
 */




get_header();
?>
<div id="primary" class="site-content">
  <div id="content" role="main">




    <script type="text/javascript">
      jQuery(document).ready(function($) {
        /*        
        //taday and 6 days back
        var to_date = $.datepicker.formatDate('yy-mm-dd', new Date());
        var from_date = new Date(new Date().setDate(new Date().getDate()-6));
        from_date = $.datepicker.formatDate('yy-mm-dd', from_date);
        //console.log(from_date);
        //console.log(to_date);

        //on page load - load 7 days steps
        var data = {
          mm_id:     $('#user-id').attr('value'),
          from_date: from_date,
          to_date:   to_date
        };              
        $.ajax({
          type: "POST",
          url: "http://mm.dev/ajax/data/getStepSumPerDay.php",
          data: data,
          success: function(data){
            //$('#preview-step-list').html(data).fadeIn();
          }
        });
         */

        /*

         //submit steps to db via ajax
         $("#submit").click(function(event) {
           event.preventDefault();
           //activity_id = $('#activity_id').attr('value');
           activity_id = $('#activity-cat-id option:selected').val(); 
           if(activity_id === undefined){
             activity_id = 5;
           }
           var data = {
             mm_id:       $('#user-id').attr('value'),
             count:       $('#count').attr('value'),
             date:        $('#step-date').attr('value'),
             activity_id: activity_id
           };
          
           $.ajax({
             type: "POST",
             url: "http://mm.dev/ajax/actions/savesteps.php",
             data: data,
             success: function(data){
               $('#preview-step-list').html(data).fadeIn();
               $('#activity-cat-id').val(5);
              
               $('#activity-list').hide();
               $('#step-severity').hide();
              
             }
           });
           return false;
         });

         */


        /************ catch events ********************/
        
        /*       
         //show other actions dropdown
         $("#activity-link").click(function(event){
           event.preventDefault();
           $('#activity-list').fadeIn();
         });        


         //get the activity serverities as a dropdown and display it
         $("#activity-list").change(function() {
           var data = {
             activity_id: $('#activity-cat-id').attr('value')
           };
           $.ajax({
             type: "POST",
             data: data,
             url: "http://mm.dev/ajax/includes/display_severity.php",
             success: function(data) {
               $('#step-severity').html(data).fadeIn();              
             }
           });
           return false;
         });

         */
 


      });
    </script>










    <input type="hidden" name="mm-id" id="user-id" value="<?php echo $mmStatus->mm_mid; ?>" />
    <div class="grid_8">
      <div id="placeholder" style="width:520px;height:300px"></div>
         <!-- p><input id="enableTooltip" type="checkbox">Enable tooltip</p -->



      <script type="text/javascript">
        jQuery(function ($) { 
          var steps = null; //[[1, 7500], [2, 8000], [3, 5600], [4, 14000], [5, 9040], [6, 11500], [7, 13000],]; 
          var average = null; //[[1.3, 8100], [2.3, 8500], [3.3, 9200] ];   //, [4.3, 8700], [5.3, 8000], [6.3, 7500], [7.3, 9400]];
          var ticks = null; //[[1.3, "lör 29/12"],[2.3, "sön 30/12"],[3.3, "mån 31/12"], [4.3, "tis 1/1"], [5.3, "ons 2/1"], [6.3, "tor 3/1"], [7.3, "fre 4/1"]];

          //today and 6 days back
          var to_date = $.datepicker.formatDate('yy-mm-dd', new Date());
          var from_date = new Date(new Date().setDate(new Date().getDate()-6));
          from_date = $.datepicker.formatDate('yy-mm-dd', from_date);
          //console.log(from_date);
          //console.log(to_date);

          //on page load - load 7 days steps
          var data = {
            mm_id:     $('#user-id').attr('value'),
            from_date: from_date,
            to_date:   to_date          
          };              
          apa = $.ajax({
            type: "POST",
            url: "http://mm.dev/ajax/data/getStepSumPerDay.php",
            data: data,
            dataType: "json",
            async: false,
            success: function(data){
              steps =  data.steps;
              average =  data.average;
              ticks = data.ticks;
              onDataReceived();
            }
            
          });
          
          
          
          
          
          function onDataReceived(){
            var plot =  $.plot($("#placeholder"),
            [
              {label: "Dina steg", data: steps, bars: { show: true, barWidth: 0.6 }, color: "rgba(0, 173, 223, 0.7)"},
              {label: "Medel samtliga deltagare", data: average, lines: { show: true }, points: { show: true }, color: "#306EFF"}
            ],
            {
              xaxis: {ticks: ticks 
              },
              grid: {hoverable: true, clickable: true, markings: [ 
                  { xaxis: { from: 0, to: 1000 },
                    yaxis: { from: 7000, to: 11000 }, color: "#CCCCCC" },
                  { xaxis: { from: 0, to: 1000 },
                    yaxis: { from: 11000, to: 99000 }, color: "rgba(251, 185, 23, 0.4)" } ]}
            });

          }
          
          

          function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css( {
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
          $("#placeholder").bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            //if ($("#enableTooltip:checked").length > 0) {
            if (item) {
              if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                y = item.datapoint[1];//.toFixed(2);

                showTooltip(item.pageX, item.pageY, y);
              }
              /*}
            else {
              $("#tooltip").remove();
              previousPoint = null;
            }
               */
            }
          });

          $("#placeholder").bind("plotclick", function (event, pos, item) {
            if (item) {
              $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
              plot.highlight(item.series, item.datapoint);
            }
          });





        });
      </script>  
    </div>    

















  </div><!-- #content -->
</div><!-- #primary .site-content -->
<div class="clear"></div>
<?php includeSnippet("inc_page_promo_footer.php"); ?>
<?php get_footer(); ?>
