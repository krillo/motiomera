Hej och tack för din anmälan till MotioMera! 

Nedan ser du ditt kvitto. Er tävling startar {$orderList.startDatum} och senast 10 dagar innan start levereras de stegräknarpaket du beställt. 
Logga in på administrationssidorna för att administrera er tävling och göra tilläggsbeställningar. Paketen innehåller stegräknare (om ni beställt sådana) samt deltagarbrev med fullständiga inloggningsinstruktioner till användarna.
Användarnamn: {$orderList.foretagANamn}
Lösenord: {$orderList.foretagLosen}
http://motiomera.se/pages/foretaglogin.php?u={$orderList.foretagANamn}&p={$orderList.foretagLosen}


Kvitto

Köpet genomfört: {$orderList.date|date_format:"%Y-%m-%d %H:%m"}
Orderid: {$orderList.orderId}
Betalsätt: {$orderList.payment}
Totalsumma: {$orderList.sumMoms} kr inkl. moms


Artiklar

{section name=orderItem loop=$orderItemList}
{strip}
{$orderItemList[orderItem].quantity} st. {$orderItemList[orderItem].item} {$orderItemList[orderItem].price} kr exkl. moms 
{/strip}
{/section}
{$orderList.sum} kr exkl. moms
{$orderList.sumMoms} kr inkl. moms

Leverans

Företag: {$orderList.reciveCompanyName}
Namn: {$orderList.reciverName}
Ort: {$orderList.reciverZipCode}  {$orderList.reciverCity}  
Adress: {$orderList.reciverAddress}
Land: {$orderList.reciverCountry}
E-postadress: {$orderList.reciverEmail}
Telefonnummer: {$orderList.reciverPhone}
Mobilnummer: {$orderList.reciverMobile}



Betalare

Företag: {$orderList.payerCompanyName}
Namn: {$orderList.payerName}
Ort: {$orderList.payerZipCode} {$orderList.payerCity}
Adress: {$orderList.payerAddress}
Land: {$orderList.payerCountry}
E-postadress: {$orderList.payerEmail}
Telefonnummer: {$orderList.payerPhone}
Mobilnummer: {$orderList.payerMobile}




Tack för din beställning!
