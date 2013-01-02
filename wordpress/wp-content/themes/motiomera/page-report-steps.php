<?php
/**
  Template Name: Motiomera-report-steps
 */
get_header();
?>
<div id="primary" class="site-content">
  <div id="content" role="main">


    <script type="text/javascript">
      jQuery(document).ready(function($) {

        //refresh the stepdata
        function refreshData(){
          alert("cepa");
          $.ajax({
            type: "POST",
            url: "user/refreshstepdata/",
            data: '',
            success: function(data){
              $('#step-data').html(data).show();
            }
          });
          return false;
        }


        //open report steps dialog on click
        //when dialog closes update stuff..
        $("#btn-report-dialog").click(function(){
          $("#dialog").dialog({
            title: "Rapportera steg",
            height: 440,
            width: 800,
            close: function() {alert("apa"); refreshData(); }
          });
        });



      });
    </script>


    <div class="grid_3">
      <button id="btn-report-dialog">Rapportera Steg</button>
    </div>    




    <script type="text/javascript">
      jQuery(document).ready(function($) {
    
        //add the datepicker
        $("#datepicker").datepicker({
          firstDay: 1,
          monthNames: ['jan','feb','mar','apr','maj','jun','jul','aug','sep','okt','nov','dec'],
          dayNamesMin: [ 'sön', 'mån', 'tis', 'ons', 'tor', 'fre', 'lör'],
          altField: "#step-date",
          dateFormat: 'yy-mm-dd',
          minDate: new Date(2011, 1, 15),  //todo set register date
          maxDate: new Date(),
          onSelect: function(dateText, inst) {  //display steps matrix for selected date
            var data = {
              mm_id:  $('#user-id').attr('value'),
              date:   $('#step-date').attr('value')
            }; 
            $.ajax({
              type: "POST",
              url: "http://mm.dev/ajax/includes/display_step_rows.php",
              data: data,
              success: function(data){
                $('#preview-step-list').html(data).fadeIn();
              }
            });
            return false;
          }
        });


        //on page load - load todays steps list (only first time)  
        var data = {
          mm_id:  $('#user-id').attr('value'),
          date:   $('#step-date').attr('value')
        };              
        $.ajax({
          type: "POST",
          url: "http://mm.dev/ajax/includes/display_step_rows.php",
          data: data,
          success: function(data){
            $('#preview-step-list').html(data).fadeIn();
          }
        });


        //on page load - the activity list
        var data = {date:   $('#step-date').attr('value')};
        $.ajax({
          type: "POST",
          url: "http://mm.dev/ajax/includes/display_activities.php",
          data: data,
          success: function(data){
            $('#activity-list').html(data);
            $('#activity-list').hide();
            
          }
        });


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




        /************ catch events ********************/
        
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


        $("#update-diary").click(function(event){
          event.preventDefault();
          alert('apa');
          var data = {
            mm_id:    $('#user-id').attr('value'),
            comment:  $('#comment').attr('value'),
            smiley:   $('input:radio:checked').attr('value'),
            date:     $('#step-date').attr('value'),
            diary_id: $('#diary-id').attr('value')
          };                    
          $.ajax({
            type: "POST",
            url: "http://mm.dev/ajax/actions/updatediary.php",
            data: data,
            success: function(data){
              $('#preview-step-list').html(data).show();
            }
          });
          return false;
        });



      });  //functions below please



      function deleteRow(rowId){
        var data = {
          row_id:  rowId,          
          mm_id:   jQuery('#user-id').attr('value'),
          date:    jQuery('#step-date').attr('value')
        };            
        jQuery.ajax({
          type: "POST",
          url: "http://mm.dev/ajax/actions/deletesteps.php",
          data: data,
          success: function(data){
            jQuery('#preview-step-list').html(data).show();
          }
        });
        return false;
      }
      
      function updateDiary(diary_id){
        var data = {
          mm_id:    jQuery('#user-id').attr('value'),
          comment:  jQuery('#comment').attr('value'),
          smiley:   jQuery('input:radio:checked').attr('value'),
          date:     jQuery('#step-date').attr('value'),
          diary_id: diary_id //$('#diary-id').attr('value')
        };                    
        jQuery.ajax({
          type: "POST",
          url: "http://mm.dev/ajax/actions/updatediary.php",
          data: data,
          success: function(data){
            jQuery('#preview-step-list').html(data).show();
          }
        });
        return false;
      }      
      

      /*
      function addMessage(){
        var user_id = $('#user-id').attr('value');
        var date    = $('#step-date').attr('value');
        var msg     = $('#message').attr('value');
        var smiley  = $('input:radio:checked').attr('value');
         $.ajax({
            type: "POST",
            url: "message/usercreate",
            data: "user_id="+ user_id +"&date="+ date +"&message="+ msg +"&smiley="+ smiley,
            success: function(data){
              $('#preview-step-list').html(data).show();
            }
          });
          return false;
      }

       */
      /*
      function updateMessage(message_id){
        var data = {
          mm_id:   $('#user-id').attr('value'),
          comment: $('#comment').attr('value'),
          smiley:  $('input:radio:checked').attr('value'),
          date:    $('#step-date').attr('value')
        };                    
         $.ajax({
            type: "POST",
            url: "http://mm.dev/ajax/actions/updatediary.php",
            data: data,
            success: function(data){
              $('#preview-step-list').html(data).show();
            }
          });
          return false;
      }
      
       */
    </script>

    <?php
    global $mmStatus;
//print_r($mmStatus);
    ?>
    <style>
      #dialog{font-size: 16px; background-color: #fff;}
      .ui-widget-header{background-color: #5B7A19;background-image: none;border-color: #384A0E;}
      .ui-widget {font-family: 'Cabin Condensed';font-size: 1.1em;}
      #datepicker{float:left;}
      .ui-datepicker {width: 14em;}
      #step-data-area{float:left;margin-left: 15px;}
      #count{width:3em;margin:0 0.3em 0 0;}
      #submit-steps{margin-bottom: 10px;}
      #activity-link{margin:0 1em;}
      #activity-list, #step-severity{font-size: 14px;margin-top:5px;}
      #preview-step-list{maring-top:15px;font-size: 14px;}
      #motiomera_steg_preview_header {color: #333;background-color: #fff;}
      #motiomera_steg_preview_header td{padding:0 10px 0 10px;}


      .BoxTitle {
        color: #FFFFFF;
        font-size: 18px;
        font-weight: normal;
        margin: 10px 0 0 15px;
        position: absolute;
      }
      .mmAlbumBoxTop {
        background-color: #D0E0C7;    
        height: 34px;
        margin-top: 2px;
        padding: 10px 0 0 10px;
        width: 100%;
      }      
      .mmAlbumBoxTop-first {border-radius: 10px 0 0 0;}
      .mmAlbumBoxTop-last {border-radius: 0 10px 0 0;}
      .odd {background-color: #E0EDD9;}
      .even {background-color: #D0E0C7;}      
    </style>




    <div id="dialog" title="Rapportera steg" >
      <div id="datepicker" ></div>
      <div id="step-data-area">
        <form id="submit-steps" method="post">
          <input type="hidden" name="mm-id" id="user-id" value="<?php echo $mmStatus->mm_mid; ?>" />
          <input type="hidden" name="step-date" id="step-date" value="<?php echo date("Y-m-d"); ?>" />
          <input type="text"  name="count" id="count" value="" />steg<a href="#" title="Annan aktivitet" id="activity-link">Annan aktivitet?</a>
          <input type="button" name="submit" id="submit" value="Lägg till" />
          <div id="activity-list" style="">
          </div>
          <div id="step-severity" style="display:none;"></div>
        </form>
        <div id="preview-step-list" style="display:none;"></div>

      </div>
      <div id="success" style="display: none;">Steps has been added.</div>
    </div>










  </div><!-- #content -->
</div><!-- #primary .site-content -->
<div class="clear"></div>
<?php includeSnippet("inc_page_promo_footer.php"); ?>
<?php get_footer(); ?>
