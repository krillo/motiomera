<h1>Visningsbilder</h1>
<p>&rarr; <a href="{$urlHandler->getUrl(CustomVisningsbild, URL_ADMIN_LIST)}">Uppladdade visningsbilder</a></p>
<table border="0" cellpadding="0" cellspacing="0">
{foreach from=$visningsbilder item=visningsbild}
<tr>
	<td class="mmWidthSexNollPixlar"><a href="{$visningsbild->getUrl()}"><img src="{$visningsbild->getUrl()}" width="50" /></a></td>
	<td>
		{$visningsbild->getNamn()}<br />
		<small>
			<a href="{$urlHandler->getUrl(Visningsbild, URL_ADMIN_DELETE, $visningsbild->getNamn())}" onclick="{jsConfirm msg="Är du säker på att du vill ta bort den här visningsbilden?"}">Ta bort</a>
			{if !$visningsbild->isStandard()}
				<br />
				<a href="{$urlHandler->getUrl(Visningsbild, URL_SET_DEFAULT, $visningsbild->getNamn())}">Gör standard</a>
			{/if}
		</small>
	</td>
</tr>

{/foreach}
</table>
<p>
<b>Ladda upp visningsbild</b>

<form enctype="multipart/form-data" action="{$urlHandler->getUrl(Visningsbild, URL_ADMIN_SAVE)}" method="post">
	<input type="file" name="image" /><br />
	<input type="submit" value="Ladda upp" />
</form>
</p>