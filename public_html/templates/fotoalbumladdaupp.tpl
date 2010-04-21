	<div class="mmh1 mmMarginBottom">
	{if $namn}
		Ladda upp bilder till albumet <em>{$namn|truncate:26:"...":true}</em>
	{else}
		Ladda upp bilder till ditt fotoalbum
	{/if}
</div><br />

<form action="{$urlHandler->getUrl(FotoalbumBild, URL_SAVE, $fid)}" method="post" enctype="multipart/form-data"{*} {$fileWidget->renderFormExtra()}{*}>
{*}	{$fileWidget->renderHidden()}{*}
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table" id="filer">
		<tr id="filvaljare0">
			<th class="mmWidthEttTvaNollPixlar">Bild</th>
			<td class="mmWidthEttFemNollPixlar">
				<div id="fil0">
					<input type="file" name="image0" id="image0" class="mmFileUpload" onchange="fotoalbumbildFlyttaFil(0)" />
				</div>
			</td>
		</tr>
	</table>
	<div class="mmHeight40"></div>
	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table">
		<tr>
			<th class="mmWidthEttTvaNollPixlar">Valda bilder</th>
			<td>
				<select size="10" id="valda_filer">

				</select>
				<br />
				<a class="pointer" onClick="fotoalbumbildTaBort()"><img src="/img/icons/ImageDeleteIcon.gif" alt="Radera" class="mmPadding3" /></a> <a class="pointer" onClick="fotoalbumbildTaBort()">Ta bort vald bild</a>
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
			<td align="right" class="mmPaddingTop10">
				<input type="image" src="/img/icons/LaddauppBildIcon.gif" alt="Ladda upp valda bilder" />
			</td>
		</tr>
	</table>
</form>

<div class="mmClearBoth"></div>

