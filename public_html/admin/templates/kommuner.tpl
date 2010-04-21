<h1>Kommuner</h1>
<p>
	<a href="{$urlHandler->getUrl(Kommun, URL_CREATE)}">Ny kommun</a>
</p>

<p>
	{foreach from=$kommuner item=kommun}
		<a href="{$urlHandler->getUrl(Kommun, URL_EDIT, $kommun->getId())}">{$kommun->getNamn()}</a><br />
	{/foreach}
</p>
