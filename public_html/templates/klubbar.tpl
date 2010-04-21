<h1>Klubbar</h1>

<p>
	<a href="{$urlHandler->getUrl(Grupp, URL_CREATE)}">Skapa ny klubb</a>
</p>

{if isset($invites)}
<p>
	<strong>Inbjudningar</strong><br />
	{foreach from=$invites item=invite}
		{$invite->getNamn()} <a href="/actions/answerinvite.php?do=accept&amp;gid={$invite->getId()}">Acceptera</a> <a href="/actions/answerinvite.php?do=deny&amp;gid={$invite->getId()}">Ignorera</a><br />
	{/foreach}
</p>
{/if}

{if isset($medlemsgrupper)}

<p><img src="/img/icons/star.gif" alt="Skapad klubb" class="mmStarText" /> = Klubb som jag skapat</p>

<h3>Mina klubbar</h3>
<p>
{foreach from=$medlemsgrupper item=grupp}
<a href="{$urlHandler->getUrl(Grupp, URL_VIEW, $grupp->getId())}">{$grupp->getNamn()}</a>
{if $grupp->getSkapareId() == $USER->getId()}<img src="/img/icons/star.gif" alt="" class="mmStarText" />&nbsp;|&nbsp;<a href="{$urlHandler->getUrl(Grupp, URL_EDIT, $grupp->getId())}" title="Hantera klubb"><em>Hantera klubb</em></a>{/if}
<br />
{/foreach}
</p>
{/if}

<h3>Alla klubbar</h3>
	
	{foreach from=$kommunerOchGrupper key=kommun_id item=grupper name=kommunloop}

	<div class="mmKommungrupperKommun">
		<span id="mmKommungrupperPrefix{$smarty.foreach.kommunloop.iteration}">+</span>
		<h3>
			{assign var=kommunvapen value=$kommuner[$kommun_id]->getKommunvapen()}
			{if $kommunvapen}
			<img src="../../files/kommunbilder/{$kommunvapen->getThumb()}" alt="" class="mmKommunvapen" />
		{/if}
			<a href="#" onclick="motiomera_expanderaKommungrupper({$smarty.foreach.kommunloop.iteration}); return false;">
				{$kommuner[$kommun_id]->getNamn()} ({$grupper|@count})
			</a>
		</h3>

	</div>
	<div class="mmKommungrupperGrupper mmDisplayNone" id="mmKommungrupperKommuner{$smarty.foreach.kommunloop.iteration}">
		{foreach from=$grupper item=grupp}
		{assign var=skapare value=$grupp->getSkapare()}
		<div>
			<h3>{if $grupp->getId()|in_array:$medlemsgrupper_id}<b>{/if}<a href="{$urlHandler->getUrl(Grupp, URL_VIEW, $grupp->getId())}" title="Gå till {$grupp->getNamn()}">{$grupp->getNamn()}</a>{if $grupp->getId()|in_array:$medlemsgrupper_id}</b>{/if}</h3>
			<span>
				{$grupp->getAntalMedlemmar()} {$grupp->getAntalMedlemmar()|mm_countable:"medlem":"medlemmar"}
				|
				Totalt {$grupp->getStegTotal()|nice_tal} steg
				|
				Skapad av <a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $skapare->getId())}">{$skapare->getANamn()}</a> {$grupp->getSkapad()|nice_date:"j F Y":"":true}
			</span>
		</div>
		{/foreach}
	</div>

	
	{/foreach}



