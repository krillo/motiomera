		<div class="mmh1 mmMarginBottom">Namnge bilder</div><br />

<form action="/actions/save.php?table=fotoalbumbild" method="post" class="mmDisplayInline">
	<table>
		{foreach from=$bilder item=bild}
			<input type="hidden" name="id" value="{$bild.id}" />
			<tr>
				<td>
					<a href="/actions/visafotoalbumbild.php?id={$bild.id}&amp;storlek=stor" class="highslide" onclick="return hs.expand(this)">
						<img src="/actions/visafotoalbumbild.php?id={$bild.id}&storlek=liten" alt=""  title="" width="{$bild.liten_bredd}" height="{$bild.liten_hojd}" border="0" /></a>
				</td>
				<td>
					<table>
						<tr>
							<th>Namn</th>
							<td><input type="text" name="namn[{$bild.id}]" value="" class="mmTextField" /></td>
		
						</tr>
						<tr>
							<th>Beskrivning</th>
							<td><input type="text" name="beskrivning[{$bild.id}]" value="" class="mmTextField" /></td>
						</tr>
						<tr>
							<th>Bilden är knuten till kommun</th>
							<td>{mm_html_options name=kid options=$opt_kommuner}</td>
						</tr>
						{if $visaAlbumlista}
						<tr>
							<th>Fotoalbum</th>
							<td>
								<select name="fotoalbum[{$bild.id}]" size="1">
									{if $flerAnEttAlbum}
										{*}<option value="0">- Välj fotoalbum -</option>{*}
									{/if}
									{foreach from=$fotoalbum item=album}
										<option value="{$album.id}">{$album.namn}</option>
									{/foreach}
								</select>
							</td>
						</tr>
						{/if}
					</table>
				</td>
			</tr>
		{/foreach}
		<tr>
			<td>&nbsp;</td>

			<td>
				<input type="image" src="/img/icons/UppdateraBildIcon.gif" alt="Uppdatera bild" />
			</td>
		</tr>
	</table>
</form>


<div class="mmClearBoth"></div>

{include file="highslide_controlbar.tpl"}
