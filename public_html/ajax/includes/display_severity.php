<?php
 /**
  * 12-12-31 Kristian Erendi, Reptilo.se
  * Yes, working on new years eve!
  */
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
header("Content-Type: text/html; charset=utf-8");
if(!isset($req)){
  $req = new stdClass;
  !empty($_REQUEST['activity_id']) ? $req->activity_id = addslashes($_REQUEST['activity_id']) : $req->activity_id = ''; 
}
$list = Aktivitet::listSeveritys($req->activity_id);
?>
<?php if(count($list) > 1):?>
<select id="activity_id" name="activity_id">
  <?php foreach ($list as $id => $activity): ?>
    <option value="<?php echo $id; ?>" ><?php echo $activity['svarighetsgrad']; ?></option>
  <?php endforeach; ?>
</select>
<?php endif; ?>