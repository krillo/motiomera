<div id="mapdiv" class="mmFloatRight">

</div>
<h1>{$laen}</h1>
<a href="../">Tillbaka till kommunjaktens startsida</a>
<br/><br/>

{foreach from=$kommuner item=kommun name="kommunvapenLoop"}

	{assign var=kommunvapen value=$kommun->getKommunvapen() key=key}

	{if $kommunvapen}
		{if $smarty.foreach.kommunvapenLoop.iteration % 4 == 1}
			<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}" class="mmProfilKommunJaktItemFirst"><div class="mmProfilKommunJaktItemVapen" height="30" style="background-image:url('../../files/kommunbilder/{$kommunvapen->getThumb()}');">{if isset($USER) && $kommun->medlemKlarat($USER)}<img src="../../img/icons/gronbock.png" alt="Klarat quiz" class="mmProfilKommunJaktItemFirstKlarat" />{/if}</div>{$kommun->getNamn()}</a>
		{else}
			<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}" class="mmProfilKommunJaktItemFirst"><div class="mmProfilKommunJaktItemVapen" height="30" style="background-image:url('../../files/kommunbilder/{$kommunvapen->getThumb()}');">{if isset($USER) && $kommun->medlemKlarat($USER)}<img src="../../img/icons/gronbock.png" alt="Klarat quiz" />{/if}</div>{$kommun->getNamn()}</a>
		{/if}
	
		
	{else}
		{if $smarty.foreach.kommunvapenLoop.iteration % 4 == 1}
			<div class="mmProfilKommunJaktItemFirst"><a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}">{$kommun->getNamn()}</a></div>
		{else}
			<div class="mmProfilKommunJaktItem"><a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}">{$kommun->getNamn()}</a></div>
		{/if}		
	{/if}
	
	{if $smarty.foreach.kommunvapenLoop.iteration % 4 == 0}
		<div class="mmProfilKommunJaktItemNR"></div><br />
	{/if}

{/foreach}

{if $smarty.foreach.kommunvapenLoop.iteration % 4 != 0}
	<div class="mmProfilKommunJaktItemNR"></div>
{/if}
<br />
<br />
<p><img src="../../img/icons/gronbock.png" alt="Quiz klarat" class="mmVerticalAlignTop" /> = du har klarat Quizen f&ouml;r denna kommun</p>

<script type="text/javascript">
	var map = new FusionMaps("/maps/{$karta}.swf", "Map1Id", "340", "400", "0", "0");
	map.setDataURL("/maps/kommuner.php");
	map.render("mapdiv");
</script>