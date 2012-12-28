<h1>Topplistor{if isset($gruppnamn)} för klubben {$gruppnamn}{else} för hela Motiomera{/if}</h1>


<div class="mmTopplistaRuta tavlingsresult-width margin-right">
	<div class="mmAlbumBoxTop tavlingsresult-width">
		<h3 class="mmWhite BoxTitle">Steglistan 4 veckor</h3>
	</div>
	<div class="mmRightMinSidaBox tavlingsresult-width">
	<table width="155" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Steg</b></td>
		</tr>
		{assign var=tomrad value=0}
		{foreach name=steglista from=$topplista_28->getTopplista(100,$medlem) item=placering}		
		{if $placering.placering == 11}
			{assign var=tomrad value=1}
		{/if}
		{if $placering.placering > 10 && $tomrad == 0}		
			{assign var=tomrad value=1}			
			<tr><td colspan="3"><hr/></td></tr>		
		{/if}
		<tr {if $smarty.foreach.steglista.index % 2}{else}class="odd"{/if}>
			<td>{$placering.placering}.</td>
			<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()|truncate:16}</strong>{else}{$placering.medlem->getANamn()|truncate:16}{/if}</a></td>
			<td>{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
		</tr>
		{/foreach}
	</table>
	</div>
</div>




<div class="mmTopplistaRuta tavlingsresult-width margin-right">
	<div class="mmAlbumBoxTop tavlingsresult-width">
		<h3 class="mmWhite BoxTitle">Steglistan 7 dagar</h3>
	</div>
	<div class="mmRightMinSidaBox tavlingsresult-width">
	<table width="155" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Steg</b></td>
		</tr>	
		{foreach name=steglista from=$topplista_sju->getTopplista(25,$medlem) item=placering}
		{if $placering.placering == 11}
			{assign var=tomrad value=1}
		{/if}
		{if $placering.placering > 10 && $tomrad == 0}		
			{assign var=tomrad value=1}			
			<tr><td colspan="3"><hr/></td></tr>		
		{/if}
		<tr {if $smarty.foreach.steglista.index % 2}{else}class="odd"{/if}>
			<td>{$placering.placering}.</td>
			<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()|truncate:16}</strong>{else}{$placering.medlem->getANamn()|truncate:16}{/if}</a></td>
			<td>{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
		</tr>			
		{/foreach}
	</table>
	</div>
</div>



  
<div class="mmTopplistaRuta tavlingsresult-width">
	<div class="mmAlbumBoxTop tavlingsresult-width">
		<h3 class="mmWhite BoxTitle">Kommunquiz 7 dagar</h3>
	</div>
	<div class="mmRightMinSidaBox tavlingsresult-width">	
	<table width="155" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Quiz</b></td>
		</tr>
		{foreach name=steglista from=$topplista_quiz->getTopplista(10,$medlem) item=placering}		
		{if $placering.placering == 11}
			{assign var=tomrad value=1}
		{/if}		
		{if $placering.placering > 10 && $tomrad == 0}		
			{assign var=tomrad value=1}			
			<tr><td>&nbsp;</td></tr>		
		{/if}
		<tr {if $smarty.foreach.steglista.index % 2}{else}class="odd"{/if}>
			<td>{$placering.placering}.</td>
			<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()|truncate:16}</strong>{else}{$placering.medlem->getANamn()|truncate:16}{/if}</a></td>
			<td>{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.quiz_antal|nice_tal}</strong>{else}{$placering.quiz_antal|nice_tal}{/if}</td>
		</tr>			
		{/foreach}
	</table>
	</div>
</div>

  
  



<div class="mmTopplistaRuta margin-top tavlingsresult-width">
	<div class="mmAlbumBoxTop tavlingsresult-width">
		<h3 class="mmWhite BoxTitle">Kommuner 7 dagar</h3>
	</div>
	<div class="mmRightMinSidaBox tavlingsresult-width">
	<table width="155" cellpadding="0" cellspacing="0" border="0">
		<tr>
		  <td>&nbsp;</td>
		  <td><b>Medlem</b></td>
		  <td><b>Kommuner</b></td>
		</tr>
		{foreach name=steglista from=$topplista_kommuner->getTopplista(10,$medlem) item=placering}
		{if $placering.placering == 11}
			{assign var=tomrad value=1}
		{/if}
		{if $placering.placering > 10 && $tomrad == 0}
			{assign var=tomrad value=1}
			<tr><td>&nbsp;</td></tr>		
		{/if}
		<tr {if $smarty.foreach.steglista.index % 2}{else}class="odd"{/if}>
			<td>{$placering.placering}.</td>
			<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()|truncate:16}</strong>{else}{$placering.medlem->getANamn()|truncate:16}{/if}</a></td>
			<td>{if isset($medlem) && $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.kommuner_antal|nice_tal}</strong>{else}{$placering.kommuner_antal|nice_tal}{/if}</td>
		</tr>			
		{/foreach}
	</table>
	</div>
</div>