<h1>{if isset($level)}Redigera medlemsnivån {$level->getNamn()}{else}Ny medlemsnivå{/if}</h1>

{if isset($level)}<a href="{$urlHandler->getUrl(Level, URL_ADMIN_DELETE, $level->getId())}">Radera denna medlemsnivå</a>{/if}
<br /><br />


{if isset($level)}
	{assign var=levelId value=$level->getId()}
{else}
	{assign var=levelId value=0}
{/if}

<form action="{$urlHandler->getUrl(Level, URL_ADMIN_SAVE, $levelId)}" method="post">

		Namn:<br />
		<input type="text" name="namn" value="{if isset($level)}{$level->getNamn()}{/if}" />
	<br /><br />
	<input type="submit" value="Spara" />

	</form>