<?php
global $SETTINGS;
$employeehelp = Help::loadById(28);
?>
<script type="text/javascript">
  jQuery(document).ready(function($) {

    /**
     * Ajax call to set fadmin
     */
    $("#fadmin-button").click(function(event) {
      event.preventDefault();
      $("#ajax-loader").css("display", "block");    //show progress wheel
      mm_id = $('input:radio[name=fadmin]:checked').val();
      fid = $('#fid').val();
      domain = $('#domain').val();
      var data = {
        mm_id: mm_id,
        fid: fid
      };
      $.ajax({
        type: "POST",
        url: domain + "/ajax/actions/setFadmin.php",
        dataType: 'json',
        data: data,
        success: function(data) {
          console.log(data);
          $('#success').html(data.success);
          $("#ajax-loader").css("display", "none");
        }
      });
      return false;
    });

  });
</script>

<a href="javascript:;" onclick="mm_rapportera_show_help(28,<?= $employeehelp->getSizeX() ?>,<?= $employeehelp->getSizeY() ?>, 'topleft')"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" title="Hjälp" class="mmFloatRight" /></a>
<p>
  Här kan du se vilka deltagare som har registrerat sig på sajten med den kod de fått i sina stegräknarpaket. 
  Du kan också välja att avregistrera en deltagare från tävlingen. 
  Företagsnyckeln frigörs då och kan användas av någon annan om så önskas.

  <br /><br /><?= count($foretag->listMedlemmar()); ?> av <?= count($foretag->listMedlemmar()) + count($foretag->listNycklar(true)); ?> deltagare har registerat sig hittills.
  <?php
  $withoutLag = $foretag->getMembersWithoutLag();
  if ($withoutLag > 0) {
    echo '<br /><a href="/pages/editforetag.php?fid='.$foretag->getId().'&tab=0" class="mmRed mmUnderline">OBS! ';
    echo $withoutLag . ' deltagare är ännu ej indelad ';
    echo $withoutLag > 1 ? 'e' : '';
    echo ' i något lag.</a><br/><br/>';
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
      <tr class="mmHeight20 mmAdressbokCellWhite1">
        <th>Namn</th>
      <?php
      if (isset($ADMIN)) {
        echo '<th>Edit</th>';
      }
      ?>        
        <th>Lag</th>
        <th>Kod</th>
        <th>Avregistrera</th>
        <th class="noIndent mmNoUnderline"><input type="button" value="Spara som admin" id="fadmin-button">
          <input type="hidden" value="<?php echo $foretag->getId(); ?>" id ="fid"/>
          <input type="hidden" value="<?php echo $SETTINGS['url']; ?>" id="domain"/>
    <div id="ajax-loader"><img src="/img/ajax-loader.gif" alt="" /></div>
  </th>
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
          echo '<a href="/pages/editforetag.php?fid='.$foretag->getId().'&tab=0" class="mmRed mmUnderline">Ej i lag</a>';
        ?>
      </td>
      <td>
        <?= $medlem->getForetagsnyckel(); ?>
      </td>
      <td class="mmCell1">
        <img src="/img/icons/AdressbokDeleteIcon2_BlueBG.gif" alt="" />
        <a href="<?= $urlHandler->getUrl("Foretag", URL_REMOVE_USER, array($foretag->getId(), $medlem->getId())) ?>" title="Avreg fr&aring;n t&auml;vlingen">Avreg. från tävling</a>
      </td>
      <td class="mmCell1">
        <input type="radio" name="fadmin" value="<?php echo $medlem->getId(); ?>" <?php echo $medlem->getFadmin() == $foretag->getId() ? 'checked' : ''; ?> />
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