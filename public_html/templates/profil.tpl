{debug}
{if isset($USER) && !$selfProfile}
	<div id="mmProfilAdressbok">
		{if isset($invitable)}
			<img src="/img/icons/AdressbokIcon.gif" alt=""/> <a href="#" onclick="motiomera_bjudInTillKlubb({$medlem->getId()}); return false;" title="Bjud in till klubb">Bjud in till klubb</a>
		{/if}


		{if $USER->inAdressbok($medlem)}
			<img src="/img/icons/AdressbokIcon.gif" alt="" class="mmMarginLeft10" /> <a href="{$urlHandler->getUrl(Adressbok, URL_DELETE, $medlem->getId())}" title="Ta bort som vän">Ta bort som vän</a>
		{else}
			<img src="/img/icons/AdressbokIcon.gif" alt="" class="mmMarginLeft10" /> <a href="{$urlHandler->getUrl(Adressbok, URL_SAVE, $medlem->getId())}" title="Lägg till som vän">Lägg till som vän</a>
		{/if}
		{*}
		{if $medlem->inAdressbok($USER)}
			<img src="/img/icons/SkickaMeddelandeIcon.gif" alt="" class="mmMarginLeft10" /> <a href="javascript:;" onclick="motiomera_mail_send('{$medlem->getId()}');return false;" title="Skicka meddelande">Skicka meddelande</a>
		{else}
			<img src="/img/icons/SkickaMeddelandeIcon.gif" alt="" class="mmMarginLeft10" /> Kan ej skicka meddelande
		{/if}
		{*}
		{if $blockerad_av_medlem neq 1}
			<img src="/img/icons/SkickaMeddelandeIcon.gif" alt="" class="mmMarginLeft10" /> <a href="javascript:;" onclick="motiomera_mail_send('{$medlem->getId()}');return false;" title="Skicka meddelande">Skicka meddelande</a>
		{else}
			<img src="/img/icons/SkickaMeddelandeIcon.gif" alt="" class="mmMarginLeft10" /> Kan ej skicka meddelande
		{/if}
		{if $USER->inAdressbok($medlem)}
		{elseif $blockerat_medlem neq 1}
			<img src="/img/icons/SkickaMeddelandeIcon.gif" alt="" class="mmMarginLeft10" /> <a href="{$urlHandler->getUrl(Medlem, URL_BLOCK_MEMBER, $medlem->getId())}" title="Spärra medlem">Spärra medlem</a>
		{else}
			<img src="/img/icons/SkickaMeddelandeIcon.gif" alt="" class="mmMarginLeft10" /> Medlemmen spärrad
		{/if}
</div>
{/if}


{if $selfProfile}
	<form action="" method="post" class="margin0" onsubmit="mm_saveStatus(this.status.value); return false;" id="mmUpdateStatusForm">
{/if}

<div class="mmh1 mmMarginBottom mmProfilH1">
	{$medlem->getANamn()}
	
	<span class="{if $selfProfile}editable {/if}mmGray">
		
		<span id="mmUpdateStatus">
			<input type="text" name="status" id="mmStatusField" value=""  />
			<input type="submit" name="save" value="Spara" id="save" />
			<input type="button" onclick="mm_toggleUpdateStatus(false);" name="clear" value="Avbryt" id="clear" />
			<img src="/img/icons/loadinganim.gif" alt="" id="mmStatusLoading" />
		</span>
		
		{if $selfProfile}
		<a href="#" onclick="mm_toggleUpdateStatus(true); return false;">
		{/if}
			<span id="mmMedlemStatusText">{if $medlem->getStatus()}{$medlem->getStatus()}{else}{if $selfProfile}Vad gör du just nu?{/if}{/if}</span>
		{if $selfProfile}
		</a>
		{/if}
	</span>
	
</div>
{if $selfProfile}
	</form>
{/if}





<div class="mmClearBoth"></div>

{if isset($lagnamn) && isset($foretagnamn)}
<div class="mmStegOverText mmMarginBottom">{$medlem->getANamn()} tävlar just nu för lag <a href="{$urlHandler->getUrl("Lag", URL_VIEW, $lagid)}">{$lagnamn}</a> från {$foretagnamn}{if isset($tavlingstart)}. Tävlingen startar om {$tavlingstart} dagar{/if}.</div>
{/if}
<div id="mmProfilBild">
	<img src="{$visningsbild->getUrl()}" alt="" class="mmImgBorderBlack" />
</div>
<div id="mmProfilInfo1">
	Namn: <br />
	Hemort:<br />
	Medlem sedan: <br />
</div>

<div id="mmProfilInfo2">
	{$medlem->getFNamn()} {$medlem->getENamn()} <br />
	{assign var=kommun value=$medlem->getKommun()}

	<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getNamn())}">{$kommun->getNamn()}</a><br />
	{$medlem->getSkapad()|date_format} <br />
</div>

<div id="mmProfilInfo3">
	Status: <br />
	Senast inloggad:<br />
	Totalt antal steg: <br />
	Just nu i: <br />
	{if isset($nastaKommun)}
	P&aring; v&auml;g till:<br />
	{/if}
</div>

<div id="mmProfilInfo4">
	{if $medlem->isInloggad()}<span class="mmGreen">Online</span>{else}Offline{/if} <img src="/img/icons/{if $medlem->getKon() eq "man"}Man{else}Kvinna{/if}Icon{if $medlem->isInloggad()}Online{else}Offline{/if}.gif" alt="" class="mmStatusIcon" /><br />
	{$medlem->getSenastInloggad()|nice_date:"j F"|capitalize}<br />
	
	{$medlem->getStegTotal()|nice_tal} steg<br />

	{assign var=justnukommun value=$medlem->getCurrentKommun()}

	<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $justnukommun->getUrlNamn())}">{$justnukommun->getNamn()}</a><br />
	{if isset($nastaKommun)}
	<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $nastaKommun->getUrlNamn())}">{$nastaKommun->getNamn()}</a><br />
	{/if}
</div>
{if $OM}
<div id="mmProfilInfoBottom">
<span class="mmProfilNamnSpan">Om {$medlem->getANamn()}:</span>
{$OM}
</div>
{/if}
<div class="mmClearBoth"></div>

{include file="steggrafik.tpl"}



<div id="mmColumnRightMinSida">

	<div class="mmAlbumBoxTop">
		<h3 class="mmWhite BoxTitle">Placeringar</h3>
	</div>
	<div class="mmRightMinSidaBox">
	
	<strong>Steglistan senaste 7 dagarna</strong><br /><br />
	<table width="155" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Steg</b></td>
		</tr>

		{foreach name=steglista from=$topplista->getTopplista(10,$medlem) item=placering}
		
		{if $placering.placering == 11}

			{assign var=tomrad value=1}

		{/if}
		
		{if $placering.placering > 10 && $tomrad == 0}
		
			{assign var=tomrad value=1}
			
			<tr><td>&nbsp;</td></tr>
		
		{/if}
		
		<tr>
			<td>{$placering.placering}.</td>
			<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()|truncate:16}</strong>{else}{$placering.medlem->getANamn()|truncate:16}{/if}</a></td>
			<td class="mmNoBr">{if $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
		</tr>
			
		{/foreach}
	</table>
	
	<br/>
	<a href="{$urlHandler->getUrl("Topplista", URL_LIST,$topplista_array)}">Visa fler topplistor <img src="/img/icons/ArrowCircleBlue.gif" alt="" /></a>
	
	</div>
	
	<br/>
	
	<div class="mmAlbumBoxTop">
		<h3 class="mmWhite BoxTitle">Klubbar</h3>
	</div>
	<div class="mmRightMinSidaBox">
	
	{foreach from=$grupper item=grupp}
		<a href="{$urlHandler->getUrl(Grupp, URL_VIEW, $grupp->getId())}">{$grupp->getNamn()}</a>
		{if $grupp->getSkapareId() == $medlem->getId()}<img src="/img/icons/star.gif" alt="" class="mmStarText" />{/if}
		<br />
	{/foreach}
	
	<br/>
	<img src="/img/icons/star.gif" alt="" /> = skapad av denna medlem

	</div>
	
	<br/>	

	{include file="fotoalbumblock.tpl"}

	<br />
	{if $sajtDelarObj->medlemHasAccess($USER,'minaQuizVisa')}
		{include file="minaquizblock.tpl"}
	{/if}
</div>

{include file="kommunjakten.tpl"}