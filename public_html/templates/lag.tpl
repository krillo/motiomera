<div id="mm_lid" style="display: none;">{$lag2->getId()}</div>
<div id="mm_compstart" style="display: none;">{$lag2->getStart()}</div>
<div id="mm_compstop" style="display: none;">{$lag2->getSlut()}</div>
<div id="statsHeadingArea">
  <div class="mmKlubbarAvatarTop"><img src="{$bildURL}" alt="" /></div>
  <div class="statsHeading">{$lag2->getNamn()} - <a href="{$urlHandler->getUrl(Foretag, URL_VIEW, $foretag2->getId())}">{$foretag2->getNamn()}</a></div>
  <div class="startStopDate">
    <span>Start: {$lag2->getStart()|nice_date:"j F Y"}</span>
    <span>Slut: {$lag2->getSlut()|nice_date:"j F Y"}</span>
  </div>
</div>

{include file="positionerlagm.tpl"}

<div class="mmFloatRight">
  <div class="mmAlbumBoxTop">
    <h3 class="mmWhite BoxTitle">Steg sen tävlingsstart</h3>
  </div>
  <div class="mmRightMinSidaBox">
    <table width="155" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>&nbsp;</td>
        <td><b>Medlem</b></td>
        <td><b>Steg</b></td>
      </tr>
      {foreach name=steglista from=$topplista->getTopplista(100,$medlem) item=placering}
        {if $placering.placering == 101}
          {assign var=tomrad value=1}
        {/if}
        {if $placering.placering > 100 && $tomrad == 0}
          {assign var=tomrad value=1}
          <tr><td>&nbsp;</td></tr>
        {/if}
        <tr  {if $smarty.foreach.steglista.index % 2}{else}class="odd"{/if}>
          <td>{$placering.placering}.</td>
          <td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()}</strong>{else}{$placering.medlem->getANamn()}{/if}</a></td>
          <td>{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
        </tr>
      {/foreach}
    </table>
    <br />
    <a href="{$urlHandler->getUrl(Foretagstavling, URL_VIEW)}">Företagstopplistor <img src="/img/icons/ArrowCircleBlue.gif" alt="Steg sen start" /></a>
  </div>
  <br />
  {include file='bildblock.tpl'}
</div>

<div class="mmBlueBoxTop"><h3 class="mmWhite BoxTitle">Lagmedlemmar</h3></div>
<div class="mmBlueBoxBg">
  <table class="mmMedlemmarTabell" border="0" cellpadding="5" cellspacing="0">
    <tr>

      {foreach name=medlemloop from=$medlemmar item=medlem}
        <td class="mmCenterText">
          {assign var=avatar value=$medlem->getAvatar()}
          <a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}"><img src="{$avatar->getUrl()}" alt="" width="15" class="mmAvatarMini" /></a><br /><a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}">{$medlem->getANamn()}</a>
        </td>
        {if !($smarty.foreach.medlemloop.iteration mod 4)}
        </tr>
        <tr>
        {/if}
      {/foreach}
    </tr>
  </table><br />
</div>
<div class="mmBlueBoxBottom"></div>

{php}
  $heading = "Snittsteg för laget under hela tävlingen ";
  $legend1 = "Lagets snittsteg";
  $dateSelector = false;
  include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_graph.php');
{/php}
<div class="mmClearBoth"></div>

<div class="mmAnslagstavla">

  <div class="mmBlueBoxTop"><h3 class="BoxTitle">{$lag2->getNamn()}s anslagstavlan</h3></div>
  <div class="mmBlueBoxBg mmTabell">
    {if $nbrPosts > 0}
      <table class="mmAnslagstavlaTabell">
        <tr>
          <td><b>Medlem</b></td>
          <td><b>När</b></td>
          <td><b>Meddelande</b></td>
        </tr>
        {foreach name=lista from=$atavla key=myId item=i}
          <tr {if $smarty.foreach.lista.index % 2}{else}class="odd"{/if}>
            <td class="anslag_name"><a href="/pages/profil.php?mid={$i.medlem_id}">{$i.anamn}</a></td>
            <td class="anslag_time"><em>{$i.ts|nice_date:"d/m-y"}</em>&nbsp;</td>
            <td class="anslag_txt">{$i.text}</td>
          </tr>
        {/foreach}
      </table>
    {/if}
    {if $owner || $ismember}
      <form action="{$urlHandler->getUrl(AnslagstavlaRad, URL_SAVE)}" method="post" id="anslagstavla_post">
        <input type="hidden" name="gid" value="{$lag2->getId()}"/>
        <input type="hidden" name="aid" value="{$lag2->getAnslagstavlaId()}"/>
        <b>Skriv på anslagstavlan:</b>
        <textarea name="atext" ></textarea>
        <input type="submit" value="Skicka"/>
      </form>
    {/if}
  </div>
</div>
<div class="mmClearBoth"></div>