<script>
  jQuery(function($) {
    $("#tabs").tabs({
      event: "mouseover"
    });
  });</script>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Pågående företagstävling</a></li>
    <li><a href="#tabs-2">All time high</a></li>
  </ul>
  <div id="tabs-1" class="tabArea">

    <div class="floatit">
      <div class="mmAlbumBoxTop">
        <h3 class="mmWhite BoxTitle">Snittsteg sen tävlingsstart</h3>
      </div>
      <div class="mmRightMinSidaBox">
        <table width="155" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>&nbsp;</td>
            <td><b>Medlem</b></td>
            <td><b>Steg</b></td>
          </tr>
          <?php
          $list = Steg::getStepdataPerAllCurrentCompetitionMembers(true);
          foreach ($list as $mid => $member): $i++;
            ?>
            <tr>
              <td><?php echo $i ?>.</td>
              <td><a href = "/pages/profil.php?mid=<?php echo $mid; ?>"><?php echo $member['anamn'] ?></a></td>
              <td><?php echo $member['average'] ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>


    <div class="floatit">
      <div class="mmAlbumBoxTop">
        <h3 class="mmWhite BoxTitle">Snittsteg per lag</h3>
      </div>
      <div class="mmRightMinSidaBox">
        <table width="155" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>&nbsp;</td>
            <td><b>Medlem</b></td>
            <td><b>Steg</b></td>
          </tr>
          <?php
          $list = Steg::getStepdataPerAllCurrentCompetitionTeams();
          foreach ($list as $mid => $member): $i++;
            ?>
            <tr>
              <td><?php echo $i ?>.</td>
              <td><a href = "/pages/profil.php?mid=<?php echo $mid; ?>"><?php echo $member['anamn'] ?></a></td>
              <td><?php echo $member['average'] ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>


    <div class="floatit">
      <div class="mmAlbumBoxTop">
        <h3 class="mmWhite BoxTitle">Snittsteg per företag</h3>
      </div>
      <div class="mmRightMinSidaBox">
        <table width="155" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>&nbsp;</td>
            <td><b>Medlem</b></td>
            <td><b>Steg</b></td>
          </tr>
          <?php
          $list = Steg::getStepdataPerAllCurrentCompetitionMembers(true);
          foreach ($list as $mid => $member): $i++;
            ?>
            <tr>
              <td><?php echo $i ?>.</td>
              <td><a href = "/pages/profil.php?mid=<?php echo $mid; ?>"><?php echo $member['anamn'] ?></a></td>
              <td><?php echo $member['average'] ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>   
    </div>



  </div>
  <div id="tabs-2">
    <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id n</p>
  </div>
</div>