<h1>Lägg till ljudfil för {$kommun->getNamn()}</h1>
<div class="mmText">Här kan du tipsa oss om du har en mp3-ljudfil med exempel på någon som pratar med dialekt från {$kommun->getNamn()}. För att tipsa oss med formuläret krävs det att ljudfilen redan ligger ute på internet, på din egna hemsida till exempel.
<p/>
Om du har en fil på din dator och inte har någonstans att lägga upp den får du gärna maila den till oss på adress <a href="mailto:motiomera@aller.se">motiomera@aller.se</a> (filen får ej vara större än 5 Mb). Glöm inte ange kommuntillhörighet, ålder och kön på den som talar och så ditt eget namn och gärna ditt användarnamn på MotioMera. </div>

<form action="{$urlHandler->getUrl(Kommundialekt, URL_SAVE)}" method="post">
	

<input type="hidden" name="kid" value="{$kommun->getId()}" />
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<tr>
			<th>Ålder</th>
			<td>
				{mm_html_options name=alder options=$opt_alder selected=$sel_alder}
				
			</td>
		</tr>
		<tr>
			<th>Kön</th>
			<td>
				{mm_html_options name=kon options=$opt_kon selected=$sel_kon}
			</td>
		</tr>
		<tr>
			<th>Länk till ljudfil<div style="font-weight:normal;">måste vara en mp3</div></td>
			<td><input type="text" class="mmWidthHundraProcent" name="url" value="{if isset($dialekt)}{$dialekt->getUrl()}{/if}" /><br/>
			Skriv hela adressen till ljudklippet, t.ex. <span class="mmNoWrap">http://www.motiomera.se/ljud/{$kommun->getNamn()}.mp3</span></td>
		</tr>

		<tr class="mmLastRow">
			<td></td>
			<td><input type="submit" value="Skicka" /></td>
		</tr>
	</table>
	
</form>
<p>
	<a href="{$urlHandler->getUrl(Kommun, URL_VIEW, $kommun->getNamn())}">Tillbaka till {$kommun->getNamn()} <img alt="Tillbaka till {$kommun->getNamn()}" src="/img/icons/ArrowCircleBlue.gif"/></a>
</p>
