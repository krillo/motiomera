<h1>Välj avatar</h1>
<div>

{foreach from=$avatarer item=avatar}

	<a href="#" onclick="motiomera_sparaLagAvatar('{$avatar->getNamn()}', {$lagid}); return false;" title="Välj den här avataren"><img src="{$avatar->getUrl()}" alt="Avatar" class="mmAvatar" /></a>
{/foreach}
</div>