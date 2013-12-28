<?php
/**
 * Description: The complete code for report stepa
 * 
 * Date: 2013-09-16
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 */
!isset($show) ? $display = "display:none;" : null;
!isset($enableInput) ? $enableInput = true : null;
!isset($showComments) ? $showComments = 'true' : null;
?>

<div id="mm-dialog" title="Rapportera steg" style="<?php echo $display; ?>">
  <div id="mm-datepicker" ></div>
  <input type="hidden" name="mm-step-date-today" id="mm-step-date-today" value="<?php echo date("Y-m-d"); ?>" />
  <input type="hidden" name="mm-step-date" id="mm-step-date" value="<?php echo date("Y-m-d"); ?>" />
  <input type="hidden" name="enable-input" id="enable-input" value="<?php echo $enableInput; ?>" />
  <div id="mm-step-data-area">
    <div id="submit-steps">
      <?php if ($enableInput): ?>
        <input type="text"  name="mm-count" id="mm-count" value="" />steg<a href="#" title="Annan aktivitet" id="mm-activity-link">Annan aktivitet?</a>
        <input type="button" name="mm-submit" id="mm-submit" value="Lägg till" />
      <?php endif; ?>
      <img id="mm-progress" src="/wp-content/themes/motiomera/img/ajax-loader.gif" alt="" />
      <div id="mm-activity-list" style=""></div>
      <div id="step-severity" style="display:none;"></div>
    </div>

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
    <?php if ($showComments): ?>
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
    <?php endif; ?>
  </div>      
</div>
