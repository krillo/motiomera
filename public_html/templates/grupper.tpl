<h1>Grupper</h1>
<p>
	<strong>Mina skapade grupper</strong><br />
{foreach from=$mygroups item=thisgroup}

<a href="{$urlHandler->getUrl(Grupp, URL_EDIT, $thisgroup->getId())}">{$thisgroup->getNamn()}</a><br />

{foreachelse}
	Du har inte skapat några grupper.
{/foreach}
<br />
<a href="{$urlHandler->getUrl(Grupp, URL_CREATE)}">Skapa ny grupp</a>
</p>

{if isset($joinedgroups)}
<p>
	<strong>Du är medlem i följande grupper</strong><br />
	{foreach from=$joinedgroups item=grupp}
		<a href="{$urlHandler->getUrl(Grupp, URL_VIEW, $grupp->getId())}">{$grupp->getNamn()}</a><br />
	{/foreach}
</p>
{/if}


<p>
	<strong>Alla publika grupper</strong><br />
	{foreach from=$allgroups item=thisgroup}
		<a href="{$urlHandler->getUrl(Grupp, URL_VIEW, $thisgroup->getId())}">{$thisgroup->getNamn()}</a><br />
	{/foreach}
</p>
{if isset($invites)}
<p>
	<strong>Inbjudningar</strong><br />
	{foreach from=$invites item=invite}
		{$invite->getNamn()} <a href="/actions/answerinvite.php?do=accept&amp;gid={$invite->getId()}">Acceptera</a> <a href="/actions/answerinvite.php?do=deny&amp;gid={$invite->getId()}">Ignorera</a><br />
	{/foreach}
</p>

{/if}