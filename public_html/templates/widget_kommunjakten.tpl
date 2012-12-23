<div class="mmBlueBoxTop"><h3 class="BoxTitle">Troféer</h3><a href="javascript:;" onclick="mm_rapportera_show_help(11,280,400,'topleft')"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" class="mmBlueBoxMiddleFaqCircle" /></a></div>
<div class="mmBlueBoxBg">
	<table border="0" cellspacing="0" cellpadding="0" class="mmWidthHundraProcent">
		<tr>
			<td class="mmMedaljerTd"><strong>Medaljer</strong></td>
			<td>
				
   				{assign var=antal value=$guldmedaljer|@count}
				{assign var=medalj value=guld}
				{assign var=medaljer value=$guldmedaljer}
				
				{section name=medaljloop loop=2}
				
				{if $antal lt 3}
				{foreach from=$medaljer item=medalj}
					<img src="/img/icons/medalj_{$medalj.medalj}.gif"  title="{if $medalj eq "guld"}Guldmedalj{else}Silvermedalj{/if} får du genom att gå minst {if $medalj eq "guld"}77 000{else}49 000-76 999{/if} steg på en vecka"/>
				{/foreach}
				{else}
				{if $antal gt 18}{assign var=loopantal value=18}{else}{assign var=loopantal value=$antal}{/if}
				<span class="mmMedaljAntal{if $medalj eq "guld"} mmMedaljAntalGold{/if}">({$antal})</span> <img src="/img/icons/medalj_{$medalj}_1.gif" alt="" title="{if $medalj eq "guld"}Guldmedalj{else}Silvermedalj{/if} får du genom att gå {if $medalj eq "guld"}minst {77000|nice_tal}{else}49 000-76 999{/if} steg på en vecka" />{section name=medaljerloop loop=$loopantal-1}<img src="/img/icons/medalj_{$medalj}_2.gif" alt="" title="{if $medalj eq "guld"}Guldmedalj{else}Silvermedalj{/if} får du genom att gå {if $medalj eq "guld"}minst {77000|nice_tal}{else}49 000-76 999{/if} steg på en vecka" />{/section}<img src="/img/icons/medalj_{$medalj}_3.gif" alt="" title="{if $medalj eq "guld"}Guldmedalj{else}Silvermedalj{/if} får du genom att gå {if $medalj eq "guld"}minst {77000|nice_tal}{else}49 000-76 999{/if} steg på en vecka" />
				
				{/if}
				
				{assign var=antal value=$silvermedaljer|@count}
				{assign var=medalj value=silver}
				{assign var=medaljer value=$silvermedaljer}
				
				{/section}

			</td>
		</tr>
		<tr>
			<td><strong>Pokaler</strong></td>
			<td>
				{foreach from=$guldpokaler item=pokal}
				<img src="/img/icons/pokal_{$pokal.pokal}.gif" alt="" title="Guldpokal får du genom att gå minst {$stegGuldpokal|nice_tal} steg på ett kavartal (91 dagar)" />
				{/foreach}
				{foreach from=$silverpokaler item=pokal}
				<img src="/img/icons/pokal_{$pokal.pokal}.gif" alt="" title="Silverpokal får du genom att gå minst {$stegSilverpokal|nice_tal} steg på ett kavartal (91 dagar)" />
				{/foreach}
			</td>
		</tr>
		{if count($staticRoutePokal) > 0}
		<tr>
			<td colspan="2"><strong>Fasta Rutter</strong></td>
		</tr>
		<tr>
			<td colspan="2">
				<ul id="fastaRutterPokaler">
				{foreach from=$staticRoutePokal item=pokal}
					<li>{$pokal.namn }</li>
				{/foreach}
				</ul>
			</td>
		</tr>
		{/if}
	</table>
	<br />
	<div><strong>Kommuner</strong></div>
	{foreach from=$kommunvapenList item=thisKommunvapen name="kommunvapenLoop"}
	{assign var=kommun value=$thisKommunvapen->getKommun()}

	{if $smarty.foreach.kommunvapenLoop.iteration % 6 == 1}
		<div class="mmProfilKommunJaktItemFirst"><a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}"><img src="../../files/kommunbilder/{$thisKommunvapen->getThumb()}" alt="{$kommun->getNamn()}" height="30" /></a></div>
	{else}
		<div class="mmProfilKommunJaktItem"><a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}" title="{$kommun->getNamn()}"><img src="../../files/kommunbilder/{$thisKommunvapen->getThumb()}" alt="{$kommun->getNamn()}" height="30" /></a></div>
	{/if}

	{if $smarty.foreach.kommunvapenLoop.iteration % 6 == 0}
		<div class="mmProfilKommunJaktItemNR"></div>
	{/if}
	{/foreach}
	
	{if $smarty.foreach.kommunvapenLoop.iteration % 6 != 0}
		<div class="mmProfilKommunJaktItemNR"></div>
	{/if}

	<br />
	<div class="{if $medlem->getAntalSuccessfullQuizzes() != 0}pointer {/if}mmQuizKnapp mmCursorDefault">
		<div>
			<span class="mmh3 mmOrange"><br />Har klarat {$medlem->getAntalSuccessfullQuizzes()} Kommunquiz</span>
		</div>
	</div>
</div>
<div class="mmBlueBoxBottom"></div>
<div class="mmClearLeft"></div>