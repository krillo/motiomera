<div class="mmFlash">
	<div id="mmSplash">
	{if $browser eq IE6}
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="430" height="290" id="slideshow" align="middle">
		<param name="allowFullScreen" value="false" />
		<param name="movie" value="/slideshow/slideshow.swf" />
		<param name="quality" value="high" />
		<param name="bgcolor" value="#ffffff" />
		<param name="allowScriptAccess" value="sameDomain" />
		<embed src="/slideshow/slideshow.swf" quality="high" bgcolor="#ffffff" width="430" height="290" name="slideshow" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
	{else}
	<object type="application/x-shockwave-flash" data="/slideshow/slideshow.swf" width="430" height="290" id="slideshow" align="middle">
		<param name="movie" value="/slideshow/slideshow.swf" />
		<param name="quality" value="high" />
		<param name="allowFullScreen" value="false" />
		<param name="scale" value="showall" />
	</object>
	{/if}

	</div>
</div>
			<div class="mmGrayLineMiddle"></div>
			<div id="mmBlueBoxMiddleWide"><div id="mmBlueBoxMiddleWideText">Senaste nytt från mabra.com:</div></div>
			{foreach from=$rss item=puff name=rss}
        {if  $smarty.foreach.rss.iteration > 1 }
          <div class="mmArticleNarrowStart{if $smarty.foreach.rss.iteration < 4} mmMarginRight{/if}">
            {$puff.imageurl}
            <span class="mmh2 mmBlue">{$puff.title}</span><br />
            {$puff.excerpt}
            <div class="mmTextAlignRight"><a href="{$puff.link}" target="_blank">L&auml;s mer <img src="img/icons/ArrowsBlue.gif" class="mmVerticalAlignMiddle" alt="Läs mer" /></a></div>
          </div>
        {/if}
			{/foreach}

							<div class="mmClearBoth"></div>
							<div class="mmGrayLineMiddle"></div>
								{$texteditor_nm->getTexten()}
								{*}
								<img src="img/startsida/RegStegBild.jpg" alt="Registrera dina steg" />
								<div id="mmRegSteg" class="mmFontWhite"><a href="default.html">Registrera dina steg h&auml;r</a></div>

						<div class="mmClearBoth mmMarginTop13"></div>
							<div class="mmGrayLineMiddle"></div>

			<div class="mmArticleWideStart">

				<img src="img/startsida/Matnyttigt.jpg" alt="Matnyttigt" align="left" class="mmMarginRight15;" />
				<span class="mmh2 mmGreen">MATNYTTIGT!</span><br />
				Vi ger dig inspiration till goda och nyttiga matr&auml;tter som g&aring;r snabbt att laga!
					<div class="mmTextAlignRight"><a href="default.html">Recept h&auml;r</a> <a href="default.html"><img src="img/icons/ArrowCircleGreen.gif" class="mmVerticalAlignMiddle" /></a></div>

			</div>
		{*}
