	<div class="mmAlbumBoxTop">
		<h3 class="BoxTitle">Klubbar</h3>
	</div>
	<div class="mmRightMinSidaBox box-margin-bottom">
	{foreach from=$grupper item=grupp}
		<a href="{$urlHandler->getUrl(Grupp, URL_VIEW, $grupp->getId())}">{$grupp->getNamn()}</a>
		{if $grupp->getSkapareId() == $USER->getId()}<img src="/img/icons/star.gif" alt="Skapad av mig" class="mmStarText" />{/if}
		<br />
	{/foreach}
	<br />
	<img src="/img/icons/star.gif" alt="Skapad av mig" /> = skapad av mig
	</div>
