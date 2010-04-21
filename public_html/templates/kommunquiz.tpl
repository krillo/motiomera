<h1>Kommunquiz</h1>
Här kan du göra våra underhållande och utmanande kommunquiz!
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
