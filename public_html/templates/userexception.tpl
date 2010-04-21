<h1>{$exception->getTitle()}</h1>
<p>{$exception->getMessage()}</p>
{if $exception->getBacklink()}
<p>
	<a href="{$exception->getBacklink()}" title="{$backlinktitle}">{$backlinktitle}</a>
</p>
{/if}