<h1>Påminnelsesystemet</h1>
<h2>Påminnelser</h2>
<div id="existerande_paminnelser">
	{if $superAdmin}
	<a href="{$urlHandler->getUrl('PaminnelseSQL', URL_ADMIN_CREATE)}">Skapa ny påminnelse</a>
	{else}
	<span class="mmPaminnelseNone"><b>Bara <u>Superadmin</u> kan skapa nya SQL-satser</b></span>
	{/if}
	<table border="0" cellspacing="10">
		<tr>
			<th>Namn/Beskrivning</th>
			<th>Hantera</th>
			<th>Används&nbsp;av...</th>
		</tr>
	{foreach from=$queries item=query}
		<tr>
			<td>{$query.namn}</td>
			<td>{if $superAdmin}<a href="{$urlHandler->getUrl(PaminnelseSQL, URL_ADMIN_EDIT, $query.id)}">Redigera</a>,&nbsp;<a href="{$urlHandler->getUrl(PaminnelseSQL, URL_ADMIN_DELETE, $query.id)}" onclick="return confirm('Är du säker på att du vill ta bort påminnelsen?\n{$query.prenumeranter} {if $query.prenumeranter == 1}medlem{else}medlemmar{/if} använder sig av den!');">Ta&nbsp;bort</a>, <a href="{$urlHandler->getUrl(PaminnelseSQL, 'TrialRun', $query.id)}">Provkör</a>{else}<a href="{$urlHandler->getUrl(PaminnelseSQL, URL_ADMIN_EDIT, $query.id)}">Visa</a>{/if}</td>
			<td>{$query.prenumeranter}&nbsp;användare</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="3" class="mmPaminnelseNone">Det finns inga påminnelser</td>
		</tr>
	{/foreach}
	</table>
</div>
<br />
<br />

<h2>Yttre mallar</h2>
<div id="existerande_mallar">
	<a href="{$urlHandler->getUrl('PaminnelseMeddelande', URL_ADMIN_CREATE)}">Skapa ny mall</a>
	<table border="0" cellspacing="10">
		<tr>
			<th>Namn/Beskrivning</th>
			<th>Hantera</th>
			<th>Används&nbsp;i...</th>
		</tr>
		{foreach from=$yttre_mallar item=mall}
		<tr>
			<td>{$mall.namn}</td>
			<td><a href="{$urlHandler->getUrl(PaminnelseMeddelande, URL_ADMIN_EDIT, $mall.id)}">Redigera</a>,&nbsp;<a href="{$urlHandler->getUrl(PaminnelseMeddelande, URL_ADMIN_DELETE, $mall.id)}" onclick="return confirm('Är du säker på att du vill ta bort mallen?\n{$mall.queries} {if $mall.queries == 1}påminnelse{else}påminnelser{/if} använder sig av den!');">Ta&nbsp;bort</a></td>
			<td>{$mall.queries} påminnelse{if $mall.queries != 1}r{/if}</td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="3" class="mmPaminnelseNone">Det finns inga yttre mallar</td>
		</tr>
		{/foreach}
	</table>
</div>