
{if $namn}
	<h1>Ladda upp bilder till fotoalbumet <em>{$namn}</em></h1>
{else}
	<h1>Ladda upp bilder till ditt fotoalbumet</h1>
{/if}

<form action="{$urlHandler->getUrl(FotoalbumBild, URL_SAVE, $fid)}" method="post" enctype="multipart/form-data" {$fileWidget->renderFormExtra()}>
	{$fileWidget->renderHidden()}
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table" id="filer">
		<tr id="filvaljare0">
			<th class="mmWidthEttTvaNollPixlar">Bilden</th>
			<td class="mmWidthEttFemNollPixlar">
				<div id="fil0">
					<input type="file" name="image0" id="image0" class="mmFileUpload" onChange="fotoalbumbildFlyttaFil(0)" />
				</div>
			</td>
		</tr>
	<table>
	<div class="mmHeight40"></div>
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table">
		<tr>
			<th class="mmWidthEttTvaNollPixlar">Valda bilder</th>
			<td>
				<select size="10" id="valda_filer">
				</select>
				<br />
				<a class="pointer" onClick="fotoalbumbildTaBort()">Ta bort vald bild</a>
				<br /><br />
			</td>
		</tr>
		<tr>
			<th class="mmWidthEttTvaNollPixlar">
			</th>
			<td>
<!--				{$fileWidget->render()} -->
				{$fileWidget->renderProgressBar()}
			</td>
		</tr>
		<tr>
			<th></th>
			<td align="right" class="mmPaddingTop20">
				<input type="submit" name="button" value="Ladda upp valda bilder" />
			</td>
		</tr>
	</table>
</form>
