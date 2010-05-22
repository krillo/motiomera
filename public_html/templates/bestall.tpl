{if !empty($_GET.id)}
<h1>Ditt medlemskap har utgått</h1>
<p>
Förläng ditt medlemskap på MotioMera här!
</p>
{else}
<h1>Skaffa eller förläng ett medlemsskap</h1>
{/if}
<form action="{$urlHandler->getUrl(Order, URL_SEND)}" method="post" onsubmit="return motiomera_validateBestallForm(this);">
	<input type="hidden" name="typ" value="medlem" />
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
	
	<tr>
		<th>Erbjudande</th>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" class="mmKontotypTable">

				{foreach from=$campaignCodes item=details key=code name=codeLoop}
					<tr>
						<th{if $smarty.foreach.codeLoop.iteration eq count($campaignCodes)} class="mmBorderNone"{/if}><input type="radio" name="kontotyp" value="{$code}" /> {$details.text}
						{if isset($details.popupid)}
							&nbsp;<a href="javascript:;" onclick="mm_rapportera_show_help({$details.popupid},{$details.popupwidth},{$details.popupheight},'topleft')">L&auml;s mer</a>
						{/if}</th>
						
						{*}popupwidth,popupheight samt id hämtas från order.php classen{*}
						<td{if $smarty.foreach.codeLoop.iteration eq count($campaignCodes)} class="mmBorderNone"{/if}>{$details.pris} kr</td>
					</tr>
				{/foreach}
			</table>
		</td>
		<tr class="mmLastRow">
			<td></td>
			<td><input type="submit" value="Gå vidare till beställning" /></td>
		</tr>
	</table>
</form>