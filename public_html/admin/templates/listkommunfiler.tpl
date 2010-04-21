
<h1>Kommundialekter</h1>

<p>
	<b>Ej godkända ljudfiler</b>
</p>
<p>
	{foreach from=$dialekter item=dialekt}
	
	<a href="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_EDIT, $dialekt->getId())}">{$dialekt->getFilnamn()}</a><br />
	
	{foreachelse}
	
	Inga nya ljudfiler.
	
	{/foreach}
	
</p>

<p>
	<b>Alla ljudfiler</b>
</p>
<p>
	{foreach from=$alladialekter item=dialekt}
	
	<a href="{$urlHandler->getUrl(Kommundialekt, URL_ADMIN_EDIT, $dialekt->getId())}">{$dialekt->getFilnamn()}</a><br />
	
	{foreachelse}
	
	Inga ljudfiler tillagda.
	
	{/foreach}
	
</p>