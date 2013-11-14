{if isset($USER) && !$selfProfile}
	<div id="mmProfilAdressbok">
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
	Profilsida: {$medlem->getANamn()}
	
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
  {include file="widget_steglista.tpl"}  
  {*include file="widget_klubbar.tpl"*}  
	{include file="fotoalbumblock.tpl"}  
</div>

{php}
  include(BASE_PATH . '/wordpress/wp-content/themes/motiomera/snippets/inc_graph.php');
{/php}

<div style="min-height:340px;margin-bottom: 15px;float:left">
{include file="widget_kommunjakten.tpl"}
</div>  
  
