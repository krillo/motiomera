<h1>{$pagetitle}</h1>

<div id="infotext" style="position:absolute;left:530px;top:131px;border:2px solid;padding:6px;">
<h3>TILLÄGGSBESTÄLLNING</h3>
Är du redan kund och vill göra en<br/>tilläggsbeställning? Logga in på din<br/>administrationssida där du enkelt kan<br/>lägga till fler deltagare. <a href="/pages/foretaglogin.php">Klicka här</a>.
</div>

<form action="{$urlHandler->getUrl(Order, URL_SEND)}" method="post" onsubmit="return motiomera_validateSkapaForetagForm(this)">
	<input type="hidden" name="typ" value="foretag">
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table" style="width:523px;">
		<tr>
			<th>Företagets namn</th>
			<td><input type="text" name="namn" /></td>
		</tr>
		{*}<tr>
			<th>Antal deltagare</th>
			<td><input type="text" name="antal" size="4" maxlength="4" onblur="mm_foretag_uppdateraPriser(this.value);" /></td>
		</tr>{*}
		
		{*}<tr>
			<th>Startkommun att utgå ifrån</th>
			<td>{mm_html_options name=kid options=$opt_kommuner}</td>
		</tr>{*}
     
        
		<tr>
			<th  style="border-bottom:none;">Startdatum</th>					
			<td  style="border-bottom:none;">
				<input name="startdatumRadio" id="mmForetagStartdatumRadio1" type="radio" value="2012-03-12" checked>
				<span style="text-decoration: none;">Den stora vårtävlingen 12 mars </span>
				<!-- span style="color: #f00; font-weight:bold;">
				   Det är fortfarande möjligt att anmäla er till Vårtävlingen. Vi kan dock inte garantera att stegräknarna levereras till tävlingens startdatum. Det är även möjligt att välja annat startdatum nedan.
				</span-->
				<!-- span style="color: #f00; font-weight:bold;">
				   Anmälan till Vårtävlingen är nu stängd. Det är dock möjligt att välja annat startdatum nedan, samt att anmäla er till höstens tävling. Välkomna!
				</span -->
			</td>
		
			
		</tr>
		<tr>
			<!--td  style=""></td-->
			{*}<th  style="border-bottom:none;">Startdatum</th>{*}
		  <th  style=""></th>
			<td class="mmRawText" style="">
				<input name="startdatumRadio" id="mmForetagStartdatumRadio2" type="radio" value="egetdatum"  >

				{*}<span id="foretagStartdatum">{$startdatumStr}</span>{*}
				<select name="startdatum" id="mmForetagStartdatum" onchange="checkforetagstartdatum()">
					<!-- option value="{$datumalt}">Välj...</option -->
				{foreach from=$datumalternativ item=datumalt key=code name=datumloop}
					<option value="{$datumalt}">{$datumstralt.$code}</option>
				{/foreach}
				</select>		
			</td>		
		</tr>


    {*}
		<tr>
			<td></td>
			<td>
				<input name="startdatumRadio" id="mmForetagStartdatumRadio3" type="radio" value="2010-09-27" disabled>
				<span style="text-decoration: line-through;">Den stora hösttävlingen 27 september</span>
				<br/>
				<span style="color: #f00; font-weight:bold;">
				   Anmälan till hösttävlingen är nu stängd. Det är dock möjligt att välja annat startdatum. Välkomna!
				</span>
			</td>
		</tr>
    {*}

    {*} exta camapign text{*}
    {*}
		<tr>
      <td colspan="3" style="border-bottom:none;">
        <span style="color:red;font-size:18px;">EXTRA! Beställ senast 11/3 och få 20% rabatt!</span>
      </td>
		</tr>
    {*}


		<tr>
			<th style="border-bottom:none;">Antal deltagare</th>
			<td>
				<table border="0" cellpadding="0" cellspacing="0" class="mmKontotypTable">			
					{foreach from=$campaignCodes item=details key=code name=codeLoop}
					<tr style="border-bottom:none;">

            {*}    removed for the 20% campaign - krillo
						<th{if $smarty.foreach.codeLoop.iteration eq count($campaignCodes)} class="mmBorderNone"{/if} style="border-bottom:none;">						    
						    <input type="hidden" name="$camparray[{$smarty.foreach.codeLoop.index}][kampanjkod]" value="{$code}" />						    
			          <input type="text" name="$camparray[{$smarty.foreach.codeLoop.index}][antal]" size="4" maxlength="4" onblur="mm_krillo_foretag_uppdateraPriser(this.value, '{$code}');" />			          
						    {$details.text} ({$details.pris}:-) 
						    {if isset($details.popupid)}
							    &nbsp;<a href="javascript:;" onclick="mm_rapportera_show_help({$details.popupid},{$details.popupwidth},{$details.popupheight},'topleft')">L&auml;s mer</a>
						    {/if}
						</th>
            {*}

						<th{if $smarty.foreach.codeLoop.iteration eq count($campaignCodes)} class="mmBorderNone"{/if} style="border-bottom:none;">
                {*}<input type="radio" name="kontotyp" value="{$code}" / > {*}
						    <input type="hidden" name="$camparray[{$smarty.foreach.codeLoop.index}][kampanjkod]" value="{$code}" />
			          <input type="text" name="$camparray[{$smarty.foreach.codeLoop.index}][antal]" size="4" maxlength="4" onblur="mm_krillo_foretag_uppdateraPriser(this.value, '{$code}');" />
						    {$details.text} <span style="color:red;">{$details.pris}kr</span> {$details.extra}
						</th>

						
						{*}popupwidth,popupheight samt id hämtas från order.php classen{*}
						<td{if $smarty.foreach.codeLoop.iteration eq count($campaignCodes)} class="mmBorderNone"{/if} style="border-bottom:none;padding-top:12px;">
							<span id="mmForetagVisaPris{$code}">0</span>&nbsp;kr&nbsp;ex.&nbsp;moms
							<div id="mmForetagKampanjPris{$code}" class="mmDisplayNone">{$details.pris}</div>
							<div id="antal{$code}" class="mmDisplayNone">-1</div>
						</td>
					</tr>
					{/foreach}
					<tr >
					<td style="border-bottom:none;"></td>
					 <td style="border-bottom:none;"><span id="mmTotPrice">0</span>&nbsp;kr&nbsp;ex.&nbsp;moms</td>
					
						{*}<td{if $smarty.foreach.codeLoop.iteration eq count($campaignCodes)} class="mmBorderNone"{/if} style="border-bottom:none;">
							<span id="mmForetagVisaPris{$code}">{$details.pris}</span>&nbsp;kr&nbsp;ex.&nbsp;moms
							<div id="mmForetagKampanjPris{$code}" class="mmDisplayNone">{$details.pris}</div>
						</td>{*}
					</tr>					
				</table>
			</td>
		</tr>
		<!-- tr>
			<th>Kampanjkod (om du har någon)</th>
			<td -->
			<input type="hidden" name="compaffcode" value="{$compAffCode}"/>
		<!-- /td>
		</tr-->				
		<tr>
			<th>Hur hörde du talas om Motiomera?</th>			
			<td>			
			<select name="kanal" id="kanal">
				<option value="">Välj...</option>			
				<option value="email">Email</option>
				<option value="telefon">Telefon</option>
				<option value="direktreklam">Direktreklam</option>
        <option value="kontorspost">Kontorspost</option>
 				<option value="tidningsannons">Tidningsannons</option>
				<option value="tidningskupong">Reklamblad i tidning</option>					
				<option value="banner">Bannerannons på internet</option>
				<option value="bannerinyhetsbrev">Bannerannons i nyhetsbrev</option>
				<option value="sokmotor">Sökmotor på internet</option>
				<option value="fax">Faxannons</option>				
				<option value="tipsbekant">Tips från en bekant</option>
				<option value="event">Mässa eller event</option>						
				<option value="tidigarekund">Kund sedan tidigare</option>				
				<option value="annat">Annat sätt</option>			
			</select>
		</tr>
		<tr>
			<td></td>
			<td>
			<input type="checkbox" id="integritetspolicy" value="1" name="villkar" /> Ja, jag godkänner <a href="/pages/integritetspolicy.php" target="_blank">Motiomeras integritetspolicy</a> och är över 16 år</td>
		</tr>
		<tr class="mmLastRow">
			<td></td>
			<td>
				<input type="submit" value="Fortsätt" />
			</td>
		</tr>
	</table>
</form>    
<br>
<div id="pers-service">
  <div id="pers-img" style="float:left;">
    <img src="/img/kristian_80x90.png" alt="Kristian"/>
  </div>
  <div id="pers-text" style="float:left;padding-left:15px;width:330px;padding-top:5px">
    <span  class="mmObs mmObsText">För beställning per telefon ring Kristian på
           0761-393855
      <!--  042-17 37 91 -->
      <!-- 0705-17 35 95 -->
      <!-- eller -->
      <!-- 042-444 30 25 -->
      <!-- 042-17 36 21 -->
      {*}</br><a href="mailto:Marina.Kulevska@aller.se" style="text-decoration:underline;">Marina.Kulevska@aller.se</a>{*}
{*}
<br/>
<br/>eller maila 
<br/><a href="#" onclick="motiomera_kontakt(); return false;" style="text-decoration:underline;">Klicka här för att maila</a>
{*}

    </span>
  </div>
</div>
