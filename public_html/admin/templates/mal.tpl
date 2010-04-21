<h1>{if isset($mal)}{$mal->getNamn()}{else}Nytt måll{/if}</h1>
<form action="{$urlHandler->getUrl(Mal, URL_ADMIN_SAVE, $malId)}" method="post">

	<p>
		Namn:<br />
		<input type="text" name="namn" value="{if isset($mal)}{$mal->getNamn()}{/if}" />
	</p>
	<p>
		Utgångskommun:<br />
		{mm_html_options name=kid options=$opt_kommun selected=$sel_kommun}
	</p>
	<p>
		Avstånd:<br />
		<input type="text" name="avstand" value="{if isset($mal)}{$mal->getAvstand()}{/if}" />
	</p>
	<p>
		<input type="submit" value="Spara" />
	</p>
</form>