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
!isset($heading) ? $heading = "Framsteg de senaste " : null;
!isset($legend1) ? $legend1 = "Dina steg " : null;
!isset($dateSelector) ? $dateSelector = true : null;
!isset($nbrDays) ? $nbrDays = 7 : null;
!isset($graphWidth) ? $graphWidth = 520 : null;
?>


<input type="hidden" id="graph-width" value="<?php echo $graphWidth; ?>" />
<div class="mmBlueBoxTop">
  <div class="BoxTitlePart"><?php echo $heading; ?></div><div class="BoxTitlePart"> <?php if ($dateSelector): ?><input type="text" id="mm-nbr-days" value="<?php echo $nbrDays; ?>" /> dagarna </div><div class="BoxTitlePart BoxTitlePart-button"><input type="button" value="ok" id="mm-nbr-days-ok"></div><?php endif; ?>
</div>
<div id="mm-graph">
  <div id="placeholder" ></div>
  <div id="mm-legend-area">
    <div id="mm-legend-left">
      <div class="mm-legend-container-long"><div class="mm-legend mm-legend-steps"></div><?php echo $legend1; ?></div>
      <div class="mm-legend-container-long"><div class="mm-legend mm-legend-average"></div>Snitt samtliga deltagare</div>
    </div>
    <div id="mm-legend-middle">
      <div class="mm-legend-container"><div class="mm-legend mm-legend-gold"></div>Guldnivå</div>
      <div class="mm-legend-container"><div class="mm-legend mm-legend-silver"></div>Silvernivå</div>
    </div>
    <div id="mm-legend-right">
      <div id="mm-legend-steps"></div>
      <div id="mm-legend-average"></div>
    </div>
  </div>
</div>    