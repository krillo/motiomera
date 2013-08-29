<?php
$employeehelp = Help::loadById(28);
?>
<a href="javascript:;" onclick="mm_rapportera_show_help(28,<?= $employeehelp->getSizeX() ?>,<?= $employeehelp->getSizeY() ?>, 'topleft')"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" title="Hjälp" class="mmFloatRight" /></a>
<p>
  Här kan du se vilka deltagare som har registrerat sig på sajten med den kod de fått i sina stegräknarpaket. 
  Du kan också välja att avregistrera en deltagare från tävlingen. 
  Företagsnyckeln frigörs då och kan användas av någon annan om så önskas.

  <br /><br /><?= count($foretag->listMedlemmar()); ?> av <?= count($foretag->listMedlemmar()) + count($foretag->listNycklar(true)); ?> deltagare har registerat sig hittills.
  <?php
  $withoutLag = $foretag->getMembersWithoutLag();
  if ($withoutLag > 0) {
    ?>
    <br />
    <span class="mmRed">
      OBS! <?= $withoutLag ?> deltagare är ännu ej indelad<?= $withoutLag > 1 ? 'e' : ''; ?> i något lag.
    </span><br /><br />
    <?php
  }
  ?>
</p>
<?
//$foretag->getAntalMedlemmar();
$medlemmar = $foretag->listMedlemmar();
if (count($medlemmar) > 0) {
  ?>
  <table border="0" cellspacing="2" cellpadding="4" class="mmAdressbokTable">
    <thead>
      <tr>
        <th>Namn</th>
        <th>Lag</th>
        <th>Kod</th>
        <th>Avregistrera</th>
        <th><input type="button" value="Spara som admin"></th>
      </tr>
    </thead>	
    <?php
    $i = 1;
    foreach ($medlemmar as $medlem) {
      ?>

      <tr class="mmAdressbokCell<?php
      if ($i == 0) {
        echo "White";
      } else {
        echo "Blue";
      }
      ?>1">
        <td class="mmCell1">
          <img src="<?= $medlem->getAvatar()->getUrl(); ?>" class="mmAvatarMini" alt="<?= $medlem->getAnamn() ?>_avatar" />
          <a href="<?= $urlHandler->getUrl("Medlem", URL_VIEW, $medlem->getId()) ?>" title="<?php echo $medlem->getFNamn() . " " . $medlem->getENamn() ?>"><?php echo $medlem->getFNamn() . " " . $medlem->getENamn() ?></a>
        </td>
        <?php
        if (isset($ADMIN)) {
          echo '<td class="mmCellAdmin">';
          echo '<a href="/admin/pages/medlem.php?id=' . $medlem->getId() . '" class="mmAdminColor">edit</a>';
          echo '</td>';
        }
        ?>		    
        <td class="mmCell1">
          <?
          $lag = $medlem->getLag();
          if (isset($lag)) {
            echo $lag->getNamn();
          }
          else
            echo '<span class="mmRed">Ännu ej indelad i lag</span>';
          ?>
        </td>
        <td>
    <?= $medlem->getForetagsnyckel(); ?>
        </td>
        <td class="mmCell1">
          <img src="/img/icons/AdressbokDeleteIcon2_BlueBG.gif" alt="" />
          <a href="<?= $urlHandler->getUrl("Foretag", URL_REMOVE_USER, array($foretag->getId(), $medlem->getId())) ?>" title="Avregistrera fr&aring;n t&auml;vlingen">Avregistrera från tävling</a>
        </td>
        <td class="mmCell1">
          <input type="radio" name="foretagsadmin" value="" <?php echo  $medlem->getAdmin() == $foretag->getId() ? 'checked' : ''; ?> >
        </td>
      </tr>



      <?php
      $i = (int) !(bool) $i++;
    }
    ?>
  </table>

<?php }else { ?>
  <p>
    Inga deltagare ännu.
  </p>
  <?php
}
?>