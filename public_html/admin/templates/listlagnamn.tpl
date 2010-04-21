<h1>Lagnamn</h1>
<p>
	<a href="{$urlHandler->getUrl(LagNamn, URL_CREATE)}">Lägga in nytt lag</a>
</p>
<p>
{foreach from=$listLagNamn item=thisLagNamn}

<img src="{$thisLagNamn->getImgUrl()}"  />
<a href="{$urlHandler->getUrl(LagNamn, URL_EDIT, $thisLagNamn->getId())}">{$thisLagNamn->getNamn()}</a><br />

{/foreach}
</p>