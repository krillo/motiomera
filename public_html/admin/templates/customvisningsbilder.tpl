<h1>Uppladdade Visningsbilder</h1>
<p>
	<b>Icke godkända</b><br />
	{foreach from=$unapproved item=tempVisningsbild}
		<img src="{$tempVisningsbild->getUrl()}" /><br />
		<a href="{$urlHandler->getUrl(CustomVisningsbild, URL_ADMIN_APPROVE, $tempVisningsbild->getNamn())}">Godkänn</a> Avböj
	{foreachelse}
		Inga nya visningsbilder.
	{/foreach}
</p>

<p>
	<b>Godkända</b><br />
	{foreach from=$approved item=tempVisningsbild}
		<img src="{$tempVisningsbild->getUrl()}" /><br />
	{foreachelse}
		Inga visningsbilder.
	{/foreach}
</p>