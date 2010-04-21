<div class="mmTotaltAntalSteg"><b>Totalt:</b> {$grupp->getStegTotal()|nice_tal} steg</div>
	<div class="mmKlubbarAvatarTop"><img src="/img/icons/AvatarKlubbTop.gif" alt="" /></div>
		<div class="mmh2">{$grupp->getNamn()}</div>
		Skapad av <a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $skapare->getId())}">{$skapare->getANamn()}</a> {$grupp->getSkapad()|nice_date:"j F"} | {$grupp->getAntalMedlemmar()} {$grupp->getAntalMedlemmar()|mm_countable:"medlem":"medlemmar"} {if $requestable} | <a href="#" onClick="motiomera_ansok_medlem({$grupp->getId()})">Ansök om medlemskap &raquo;</a>
		{*}<a href="/actions/joingroup.php?gid={$grupp->getId()}">Ansök om medlemskap &raquo;</a>{*}
	{elseif $ismember} | 
		<a href="/actions/leavegroup.php?gid={$grupp->getId()}" onclick="var q=confirm('Är du säker på att du vill lämna den här klubben?'); return q;">Lämna klubben &raquo;</a>
	{else}
		| Ansökan skickad
	{/if}
	{if $grupp->getSkapareId() == $USER->getId()}
		&nbsp;|&nbsp;<a href="{$urlHandler->getUrl(Grupp, URL_EDIT, $grupp->getId())}" title="Hantera klubb">Hantera klubb</a>
	{/if}<br /><br />
	{if $grupp->getStart() && $grupp->getStegTotal() > 0}
	{include file="positioner.tpl"}
	{/if}
		<div class="mmFloatRight">
		
			<div class="mmAlbumBoxTop">
				<h3 class="mmWhite BoxTitle">Stegtoppen</h3>
			</div>
			<div class="mmRightMinSidaBox">
			
			<strong>Steg senaste 7 dagarna</strong><br /><br />
			<table width="155" cellpadding="0" cellspacing="0" border="0">
				<tr>
				  <td>&nbsp;</td>
				  <td><b>Medlem</b></td>
				  <td><b>Steg</b></td>
				</tr>
		
				{foreach name=steglista from=$topplista->getTopplista(10,$medlem) item=placering}
				{if $placering.placering == 11}
		
					{assign var=tomrad value=1}
		
				{/if}
				
				{if $placering.placering > 10 && $tomrad == 0}
				
					{assign var=tomrad value=1}
					
					<tr><td>&nbsp;</td></tr>
				
				{/if}
				<tr>
					<td>{$smarty.foreach.steglista.iteration}.</td>
					<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()}</strong>{else}{$placering.medlem->getANamn()}{/if}</a></td>
					<td>{if $placering.medlem->getId() == $medlem->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
				</tr>
					
				{/foreach}
			</table>
			
			<br/>
			<a href="{$urlHandler->getUrl("Topplista", URL_LIST, $person_klubb_array)}">Visa fler topplistor <img src="/img/icons/ArrowCircleBlue.gif" alt="" /></a>
			
			</div>
			
			<br/>
		
			{include file='bildblock.tpl'}
		</div>

		{include file="stegstaplar.tpl"}

		<div class="mmBlueBoxTop"><h3 class="mmWhite BoxTitle">Medlemmar</h3></div>
		<div class="mmBlueBoxBg">
			<table class="mmMedlemmarTabell" border="0" cellpadding="5" cellspacing="0">
				<tr>
					{foreach name=medlemloop from=$medlemmar item=medlem}
							<td class="mmCenterText">
								{assign var=avatar value=$medlem->getAvatar()}
								<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}"><img src="{$avatar->getUrl()}" alt="" width="15" class="mmAvatarMini" /></a><br /><a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}">{$medlem->getANamn()}</a>
							</td>
							{if !(($smarty.foreach.medlemloop.iteration) mod 4)}
								</tr>
								<tr>
							{/if}
					{/foreach}
				</tr>
			</table><br />
			{*}<div class="mmTextalignRight mmMarginRight20"><a href="#">Se alla deltagare</a><a href="#"><img src="/img/icons/ArrowCircleBlue.gif" class="mmMarginLeft3 mmArrow" alt="" /></a></div>{*}
		</div>
		<div class="mmBlueBoxBottom"></div>

		
		<div class="mmAnslagstavla">
			
			<div class="mmAnslagstavlaBoxTop"><h3 class="mmWhite BoxTitle AnslagTitle">Anslagstavlan</h3></div>
			<div class="mmAnslagstavlaBoxBg">
				

				
				{if $nbrPosts > 0}
				<table class="mmAnslagstavlaTabell" cellpadding="1" cellspacing="0">
					{foreach from=$atavla key=myId item=i}
					<tr>
						<td>{$i.anamn}</td>
						<td><em>{$i.ts|nice_date:"d/m-y"}</em>&nbsp;</td>
						<td>{$i.text}</td>
					</tr>
					{/foreach}
				</table>
				{/if}				
				
				<div class="mmTextalignRight mmMarginRight10">
					{*}<a href="#">L&auml;s alla inlägg</a>
					<a href="#">
						<img src="/img/icons/ArrowCircleBlue.gif" alt="" class="mmMarginLeft3 mmArrow" />
					</a>{*}
				</div>
				{if $owner || $ismember}
				<form action="{$urlHandler->getUrl(AnslagstavlaRad, URL_SAVE)}" method="post">
					<input type="hidden" name="gid" value="{$grupp->getId()}"/>
					<input type="hidden" name="aid" value="{$grupp->getAnslagstavlaId()}"/>
					Skriv på anslagstavlan:<br/>
					<textarea name="atext" rows="5" cols="5"></textarea>
					<br/>
					<input type="submit" value="Skicka"/><br/><br/>
				</form>
				{/if}
			</div>

			<div class="mmAnslagstavlaBoxBottom"></div>
			
		</div>

		<div class="mmClearBoth"></div>
