<h1>Ladda upp lagbild för {$lagnamn}</h1>





<div class="mmFileUpload">

	<form action="{$urlHandler->getUrl(CustomLagbild, URL_SAVE, $lag->getId())}" method="post" enctype="multipart/form-data">
		<input type="file" name="image" /><br />
		<input type="submit" value="Ladda upp" class="mmMarginTop" />
	</form>
</div>
