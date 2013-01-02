<?php
/**
 * 12-12-31 Kristian Erendi, Reptilo.se
 * Yes, working on new years eve!
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
header("Content-Type: text/html; charset=utf-8");
if (!isset($req)) {
  $req = new stdClass;
  !empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = '';
  !empty($_REQUEST['count']) ? $req->count = addslashes($_REQUEST['count']) : $req->count = '';
  !empty($_REQUEST['date']) ? $req->date = addslashes($_REQUEST['date']) : $req->date = '';
  !empty($_REQUEST['activity_id']) ? $req->activity_id = addslashes($_REQUEST['activity_id']) : $req->activity_id = '';
  !empty($_REQUEST['diary']) ? $req->diary = addslashes($_REQUEST['diary']) : $req->diary = '';
  !empty($_REQUEST['grade']) ? $req->grade = addslashes($_REQUEST['grade']) : $req->grade = '';
  $medlem = Medlem::loadById($req->mm_id);
}
$list = Steg::listByDatum($req->date, $medlem);
$dagbok = Dagbok::getEntryBymmIdDate($req->mm_id, $req->date);
if ($list != null): $defaultActivityId = 5;
  ?>

  
    <table style="display: block;" id="motiomera_steg_preview_header" >
      <thead >
        <tr>
          <th class="mmAlbumBoxTop mmAlbumBoxTop-first">Datum</th>
          <th class="mmAlbumBoxTop">Aktivitet</th>
          <th class="mmAlbumBoxTop">Tid</th>
          <th class="mmAlbumBoxTop" colspan="2">Antal</th>
          <th class="mmAlbumBoxTop mmAlbumBoxTop-last"></th>
        </tr>
      </thead>
      <tbody id="preview-step-rows">
        <?php foreach ($list as $stegId => $stegObject): $i++;?>
          <tr class="<?php echo $i % 2 == 0 ? 'even' : 'odd';   ?>">
            <td class="step-table-cell"><?php echo $req->date; ?></td>
            <td class="step-table-cell-big capitalize"><?php echo $stegObject->getAktivitetId() != $defaultActivityId ? $stegObject->getAktivitet()->getNamn() : ''; ?></td>
            <td class="step-table-cell"><?php echo $stegObject->getAktivitetId() != $defaultActivityId ? $stegObject->getAntal() : ''; ?></td>
            <td class="step-table-cell-small"><?php echo $stegObject->getSteg(); ?></td>
            <td class="step-table-cell-small">steg</td>
            <td class="step-table-cell"><button id="delete-step-row<?php echo $stegId; ?>" class="ui-icon ui-icon-closethick" onclick="deleteRow(<?php echo $stegId; ?>)" ></button></td>
            
            
          </tr>
        <?php endforeach; ?>    
      </tbody>
    </table>
  </div>
<?php endif; ?>


<div style="border:#999 solid 1px;padding:10px;height:80px;">
  <div>Kommentera dagens motion, <?php echo $dagbok->datum; ?></div><div class="clear"></div>
  <div>
    <textarea id="comment" name="comment"  rows="2" cols="50"><?php echo $dagbok->kommentar; ?></textarea>
  </div>
  <div style="float:left;width:285px;">
    <img src="/img/smileys.png" alt=""/>
    <input type="radio" id="smiley1" name="smiley" value="1" style="margin-right:35px; margin-left:16px;" <?php echo $dagbok->betyg == 1 ? 'checked' : ''; ?>  />
    <input type="radio" id="smiley2" name="smiley" value="2" style="margin-right:35px;" <?php echo $dagbok->betyg == 2 ? 'checked' : ''; ?> />
    <input type="radio" id="smiley3" name="smiley" value="3" style="margin-right:40px;" <?php echo $dagbok->betyg == 3 ? 'checked' : ''; ?>/>
    <input type="radio" id="smiley4" name="smiley" value="4" style="margin-right:35px;" <?php echo $dagbok->betyg == 4 ? 'checked' : ''; ?>/>
    <input type="radio" id="smiley5" name="smiley" value="5" style="margin-right:0px;"  <?php echo $dagbok->betyg == 5 ? 'checked' : ''; ?>/>
    <input type="hidden" id="diary-id" value="<?php echo $dagbok->id; ?>" />
  </div>
  <div style="float:left;">
    <button id="update-diary" onclick="updateDiary(<?php echo $dagbok->id; ?>)">Uppdatera</button>
  </div>    
</div>
