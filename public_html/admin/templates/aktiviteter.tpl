<h1>Aktiviteter</h1>
<p>
Klicka på resp aktivitet för att redigera den
</p>
<form action="{$urlHandler->getUrl(Aktivitet, URL_ADMIN_SAVE)}" method="post">
		<input type="hidden" name="id" value="{if isset($aktivitet)}{$aktivitet->getId()}{/if}" />
		{*}<h3>{if isset($aktivitet)}Redigera aktivitet <a href="{$urlHandler->getUrl(Aktivitet, URL_ADMIN_LIST)}">[Ny]</a>{else}Ny aktivitet{/if}</h3>{*}
		<p>
			<b>Namn</b><br/>
			<input type="text" name="namn" {if isset($aktivitet)}value="{$aktivitet->getNamn()}"{/if}/>
		</p>
		<p>
			<b>Svårighetsgrad<small> (lätt/medel/tuff)</small><br />
			<input type="text" name="svarighetsgrad" {if isset($aktivitet)}value="{$aktivitet->getSvarighetsgrad()}"{/if}/>
		</p>
		<p>
			<b>Enhet</b><br />
			<input type="text" name="enhet" {if isset($aktivitet)}value="{$aktivitet->getEnhet()}"{/if}/>
		</p>
		<p>
			<b>Värde</b> <small>(Hur många steg en enhet motsvarar)</small><br />
			<input type="text" name="varde" {if isset($aktivitet)}value="{$aktivitet->getVarde()}"{/if}/>
		</p>
		<p>
			<b>Beskrivning</b><br />
			<input type="text" name="beskrivning" {if isset($aktivitet)}value="{$aktivitet->getBeskrivning()}"{/if}/>
		</p>
		<p>
			<input type="submit" value="{if isset($aktivitet)}Spara{else}Lägg till{/if}" />
		</p>
</form>

<p>
	{foreach from=$aktiviteter item=thisAktivitet}
		-<a href="{$urlHandler->getUrl(Aktivitet, URL_ADMIN_EDIT, $thisAktivitet->getId())}">{$thisAktivitet->getNamn()} ({$thisAktivitet->getSvarighetsgrad()})</a> - <a href="/admin/actions/save.php?table=aktivitet_s&borttagen=true&id={$thisAktivitet->getId()}" class="mmRod">Ta bort</a><br />
	{/foreach}
</p>
