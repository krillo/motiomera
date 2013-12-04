<h1>Lag</h1>
<p>
	<a href="{$urlHandler->getUrl(Lag, URL_CREATE)}">Lägga in nytt lag</a>
</p>
<p>
{foreach from=$listLag item=thisLag}

<a href="{$urlHandler->getUrl(Lag, URL_EDIT, $thisLag->getId())}">{$thisLag->getNamn()}</a><br />

{/foreach}
</p>