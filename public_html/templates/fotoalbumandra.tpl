<div class="mmh1 mmMarginBottom">Uppdatera fotoalbum</div><br />

<form action="{$urlHandler->getUrl(Fotoalbum, URL_SAVE, $fid)}" method="post" onsubmit="return motiomera_validateSettingsForm(this);">

	<table border="0" cellspacing="0" cellpadding="0" class="motiomera_installningar_table">
		<tr>
			<th>Namn</th>
			<td><input type="text" name="namn" value="{$fotoalbum->getNamn()}" class="mmTextField" /></td>
		</tr>
		<tr>
			<th>Beskrivning (frivilligt)</th>
			<td><input type="text" name="beskrivning" value="{$fotoalbum->getBeskrivning()}" class="mmTextField" /></td>
		</tr>
		<tr>
			<th>Tillträde</th>
			<td>
				<input type="radio" name="tilltrade" value="alla" class="mmWidthTvaNollPixlar" {if $fotoalbum->getTilltrade() == "alla"}checked{/if} /> Alla<br />
				{if $grupper != null || $foretag != null}
					<input type="radio" name="tilltrade" value="vissa" class="mmWidthTvaNollPixlar" {if $fotoalbum->getTilltrade() == "vissa"}checked{/if} /> Vissa<br />
				{/if}
				<table>
					<tr>
						<td class="mmTilltradeTd">
							{if $grupper != null}
								<h3>Grupper</h3>
								<input type="checkbox" name="tilltrade_grupper[]" value="alla" class="mmWidthTvaNollPixlar" {if $fotoalbum->getTilltradeAllaGrupper() == "ja"} checked{/if}/> Alla grupper<br />
								{foreach from=$grupper item=grupp}
									<input type="checkbox" name="tilltrade_grupper[]" value="{$grupp->getId()}" class="mmWidthTvaNollPixlar"" {if $fotoalbum->harGruppTilltrade($grupp->getId(), $fotoalbum->getId())}checked{/if}/> {$grupp->getNamn()}<br />
								{/foreach}
							{/if}
							{if $foretag != null}
								<br />
								<h3>Företag</h3>
								<input type="checkbox" name="tilltrade_foretag" value="ja"class="mmWidthTvaNollPixlar"" {if $fotoalbum->harForetagTilltrade()}checked{/if} /> {$foretag->getNamn()}<br />
							{/if}
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th></th>
			<td><br/><input type="image" src="/img/icons/UppdateraAlbumIcon.gif" alt="Uppdatera fotoalbum" /></td>
		</tr>
	</table>
</form>
<div class="mmClearBoth"></div>
