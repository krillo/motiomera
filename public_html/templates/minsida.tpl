
<div class="mmh1 mmMarginBottom mmProfilH1" style="float:left;width:400px;">
{if $selfProfile}
	<form action="" method="post" class="margin0" onsubmit="mm_saveStatus(this.status.value); return false;" id="mmUpdateStatusForm">
{/if}  
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
<div style="float:left;width:200px;height: 30px;">
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FMotioMera%2F63606043494&amp;send=false&amp;layout=standard&amp;width=200&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=30" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:30px;" allowTransparency="true"></iframe>  
</div>
<div class="clear"></div>


{include file=steggrafik.tpl}



<!-- START mmColumnRightMinSida -->
{* include file="widget_userblogg.tpl" *}
<div id="mmColumnRightMinSida">
  {include file="widget_steglista.tpl"}  
  {include file="widget_tidigare_tavling.tpl"}  
  {*include file="widget_klubbar.tpl"*}  
	{include file="fotoalbumblock.tpl"}
	
	{*if $sajtDelarObj->medlemHasAccess($USER,'minaQuiz')}
		{include file="minaquizblock.tpl"}
	{/if*}
</div>


	<!-- END mmColumnRight -->

<!-- START mmColumnMiddle -->

{include file="kommunjakten.tpl"}


<div class="mmBlueBoxTop"><h3 class="BoxTitle">Händelser</h3></div>
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
