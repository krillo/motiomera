<h1>{$namn}</h1>

<p>
<img src="{$bild->getUrl()}" alt="{$namn}" /><br />
</p>
<p>
{$beskrivning}
</p>

<p>
	<a href="{$urlHandler->getUrl("Kommun", URL_VIEW, $kommun->getNamn())}" title="Tillbaka">Tillbaka</a>
</p>