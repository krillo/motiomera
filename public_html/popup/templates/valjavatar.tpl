<h1>Välj avatar</h1>
<div>

{foreach from=$avatarer item=avatar}

	<a href="#" onclick="motiomera_sparaAvatar('{$avatar->getNamn()}'); return false;" title="Välj den här avataren"><img src="{$avatar->getUrl()}" alt="Avatar" class="mmAvatar" /></a>
	
{/foreach}
</div>