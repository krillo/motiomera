<h1>Hjälprutor</h1>
{if $ADMIN->isTyp(ADMIN)}
<p>
	<a href="{$urlHandler->getUrl(Help, URL_ADMIN_CREATE)}">Skapa ny hjälpruta</a>
</p>
{/if}
<p>
{foreach from=$listHelpers item=thisEditor}

<a href="{$urlHandler->getUrl(Help, URL_ADMIN_EDIT, $thisEditor->getId())}">{$thisEditor->getNamn()}</a> (id {$thisEditor->getId()})<br />

{/foreach}
</p>
