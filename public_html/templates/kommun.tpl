<div id="mmColumnRightKommun">

	{if $kommunvapen}
	<img src="{$kommunvapen->getUrl()}" alt="Kommunvapen" height="200" align="left" class="mmFloatRight" />
	{/if}

	<div class="mmClearBoth"></div>
	<div class="mmGrayLineRight"></div>

	<div class="mmBoxRight">
		<h3 class="mmGreen">Snabbfakta</h3>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th class="mmWidthHundra">Huvudort</th>
				<td>{$kommun->getOrt()}</td>
			</tr>
			<tr>
				<th>Areal km2</th>
				{*}<td>{$kommun->getAreal()|nice_tal} km2</td>{*}
				<td>{$kommun->getAreal()}</td>
			</tr>
			<tr>
				<th>Folkm&auml;ngd</th>
				<td>{$kommun->getFolkmangd()|nice_tal}</td>
			</tr>
			<tr>
				<th>Hemsida</th>
				<td><a href="http://{$kommun->getWebb()}/" title="Besök {$kommun->getWebb()}" rel="external">{$kommun->getWebb()}</a></td>
			</tr>
		</table>
	</div>
	
	<div class="mmGrayLineRight"></div>
	
	<div class="mmBoxRight">
		<div class="mmQuizKnapp" onclick="location.href='{$urlHandler->getUrl(Quiz, URL_VIEW, $kommun->getUrlNamn())}'">
			<div>
				<span class="mmh3 mmOrange">MotioMera <br />kommunquiz</span>
				<p><a href="{$urlHandler->getUrl(Quiz, URL_VIEW, $kommun->getUrlNamn())}" >Gå till testet <img src="/img/icons/ArrowCircleOrange.gif" alt=""/></a></p>
			</div>
		</div>
	</div>


	<div class="mmGrayLineRight"></div>

	<div class="mmBoxRight">
		<img src="/img/icons/GrannkommunerIcon.gif" alt="Grannkommuner" align="right" /><h3 class="mmGreen">Grannkommuner:</h3>
			{foreach from=$grannkommuner item=kommuntemp}
				
				<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommuntemp->getUrlNamn())}">{$kommuntemp->getNamn()}</a><br />
			{/foreach}
	</div>
	
  
  
  
	{if isset($USER) || $dialekter}
	<div class="mmGrayLineRight"></div>
	<div class="mmBoxRight">
		<h3 class="mmGreen">Dialekt:</h3>
			{foreach from=$dialekter item=dialekt}
				<div class="mmKommundialektFlash">
					<object type="application/x-shockwave-flash" data="/flash/soundPlayer.swf?url={$dialekt->getUrl()}" width="23" height="23" id="kommundialekt" align="middle">
							<param name="movie" value="/flash/soundPlayer.swf?url={$dialekt->getUrl()}" />
							<param name="quality" value="high" />
							<param name="allowFullScreen" value="false" />
							<param name="scale" value="showall" />
					</object>
				</div>
				{assign var=uppladdare value=$dialekt->getMedlem()}
				<div style="{if !isset($uppladdare)}padding-top: 5px;{/if}" class="mmKommundialektInfo">
					<strong>{$dialekt->getAlder()|capitalize} {$dialekt->getKon()}</strong>
					{if $dialekt->getMedlem()}
					<br />
					<small>Uppladdad av <a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $uppladdare->getId())}">{$uppladdare->getANamn()}</a></small>
					{else}
					<br />
					<small>Ljudet spelas upp från <a href="http://swedia.ling.gu.se" target="_blank">swedia.ling.gu.se</a></small>
					{/if}
				</div>
				<div class="mmClearBoth"></div>
			{/foreach}
			{if isset($USER)}
			<p>
				<a href="{$urlHandler->getUrl(Kommundialekt, URL_CREATE, $kommun->getId())}">Har du ett eget exempel på dialekt från denna kommun? Klicka här.</a>
			</p>
			{/if}
	</div>		
	{/if}
  
  
 	<div class="mmGrayLineRight"></div>
	<div class="mmBoxRight">
		<img src="/img/icons/MedlemmarIcon.gif" alt="Medlemmar" align="right" />
		<h3 class="mmGreen">Medlemmar i kommunen just nu:</h3>
		{foreach from=$medlemmarIKommun item=medlem}
			<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}">{$medlem->getANamn()}</a><br />
		{/foreach}
	</div>
 
  
  
  
	{if $taggbilder}
	<div class="mmGrayLineRight"></div>
		<div class="mmBoxRight">
		<h3 class="mmGreen">Bilder:</h3>
			{foreach from=$taggbilder item=bild}
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
			{/foreach}
		</div>
	{/if}

</div>


<div id="mmInlineColumnMiddle">
	<h1>{$kommunNamn}</h1>
	{*}<a href="/kommunjakten/{$kommun->getLanForLink()}/">{$kommun->getLan()}</a>{*}
	<a href="/kommunjakten/#{$lan_slug}">{$kommun->getLan()}</a>
	
	<br class="mmClearBoth" /><br />
	<img src="/img/icons/ForstoringsglasIcon.gif" alt="F&ouml;rstora Bilder" class="mmVerticalAlignMiddle" /> Klicka p&aring; bilderna f&ouml;r att f&ouml;rstora<br /><br />

	{foreach from=$kommunbilder item=kommunbild}
	{assign var=storbild value=$kommunbild->getBild()}
	{assign var=bild value=$kommunbild->getFramsidebild()}
	{if $bild}
	<div class="mmKommunKommunBild">
		<a href="{$kommunbild->getBildUrl()}" onclick="return hs.expand(this)"><img src="{$bild->getUrl()}" alt="" class="mmImgBorderGray" /></a><br />
		<b>{$kommunbild->getNamn()}</b>
	</div>
	{/if}
	{/foreach}
	

	<div class="mmClearBoth""></div>
	

	
	<div class="mmh3 mmBlue mmMarginBottom ">{$kommun->getNamn()}</div>
	
	<p>
		{$kommun->getInfo()|nl2br}
	</p>
	
</div>


