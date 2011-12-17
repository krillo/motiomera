<div style="float:left;" ><h1>Bli medlem</h1></div>
<div style="float:right;width:100px;">&nbsp;</div>
<div style="float:right;"><a href="/pages/skapaforetag.php" class="mmObs mmObsBiggerText">Anmäl ditt företag</a></div>

<div style="float:left; clear:both;">
<form action="/actions/newuser.php" method="post" onsubmit="motiomera_validateSkapaMedlemForm(this); return false;">
	{if isset($inv)}
	<input type="hidden" name="inv" value="{$_GET.inv}" />
	{/if}
	<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table">
		<tr>
			<th>Välj ett alias</th>
			<td>
				<input type="text" name="anamn" onfocus="getById('mmANamnError').style.display = 'none';" onblur="mm_ajaxValidera('mmANamnError', 'anamn', this.value);" />
				<span id="mmANamnError" class="mmRed mmFormError">Upptaget</span>
			</td>
		</tr>
		<tr>
			<th>Förnamn</th>
			<td><input type="text" name="fnamn" /></td>
		</tr>
		<tr>
			<th>Efternamn</th>
			<td><input type="text" name="enamn" /></td>
		</tr>
		<tr>
			<th>Kön</th>
			<td>
				<select name="kon">
					<option value="kvinna">Kvinna</option>
					<option value="man">Man</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Kommun</th>
			<td>{mm_html_options name=kid options=$opt_kommuner}</td>
		</tr>
		<tr>
			<th>E-postadress <em>Anges 2 gånger</em></th>
			{if isset($inv)}
			<td class="mmRawText">
				<input type="hidden" name="epost" value="{$inv.epost}" />
				{$inv.epost}
			</td>
			{else}
			<td>
				<input type="text" name="epost" id="mailone" onfocus="getById('mmEpostError').style.display = 'none';" onblur="mm_ajaxValidera('mmEpostError', 'epost', this.value);"{if isset($inv)} value="{$inv.epost}"{/if} />
				<span id="mmEpostError" class="mmRed mmFormError">Upptagen</span><br /><input type="text" name="epostcheck" id="mailtwo" onkeyup="mmCompareEmail(this.value);" /> 
<span id="emailcompare"></span>
			</td>
			{/if}
		</tr>
		<tr>
			<th>Välj lösenord</th>
			<td><input type="password" name="losenord" id="losenord" /></td>
		</tr>
		<tr>
			<th>Upprepa</th>
			<td><input type="password" name="losenord2" id="losenord2" /></td>
		</tr>
{*}		<tr>
			<th>Företagsnyckel</th>
			<td>
				<input type="text" name="foretagsnyckel" onfocus="getById('mmForetagsnyckelError').style.display = 'none';" onblur="if(this.value != '') mm_ajaxValidera('mmForetagsnyckelError', 'foretagsnyckel', this.value);" />
				<span id="mmForetagsnyckelError" class="mmRed mmFormError">Ej giltig</span>
			</td>
		</tr>
{*}		
		<!-- tr>
			<th>Kampanjkod (om du har någon)</th>
			<td -->
			<input type="hidden" name="maffcode" value="{$maffcode}"/>
		<!-- /td>
		</tr-->
		
		<tr>
			<th>Medlemsskap</th>
			<td>
				<table border="0" cellpadding="0" cellspacing="0" class="mmKontotypTable">
					<tr>
						<th class="mmBorderNone">
							<input type="radio" onclick="getById('mmForetagsnyckel').style.display='inline';" name="kontotyp" value="foretagsnyckel"   /> <span style="font-weight:bold;"> Jag har en företagsnyckel&nbsp;</span> 
							<a href="javascript:;" onclick="mm_rapportera_show_help(24,{$firstwidth},{$firstheight},'topleft')">L&auml;s mer</a>
							<input onfocus="getById('mmFNyckelError').style.display = 'none';" onblur="mm_ajaxValidera('mmForetagsnyckelError', 'foretagsnyckel', this.value);" id="mmForetagsnyckel" type="text" name="foretagsnyckel" size="25" /><span class="mmFormError mmRed" id="mmForetagsnyckelError"><br />Ogiltig företagsnyckel</span>
						</th>
						<td class="mmBorderNone"></td>
					</tr>


          {*} Kampanjkod added by krillo 2011-01-11 {*}
					<tr>
						<th class="mmBorderNone">
							<input type="radio" name="kontotyp" value="kampanjkod" onclick="getById('mmKampanjkod').style.display='inline';" /> <span style="font-weight:bold;"> Jag har en kampanjkod&nbsp;</span>
							<input type="text" id="mmKampanjkod"  name="kampanjkod" size="25" onfocus="getById('mmKampanjkodError').style.display = 'none';" onblur="mm_ajaxValidera('mmKampanjkodError', 'kampanjkod', this.value);"  />
              <span class="mmFormError mmRed" id="mmKampanjkodError"><br />Ogiltig kampanjkod</span>
						</th>
						<td class="mmBorderNone"></td>
					</tr>
					<!-- tr>
						<th><input type="radio" selected="selected" name="kontotyp" value="trial" /> 3 månader MotioMera&nbsp;<a href="javascript:;" onclick="mm_rapportera_show_help(13,{$firstwidth},{$firstheight},'topleft')">L&auml;s mer</a></th>
						<td><span class="mmGreen">0 kr</span></td>
					</tr -->



					{foreach from=$campaignCodes item=details key=code name=codeLoop}
            {if $details.public == "TRUE"} {* only show public campaigns *}
              <tr>
                <th><input type="radio" name="kontotyp" value="{$code}" /> {$details.text}
                {if isset($details.popupid)}
                  &nbsp;<a href="javascript:;" onclick="mm_rapportera_show_help({$details.popupid},{$details.popupwidth},{$details.popupheight},'topleft')">L&auml;s mer</a>
                {/if}</th>

                {*}popupwidth,popupheight samt id hämtas från order.php classen{*}
                <td>{$details.pris} kr</td>
              </tr>
            {/if}
					{/foreach}

				</table>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
			<input type="checkbox" id="integritetspolicy" value="1" /> Ja, jag godkänner <a href="http://www.integritetspolicy.se/" target="_blank">Motiomeras integritetspolicy</a> och är över 16 år</td>
		</tr>
		<tr class="mmLastRow">
			<td></td>
			<td>
				<input type="submit" value="Gå vidare" />
			</td>
		</tr>
	</table>
</form>
</div>