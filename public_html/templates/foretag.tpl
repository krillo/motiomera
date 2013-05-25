{debug}
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

{include file="stegstaplar.tpl"}

<div class="mmBlueBoxContainer">
<div class="mmBlueBoxTop"><h3 class="mmWhite BoxTitle">Deltagande Lag</h3></div>
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


{*}<p>
	<b>Lag</b><br />

	{foreach from=$lagList item=lag}
	<a href="{$urlHandler->getUrl(Lag, URL_VIEW, $lag->getId())}">{$lag->getNamn()}</a> ({$lag->getAntalMedlemmar()} medlemmar)<br />
	{foreachelse}
	Inga lag ännu.
	{/foreach}
</p>{*}
		{*}<div class="mmAnslagstavla">
			
			<div class="mmAnslagstavlaBoxTop"><h3 class="mmWhite BoxTitle AnslagTitle">Anslagstavlan</h3></div>
			<div class="mmAnslagstavlaBoxBg">
				

				{if $anslagstavlaantalrader > 0}
				<table class="mmAnslagstavlaTabell" cellpadding="1" cellspacing="0">
					{foreach from=$anslagstavlarader item=rad name=anslagstavlarader}
					{assign var=radnr value=$anslagstavlaantalrader-$smarty.foreach.anslagstavlarader.iteration+1}
					{assign var=tempMedlem value=$rad->getMedlem()}
					<tr>
						<td>#{$radnr}</td>
						<td>{$tempMedlem->getANamn()}</td>
						<td><em>{$rad->getDatum()|nice_date:"d M Y"}</em></td>
						<td>{$rad->getText()}</td>
					</tr>
					{/foreach}
				</table>
				{/if}
				
				
				
				<div class="mmTextalignRight mmMarginRight10">
					<a href="#">L&auml;s alla inlägg</a>
					<a href="#">
						<img src="/img/icons/ArrowCircleBlue.gif" alt="" class="mmMarginLeft3 mmArrow" />
					</a>
				</div>
				{if $owner || $ismember}
				<form action="{$urlHandler->getUrl(AnslagstavlaRad, URL_SAVE)}" method="post">
					<input type="hidden" name="gid" value="{$lag2->getId()}"/>
					<input type="hidden" name="aid" value="{$lag2->getAnslagstavlaId()}"/>
					Skriv på anslagstavlan:<br/>
					<textarea name="atext" rows="5" cols="5"></textarea>
					<br/>
					<input type="submit" value="Skicka"/><br/><br/>
				</form>
				{/if}
			</div>

			<div class="mmAnslagstavlaBoxBottom"></div>
			
		</div>

		<div class="mmClearBoth"></div>{*}
