<h1>Företag</h1>
<p>
	<a href="{$urlHandler->getUrl(Mal, URL_ADMIN_CREATE)}">Lägg till mål</a>
</p>
<p>
{foreach from=$listMal item=thisMal}

<a href="{$urlHandler->getUrl(Mal, URL_ADMIN_EDIT, $thisMal->getId())}">{$thisMal->getNamn()}</a><br />

{/foreach}
</p>
