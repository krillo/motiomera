		<div class="mmh1 mmMarginBottom">&Auml;ndra bild</div><br />

<form action="/actions/save.php?table=fotoalbumbild&amp;id={$bild->getId()}&amp;fid={$bild->getFotoalbumId()}" method="post" class="mmDisplayInline">
	<table>
		<tr>
			<td>
				<a href="/actions/visafotoalbumbild.php?id={$bild->getId()}&amp;storlek=stor" class="highslide" onclick="return hs.expand(this)">
					<img src="/actions/visafotoalbumbild.php?id={$bild->getId()}&amp;storlek=liten" alt=""  title="" width="{$bild->getBredd("liten")}" height="{$bild->getHojd("liten")}" border="0" /></a>
			</td>
			<td>
				<table>
					<tr>
						<th>Namn</th>
						<td><input type="text" name="namn" value="{$bild->getNamn()}" class="mmTextField" /></td>
					</tr>
					<tr>
						<th>Beskrivning</th>
						<td><input type="text" name="beskrivning" value="{$bild->getBeskrivning()}" class="mmTextField" /></td>
					</tr>
					<tr>
						<th>Fotoalbum</th>
						<td>
							<select name="fotoalbum" size="1">
								{foreach from=$alla_fotoalbum item=album}
									<option value="{$album.id}"{if $bild->getFotoalbumId() == $album.id} selected{/if}>{$album.namn|truncate:26:"...":true}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<th>Bilden är knuten till kommun</th>
						<td>{mm_html_options name=kid options=$opt_kommuner selected=$bild->getRelationTagId()}</td>
					</tr>
				</table>
			</td>
		</tr>
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
