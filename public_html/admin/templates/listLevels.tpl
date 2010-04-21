<h1>Medlemsnivåer</h1>

<a href="{$urlHandler->getUrl(Level, URL_ADMIN_CREATE)}">Ny medlemsnivå</a>

<br />
<br />


<table border="0" cellpadding="0" cellspacing="0">

	<tr>
		<th>Medlemsnivå</th>
	</tr>
	{foreach from=$levels item=level}
		
		<tr>
			<td><a href="{$urlHandler->getUrl(Level, URL_ADMIN_EDIT, $level->getId())}">{$level->getNamn()}</a></td>
			<td>{if $defaultLevel && $level->getId() == $defaultLevel->getId()}<strong>Default</strong>{else}<a href="{$urlHandler->getUrl(Level, URL_SET_DEFAULT, $level->getId())}">Gör till default</a>{/if}</td>
		</tr>
		<tr>
			{assign var=levelid value=$level->getId()}
			<td colspan=2>{foreach from=$sajtDelar item=sajtdel}
					{if $sajtDelarObj->levelHasAccess($level,$sajtdel)}
					{assign var=args value="remove,$sajtdel,$levelid"}
						<a href="{$urlHandler->getUrl(SajtDelar, URL_ADMIN_SAVE, $args)}" class="mmGron">{$sajtdel}</a>
					{else}
						{assign var=args value="give,$sajtdel,$levelid"}
						<a href="{$urlHandler->getUrl(SajtDelar, URL_ADMIN_SAVE, $args)}" class="mmRod">{$sajtdel}</a>
					{/if}
					&nbsp;

			{/foreach}<br/><br/></td>
		</tr>

	{/foreach}
</table>
