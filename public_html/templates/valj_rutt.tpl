<div id="valj_rutt_container" style="height:1960px;width:1px">

{if !$ajax}<div id="motiomera_valjrutt_rutt">{/if}

<div class="ShadowBox">
<div class="ShadowBoxTop"></div>
<h2 class="mmMarginBottom">Planerad rutt</h2>
{*}{if $egensida}
	Lägg in din önskade rutt och slutdestination här.<br/><br/>
{/if}
{*}
{if isset($firstrun)}
	<form action="/actions/save.php">
	<input type="hidden" name="table" value="changeStartKommun" />
	<input type="hidden" name="mid" value="{$medlem->getId()}" />
	Välj startkommun: <select name="startkommun" onChange="this.form.submit();">
		{html_options values=$op_id output=$op_namn}
		</select>
	</form>
{/if}
<div id="motiomera_valjrutt_scrollbox">
<table border="0" cellpadding="0" cellspacing="0" class="mmWidthTvaTvaNollPixlar">
	<tr>
		<th>&nbsp;</th>
		<th id="mmValjRuttKommunTh">&nbsp;Kommun</th>
		{*}<th id="mmValjRuttAvstandTh">Avstånd</th>{*}
		<th class="mmValjRuttAvstandTh">Avstånd<br/>från start</th>
		{*}<th class="mmValjRuttQuizResult">Quizresultat</th>{*}
	</tr>

	{assign var=bstyle value='background-color: #FBD464;'}

	{assign var=class value="mmRuttKommunSvart"}
	{foreach from=$rutten item=stracka name=rapport}
		{assign var=kommun value=$stracka.Kommun}
		{assign var=kommunvapen value=$kommun->getKommunvapen()}
	{if $smarty.foreach.rapport.index == $rutt->getCurrentIndex()}
		{$rutt->getCurrentIndex}
	{/if}
		<tr style="{$bstyle}">
			{*} {if $stracka.temp==1}#aaaaaa;{/if}{*}
			<td style="white-space:nowrap; font-weight:bold; text-align:right; background-color:{if ($stracka.temp==1)}#aaaaaa;{else}#ffffff{/if};">
			{if $smarty.foreach.rapport.first}
				Start <img src="/img/icons/PilRod.gif" alt="" class="marginRight5" />
			{/if}
			{if !$smarty.foreach.rapport.first && $smarty.foreach.rapport.index == $rutt->getCurrentIndex()}
				Just nu <img src="/img/icons/PilRod.gif" alt="" class="marginRight5" />
				{if $smarty.foreach.rapport.last}
					{assign var=slutmal value="true"}
				{/if}
			{elseif !$smarty.foreach.rapport.first && ($smarty.foreach.rapport.iteration == $lastNonTempIndex || (!$lastNonTempIndex && $smarty.foreach.rapport.last))}
				Mål <img src="/img/icons/PilRod.gif" alt="" class="marginRight5" />
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
			{if $smarty.foreach.rapport.last and $slutmal!="true"}
				<a href="javascript:;" onclick="motiomera_ruttRaderaStracka({$stracka.id})" class="taBortKommun"><img src="/img/icons/Papperskorg.gif" alt="Ta bort kommun" /></a>
			{/if}
			</td>
		</tr>
		{if $slutmal=="true"}
		<tr class="mmBgWhite">
			<td class="slutMal">
				Mål <img src="/img/icons/PilRod.gif" alt="" class"marginLeftNegative5" />
			</td>
			<td class="slutMalIngetValt">
				Inget valt
			</td>
		</tr>
		{/if}
		{if $smarty.foreach.rapport.index == $rutt->getCurrentIndex()}
			{assign var=passerat value=true}
{*}			{assign var=class value="mmRuttKommunGul"}
{*}			{assign var=bstyle value='background: #FDECB9;'}
		{/if}

	{/foreach}
	</table>
	</div>
<div class="marginLeft12">
Totalt antal steg kvar till ditt mål: {$totalStegKvar|nice_tal}<br/><br/>
Kvar om du går 7.000 steg per dag: {$dagar7000} dagar<br/>
Kvar om du går 11.000 steg per dag: {$dagar11000} dagar<br/>
</div>
<br />
<div class="marginLeft8">
	<img src="../img/icons/avbryt_ikon.gif" alt="Avbryt rutt" onclick="var q = confirm('Är du säkert på att du vill avbryta? Icke godkänd rutt kommer försvinna.'); if(q) location.href='/actions/save.php?table=stracka_r'; else return false;" />
	<img src="../img/icons/godkann_rutt_ikon.gif" alt="Godkänn rutt" onclick="location.href='/actions/save.php?table=stracka_g';"/>
</div>
<br clear="all"/>
<div class="ShadowBoxBottom"></div>


</div>
{if !$ajax}

</div>

  <!--div class="clear"></div-->  
<div id="motiomera_valjrutt_flytande_knappar">
	<img src="../img/icons/avbryt_ikon.gif" alt="Avbryt rutt" onclick="var q = confirm('Är du säkert på att du vill avbryta? Icke godkänd rutt kommer försvinna.'); if(q) location.href='/actions/save.php?table=stracka_r'; else return false;" />
	<img src="../img/icons/godkann_rutt_ikon.gif" alt="Godkänn rutt" onclick="location.href='/actions/save.php?table=stracka_g';"/>
</div>

<div id="mapdiv">
</div>

<script type="text/javascript">
	var map = new FusionMaps("/maps/C_FCMap_SwedenKommuner.swf", "pomap", "770", "2000", "0", "1");
	map.setDataURL("/maps/kommun_valjRuttNextFirsttime.php");
	map.render("mapdiv");	
</script> 

{/if}

</div> 