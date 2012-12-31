<?php
 /**
  * 12-12-31 Kristian Erendi, Reptilo.se
  * Yes, working on new years eve!
  */
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
header("Content-Type: text/html; charset=utf-8");
if(!isset($req)){
  $req = new stdClass;
  !empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = ''; 
  !empty($_REQUEST['count']) ? $req->count = addslashes($_REQUEST['count']) : $req->count = ''; 
  !empty($_REQUEST['date']) ? $req->date = addslashes($_REQUEST['date']) : $req->date = ''; 
  !empty($_REQUEST['activity_id']) ? $req->activity_id = addslashes($_REQUEST['activity_id']) : $req->activity_id = ''; 
  $medlem = Medlem::loadById($req->mm_id);
}
$list = Steg::listByDatum($req->date, $medlem);
//print_r($list);
if ($list != null):
  $defaultActivityId = 5;
?>
<!--div id="preview-step-list" style="display:none;" -->
  <table style="display: block;" id="motiomera_steg_preview_header" class="motiomera_steg_preview_table" border="0" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th class="step-table-cell">Datum</th>
        <th class="step-table-cell-big">Aktivitet</th>
        <th class="step-table-cell">Tid</th>
        <th colspan="2">Antal</th>
        <th class="step-table-cell"></th>
      </tr>
    </thead>
    <tbody id="preview-step-rows">
    <?php foreach ($list as $stegId => $stegObject): ?>
      <tr>
        <td class="step-table-cell"><?php echo $req->date; ?></td>
        <td class="step-table-cell-big capitalize"><?php echo $stegObject->getAktivitet(); /* == $defaultActivityId ? $row->name : $row->name . ' (' . $row->severity . ')'; */?></td>
        <td class="step-table-cell"><?php /*echo $row->activity_id == $defaultActivityId ? '' : $row->count . ' ' . $row->unit; */?></td>
        <td class="step-table-cell-small"><?php echo $stegObject->getSteg(); ?></td>
        <td class="step-table-cell-small">steps</td>
        <td class="step-table-cell"><button id="delete-step-row<?php echo $stegId; ?>" onclick="deleteRow(<?php echo $stegId; ?>)" >Ta bort</button></td>
      </tr>
    <?php endforeach; ?>    
    </tbody>
  </table>
<?php endif; ?>
  <!-- /div -->

  <div style="border:#999 solid 1px;padding:10px;height:80px;">
    <div>Comment <?php echo $date; ?></div><div class="clear"></div>
    <div>
      <textarea id="message" name="message"  rows="2" cols="50"><?php echo $message->message ?></textarea>
    </div>
    <div style="float:left;width:285px;">
      <img src="/img/smileys.png" alt=""/>
      <input type="radio" id="smiley1" name="smiley" value="1" style="margin-right:35px; margin-left:16px;" <?php echo $message->smiley == 1 ? 'checked': ''; ?>  />
      <input type="radio" id="smiley2" name="smiley" value="2" style="margin-right:35px;" <?php echo $message->smiley == 2 ? 'checked': ''; ?> />
      <input type="radio" id="smiley3" name="smiley" value="3" style="margin-right:40px;" <?php echo $message->smiley == 3 ? 'checked': ''; ?>/>
      <input type="radio" id="smiley4" name="smiley" value="4" style="margin-right:35px;" <?php echo $message->smiley == 4 ? 'checked': ''; ?>/>
      <input type="radio" id="smiley5" name="smiley" value="5" style="margin-right:0px;"  <?php echo $message->smiley == 5 ? 'checked': ''; ?>/>
    </div>
    <div style="float:left;">
      <?php if($message->id != -1): ?>
        <button id="update-message" onclick="updateMessage(<?php echo $message->id; ?>);" >Update</button>
      <?php else: ?>
        <button id="submit-message" onclick="addMessage();" >Add</button>
      <?php endif; ?>
    </div>    
  </div>