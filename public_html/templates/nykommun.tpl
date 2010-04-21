{if $kommunvapen}
	<img src="{$kommunvapen->getUrl()}" alt="Kommunvapen" class="mmKommunVapenImg" valign="middle" /><br/>
{/if}
<h1>Välkommen till {$kommun->getNamn()}!</h1>

{if $kommunbild}
	{assign var=bild value=$kommunbild->getFramsidebild()}
	{if $bild}
	<div id="mmQuizKommunBild">
		<a href="{$urlHandler->getUrl(Kommunbild, URL_VIEW, $kommunbild->getId())}"><img src="{$bild->getUrl()}" class="mmImgBorderGray" /></a><br />
		<b>{$kommunbild->getNamn()}</b>
	</div>
	{/if}
{/if}


Kommunen har {$kommun->getFolkmangd()} invånare och ligger i {$kommun->getLan()}.

<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}">Läs mer om {$kommun->getNamn()}</a><br/><br/>
Vad vet du om denna kommun? Gör vårt <a href="{$urlHandler->getUrl(Quiz, URL_VIEW, $kommun->getUrlNamn())}">kommunquiz för {$kommun->getNamn()}</a>!<br/>
<br/>
<br/>
Du kan också gå tillbaka till <a href="/pages/minsida.php">Min Sida</a>.
<br class="mmClearBoth" />
