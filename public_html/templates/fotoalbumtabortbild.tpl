	<div class="mmAlignCenter">

		<div class="mmh1 mmMarginBottom">Ta bort bilden?</div><br />

			<img src="/actions/visafotoalbumbild.php?id={$bild->getId()}&amp;storlek=liten" width="{$bild->liten_bradd}" height="{$bild->liten_hojd}" border="0" alt="{$bild->getNamn()}" title="{$bild->getNamn()}" /><br /><br />
			&Auml;r du s&auml;ker att du vill plocka bort denna bild?<br /><br />

			<a href="{$urlHandler->getUrl("FotoalbumBild", URL_DELETE, $bild->getId())}"><img src="/img/icons/TabortBildIcon.gif" class="mmMarginLeft20" /></a><a href="{$urlHandler->getUrl("Fotoalbum", URL_VIEW, $bild->getFotoalbumId())}"><img src="/img/icons/TillbakaIcon.gif" class="mmMarginLeft20" /></a>




<div class="mmClearBoth"></div>

	</div>
