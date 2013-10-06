<div id="mm_lid" style="display: none;">{$lag2->getId()}</div>
<div class="mmTotaltAntalSteg"><b>Totalt:</b> {$lag2->getStegTotal()|nice_tal} steg</div>
<div class="mmKlubbarAvatarTop"><img src="{$bildURL}" alt="" /></div>
<div class="mmh2">{$lag2->getNamn()}</div>
Tillhör företaget <a href="{$urlHandler->getUrl(Foretag, URL_VIEW, $foretag2->getId())}"><strong>{$foretag2->getNamn()}</strong></a> | Startdatum: {$lag2->getStart()|nice_date:"j F Y"}
<br /><br />

{include file="positionerlagm.tpl"}

<div class="mmFloatRight">

  <div class="mmAlbumBoxTop">
    <h3 class="mmWhite BoxTitle">Stegtoppen</h3>
  </div>
  <div class="mmRightMinSidaBox">

    <strong>Steg sen start</strong><br /><br />
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
        <tr>
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
  {*}<div class="mmTextalignRight mmMarginRight20"><a href="#">Se alla medlemmar</a><a href="#"><img src="/img/icons/ArrowCircleBlue.gif" class="mmMarginLeft3 mmArrow" alt="" /></a></div>{*}
</div>
<div class="mmBlueBoxBottom"></div>

{php}
  include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_graph.php');
{/php}
<div class="mmClearBoth"></div>

<div class="mmAnslagstavla">

  <div class="mmAnslagstavlaBoxTop"><h3 class="mmWhite BoxTitle AnslagTitle">Anslagstavlan</h3></div>
  <div class="mmAnslagstavlaBoxBg">


    {if $nbrPosts > 0}
      <table class="mmAnslagstavlaTabell" cellpadding="1" cellspacing="0">
        {foreach from=$atavla key=myId item=i}
          <tr>
            <td>{$i.anamn}</td>
            <td><em>{$i.ts|nice_date:"d/m-y"}</em>&nbsp;</td>
            <td>{$i.text}</td>
          </tr>
        {/foreach}
      </table>
    {/if}


    <div class="mmTextalignRight mmMarginRight10">
      {*}<a href="#">L&auml;s alla inlägg</a>
      <a href="#">
      <img src="/img/icons/ArrowCircleBlue.gif" alt="" class="mmMarginLeft3 mmArrow" />
      </a>{*}
    </div>
    {if $owner || $ismember}
      <form action="{$urlHandler->getUrl(AnslagstavlaRad, URL_SAVE)}" method="post">
        <input type="hidden" name="gid" value="{$lag2->getId()}"/>
        <input type="hidden" name="aid" value="{$lag2->getAnslagstavlaId()}"/>
        Skriv på anslagstavlan:<br />
        <textarea name="atext" rows="5" cols="5"></textarea>
        <br />
        <input type="submit" value="Skicka"/><br /><br />
      </form>
    {/if}
  </div>

  <div class="mmAnslagstavlaBoxBottom"></div>

</div>

<div class="mmClearBoth"></div>
