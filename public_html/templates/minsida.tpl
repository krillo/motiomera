{if $selfProfile}
	<form action="" method="post" class="margin0" onsubmit="mm_saveStatus(this.status.value); return false;" id="mmUpdateStatusForm">
{/if}

<div class="mmh1 mmMarginBottom mmProfilH1">
	{$USER->getANamn()}

	<span class="mmGray editable">

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

<br /><br />

{include file=steggrafik.tpl}

<!-- START mmColumnRightMinSida -->
{* include file="widget_userblogg.tpl" *}
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
			<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()|truncate:16}</strong>{else}{$placering.medlem->getANamn()|truncate:16}{/if}</a></td>
			<td>{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
		</tr>

		{/foreach}
	</table>

	<br />
	<a href="{$urlHandler->getUrl("Topplista", URL_LIST) }">Visa fler topplistor <img src="/img/icons/ArrowCircleBlue.gif" alt="Visa fler topplistor" /></a>

	</div>

	<br />

	<div class="mmAlbumBoxTop">
		<h3 class="mmWhite BoxTitle">Klubbar</h3>
	</div>
	<div class="mmRightMinSidaBox">

	{foreach from=$grupper item=grupp}
		<a href="{$urlHandler->getUrl(Grupp, URL_VIEW, $grupp->getId())}">{$grupp->getNamn()}</a>
		{if $grupp->getSkapareId() == $USER->getId()}<img src="/img/icons/star.gif" alt="Skapad av mig" class="mmStarText" />{/if}
		<br />
	{/foreach}

	<br />
	<img src="/img/icons/star.gif" alt="Skapad av mig" /> = skapad av mig

	</div>
	
	<br />
	
	{include file="fotoalbumblock.tpl"}

	<br />
	{if $sajtDelarObj->medlemHasAccess($USER,'minaQuiz')}
		{include file="minaquizblock.tpl"}
	{/if}

</div>

	<!-- END mmColumnRight -->

<!-- START mmColumnMiddle -->

{include file="kommunjakten.tpl"}


<div class="mmBlueBoxTop"><h3 class="mmWhite BoxTitle">Händelser</h3></div>
<div class="mmBlueBoxBg">

	<table class="mmFeedTable" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td></td>
			<td></td>
		</tr>
		{foreach from=$feed item=feedRow name="feedLoop"}
		{if !isset($senaste) || $senaste neq $feedRow->getDatum()|nice_date:"j F":"d"}
		<tr>
			<td colspan="2" class="mmFeedTableDatum"><b>{$feedRow->getDatum()|nice_date:"j F":"d"|capitalize}</b></td>
		</tr>
		{/if}
		{assign var=senaste value=$feedRow->getDatum()|nice_date:"j F":"d"}
		<tr>
			<td class="mmFeedTableKolumnEtt">
				{if $feedRow->isGrupp()}
				<a href="#" onclick="toggleFeedDetails(this, {$smarty.foreach.feedLoop.iteration}); return false;"><img src="/img/icons/plus.gif" alt="" /></a>
				{else}
				<img src="/img/icons/dot.gif" alt="" />
				{/if}	
			</td>
			<td>
				{$feedRow->getText()}<br />
				{if $feedRow->isGrupp()}
				<div id="mmFeedDetails{$smarty.foreach.feedLoop.iteration}" class="mmFeedDetails">
					{foreach from=$feedRow->listFeedItems() item=item}
					&nbsp;&raquo;&nbsp; {$item->getText()} ({$item->getDatum()})<br />
					{/foreach}
				</div>
				{/if}
			</td>
		</tr>	
		{/foreach}
	</table>

</div>

<div class="mmBlueBoxBottom"></div>
