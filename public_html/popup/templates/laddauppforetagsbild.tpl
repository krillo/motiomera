<h1>Ladda upp företagslogga</h1>



<div class="mmFileUpload">
	{if $foretagsid eq ""}
	<form action="{$urlHandler->getUrl(CustomForetagsbild, URL_SAVE)}" method="post" enctype="multipart/form-data">
	{else}
	<form action="{$urlHandler->getUrl(CustomForetagsbild, URL_ADMIN_SAVE, $foretagsid)}" method="post" enctype="multipart/form-data">
	{/if}
		<input type="hidden" name="fid" value="{$foretagsid}" />
		<input type="file" name="image" /><br />
		<input type="submit" value="Ladda upp" class="mmMarginTop" />
	</form>
</div>
