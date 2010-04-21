

<h1>{$kommunNamn}</h1>

{if $kommunvapen}
	<img src="{$kommunvapen->getUrl()}" />
{else}
	[kommunvapen saknas].
{/if}
<br/>
<h2>Snabbfakta:</h2>
<strong>Huvudort:</strong> {$kommun->getOrt()}<br/>
<strong>Areal:</strong> {$kommun->getAreal()|nice_tal} km2<br/>
<strong>Folkmängd:</strong> {$kommun->getFolkmangd()|nice_tal}<br/>
<a href="http://{$kommun->getWebb()}">{$kommun->getWebb()}</a><br/>
<br/><hr/><br/>
<div id="motiomera_kommun_left">
{$kommun->getInfo()|nl2br}
</div>
<div id="motiomera_kommun_right">
<strong>Medlemmar i kommunen just nu</strong><br/><br/>
<table border="1" cellspacing="0" cellpadding="10">
{foreach from=$medlemmar item=medlem}
<a href="{$urlHandler->getUrl(Medlem, URL_VIEW,$medlem->getId())}">{$medlem->getANamn()}</a><br/>
{/foreach}
</table>

</div>
<div class="motiomera_clear">


</div>

<p>
	<strong>Grannkommuner:</strong><br />
{foreach from=$avstand item=thisavstand}

	<a href="{$urlHandler->getUrl(Kommun, URL_VIEW,$kommunnamn[$thisavstand.id])}">{$kommunnamn[$thisavstand.id]}</a>
	
	<br />

{foreachelse}
	Inga grannkommuner finns.
{/foreach}
</p>
</form>

<h2>Bilder från kommunen</h2>
<table border="1" cellspacing="0" cellpadding="10">
{foreach from=$kommunbilder item=kommunbild}
{assign var=bild value=$kommunbild->getBild()}
<tr>
	<td>
		<b>{$kommunbild->getNamn()}</b><br />
		<a href="{$bild->getUrl()}"><img src="{$bild->getUrl()}" width="150" /></a><br />
	</td>
</tr>
{/foreach}
</table>
