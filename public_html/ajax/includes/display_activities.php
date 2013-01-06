<?php
/**
 * 13-01-02 Kristian Erendi, Reptilo.se
 * Return the HTML code for activity-dropdown 
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
header("Content-Type: text/html; charset=utf-8");
$list = Aktivitet::listDistinctActivities();
?>
<select name="mm-activity_id" id="mm-activity-cat-id">
  <?php foreach ($list as $id => $activity): ?>
    <option value="<?php echo $id; ?>" <?php echo $id == 5 ? 'selected="selected"' : ''; ?>  >  <?php echo $activity['namn']; ?> (<?php echo $activity['enhet']; ?>)</option>
  <?php endforeach; ?>
</select>