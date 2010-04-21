<h1>Texthantering</h1>
{if $ADMIN->isTyp(SUPERADMIN)}
<p>
	<a href="{$urlHandler->getUrl(TextEditor, URL_ADMIN_CREATE)}">Skapa ny text</a>
</p>
{/if}
<p>
{foreach from=$listTextEditors item=thisEditor}

<a href="{$urlHandler->getUrl(TextEditor, URL_ADMIN_EDIT, $thisEditor->getId())}">{$thisEditor->getNamn()}</a><br />

{/foreach}
</p>
