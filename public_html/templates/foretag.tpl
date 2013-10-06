<div id="mm_fid" style="display: none;">{$this_foretag->getId()}</div>
<div class="mmTotaltAntalSteg"><b>Totalt:</b> {$this_foretag->getStegTotal()|nice_tal} steg</div>
	<div class="mmKlubbarAvatarTop"><img src="{if $foretagCustomBild!=null}{$foretagCustomBild}{else}/img/icons/AvatarKlubbTop.gif{/if}" alt="" /></div>
		<div class="mmh2">{$this_foretag->getNamn()}</div>
		<b>Tävlingsdatum: &nbsp; </b>{$this_foretag->getStartdatum()|nice_date:"j F Y"} &nbsp; - &nbsp;  {$this_foretag->getSlutdatum()|nice_date:"j F Y"}
		<br /><br />
  	{include file="positionerlag.tpl"}

<div class="mmFloatRight">
	{include file="widget_foretaglagtoppen.tpl"}
	<br />
	<br />
	<br />

		<div class="mmAlbumBoxTop">
			<h3 class="BoxTitle">Deltagartoppen</h3>
		</div>
		<div class="mmRightMinSidaBox">
      <div class="mmHeightTvaNollPixlar"><b>Steg sen start</b></div>		
        <table width="155" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>&nbsp;</td>
            <td><b>Medlem</b></td>
            <td><b>Steg</b></td>
          </tr>
          {foreach name=steglista from=$topplistaDeltagare->getTopplista(10,$medlem) item=placering}
            {if $placering.placering == 11}
              {assign var=tomrad value=1}
            {/if}
            {if $placering.placering > 10 && $tomrad == 0}
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
        <a href="{$urlHandler->getUrl(Foretagstavling, URL_VIEW) }">Företagstopplistor <img src="/img/icons/ArrowCircleBlue.gif" alt="Steg sen start" /></a>
		</div>
		<br/>
		{include file="bildblock.tpl"}	
</div>

{*include file="stegstaplar.tpl"*}
{php}
  include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_graph.php');
{/php}
<div class="mmClearBoth"></div>



<div class="mmBlueBoxContainer">
<div class="mmBlueBoxTop"><h3 class="BoxTitle">Deltagande Lag</h3></div>
<div class="mmBlueBoxBg">
	<table class="mmMedlemmarTabell" border="0" cellpadding="5" cellspacing="0">
		<tr>
			{foreach name=lagloop from=$lagList item=l}
			<td class="mmCenterText">
				<a href="{$urlHandler->getUrl(Lag, URL_VIEW, $l->getId())}"><img src="{$l->getBildFullUrl()}" class="mmAvatar" alt="" /><br />
				{$l->getNamn()}</a>
			</td>
					{if !(($smarty.foreach.lagloop.iteration) mod 4)}
						</tr>
						<tr>
					{/if}
			{/foreach}
		</tr>
	</table><br />
	{*}<div class="mmTextalignRight mmMarginRight20"><a href="#">Se alla deltagare</a><a href="#"><img src="/img/icons/ArrowCircleBlue.gif" class="mmMarginLeft3 mmArrow" alt="" /></a></div>{*}
</div>
<div class="mmBlueBoxBottom"></div>
</div>