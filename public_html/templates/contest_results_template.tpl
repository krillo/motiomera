<script type="text/javascript" src="http://www.google.com/jsapi/"></script>
<script type="text/javascript">
  {literal}
  google.load("jquery", "1.2.6");
  google.setOnLoadCallback(function()
  {
    var $username = $('#mmInloggad strong').text();
    $username = $username.substr(0,$username.length-1);
    if ($username)
    {
      $('#mmColumnMiddleWide .mmRightMinSidaBox td').each(function(index)
      {
        if ($.trim($(this).text()) === $username)
        {
          $(this).parent().addClass('mmCurrentUser');
        }
      });
    }
  });
  {/literal}
</script>
<div class="mmTotaltAntalSteg"><b>Totalt:</b> {$foretag->getStegTotal()|nice_tal} steg</div>
  <div class="mmKlubbarAvatarTop"><img src="{if $foretagCustomBild!=null}{$foretagCustomBild}{else}/img/icons/AvatarKlubbTop.gif{/if}" alt="" /></div>
    <div class="mmh2">{$foretag->getNamn()}</div>
    <b>Startdatum för företagstävling: {$foretag->getStartdatum()|nice_date:"j F Y"}</b>
    <br /><br />

{include file="positionerlag.tpl"}
<br />
<div class="mmFloatLeft mmBlueBoxContainer mmWidthFyraEttNollPixlar">
  <div class="mmBlueBoxTop">
    <h3 class="mmWhite BoxTitle">Slutresultat inom lagen</h3>
  </div>
  <div class="mmBlueBoxBg">
    {foreach from=$CompanyTeams name=lagLoop item=team}
      <div class="eventResults">
        <div class="mmAlbumBoxTop">
          <h3 class="mmWhite BoxTitle">{$team->getNamn()}</h3>
        </div>
        <div class="mmRightMinSidaBox">
          <div class="mmHeightTvaNollPixlar"><b>Steg sen start</b></div>
          <table width="155" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td>&nbsp;</td>
              <td><b>Medlem</b></td>
              <td><b>Steg</b></td>
            </tr>
            {assign var=users value=$team->listMedlemmar('steg')}
            {foreach name=steglista from=$users item=user}
              <tr>
                <td>{$smarty.foreach.steglista.iteration}.</td>
                <td><a href="{$urlHandler->getUrl('Medlem', URL_VIEW, $user.medlem_id)}">{$user.aNamn}</a></td>
                <td class="mmNoWrap">{$medlem->getStegTotalForMedlemId($user.medlem_id,$startDatum,$slutDatum)|nice_tal}</td>
              </tr> 
            {/foreach}
          </table>
        </div>
      </div>
      {if $smarty.foreach.lagLoop.index % 2 != 0}
        <div class="mmClearBoth"></div>
      {/if}
    {/foreach}
    <br class="mmClearBoth">
  </div>
  <div class="mmBlueBoxBottom"></div>
  <div class="mmClearBoth"></div>
</div>
<div class="mmFloatLeft mmRightSideContestBar">
  <div class="mmAlbumBoxTop">
    <h3 class="mmWhite BoxTitle">Lagtoppen</h3>
  </div>
  <div class="mmRightMinSidaBox">
    <div class="mmHeightTvaNollPixlar"><b>Snitt per deltagare från start</b></div>
  
    <table width="155" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>&nbsp;</td>
        <td><b>Medlem</b></td>
        <td><b>Steg</b></td>
      </tr>
      {foreach name=steglista from=$topplistan item=l}
      <tr>
        <td>{$smarty.foreach.steglista.iteration}.</td>
        <td><a href="{$urlHandler->getUrl("Lag", URL_VIEW, $l->getId())}">{$l->getNamn()}</a></td>
        <td>{$l->getStegTotal(true,null,true)|nice_tal}</td>
      </tr> 
    {/foreach}
    </table>
  </div>
  <br />
  <div class="mmAlbumBoxTop">
    <h3 class="mmWhite BoxTitle">Deltagartoppen</h3>
  </div>
  <div class="mmRightMinSidaBox">
    <div class="mmHeightTvaNollPixlar"><b>Steg sen start</b></div>
    <table width="155" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>&nbsp;</td>
        <td><b>Medlem</b></td>
        <td><b>Steg</b></td>
      </tr>
      {foreach name=steglista from=$topplistaDeltagare->getTopplista(null,$medlem) item=placering}
        {if isset($placering.medlem)}
        <tr>
          <td>{$placering.placering}.</td>
          <td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">
            {$placering.medlem->getANamn()|truncate:11}</a>
          </td>
          <td class="mmNoWrap">
            {$placering.steg|nice_tal}
          </td>
        </tr>
        {/if}
      {/foreach}
    </table>
  </div>
  <br />
  {include file="bildblock.tpl"}
  <br />
</div>
<br class="mmClearBoth" />

<div class="mmBlueBoxWideTop"
  <h3 class="mmFontWhite BoxTitle">Slutresultat för alla deltagare i företagstävlingen {$eventDescription}</h3>
</div>
<div class="mmBlueBoxWideBg">
  <table>
    <tr>
      <td>
        <div class="mmTopplistaRuta">
          <div class="mmAlbumBoxTop">
            <h3 class="mmWhite BoxTitle">Deltagartoppen</h3>
          </div>
          <div class="mmRightMinSidaBox">
            <strong>Snitt per deltagare från start</strong><br /><br />
            <table width="155" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>&nbsp;</td>
                <td><b>Medlem</b></td>
                <td><b>Steg</b></td>
              </tr>
              {foreach name=steglista from=$topplista_medlem item=medlem key=lagKey}
                <tr>
                  <td>{$smarty.foreach.steglista.iteration}.</td>
                  <td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $medlem.id)}">{$medlem.namn|truncate:13}</a></td>
                  <td>{$medlem.stegindex}</td>
                </tr>
              {/foreach}
            </table>
          </div>
        </div>
        <div class="mmTopplistaRuta">
          <div class="mmAlbumBoxTop">
            <h3 class="mmWhite BoxTitle">Lagtoppen</h3>
          </div>
          <div class="mmRightMinSidaBox">
            <strong>Snitt per deltagare från start</strong><br /><br />
            <table width="155" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>&nbsp;</td>
                <td><b>Lag</b></td>
                <td><b>Steg</b></td>
              </tr>
              {foreach name=steglista from=$topplista_lag item=lag}

                <tr>
                  <td>{$smarty.foreach.steglista.iteration}.</td>
                  <td><a href="{$urlHandler->getUrl("Lag", URL_VIEW, $lag.id)}">{$lag.namn}</a></td>
                  <td>{$lag.stegindex}</td>
                </tr>
              {/foreach}
            </table>
          </div>
        </div>
        <div class="mmTopplistaRuta">
      
          <div class="mmAlbumBoxTop">
            <h3 class="mmWhite BoxTitle">F&ouml;retagstoppen</h3>
          </div>
          <div class="mmRightMinSidaBox">
            <strong>Dagligt snitt per deltagare från start</strong><br /><br />
            <table width="155" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>&nbsp;</td>
                <td><b>Medlem</b></td>
                <td><b>Steg</b></td>
              </tr>
              {foreach name=steglista from=$topplista_foretag item=foretag key=foretagKey}
              
                <tr>
                  <td>{$smarty.foreach.steglista.iteration}.</td>
                  <td>{$foretag.namn}</td>
                  <td>{$foretag.stegindex}</td>
                </tr>
              {/foreach}
            </table>
          </div>
        </div>
      </td>
    </tr>
  </table>
</div>
<div class="mmBlueBoxWideBottom"></div>
<div class="mmClearBoth"></div>
