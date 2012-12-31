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
            var user_id     = $('#user-id').attr('value');
            var date        = $('#step-date').attr('value');
            $.ajax({
              type: "POST",
              url: "step/showStepsPreview",
              data: "user_id="+ user_id +"&date="+ date,
              success: function(data){
                $('#preview-step-list').html(data).fadeIn();
              }
            });
            return false;
          }
        });

        /*

        //on page load - load todays steps list (only first time)  
        var user_id = $('#user-id').attr('value');
        var date1 = $('#step-date').attr('value');
        $.ajax({
          type: "POST",
          url: "step/showStepsPreview",
          data: "user_id="+ user_id +"&date="+ date1,
          success: function(data){
            $('#preview-step-list').html(data).fadeIn();
          }
        });


        //show other actions dropdown
        $("#activity-link").click(function(event){
          event.preventDefault();
          $('#activity-list').fadeIn();
        });


        //get the activity serverities as a dropdown and display it
        $("#activity-list").change(function() {
          var activity_id = $('#activity-cat-id').attr('value');
          $.ajax({
            type: "POST",
            url: "activities/same/" + activity_id,
            success: function(data) {
              $('#step-severity').html(data).fadeIn();
            }
          });
          return false;
        });
    
         */





        //submit steps to db via ajax
        $("#submit").click(function(event) {
          event.preventDefault();
          alert('jepp');
          var data = {
            mm_id:       $('#user-id').attr('value'),
            count:       $('#count').attr('value'),
            date:        $('#step-date').attr('value'),
            activity_id: $('#activity_id').attr('value')
          };      
          $.ajax({
            type: "POST",
            url: "http://mm.dev/ajax/includes/savesteps.php",
            data: data,
            success: function(data){
              console.log(data);
              //$('#preview-step-list').html(data).fadeIn();
            }
          });
          return false;
        });



      });  //functions below please

      /*

      function deleteRow(rowId){
        //alert(rowId);
        var date = $('#step-date').attr('value');
         $.ajax({
            type: "POST",
            url: "step/delete/" + rowId +"/showStepsPreview/" + date,
            data: '',
            success: function(data){
              $('#preview-step-list').html(data).show();
            }
          });
          return false;
      }


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


      function updateMessage(message_id){
        var user_id = $('#user-id').attr('value');
        var msg     = $('#message').attr('value');
        var smiley  = $('input:radio:checked').attr('value');
        var date    = $('#step-date').attr('value');
         $.ajax({
            type: "POST",
            url: "message/updatebyid/" + message_id,
            data: "user_id="+ user_id +"&message="+ msg +"&smiley="+ smiley+"&date="+ date,
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
      #datepicker{float:left;}
      .ui-datepicker {width: 14em;}
      #step-data-area{float:left;}
      #count{width:3em;margin:0 0.3em 0 1em;}
      #activity-link{margin:0 1em;}
    </style>




    <div id="dialog" title="Rapportera steg" >
      <div id="datepicker" ></div>
      <div id="step-data-area">
        <form id="submit-steps" method="post">
          <input type="hidden" name="mm-id" id="user-id" value="<?php echo $mmStatus->mm_mid; ?>" />
          <input type="hidden" name="step-date" id="step-date" value="<?php echo date("Y-m-d"); ?>" />
          <input type="text"  name="count" id="count" value="" />steg<a href="#" title="Annan aktivitet" id="activity-link">Annan aktivitet?</a>
          <input type="button" name="submit" id="submit" value="Lägg till" />
          <div id="activity-list" style="display:none;">

            <!--?php echo form_dropdown('activity_ id', $activites_data, '1', 'id="activity-cat-id"'); ?-->
            <input id="activity_id" value="5"/>


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
