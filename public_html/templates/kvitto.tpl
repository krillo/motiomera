{if $orderList.pro_order}
  <h1>Välkommen till MotioMera!</h1>
{elseif $orderList.typ == "foretag"}
  <h1>Välkommen till MotioMera!</h1>{/if}
{*}<p>Ett kvitto har skickats till <b>{$order->getPayerEmail()}</b></p>{*}

{if $orderList.typ == "foretag"}
Hej och tack för din anmälan till MotioMera! Nedan ser du ditt kvitto. Er tävling startar {$orderList.startDatum} och ca 10 dagar innan start levereras de stegräknarpaket du beställt. Paketen innehåller stegräknare (om ni beställt sådana) samt deltagarbrev med fullständiga inloggningsinstruktioner till användarna. Både ovanstående info och kvittot kommer att skickas till dig per e-post.

<h2>Lagindelning och tilläggsbeställning</h2>
Som tävlingsansvarig har du tillgång till din egen administrationssida, http://motiomera.se/pages/foretaglogin.php. På denna sida kan du göra din egen lagindelning samt göra tilläggsbeställningar. Se dina inloggningsuppgifter nedan.

{elseif $orderList.typ == "foretag_again"}
Hej och tack för att ni valt att förnya er tävling här på MotioMera! Nedan ser du ditt kvitto. Er tävling startar {$orderList.startDatum} och du kan fortsätta att logga in på kontot för tävlingsansvarig som vanligt. Kvittot nedan kommer även att skickas till dig per e-post.

{elseif $orderList.typ == "foretag_tillagg"}
Hej och tack för din tilläggsbeställning! Nedan ser du ditt kvitto. Er tävling startar {$orderList.startDatum} och vi skickar era stegräknarpaket snarast. Både nedanstående info och kvittot kommer att skickas till dig per e-post.

{else}
  {if $orderList.pro_order}
    Hoppas du får en fortsatt rolig tid hos Motiomera!
  {else}
   <p>Grattis, du är nu medlem i MotioMera! Men innan du kan köra igång måste du aktivera ditt konto. <br />Det är enkelt, så här gör du:</p><p>Vi har nu skickat ett mail till den epostadress du uppgav vid beställningen. När du klickar på länken som finns i mailet så aktiveras ditt MotioMera-konto. Proceduren är en säkerhetsåtgärd som vi använder för att ingen ska registrera ett konto i ditt namn.</p><p>Hoppas du får en rolig tid hos Motiomera!{/if}<br />Med vänlig hälsning</p><p><b>MåBra</b><br />- specialtidningen för kropp &amp; själ</p>
{/if}

<table border="0" cellpadding="0" cellspacing="0" id="mmKvittoTable">
	<tr>
		<td class="mmWidthTvaHundraPixlar"></td><td></td><td></td>
	</tr>
	
	{if $orderList.typ == "foretag"} 
	<tr>
		<td colspan="3"><h2>Inloggningsuppgifter</h2></td>
	</tr>
	<tr>
		<td colspan="3">
			<b>Användarnamn:</b> {$orderList.foretagANamn}<br />
			<b>Lösenord:</b> {$orderList.foretagLosen}
			<p>
				<a href="/pages/foretaglogin.php?u={$orderList.foretagANamn}&p={$orderList.foretagLosen}" class="noprint">Klicka här för att komma till administrationssidan för er tävling</a> 
			</p>
		</td>
	</tr>
	{/if}

  {if $orderList.typ == "foretag_tillagg"}
  <tr> 
    <td colspan="3">
      <p>
        <a href="/pages/editforetag.php?fid={$orderList.foretagsId}" class="noprint">Tillbaks till administrationssidan</a>
      </p>
    </td>
  </tr>
  {/if}


	{if $orderList.typ == "foretag" || $orderList.typ == "foretag_again" || $orderList.typ == "foretag_tillagg"}
	<tr>
		<td colspan="3"><h2>Kvitto</h2></td>
	</tr>
	<tr>
		<td><b>Köpet genomfört:</b> {$orderList.date|date_format:"%Y-%m-%d %H:%m"}</td>
    <td colspan="2"><b>Betalsätt:</b><span style="text-transform: capitalize;"> {$orderList.payment}</span></td>		
	</tr>	
	<tr>
		<td><b>Orderid:</b> {$orderList.orderId}</td>
		<td colspan="2"><b>Totalsumma:</b> {$orderList.sumMoms} kr inkl. moms</td>
	</tr>	
	<tr>
		<td colspan="3" class="mmRowHeading"><h3>Info</h3></td>
	</tr>
	<tr>
		<td><b>Startdatum:</b> {$orderList.startDatum}</td>
	</tr>
	<tr>
		<td><b>Er referenskod:</b> {$orderList.orderRefCode}</td>
	</tr>
  
	<tr>
		<td colspan="3" class="mmRowHeading"><h3>Artiklar</h3></td>
	</tr>
	{section name=orderItem loop=$orderItemList}
	{strip}
		<tr>
			<td colspan="2" width="300px">{$orderItemList[orderItem].antal} st. {$orderItemList[orderItem].item}</td>
			<td>&nbsp; &nbsp; {$orderItemList[orderItem].price}  kr exkl. moms</td>		
		</tr>	
	{/strip}
	{/section}
	<tr>
		<td colspan="3" class="mmRowHeading"><h3>Betalare</h3></td>
	</tr>
	<tr>
		<td><b>Företag:</b> {$orderList.companyName}</td>
		<td colspan="2"><b>Ort:</b> {$orderList.payerZipCode}  {$orderList.payerCity}</td>
	</tr>
	<tr>
		<td><b>Namn:</b> {$orderList.payerName}</td>	
		<td colspan="2"><b>Land:</b> {$orderList.payerCountry}</td>
	</tr>
	<tr>
		<td><b>Adress:</b> {$orderList.payerAddress}</td>
		<td colspan="2"><b>E-postadress:</b> {$orderList.payerEmail}</td>
	</tr>
	<tr>
		<td><b>c/o:</b> {$orderList.payerCo}</td>	
		<td colspan="2"><b>Telefon:</b> {$orderList.payerPhone}</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="2"><b>Mobil:</b> {$orderList.payerMobile} </td>
	</tr>
	
	
	<tr>
		<td colspan="3" class="mmRowHeading"><h3>Leverans</h3></td>
	</tr>
	<tr>
		<td><b>Företag:</b> {$orderList.reciverCompanyName}</td>
		<td colspan="2"><b>Ort:</b> {$orderList.reciverZipCode}  {$orderList.reciverCity}</td>
	</tr>
	<tr>
		<td><b>Namn:</b> {$orderList.reciverName}</td>	
		<td colspan="2"><b>Land:</b> {$orderList.reciverCountry}</td>
	</tr>
	<tr>
		<td><b>Adress:</b> {$orderList.reciverAddress}</td>	
		<td colspan="2"><b>E-postadress:</b> {$orderList.reciverEmail}</td>
	</tr>
	<tr>
		<td><b>c/o:</b> {$orderList.reciverCo}</td>	
		<td colspan="2"><b>Telefon:</b> {$orderList.reciverPhone}</td>		
	</tr>
	<tr>
		<td></td>		
		<td colspan="2"><b>Mobil:</b> {$orderList.reciverMobile} </td>		
	</tr>


	{/if}
</table>


<p>

