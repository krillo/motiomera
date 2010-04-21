	<div class="mmAlignCenter">

		<div class="mmh1 mmMarginBottom">Ta bort fotoalbumet <em>{$fotoalbum->getNamn()}</em>?</div><br />

										&Auml;r du s&auml;ker att du vill plocka bort detta album?<br />
										Alla bilder i albumet kommer ocks&aring; att f&ouml;rsvinna.<br /><br />

				<a href="/actions/delete.php?table=fotoalbum&amp;fid={$fotoalbum->getId()}"><img src="/img/icons/TabortAlbumIcon.gif" class="mmMarginLeft20" /></a><a href="{$urlHandler->getUrl("Fotoalbum", URL_VIEW, $fotoalbum->getId())}"><img src="/img/icons/TillbakaIcon.gif" class="mmMarginLeft20" /></a>




<div class="mmClearBoth"></div>

	</div>
