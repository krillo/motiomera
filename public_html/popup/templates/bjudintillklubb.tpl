<form action="" method="post" id="mmBjudInForm" onsubmit="motiomera_skickaInvbjudan(this); return false;">
	<input type="hidden" name="mid" value="{$_POST.medlem_id}" />
	<h1>Skicka inbjudan</h1>
	{if isset($grupp)}
		<input type="hidden" name="gid" value="{$grupp->getId()}" />
	{else}
		{mm_html_options options=$opt_grupper name=gid}
	{/if}
	<input type="submit" value="Bjud in{if isset($grupp)} till {$grupp->getNamn()}{/if}" />
</form>
<div id="mmInbjudanSkickad" class="hide">
	<p class="inbjuden">
		Inbjudan skickad!
	</p>
</div>