<div class="mmAlbumBoxTop">
	<h3 class="mmWhite BoxTitle">Fotoalbum</h3>
</div>		
	<div class="mmRightMinSidaBox">
		{if $bildblock == false}
		<span><br />&nbsp;&nbsp;Inga bilder.</span>
		{else}
		<table cellspacing="2" border="0">
			<tr>
				{foreach from=$bildblock item=bild}
				<td>
					<a href="/actions/visafotoalbumbild.php?id={$bild->getId()}&amp;storlek=stor" class="highslide" onclick="return hs.expand(this)">
					<img src="/actions/visafotoalbumbild.php?id={$bild->getId()}&amp;storlek=mini" alt="{$bild->getNamn()}"  title="{$bild->getBeskrivningNinja()}" width="{$bild->getBredd("mini")}" height="{$bild->getHojd("mini")}" border="0" />
					</a>
					<br />
					{php}
						global $x;
						$x++;
						if (($x % 4) == 0) {
							$this->assign("show", true);
						} else {
							$this->assign("show", false);
						}
						$this->assign("x", $x);
					{/php}
				</td>
				{if $show}
			</tr>
			<tr>
				{/if}
				{/foreach}
			</tr>
		</table>
		{/if}
</div>