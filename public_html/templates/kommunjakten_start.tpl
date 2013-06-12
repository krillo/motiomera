<div id="mapdiv" class="mmFloatRight">

</div>
<h1>Kommunjakten</h1>

Alla kommuner i Sverige ingår i MotioMeras tävling Kommunjakten. Som MotioMera-medlem samlar du troféer genom att gå genom så många kommuner som möjligt och lösa Kommun-quizar. Navigera dig till den kommun du önskar för att läsa om kommunen och lösa roliga quizar<br/>
<br/>

{*}Här kan du göra våra underhållande och utmanande kommunquiz!
<br/><br/>


{foreach from=$kommuner item=kommun name="kommunvapenLoop"}

	{assign var=kommunvapen value=$kommun->getKommunvapen() key=key}

	{if $kommunvapen}
		{if $smarty.foreach.kommunvapenLoop.iteration % 10 == 1}
			<div class="mmProfilKommunJaktItemFirst"><a href="{$urlHandler->getUrl(Quiz, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}"><img src="{$kommunvapen->getUrl()}" alt="{$kommun->getNamn()}" height="30" /></a></div>
		{else}
			<div class="mmProfilKommunJaktItem"><a href="{$urlHandler->getUrl(Quiz, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}"><img src="{$kommunvapen->getUrl()}" alt="{$kommun->getNamn()}" height="30" /></a></div>
		{/if}
	
		
	{else}
		{if $smarty.foreach.kommunvapenLoop.iteration % 10 == 1}
			<div class="mmProfilKommunJaktItemFirst"><a href="{$urlHandler->getUrl(Quiz, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}">{$kommun->getNamn()}</a></div>
		{else}
			<div class="mmProfilKommunJaktItem"><a href="{$urlHandler->getUrl(Quiz, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}">{$kommun->getNamn()}</a></div>
		{/if}		
	{/if}
	
	{if $smarty.foreach.kommunvapenLoop.iteration % 10 == 0}
		<div class="mmProfilKommunJaktItemNR"></div>
	{/if}

{/foreach}

{if $smarty.foreach.kommunvapenLoop.iteration % 7 != 0}
	<div class="mmProfilKommunJaktItemNR"></div>
{/if}
{*}

<script type="text/javascript">
	var map = new FusionMaps("/maps/C_FCMap_SwedenCountry.swf", "Map1Id", "340", "700", "0", "0");
	map.setDataURL("/maps/sverige.php");
	map.render("mapdiv");
</script> 

{foreach from=$lan key=link item=ettlan}

{*}<a href="{$ettlan}/">{$link}</a><br/><br/> {*}
<a href="{$urlHandler->getUrl(Kommunjakten, URL_VIEW, $ettlan)}/" title="{$link}">{$link}</a><br/><br/>



{/foreach}
