<h1>Ladda upp visningsbild</h1>





<div class="mmFileUpload">

	<form action="{$urlHandler->getUrl(CustomVisningsbild, URL_SAVE)}" method="post" enctype="multipart/form-data" onsubmit="return mm_visningsbild_checkExtension();">
		<input type="file" name="image" id="mmLaddaUppBild" /><br />
		<input type="submit" value="Ladda upp" class="mmMarginTop" />
	</form>
</div>
