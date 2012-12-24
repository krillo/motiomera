<div id="mmFotoalbumTopRight">
{if $isAgare}
	<a href="{$urlHandler->getUrl(Fotoalbum, URL_CREATE)}"><img src="/img/icons/AlbumNewIcon.gif" alt="Skapa ett nytt album" /></a> <a href="{$urlHandler->getUrl(Fotoalbum, URL_CREATE)}">Skapa ett nytt album</a>

	{if count($fotoalbum) == 0}
		För att ladda upp bilder måste du först skapa ett nytt album<br />
	{else}
		<a href="{$urlHandler->getUrl(FotoalbumBild, URL_CREATE, 0)}"><img src="/img/icons/ImageAddIcon.gif" alt="Ladda upp bild(er)" class="mmMarginLeft20" /></a> <a href="{$urlHandler->getUrl(FotoalbumBild, URL_CREATE, 0)}">Ladda upp bild(er)</a><br />
	{/if}
	<br />
	</div>
	<div class="mmh1 mmMarginBottom">Mina fotoalbum</div>
{else}
	</div>
	<div class="mmh1 mmMarginBottom">Fotoalbum tillhörande {$medlem->getANamn()}</div>
{/if}

{if count($fotoalbum) == 0 && !$isAgare}
	<h2>Det finns inga fotoalbum du har tillgång till</h2>
{elseif count($fotoalbum) == 0 && $isAgare}
	<h2>Du har inte skapat något fotoalbum än</h2>
{/if}

<br/>
{foreach from=$fotoalbum item=album}

	<div class="mmBlueBoxWideTop"><h3 class="BoxTitle BoxTitle-wide"><a href="{$urlHandler->getUrl("Fotoalbum", URL_VIEW, $album.id)}">{$album.namn|truncate:35:"...":true}</a></h3><div class="mmFontWhite mmBoxTitleTextRight"><a href="{$urlHandler->getUrl("Fotoalbum", URL_VIEW, $album.id)}">Se alla bilder</a>&nbsp; <a href="{$urlHandler->getUrl("Fotoalbum", URL_VIEW, $album.id)}"><img src="/img/icons/AlbumImages.gif" alt="Bilder" class="mmMarginTop-6" /></a></div></div>
	
	<div class="mmBlueBoxWideBgMain">
	<div class="mmBlueBoxWideBgMainPadding">
	
	<table>
		<tr>
			{php}
				global $x;
				$x = 1;
				$this->assign("x", $x);
			{/php}
			{foreach from=$album.bilder item=bild}
			<td align="left" width="25%">
				<a href="/actions/visafotoalbumbild.php?id={$bild.id}&amp;storlek=stor" class="highslide" onclick="return hs.expand(this)"><img src="/actions/visafotoalbumbild.php?id={$bild.id}&amp;storlek=liten" alt="{$bild.namn}"  title="{$bild.namn}" width="{$bild.bredd_liten}" height="{$bild.hojd_liten}" /></a><br />
				{$bild.namn}<br />
				<em>{$bild.beskrivning}</em><br/>
				{if $egensida == 1}
					<a href="fotoalbumandrabild.php?fid={$bild.fotoalbum_id}&amp;id={$bild.id}"><img src="/img/icons/ImageEditIcon.gif" alt="Ändra namn och beskrivning" class="mmPadding3" /></a> <a href="fotoalbumandrabild.php?fid={$bild.fotoalbum_id}&amp;id={$bild.id}">&Auml;ndra namn och beskrivning</a><br />
					<a href="fotoalbumtabortbild.php?fid={$bild.fotoalbum_id}&amp;id={$bild.id}"><img src="/img/icons/ImageDeleteIcon.gif" alt="Ta bort bild" class="mmPadding3" /></a> <a href="fotoalbumtabortbild.php?fid={$bild.fotoalbum_id}&amp;id={$bild.id}">Ta bort bild</a><br />
				{/if}			
					
				{php}
				global $x;
				if (($x % 3) == 0) {
					$this->assign("show", true);
				} else {
					$this->assign("show", false);
				}
				$x++;
				$this->assign("x", $x);
				{/php}


			</td>
							
							
			{if $show}
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
			{/if}
			{/foreach}
			<td>&nbsp;</td>
		</tr>
	</table>
	</div>
	</div>


	<div class="mmBlueBoxWideBottomMain"><div class="mmBlueBoxWideBottomMainPadding"></div></div>
	<div class="mmClearBoth"></div>

{/foreach}

{include file="highslide_controlbar.tpl"}
