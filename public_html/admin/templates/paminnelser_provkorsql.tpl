<h1>Provkörning av påminnelse</h1>
<a href="{$urlHandler->getUrl(Paminnelser, URL_ADMIN_LIST)}">&laquo; Gå tillbaka</a><br /><br />
<fieldset>
<legend>SQL-fråga som körts</legend>
<xmp>
{$query}
</xmp>
</fieldset>
<h2>Resultat</h2>
{foreach from=$resultat key=nyckel item=resultatdel}
<fieldset>
{if !is_numeric($nyckel)}<legend>Till: &lt;<strong>{$nyckel}</strong>&gt; Ämne: <strong>{$resultatdel.subject}</strong></legend>{/if}
<pre>
{$resultatdel.text}
</pre>
</fieldset><br />
{foreachelse}
	<span class="mmPaminnelserNone">Frågan genererade inga resultat</span>
{/foreach}