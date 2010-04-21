<h1>{if isset($lagnamn)}{$lagnamn->getNamn()}{else}Nytt lag{/if}</h1>



<form action="{$urlHandler->getUrl(LagNamn, URL_SAVE)}" enctype="multipart/form-data" method="post">
	{if isset($lagnamn)}
	<a href="{$urlHandler->getUrl(LagNamn, URL_DELETE, $lagnamn->getId())}">Radera</a>
	<br/><img src="{$lagnamn->getImgUrl()}" />
	<input type="hidden" name="id" value="{$lagnamn->getId()}">
	{/if}	<p>
		Namn: <br />
		<input type="text" name="namn" {if isset($lagnamn)}value="{$lagnamn->getNamn()}" {/if}/><br />
		Bild:<br />
		<input type="file" name="bild" />
	</p>
	<p>
		<input type="submit" value="Spara" />
	</p>

</form>
