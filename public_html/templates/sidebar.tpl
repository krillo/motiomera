		<div id="mmColumnRight">
		
			{if !$USER}
				


			
			{/if}

			<div class="mmAlbumBoxTop">
				<h3 class="mmWhite BoxTitle">Veckans värstingar</h3>
			</div>
			<div class="mmRightMinSidaBox">
				

					<table width="155" cellpadding="0" cellspacing="0" border="0">
						<tr>
						  <td>&nbsp;</td>
						  <td><b>Medlem</b></td>
						  <td><b>Steg</b></td>
						</tr>
					
						{foreach name=steglista from=$topplista->getTopplista(10) item=placering}
						<tr>
							<td>{$placering.placering}.</td>
							<td><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $placering.medlem->getId())}">{if isset($USER) && $placering.medlem->getId() == $USER->getId()}<strong class="mm_topplista_markerad">{$placering.medlem->getANamn()}</strong>{else}{$placering.medlem->getANamn()}{/if}</a></td>
							<td>{if isset($USER) && $placering.medlem->getId() == $USER->getId()}<strong class="mm_topplista_markerad">{$placering.steg|nice_tal}</strong>{else}{$placering.steg|nice_tal}{/if}</td>
						</tr>
							
						{/foreach}
					</table>
				
				<br/>
				<a href="{$urlHandler->getUrl("Topplista", URL_LIST)}">Visa fler topplistor <img src="/img/icons/ArrowCircleBlue.gif" alt="" /></a>

			</div>

			<div class="mmClearBoth"></div>
			<div class="mmGrayLineRight"></div>
			<div class="mmBoxRight">

				<span class="mmh2 mmOrange">SENASTE VECKAN</span><br /><br />
				{$stegSenasteVeckan|nice_tal} steg

			</div>

			<div class="mmCleraBoth"></div>
			<div class="mmGrayLineRight"></div>
			
			<div class="mmAlbumBoxTop">
				<h3 class="mmWhite BoxTitle">Medlemsprofiler</h3>
			</div>
			<div class="mmRightMinSidaBox">
			
			
				{foreach name=medlemsprofiler from=$medlemsprofiler item=medlemsprofil}
				{assign var=avatar value=$medlemsprofil->getAvatar()}
				{assign var=kommun value=$medlemsprofil->getKommun()}
				

				<a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $medlemsprofil->getId())}"><img src="{$avatar->getUrl()}" alt="{$medlemsprofil->getANamn()}" class="motiomera_start_medlemsprofil" /></a>
				<span class="mmLinkGreen"><a href="{$urlHandler->getUrl("Medlem", URL_VIEW, $medlemsprofil->getId())}"><b>{$medlemsprofil->getANamn()}</b></a></span> &nbsp;<img src="/img/icons/ArrowsGreen.gif" alt="" class="mmVerticalAlignMiddle" /><br />
				<strong>Bor:</strong> <a href="{$urlHandler->getUrl("Kommun", URL_VIEW, $kommun->getUrlNamn())}">{$kommun->getNamn()}</a><br />
				
				{assign var=profildata value=$medlemsprofil->getProfilDataValObject("random")}
				
				<strong>{$profildata.namn}:</strong> {$profildata.varde}

		<div class="mmClearBoth"></div>

				{/foreach}
				
				</div>

					<div class="mmGrayLineRight"></div>



				{$texteditor_nh->getTexten()}

		</div>
		<!-- END mmColumnRight -->
