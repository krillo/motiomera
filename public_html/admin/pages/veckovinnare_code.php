<?php ?>
<script type="text/javascript" src="/js/jquery-ui-1.8.9.custom.min.js"></script>
<link rel="stylesheet" href="/css/ui-lightness/jquery-ui-1.8.9.custom.css" type="text/css" media="screen" />

<script type="text/javascript">
  $(function(){
    
    //add the datepicker
    $("#datepicker").datepicker({
      firstDay: 1,
      monthNames: ['jan','feb','mar','apr','maj','jun','jul','aug','sep','okt','nov','dec'],
      dayNamesMin: [ 'sön', 'mån', 'tis', 'ons', 'tor', 'fre', 'lör'],
      altField: "#step-date html",
      dateFormat: 'yy-mm-dd',
      minDate: new Date(2011, 1, 15),  //todo set register date
      maxDate: new Date(),
      onSelect: function(dateText, inst) {  //display steps matrix for selected date
        $("#the-date").html(dateText);
        $.ajax({
          type: "POST",
          url: "/api/getActiveCompanys.php",
          data: "&date="+ dateText,
          success: function(data){
            $('#company-list').html(data).fadeIn();
            $('#options').fadeIn();
            
          }
        });
        return false;
      }
    });



    $("#get-winners").click(function(event) {
      var nbrSteps = $("#nbr-steps").val();
      var nbrWinners = $("#nbr-winners").val();
      var thedate = $("#the-date").html();
      //var all = $("#all:checked").val();
      var prevWinners = $("input:radio[name=prev-winners]:checked").val();
      
      
      var comps = [];
      $("input:checkbox[name=company]:checked").each(function() {
        comps.push($(this).val());
      });      
      //alert(comps);

      $.ajax({
        type: "POST",
        url: "/api/getWeekWinners.php",
        data: "&nbr-steps="+ nbrSteps + "&nbr-winners="+ nbrWinners +"&date=" + thedate +"&comps=" + comps + "&prevwinners=" +  prevWinners,
        success: function(data){
          $('#winner-list').html(data).fadeIn();            
        }
      });      
     });


  });
</script>




<div id="veckovinnare">
  Välj en dag i den veckan som du vill dra vinnare.<br/><br/> 
  <div id="datepicker"></div>
  <div name="the-date" id="the-date"><?php echo date("Y-m-d"); ?></div> 
  <div class="clear"></div>
  <br/><br/>
  <div id="company-list"></div>
  <div class="clear"></div>
  <br/>
  <br/>  
  <div id="options">
    <div id="">
      <?php
      $vinnare = '<label for="steg">Antal vinnare</label> <select name="nbr-winners" id="nbr-winners">';
      for ($i = 1; $i <= 50; $i++) {
        if ($i == 10) {
          $selected = ' selected="selected" ';
        } else {
          $selected = '';
        }
        $vinnare .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
      }
      $vinnare .= '</select>';
      echo $vinnare;
      ?>
    </div>
    <div class="clear"></div>
    <div id="">
      <?php
      $steg = '<label for="steg">Antal steg</label> <select name="nbr-steps" id="nbr-steps">';
      for ($i = 7; $i <= 200; $i++) {
        $n = $i * 1000;
        if ($n == 49000) {
          $selected = ' selected="selected" ';
        } else {
          $selected = '';
        }
        $steg .= '<option value="' . $n . '" ' . $selected . '>' . $n . '</option>';
      }
      $steg .= '</select>';
      echo $steg;
      ?>
    </div>
    <div class="clear"></div>
    <div>
      Tidiage vinnare?
      <label for="prev-winners-yes" >Ja</label> <input type="radio" id="prev-winners-yes" name="prev-winners" value="1" />
      <label for="prev-winners-no">Nej</label> <input type="radio" id="prev-winners-no" name="prev-winners" value="0" checked/>   
    </div>    
    <div class="clear"></div>
    <div>
      <input type="button" value="hämta" id="get-winners"> 
    </div>
  </div>  
  <div class="clear"></div>
  <br/>
  <br/>
  <div id="winner-list"></div>
  <br/>
  <br/>
  <div id="status"></div>

</div>