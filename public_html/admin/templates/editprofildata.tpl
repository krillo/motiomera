<a href="{$urlHandler->getUrl(ProfilData, URL_ADMIN_LIST)}">Tillbaka till listan</a>
<h1>{if isset($texteditor)}

{$profildata->getNamn()}
{else}
	Ny Profilinformation
{/if}</h1>
{if isset($profildata) && $ADMIN->isTyp(ADMIN)}
<p>
<a href="{$urlHandler->getUrl(ProfilData, URL_ADMIN_DELETE, $profildata->getId())}" onclick="{jsConfirm msg="Är du säker på att du vill ta bort denna text?"}">Ta bort</a>
</p>
{/if}
<div  class="mmFloatRight">
<strong>Möjliga val</strong><br/><br/>
{foreach from=$profilDataVals item=profilDataVal}

	{$profilDataVal->getVarde()}
	
	<a href="{$urlHandler->getUrl(ProfilDataVal, URL_ADMIN_DELETE, $profilDataVal->getId())}"><em>[ta bort]</em></a>
	
	<br />
	
{foreachelse}
	<em>Inga val inlagda ännu.</em>
{/foreach}
</div>

<form action="{$urlHandler->getUrl(ProfilData, URL_ADMIN_SAVE, $profilDataId)}" method="post">
{if $ADMIN->isTyp(ADMIN)}

	<p>
		Namn:<br />
		<input type="text" name="namn" value="{if isset($profildata)}{$profildata->getNamn()}{/if}" />
	</p>
	<p>
		Beskrivning:<br />
		<input type="text" name="beskrivning" value="{if isset($profildata)}{$profildata->getBeskrivning()}{/if}" />
	</p>
{/if}

<p>Lägg till val (ett val per rad):<br/>
<textarea name="profildatavals"></textarea>
</p>

	<p>
		<input type="submit" value="Spara" />
	</p>
</form>