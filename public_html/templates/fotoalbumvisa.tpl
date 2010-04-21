	<div class="mmFloatRight mmTextAlignRight">
	{if $isAgare}
		<a href="{$urlHandler->getUrl("FotoalbumBild", URL_CREATE, $fid)}"><img src="/img/icons/ImageAddIcon.gif" alt="Ladda upp" class="mmMarginLeft20" /></a> <a href="{$urlHandler->getUrl("FotoalbumBild", URL_CREATE, $fid)}">Ladda upp bild(er)</a>
		<a href="{$urlHandler->getUrl("Fotoalbum", URL_EDIT, $fid)}"><img src="/img/icons/AlbumEditIcon.gif" alt="Redigera" class="mmMarginLeft20" /></a> <a href="{$urlHandler->getUrl("Fotoalbum", URL_EDIT, $fid)}">Redigera Album</a>
		<a href="{$urlHandler->getUrl("Fotoalbum", URL_DELETE, $fid)}"><img src="/img/icons/AlbumDeleteIcon.gif" alt="Radera" class="mmMarginLeft20" /></a> <a href="{$urlHandler->getUrl("Fotoalbum", URL_DELETE, $fid)}">Ta bort fotoalbum</a><br/>
		<a href="/pages/fotoalbum.php"><img src="/img/icons/AlbumAllaIcon.gif" alt="Visa alla fotoalbum" class="mmMarginLeft20" /></a> <a href="/pages/fotoalbum.php">Visa alla fotoalbum</a>
	{/if}
	</div>
		<div class="mmh1 mmMarginBottom">Fotoalbum: {$fotoalbum->getNamn()|truncate:26:"...":true}</div>
		<em>{$fotoalbum->getBeskrivning()}</em>
<p/>

<div class="mmClearBoth"></div>


<div class="mmBlueBoxWideTop"><h3 class="mmFontWhite BoxTitle">{$fotoalbum->getNamn()|truncate:35:"...":true}</h3><div class="mmFontWhite mmBoxTitleTextRight2">{$tilltrade}</div></div>


{if count($bilder) > 0}


<div class="mmBlueBoxWideBgMain">
<div class="mmBlueBoxWideBgMainPadding">
	
	<table>
		<tr>
			{foreach from=$bilder item=bild}
			<td align="left" width="25%">
				<a href="/actions/visafotoalbumbild.php?id={$bild.id}&amp;storlek=stor" class="highslide" onclick="return hs.expand(this)"><img src="/actions/visafotoalbumbild.php?id={$bild.id}&amp;storlek=liten" alt="{$bild.namn}"  title="{$bild.namn}" width="{$bild.bredd_liten}" height="{$bild.hojd_liten}" /></a><br />
				{$bild.namn}<br />
				<em>{$bild.beskrivning}</em><br/>
				{if $isAgare == true}
				<a href="fotoalbumandrabild.php?fid={$bild.fotoalbum_id}&amp;id={$bild.id}"><img src="/img/icons/ImageEditIcon.gif" alt="Ändra" class="mmPadding3"	/></a> <a href="fotoalbumandrabild.php?fid={$bild.fotoalbum_id}&amp;id={$bild.id}">&Auml;ndra namn och beskrivning</a><br />
				<a href="fotoalbumtabortbild.php?fid={$bild.fotoalbum_id}&amp;id={$bild.id}"><img src="/img/icons/ImageDeleteIcon.gif" alt="Ta bort" class="mmPadding3" /></a> <a href="fotoalbumtabortbild.php?fid={$bild.fotoalbum_id}&amp;id={$bild.id}">Ta bort bild</a><br />
				{/if}			
					
				{php}
					global $x;
					$x++;
					if (($x % 3) == 0) {
						$this->assign("show", true);
					} else {
						$this->assign("show", false);
					}
					$this->assign("x", $x);
				{/php}



			</td>
							
							
			{if $show}
				</tr>
				<tr colspan="3">
					<td>&nbsp;</td>
				</tr>
				<tr>
			{/if}
			{/foreach}
		</tr>
	</table>
	</div>
	</div>
	
	<div class="mmBlueBoxWideBottomMain"><div class="mmBlueBoxWideBottomMainPadding"></div></div>
	<diclass="mmClearBoth";"></div>
	
{else}
	Inga bilder uppladdade
{/if}
{include file="highslide_controlbar.tpl"}
