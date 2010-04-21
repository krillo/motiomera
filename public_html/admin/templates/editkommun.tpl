<h1>{if isset($kommun)}Redigera kommun{else}Ny kommun{/if}</h1>
<p>
{if isset($kommun)}
<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}" target="_blank" >Se kommunsidan</a>
{/if}
{if $ADMIN->isTyp(EDITOR)}
<a href="{$urlHandler->getUrl(Kommun, URL_ADMIN_LIST)}">Tillbaka</a>
{/if}
{if isset($kommun) && $ADMIN->isTyp(ADMIN)}
<a href="{$urlHandler->getUrl(Kommun, URL_ADMIN_DELETE, $kommun->getId())}" onclick="{jsConfirm msg="Är du säker på att du vill ta bort den här kommunen och alla dess bilder?"}">Ta bort</a>
{/if}
</p>
<script type="text/javascript" src="http://www.google.com/jsapi/"></script>
<script type="text/javascript">
{literal}
	$(document).ready(function()
	{
		editKommun();
	});
{/literal}
</script>
<form action="{$urlHandler->getUrl(Kommun, URL_SAVE, $kommunId)}" method="post">
<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
	{if $ADMIN->isTyp(EDITOR)}
	{if !isset($kommun)}
	<tr>
		<th>Utomlands</th>
		<td><input type="checkbox" class="abroad" name="abroad" {if $checkIfAbroad}checked="checked"{/if} /></td>
	</tr>
	{/if}
	<tr id="">
		<th>Namn</th>
		<td><input type="text" name="namn" value="{$kommunNamn}" /></td>
	</tr>
	<tr id="area">
		<th>Ort</th>
		<td>
			<input type="text" name="ort" value="{if isset($kommun)}{$kommun->getOrt()}{/if}">
			{if !isset($kommun)}<br />
			<div id="sameAsArea">
				<input type="checkbox" name="sameort" value="true" /><small>Samma som kommun</small>
			</div>
			{/if}
		</td>
	</tr>
	{if $abroad == 'false'}
	<tr id="areacode">
		<th>Kommunkod</th>
		<td><input type="text" name="kod" value="{if isset($kommun)}{$kommun->getKod()}{/if}" maxlength="4" /></td>
	</tr>
	<tr id="county">
		<th>Län</th>
		<td>{mm_html_options name=lan options=$opt_lan selected=$sel_lan}</td>
	</tr>
	{/if}
	{else}
	<tr>
		<th>Kommun</th>
		<td class="mmRawText">{$kommun->getNamn()}
	</tr>
	{/if}
	<tr>
		<th>Areal: (km2)</th>
		<td><input type="text" name="areal" value="{if isset($kommun)}{$kommun->getAreal()}{/if}" /></td>
	</tr>
	<tr>
		<th>Folkmängd</th>
		<td><input type="text" name="folkmangd" value="{if isset($kommun)}{$kommun->getFolkmangd()}{/if}" /></td>
	</tr>
	<tr>
		<th>Webbplats</th>
		<td><input type="text" name="webb" value="{if isset($kommun)}{$kommun->getWebb()}{/if}" /></td>
	</tr>
	<tr>
		<th>Info</th>
		<td>
			<textarea cols="43" rows="16" name="info">{if isset($kommun)}{$kommun->getInfo()}{/if}</textarea>
		</td>
	</tr>
	{if isset($kommun)}
	<tr>
		<th>Bildlayout</th>
		<td>
			<select name="framsidebildAuto">
				<option value="1"{if $kommun->getFramsidebildAuto()} selected="selected"{/if}>Automatisk</option>
				<option value="0"{if !$kommun->getFramsidebildAuto()} selected="selected"{/if}>Manuell</option>
			</select>
		</td>
	</tr>
	{/if}
	<tr class="mmLastRow">
		<td></td>
		<td><input type="submit" value="Spara" /></td>
	</tr>
</table>
</form>


{if isset($kommun)}

{if $ADMIN->isTyp(EDITOR)}
<hr />
<form action="{$urlHandler->getUrl(Kommunavstand, URL_SAVE, $kommun->getId())}" method="post">

	<p>
		<strong>Nytt avstånd:</strong><br />
		{mm_html_options name=target options=$opt_kommuner}
		<input type="text" name="km" size="4" /> km
		<input type="submit" value="Lägg till" />
	</p>
	
	<p>
		<strong>Avstånd:</strong><br />
	{foreach from=$avstand item=thisavstand}
	
		{$allakommunnamn[$thisavstand.id]} {$thisavstand.km} km
		
		<a href="{$urlHandler->getUrl(Kommunavstand, URL_DELETE, $avstandArgs[$thisavstand.id])}">X</a>
		
		<br />
	
	{foreachelse}
		Inga avstånd inlagda ännu.
	{/foreach}
	</p>
</form>
<hr />
<h2>Kommunquiz</h2>

<a href="{$urlHandler->getUrl(QuizFraga, URL_ADMIN_LIST, $kommun->getId())}">Redigera quiz-frågor</a>
<hr />

{/if}

<h2>Ljudfiler</h2>
<p>
{foreach from=$dialekter item=dialekt name=loop_dialekter}

<a href="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_EDIT, $dialekt->getId())}">{$dialekt->getFilnamn()}</a><br />
{foreachelse}
Inga ljudfiler tillagda.
{/foreach}
<p>
<p>
	<a href="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_CREATE, $kommun->getId())}">Lägg till ljudfil</a>
</p>
<hr />

<h2>Kommunbilder</h2>
<table border="0" cellspacing="0" cellpadding="10">
<tr>
	<td>
		<b>Kommunvapen</b><br />
		{if $kommunvapen}
			<img src="{$kommunvapen->getUrl()}" />
		{else}
			Ingen bild.
		{/if}
	</td>
	<td>
		<form action="{$urlHandler->getUrl(Kommunvapen, URL_SAVE, $kommun->getId())}" enctype="multipart/form-data" method="post">
			<input type="file" name="image" /><br />
			<input type="submit" value="Ladda upp" />
		</form>
		{if $kommunvapen}<a href="{$urlHandler->getUrl(Kommunvapen, URL_DELETE, $kommun->getId())}">Ta bort</a>{/if}
	</td>
</tr>
<tr>
	<td>
		<b>Kommunkarta</b><br />
		{if $kommunkarta}
			<a href="{$kommunkarta->getUrl()}"><img src="{$kommunkarta->getUrl()}" width="150" /></a>
		{else}
			Ingen bild.
		{/if}
	</td>
	<td>
		<form action="{$urlHandler->getUrl(Kommunkarta, URL_SAVE, $kommun->getId())}" enctype="multipart/form-data" method="post">
			<input type="file" name="image" /><br />
			<input type="submit" value="Ladda upp" />
		</form>
		{if $kommunkarta}<a href="{$urlHandler->getUrl(Kommunkarta, URL_DELETE, $kommun->getId())}">Ta bort</a>{/if}
	</td>
</tr>
{foreach from=$kommunbilder item=kommunbild}
{assign var=bild value=$kommunbild->getBild()}
<tr>
	<td>
		<b>{$kommunbild->getNamn()}</b><br />
		<a href="{$bild->getUrl()}"><img src="{$bild->getUrl()}" width="150" /></a><br />
	</td>
	<td>
		<form action="{$urlHandler->getUrl(Kommunbild, URL_SAVE, $kommunbild->getId())}" enctype="multipart/form-data" method="post">
		
			<input type="hidden" name="kid" value="{$kommun->getId()}" /><br />
			Namn:<br />
			<input type="text" name="namn" value="{$kommunbild->getNamn()}" /><br />
			Beskrivning: <br />
			<textarea name="beskrivning" cols="40" rows="5">{$kommunbild->getBeskrivning()} </textarea><br />
			<input type="checkbox" name="miniatyr" id="miniatyr{$kommunbild->getId()}" {if $kommunbild->getFramsidebild()}checked="checked" {/if} {if !$kommun->getFramsidebildAuto()}onclick="getById('mm_KommunbildInstallningar{$kommunbild->getId()}').style.display=(this.checked)?'block':'none';"{/if} /> <label for="miniatyr{$kommunbild->getId()}">Visa på kommunsidan</label>


			<div id="mm_KommunbildInstallningar{$kommunbild->getId()}" style="{if $kommunbild->getFramsidebild() && $kommun->getFramsidebildAuto()}display: none; {/if}border: 1px solid #ccc; padding: 0 10px 0 10px; width: 200px;">
				<p>
					<h3>Inställningar kommunbild</h3>
					{assign var=framsidebild value=$kommunbild->getFramsidebild()}
					{if $framsidebild}
						{if $framsidebild->getBredd() eq $FB_FULLBREDD}
							{assign var=storlek value="full"}
						{elseif $framsidebild->getBredd() eq $FB_HALVBREDD}
							{assign var=storlek value="halv"}
						{elseif $framsidebild->getBredd() eq $FB_TREDELBREDD}
							{assign var=storlek value="tredel"}
						{/if}
					{else}
						{assign var=storlek value=""}
					{/if}
					<input type="radio" name="thumbsize" id="thumbsize0" value="full" {if $storlek eq "full"}checked="checked" {elseif $storlek eq null}checked="checked" {/if} /><label for="thumbsize0">Full bredd</label><br />
					<input type="radio" name="thumbsize" id="thumbsize1" value="halv" {if $storlek eq "halv"}checked="checked" {/if}/><label for="thumbsize1">Halv bredd</label><br />
					<input type="radio" name="thumbsize" id="thumbsize2" value="tredel" {if $storlek eq "tredel"}checked="checked" {/if}/><label for="thumbsize2">Tredjedels bredd</label><br />	
				</p>
				<p>
					Höjd <small>(Lämna tom för auto)</small><br />
					<input type="text" name="thumbHeight" size="4" value="" />
				</p>
			</div>

			<p>
				<input type="submit" value="Spara" />
			</p>
		</form>
		<a href="{$urlHandler->getUrl(Kommunbild, URL_DELETE, $kommunbild->getId())}" onclick="{jsConfirm msg="Vill du verkligen ta bort den här bilden?"}">Ta bort</a>
	</td>
</tr>
{/foreach}
<tr>
	<td colspan="2">
		<hr />
		<h1>Lägg till bild</h1>
		<form action="{$urlHandler->getUrl(Kommunbild, URL_SAVE)}" enctype="multipart/form-data" method="post">
			<input type="hidden" name="kid" value="{$kommun->getId()}" />
			<p>

				<input type="file" name="image" />
			</p>
			<p>
				Namn<br />
				<input type="text" name="namn" />
			</p>
			<p>
				Beskrivning<br />
				<textarea name="beskrivning" cols="20" rows="6" class="mmWidthHundraProcent"></textarea>
			</p>
			<p>
				<input type="checkbox" id="miniatyr" name="miniatyr" value="true" {if !$kommun->getFramsidebildAuto()}onclick="getById('mm_KommunbildInstallningar').style.display = (this.checked) ? 'block' : 'none';"{/if}/> <label for="miniatyr">Visa på kommunsidan</label><br />
			</p>
			<div id="mm_KommunbildInstallningar" style="display: none; border: 1px solid #ccc; padding: 0 10px 0 10px; width: 200px;">
				<p>
					<h3>Inställningar kommunbild</h3>

					<input type="radio" name="thumbsize" id="thumbsize0" value="full" checked="checked" /><label for="thumbsize0">Full bredd</label><br />
					<input type="radio" name="thumbsize" id="thumbsize1" value="halv" /><label for="thumbsize1">Halv bredd</label><br />
					<input type="radio" name="thumbsize" id="thumbsize2" value="tredel" /><label for="thumbsize2">Tredjedels bredd</label><br />	
				</p>
				<p>
					Höjd <small>(Lämna tom för auto)</small><br />
					<input type="text" name="thumbHeight" size="4" />
				</p>
			</div>
			<p>
				<input type="submit" value="Lägg till" />
			</p>
		</form>
	</td>
</tr>


</table>
{/if}
