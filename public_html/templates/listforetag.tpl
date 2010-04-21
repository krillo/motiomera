<h1>Företag</h1>

<p>

	{foreach from=$foretagList item=foretag}	
		<a href="{$urlHandler->getUrl(Foretag, URL_VIEW, $foretag->getId())}">{$foretag->getNamn()}</a><br/>
	{/foreach}

</P>