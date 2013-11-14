<?php
/**
 * Description: The complete code for the graph
 * 
 * Date: 2013-09-16
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * 
 *
 */
!isset($heading)? $heading = "Framsteg de senaste " : null;
!isset($legend1)? $legend1 = "Dina steg " : null;
!isset($dateSelector)? $dateSelector = true : null;
?>


<div class="mmBlueBoxTop">
  <h3 class="BoxTitle"><?php echo $heading; if($dateSelector): ?><input type="text" id="mm-nbr-days" value="7" /> dagarna <input type="button" value="ok" id="mm-nbr-days-ok"> <?php endif; ?></h3> 
</div>    
<div id="mm-graph">
  <div id="placeholder" ></div>
  <div id="mm-legend-area">
    <div id="mm-legend-left">
      <div class="mm-legend-container-long"><div class="mm-legend mm-legend-steps"></div><?php echo $legend1; ?></div>
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