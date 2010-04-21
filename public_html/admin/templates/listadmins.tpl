<h1>Administratörer</h1>

<p><a href="{$urlHandler->getUrl(Admin, URL_ADMIN_CREATE)}">Skapa administratör</a></p>

<table border="0" cellpadding="0" cellspacing="0">

	<tr>
		<th class="mmWidthTvaHundraPixlar">Användarnamn</th>
		<th>Typ</th>
	</tr>
	{foreach from=$admins item=admin}
	{if $admin->getTyp() neq "kommun"}
	<tr>
			
		<td><a href="{$urlHandler->getUrl(Admin, URL_ADMIN_EDIT, $admin->getId())}">{$admin->getANamn()}</a></td>
		{assign var=typ value=$admin->getTyp()}
		<td>{$adminNiceNames[$typ]}</td>
	</tr>
	{/if}

	{/foreach}
</table>

<h2>Kommuner</h2>
<p><a href="#" onclick="getById('kommunlogin').style.display='block';">Visa kommunlogin</a></p>
<div class="hide" id="kommunlogin">
<table border="0" cellpadding="0" cellspacing="0">

	<tr>
		<th class="mmWidthTvaHundraPixlar">Användarnamn</th>
		<th>Typ</th>
	</tr>
	{foreach from=$admins item=admin}
	{if $admin->getTyp() eq "kommun"}
	<tr>
			
		<td><a href="{$urlHandler->getUrl(Admin, URL_ADMIN_EDIT, $admin->getId())}">{$admin->getANamn()}</a></td>
		{assign var=typ value=$admin->getTyp()}
		<td>{$adminNiceNames[$typ]}</td>
	</tr>
	{/if}

	{/foreach}
</table>
</div>