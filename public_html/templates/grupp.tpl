{if $owner || !isset($grupp)}
	<h1>Redigera grupp</h1>
	<form action="{$urlHandler->getUrl(Grupp, URL_SAVE)}" method="post">
		{if isset($grupp)}
		<p>
			Skapad av: {$skapare->getANamn()}
		</p>
		{/if}
		<p>
			Namn: <br />
			<input type="text" name="namn" value="{$gruppnamn}" />
		</p>
		<p>
			<input type="checkbox" name="publik" value="true"{if (isset($grupp) && $grupp->getPublik() eq 1) || (!isset($grupp))} checked="checked"{/if} />
			Publik
		</p>
		<p>
			<input type="submit" value="Spara" />
			{if isset($grupp)}
			<br /><br /><a onclick="{jsConfirm msg="Vill du verkligen ta bort den här gruppen?"}" href="{$urlHandler->getUrl(Grupp, URL_DELETE, $grupp->getId())}">Ta bort</a>{/if}
		</p>
	</form>
	{if isset($requests)}
		<strong>Ansökningar:</strong><br />
		{foreach from=$requests item=request}
			<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $request->getId())}">{$request->getANamn()}</a> <a href="/actions/answerrequest.php?do=accept&amp;gid={$grupp->getId()}&amp;mid={$request->getId()}">Godkänn</a> <a href="/actions/answerrequest.php?do=deny&amp;gid={$grupp->getId()}&amp;mid={$request->getId()}">Avböj</a><br />
		{/foreach}
	{/if}
	{if isset($ignored)}
	<p>
		<strong>Ignorerade ansökningar:</strong><br />
		{foreach from=$ignored item=thisignored}
			{$thisignored->getFNamn()} {$thisignored->getENamn()} <a href="/actions/unignore.php?gid={$grupp->getId()}&amp;mid={$thisignored->getId()}">Ta bort ignorering</a><br />
		{/foreach}
	</p>
	{/if}
{/if}
{if isset($grupp) && !$owner}

	<h1>{$grupp->getNamn()}</h1>
	
	<p>Skapad: {$grupp->getSkapad()|nice_date:"j F Y"}<br />
	{$grupp->getAntalMedlemmar()} {$grupp->getAntalMedlemmar()|mm_countable:"medlem":"medlemmar"}</p>
	
	
	
	{if $requestable}
		<a href="/actions/joingroup.php?gid={$grupp->getId()}">Ansök om medlemskap</a>
	{elseif $ismember}
		<a href="/actions/leavegroup.php?gid={$grupp->getId()}">Lämna gruppen</a>
	{/if}
{/if}

{if isset($medlemmar)}
<p>
	<strong>Medlemmar:</strong><br />
	{foreach from=$medlemmar item=medlem}
		<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $medlem->getId())}">{$medlem->getANamn()}</a>
		
		{if $skapare->getId() eq $USER->getId()}- <a href="{$urlHandler->getUrl(Grupp, URL_KICK, $medlem->getId())}">Kicka</a>{/if}
		<br />
	{/foreach}
</p>
{/if}
<p>
	<strong>Topplista</strong><br />
{if count($topplista) > 0}
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="mmWidthFyraFemPixlar">Plats</td>
		<td class="mmWidthEttTvaNollPixlar">Användare</td>
		<td>Steg</td>
	</tr>
	{foreach from=$topplista item=rad name=topplista}
	<tr>
		<td>
		#{$smarty.foreach.topplista.iteration}
		</td>
		<td>
		{assign var=tempMedlem value=$medlemmar[$rad.medlem_id]}
		{$tempMedlem->getANamn()}
		</td>
		<td>
		{$rad.steg}<br />
		</td>
	</tr>
	{/foreach}
{else}
	Den här gruppen har inga steg ännu.
{/if}
</table>
<br/>

<h2>Fotoalbum</h2>
{if $bildblock == false}
	<h3>Hittade inga bilder.</h3>
{else}
	<table cellspacing="5" class="mmWidthTvaNollFemPixlar">
		<tr>
			{foreach from=$bildblock item=bild}
				<td class="mmAlignCenter" width="25%">
					<a id="thumb" href="/actions/visafotoalbumbild.php?id={$bild->getId()}&storlek=stor" class="highslide" onclick="return hs.expand(this)">
						<img src="/actions/visafotoalbumbild.php?id={$bild->getId()}&storlek=mini" alt="{$bild->getNamn()}"  title="{$bild->getBeskrivningNinja()}" width="{$bild->getBredd("mini")}" height="{$bild->getHojd("mini")}" border="0" /></a>
					<br />
					{php}
						global $x;
						$x++;
						if (($x % 4) == 0) {
							$this->assign("show", true);
						} else {
							$this->assign("show", false);
						}
						$this->assign("x", $x);
					{/php}
				</td>
				{if $show}
					</tr><tr>
				{/if}
			{/foreach}
		</tr>
	</table>
{/if}

{*}<p>
	<strong>Anslagstavla</strong><br />
	{if $owner || $ismember}
		<form action="{$urlHandler->getUrl(AnslagstavlaRad, URL_SAVE)}" method="post">
			<input type="hidden" name="gid" value="{$grupp->getId()}"/>
			<input type="hidden" name="aid" value="{$grupp->getAnslagstavlaId()}"/>
			Skriv på anslagstavlan:<br/>
			<textarea name="atext" class="gruppTextarea"></textarea>
			<br/>
			<input type="submit" value="Skicka"/><br/><br/>
		</form>
	
	{/if}

	<table class="mmWidth75Procent" cellspacing=0 cellpadding=10>
	{foreach from=$anslagstavlarader item=rad name=anslagstavlarader}
	<tr>
		<td class="borderBottomBlack">
		{assign var=radnr value=$anslagstavlaantalrader-$smarty.foreach.anslagstavlarader.iteration+1}
		#{$radnr}
		</td>
		<td class="borderBottomBlack">
		{assign var=tempMedlem value=$rad->getMedlem()}
		{$tempMedlem->getANamn()}
		</td>
		<td class="borderBottomBlack">
			{$rad->getDatum()}
		</td>
		<td class="borderBottomBlack">
		{$rad->getText()}
		</td>
	</tr>
	{/foreach}
	</table>{*}
			<div class="mmAnslagstavla">
			
			<div class="mmAnslagstavlaBoxTop"><h3 class="mmWhite BoxTitle AnslagTitle">Anslagstavlan</h3></div>
			<div class="mmAnslagstavlaBoxBg">
				

				{if $anslagstavlaantalrader > 0}
				<table class="mmAnslagstavlaTabell" cellpadding="1" cellspacing="0">
					{foreach from=$anslagstavlarader item=rad name=anslagstavlarader}
					{assign var=radnr value=$anslagstavlaantalrader-$smarty.foreach.anslagstavlarader.iteration+1}
					{assign var=tempMedlem value=$rad->getMedlem()}
					<tr>
						{*}<td>#{$radnr}</td>{*}
						<td>{$tempMedlem->getANamn()}</td>
						<td><em>{$rad->getDatum()|nice_date:"d M Y"}</em></td>
						<td>{$rad->getText()}</td>
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
					<input type="hidden" name="gid" value="{$lag2->getId()}"/>
					<input type="hidden" name="aid" value="{$lag2->getAnslagstavlaId()}"/>
					Skriv på anslagstavlan:<br />
					<textarea name="atext" rows="5" cols="5"></textarea>
					<br />
					<input type="submit" value="Skicka"/><br /><br />
				</form>
				{/if}
			</div>

			<div class="mmAnslagstavlaBoxBottom"></div>
			
		</div>

		<div class="mmClearBoth"></div>
