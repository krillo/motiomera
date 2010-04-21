<h1>Avatarer</h1>
<p>
	<b>Ladda upp avatar</b>
</p>
	<form action="{$urlHandler->getUrl(Avatar, URL_ADMIN_SAVE)}" enctype="multipart/form-data" method="post">
		<p>
			<input type="file" name="image" /><br />
			<input type="checkbox" name="standard" /> Standard
		</p>
		<p>
			<input type="submit" value="Ladda upp" />
		</p>
	</form>
</p>

<p>
	<b>Avatarer</b><br />
	<small>Klicka på en avatar för att ta bort den</small>
	<p>
	{foreach from=$avatarer item=tempavatar}
		<a href="{$urlHandler->getUrl(Avatar, URL_ADMIN_DELETE, $tempavatar->getNamn())}" onclick="{jsConfirm msg="Vill du verkligen ta bort den här avataren?"}"><img src="{$tempavatar->getUrl()}" /></a>
	{foreachelse}
		Inga avatarer har laddats upp ännu.
	{/foreach}
	</p>
</p>