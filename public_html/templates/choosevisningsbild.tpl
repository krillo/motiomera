<h1>Välj visningsbild</h1>
<p>
	<b>Nuvarande visningsbild:</b><br />
	<img src="{$visningsbild->getUrl()}" />
</p>

{if $unapprovedVisningsbild}
<p>
	<b>Uppladdad bild</b><br />
	<small>(Inväntar godkännande av moderator)</small><br />

	<img src="{$unapprovedVisningsbild->getUrl()}" />
	<br />
	<a href="{$urlHandler->getUrl(CustomVisningsbild, URL_DELETE, $unapprovedVisningsbild->getNamn())}">Ångra</A>
</p>
{/if}
<p>
	
	<form action="{$urlHandler->getUrl(CustomVisningsbild, URL_SAVE)}" enctype="multipart/form-data" method="post">
		<h2>Ladda upp bild</h2>
		<input type="file" name="image" />
		<br />
		<input type="submit" value="Ladda upp" />
	</form>
</p>

<p>
	<b>Välj en standardbild</b><br />
		{foreach from=$visningsbilder item=tempVisningsbild}
		<a href="{$urlHandler->getUrl(Visningsbild, URL_SAVE, $tempVisningsbild->getNamn())}"><img src="{$tempVisningsbild->getUrl()}" /></a><br />
		{/foreach}
	</form>
</p>