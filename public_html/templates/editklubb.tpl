<h1>{if isset($grupp)}Hantera{else}Skapa{/if} klubb</h1>
<form action="{if isset($grupp)}{$urlHandler->getUrl(Grupp, URL_SAVE, $grupp->getId())}{else}{$urlHandler->getUrl(Grupp, URL_SAVE, null)}{/if}" method="post"{if !isset($grupp)} onsubmit="if(this.namn.value.length < 3) return false; {*}if (mm_skapaGruppValidera(this)==false) return false;{*}"{else} onsubmit="mm_skapaGruppValidera(this); return false;"{/if}>
	{if isset($grupp)}
	<input type="hidden" name="id" value="{$grupp->getId()}" />	
	{/if}
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<tr>
			<th>Klubbens namn</th>
			<td{if isset($grupp)} class="mmRawText"{/if}>
				{if isset($grupp)}
					<a href="{$urlHandler->getUrl(Grupp, URL_VIEW, $grupp->getId())}">{$grupp->getNamn()}</a>
				{else}
				<input type="text" name="namn" onfocus="getById('mmNamnError').style.display='none'; " onblur="mm_ajaxValidera('mmNamnError', 'gruppnamn', this.value);" />
				<span id="mmNamnError" class="mmRed mmFormError">Namnet är upptaget</span>
				{/if}
			</td>
		</tr>
		<tr>
			<th>Åtkomst</th>
			<td>{mm_html_options options=$opt_publik selected=$sel_publik name=publik}</td>
		</tr>
		{if !isset($grupp) && count($alla_kontakter)>0}
		<tr>
			<th>Bjud in Motiomera-vänner</th>
			<td>
			{html_checkboxes name='mid' options=$alla_kontakter separator='<br />'}
			</td>

		</tr>
		{if isset($grupp)}
		<tr>
			<th>Bjud in andra</th>
			<td><input type="text" name="epost" /><br />För att bjuda in en kompis som ännu inte är medlem på Motiomera.se till din klubb kan du skicka en inbjudan via e-post.<br />Vill du skriva in flera epostadress så skilj dem åt med komma (epost@example.com,epost2@example.com)</td>
		</tr>
		{/if}
		{/if}
		{if isset($grupp)}
		<tr>
			<th>Skapad</th>
			<td class="mmRawText">{$grupp->getSkapad()}</td>
		</tr>
		{/if}
		<tr>
			<th>Startdatum</th>
			<td class="mmRawText">
				
				<input type="hidden" name="startdatum" value="{$time}" id="mmKlubbFormStartdatum" />
			
				<span id="klubbStartdatum">
					{if isset($today)}{$today|nice_date:"j F Y":"d"|capitalize}{else}{$time|nice_date:"j F Y":"d"|capitalize}{/if}
				</span>
				
				<a href="#" onclick="mm_visaKlubbKalender(); return false;">Välj datum</a>
			
			</td>
		</tr>

		</tr>
	{if isset($grupp)}
		<tr>
			<th>Medlemmar</th>
			<td class="mmRawText">
				Totalt {$medlemmar|@count} {$medlemmar|@count|mm_countable:"medlem":"medlemmar"}
					{if count($medlemmar)>0}
					- <a href="#" onclick="var e = getById('mmMedlemLista'); e.style.display = (e.style.display == '' || e.style.display == 'none') ? 'block' : 'none'; return false;">Visa / dölj &raquo;</a><br>
					<div id="mmMedlemLista" class="mmHide">
						<ul>
							{foreach from=$medlemmar item=medlem}	
							<li><a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}">{$medlem->getANamn()}</a></li>
							{/foreach}
						</ul>
					</div>
					{else}<br>
					{/if}
				<br>Totalt {$requestMedlemmar|@count} {$requestMedlemmar|@count|mm_countable:"medlem":"medlemmar"} som begärt inträde i klubben
					{if count($requestMedlemmar)>0}
					- <a href="#" onclick="var e = getById('mmMedlemListaReq'); e.style.display = (e.style.display == '' || e.style.display == 'none') ? 'block' : 'none'; return false;">Visa / dölj &raquo;</a>
					<div id="mmMedlemListaReq" class="mmHide">
						<ul>
							{foreach from=$requestMedlemmar item=reqMedlem}	
							<li><a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $reqMedlem->getId())}">{$reqMedlem->getANamn()}</a></li>
							{/foreach}
						</ul>
					</div>
					{else}<br>
					{/if}
				<br>Totalt {$invMedlemmar|@count} {$invMedlemmar|@count|mm_countable:"medlem":"medlemmar"} som är inbjudna.
					{if count($invMedlemmar)>0}
					- <a href="#" onclick="var e = getById('mmMedlemListaInv'); e.style.display = (e.style.display == '' || e.style.display == 'none') ? 'block' : 'none'; return false;">Visa / dölj &raquo;</a><br>
					<div id="mmMedlemListaInv" class="mmHide">
						<ul>
							{foreach from=$invMedlemmar item=invMedlem}	
							<li><a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $invMedlem->getId())}">{$invMedlem->getANamn()}</a></li>
							{/foreach}
						</ul>
					</div>
					{else}<br>
					{/if}
				
				{if isset($ignored)}
				<br>Totalt {$ignored|@count} {$ignored|@count|mm_countable:"medlem har blivit nekad":"medlemmar har blivit nekade"} medlemskap.- <a href="#" onclick="var e = getById('mmMedlemListaIgn'); e.style.display = (e.style.display == '' || e.style.display == 'none') ? 'block' : 'none'; return false;">Visa / dölj &raquo;</a><br>
				<div id="mmMedlemListaIgn" class="mmHide">
					<br/>
					{foreach from=$ignored item=thisignored}
						{$thisignored->getFNamn()} {$thisignored->getENamn()} <a href="/actions/unignore.php?gid={$grupp->getId()}&amp;mid={$thisignored->getId()}">Godkänn medlemskap</a><br />
					{/foreach}
				</div>
				{/if}

			</td>
		</tr>
	{else}
		<tr>
			<th>Bjud in andra</th>
			<td>
				För att bjuda in en kompis som ännu inte är medlem på Motiomera.se till din klubb kan du skicka en inbjudan via e-post.<br />Vill du skriva in flera epostadress så skilj dem åt med komma (epost@example.com, epost2@example.com)
			</td>
		</tr>
		<tr>
			<th>E-postadresser</th>
			<td><input type="text" name="epost" /></td>
		</tr>	
	{/if}
		<tr class="mmLastRow">
			<td></td>
			<td>
				<input type="submit" value="{if isset($grupp)}Spara ändringar{else}Skapa{/if}" />
				</form>
				{if isset($grupp)}
				<form action="{if isset($grupp)}{$urlHandler->getUrl(Grupp, URL_SAVE, $grupp->getId())}{else}{$urlHandler->getUrl(Grupp, URL_SAVE, null)}{/if}" method="post"{if !isset($grupp)} onsubmit="if(this.namn.value.length < 3) return false; {*}if (mm_skapaGruppValidera(this)==false) return false;{*}"{else} onsubmit="mm_skapaGruppValidera(this); return false;"{/if} style="display: inline;" >
					<input type="hidden" name="id" value="{$grupp->getId()}" />	
					<input type="hidden" name="tabort" value="ta bort">
					<input type="submit" value="Ta bort klubb" onclick="return confirm('Är du säker på att du vill ta bort den här klubben?');" />
				</form>
				{/if}	
			</td>
		</tr>	
	</table>


{if isset($grupp) && isset($ansokningar)}

	<h2 class="mmMarginBottom">Ansökningar</h2>
	<table border="0" cellpadding="0" cellspacing="0" class="mmMarginBottom mmWidthTreHundraPixlar">
		<tr>
			<th>Medlem</th>
			<th>Åtgärd</th>
		</tr>
	
	{foreach from=$ansokningar item=ansokning}
		<tr>
			<td>{$ansokning->getANamn()}</td>
			<td>
				<a href="/actions/answerrequest.php?gid={$grupp->getId()}&amp;mid={$ansokning->getId()}&amp;do=accept">Godkänn</a> / 
				<a href="/actions/answerrequest.php?gid={$grupp->getId()}&amp;mid={$ansokning->getId()}&amp;do=deny">Avböj</a>
			</td>
		</tr>
	{/foreach}
	</table>

{/if}

{if isset($grupp)}

{if count($opt_kontakter)>0}
<h2>Bjud in fler vänner</h2>

{*} onsubmit="{literal}if(this.mid.value == ''){alert('Du måste välja en medlem'); return false;}{/literal}"> {*}
<form action="{$urlHandler->getUrl(Grupp, URL_INVITE)}" method="post">
	<input type="hidden" name="gid" value="{$grupp->getId()}" />
	<input type="hidden" name="referer" value="editgrupp" />
	{html_checkboxes name='mid' options=$opt_kontakter separator='<br />'}
	{*}{mm_html_options options=$opt_kontakter name=mid}{*}
	<br><input type="submit" value="Bjud in" />
</form>
{/if}


<h2>Bjud in via e-post</h2>
<p>För att bjuda in en kompis som ännu inte är medlem på Motiomera.se till din klubb kan du skicka en inbjudan via e-post.<br />Vill du skriva in flera epostadress så skilj dem åt med komma (epost@example.com,epost2@example.com)</p>
<form action="{$urlHandler->getUrl(Grupp, URL_EXT_INVITE)}" method="post">
	<input type="hidden" name="id" value="{$grupp->getId()}" />
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<tr>
			<th>E-postadress</th>
			<td><input type="text" name="epost" /></td>
		</tr>
		<tr class="mmLastRow">
			<td></td>
			<td><input type="submit" value="Skicka inbjudan" /></td>
		</tr>
	</table>
</form>

{/if}
