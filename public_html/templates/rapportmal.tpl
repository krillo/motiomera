<h1>Mål</h1>


{if $currentMal}
<p>
	{if $malManager->getKmToNextMal() eq 0}
	Du har kommit fram till {$currentMal->getNamn()}!
	{else}
	Du är just nu påväg till {$currentMal->getNamn()} och har {$malManager->getKmToNextMal()} km kvar.<br />
	<a href="{$urlHandler->getUrl(MalManager, URL_DELETE)}">Välj ett annat mål</a>
	{/if}
</p>
{/if}

{if !$malManager->harMal()}
<form action="{$urlHandler->getUrl(MalManager, URL_SAVE)}" method="post">
	<p>
		Välj ett mål: <br />
		{mm_html_options name=mid options=$opt_mal}
		<input type="submit" value="Lägg till" />
	</p>
</form>
{/if}