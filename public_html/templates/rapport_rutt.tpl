<h1>Skapa en rutt</h1>
<div id="mapdivsmall" class="mmFloatRight mm-map-area">
{if $abroadId}
	<img src="{$abroadImage}" alt="" />
{/if}
</div>
{if !$abroadId}
<script type="text/javascript">
<!--
	var map = new FusionMaps("/maps/C_FCMap_SwedenKommuner.swf", "Map1Id", "400", "700", "0", "0");
	map.setDataURL(escape("/maps/visited_kommuner.php?z=0&medlem={$medlem->getId()}"));
	map.render("mapdivsmall");
-->
</script>

{/if}
<br/>

<div class="ShadowBox">
<div class="ShadowBoxTop"></div>
<h2 class="mmMarginBottom">Aktuell rutt</h2>
{if !isset($notown)}
	<span style="padding-right:30px;margin-bottom: 30px;">Här kan du skapa din egen rutt genom att klicka på "Skapa/utöka EGEN RUTT".</span>
{/if}
{if isset($rutten)}
<table border="0" cellpadding="0" cellspacing="0" class="mmWidthTvaTvaNollPixlar">
	<tr>
		<th>&nbsp;</th>
		<th class="mmValjRuttKommunTh">&nbsp;Kommun</th>
		<th class="mmValjRuttAvstandTh">Avstånd<br/>från start</th>
	</tr>

	{assign var=bstyle value='background: #FBD464;'}
	{assign var=fastClass value="fastRutt"}

	{assign var=class value="mmRuttKommunSvart"}
	{foreach from=$rutten item=stracka name=rapport}
		{assign var=kommun value=$stracka.Kommun}
		{assign var=kommunvapen value=$kommun->getKommunvapen()}
	{if $smarty.foreach.rapport.index == $rutt->getCurrentIndex()}
		{$rutt->getCurrentIndex}
	{/if}
		<tr {if $stracka.fastRutt == 'true'} class="{$fastClass}" {/if} style="{$bstyle}">
			{*} {if $stracka.temp==1}#aaaaaa;{/if}{*}
			<td style="white-space:nowrap; font-weight:bold; text-align:right; background-color:#{if ($stracka.temp==1)}#aaaaaa;{/if}/*ffffff*/;">
			{if $smarty.foreach.rapport.first}
				Start <img src="/img/icons/PilRod.gif" alt="" />
			{/if}
			{if !$smarty.foreach.rapport.first && $smarty.foreach.rapport.index == $rutt->getCurrentIndex()}
				Just nu <img src="/img/icons/PilRod.gif" alt="" />
				{if $smarty.foreach.rapport.last}
					{assign var=slutmal value="true"}
				{/if}
			{elseif !$smarty.foreach.rapport.first && ($smarty.foreach.rapport.iteration == $lastNonTempIndex || (!$lastNonTempIndex && $smarty.foreach.rapport.last))}
				Mål <img src="/img/icons/PilRod.gif" alt="" />
			{/if}
			</td>
			<td class="kommunVapen">
{*}				{if $kommunvapen}
					<img src="{$kommunvapen->getUrl()}" width="12" alt="{$kommun->getNamn()} kommunvapen" />
				{/if}
{*}
				<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getUrlNamn())}" class="{$class}" {*}{if !$kommunvapen}class="paddingLeft15"{/if}{*}>&nbsp;{$kommun->getNamn()}</a>
			</td>
			{*}<td class="mmValjRuttAvstandTh">{$stracka.ThisKm} km</td>{*}
			<td class="mmValjRuttAvstandTh">{$stracka.TotalKm} km</td>
			<td class="mmBgWhite">
			{if $smarty.foreach.rapport.last && $slutmal!="true" && !isset($notown) && !$userOnStaticRoute}
				<a href="{$urlHandler->getUrl(Stracka, URL_DELETE, $stracka.id)}" class="taBortKommun"><img src="/img/icons/Papperskorg.gif" alt="Ta bort kommun" /></a>
			{/if}
			</td>
		</tr>
		{if $slutmal=="true"}
		<tr class="mmBgWhite">
			<td class="ettMal">
				Mål <img src="/img/icons/PilRod.gif" alt="" />
			</td>
			<td class="ingetValt">
				Inget valt
			</td>
		</tr>
		{/if}
		{if $smarty.foreach.rapport.index == $rutt->getCurrentIndex()}
			{assign var=passerat value=true}
{*}			{assign var=class value="mmRuttKommunGul"}
{*}			{assign var=bstyle value='background: #FDECB9;'}
			{assign var=fastClass value="fastRuttPlanerad"}
		{/if}

	{/foreach}
	</table>


<br/>
<div class="ruttFloatLeft"><span class="avklaradRutt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Avklarad rutt<br /><br /></div>
{if $sajtDelarObj->medlemHasAccess($USER,"fastaRutter")}
	<div class="ruttFloatLeft"><span class="fastRutt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Fast rutt, avklarad<br /><br />  </div>
{/if}
<div class="ruttFloatLeft"><span class="planeradRutt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Planerad rutt<br /><br />	</div>
{if $sajtDelarObj->medlemHasAccess($USER,"fastaRutter")}
	<div class="ruttFloatLeft"><span class="fastRuttPlanerad">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Fast rutt, planerad</div>
{/if}
<br/>
{/if}
{if !isset($notown)}
	{if !$userOnStaticRoute}
		<p>
		<a href="valj_rutt.php" class="laggTillKommun" style="padding-right:30px;">Skapa/utöka EGEN&nbsp;RUTT<img src="/img/icons/ArrowCircleBlue.gif" alt="Lägg till kommuner på rutten" /></a>
		<br/>
	{/if}
{/if}
<br/><br/>
{if $sajtDelarObj->medlemHasAccess($USER,"fastaRutter") and !$userOnStaticRoute}

	<a class="laggTillKommun" style="clear: left; padding-top:20px; float: left">Välj en FAST&nbsp;RUTT</a><br/>
		<ul style="clear:left; padding-top: 10px;">
			{foreach from=$fastaUtmaningar item=utmaning}
				<li><a href="{$urlHandler->getUrl("FastaUtmaningar", URL_SAVE, $utmaning.id)}" onclick="var q = confirm('Du har valt en fast rutt. Ifall du redan lagt upp en egen rutt kommer den att ersättas av den fasta rutten. De kommuner du redan besökt kommer dock att ligga kvar i listan.\n\nOm du klarar hela rutten får du en fin pokal till troféhyllan på Min sida. Lycka till!'); return q;">{$utmaning.namn}&nbsp;&nbsp;<img src="/img/icons/ArrowCircleBlue.gif" alt="Välj denna fasta rutt" /></a></li>
			{/foreach}
		</ul>
<a href="/pages/fastarutter.php" class="laggTillKommunSmall">Läs mer om de fasta rutterna</a>
{/if}
{if $userOnStaticRoute}
	<br />
	<a href="{$urlHandler->getUrl("FastaUtmaningar", URL_SWITCH, $USER->getId())}" class="laggTillKommun" onclick="var q = confirm('Vill du avbryta den pågående fasta rutten? Tänk på att du bara får den fina pokalen om du gått klart hela den fasta rutten.'); return q;" style="padding-right:30px;">
	AVBRYT fast rutt &nbsp;&nbsp;<img src="/img/icons/ArrowCircleBlue.gif" alt="Välj denna fasta rutt" />
	<!--<img src="/img/minsida/mmLockedRoute.gif" alt="" />-->

</a>
	<br />
{/if}

	<br/><br/><div class="ShadowBoxBottom"></div>
</div>
