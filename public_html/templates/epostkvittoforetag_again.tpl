Hej och tack för att ni valt att förnya er tävling på MotioMera! 
Er tävling startar {$startDatum} och du kan fortsätta att logga in på kontot för tävlingsansvarig som vanligt.
http://motiomera.se/pages/foretaglogin.php


Kvitto

Köpet genomfört: {$orderList.date|date_format:"%Y-%m-%d %H:%m"}
Orderid: {$orderList.orderId}
Betalsätt: {$orderList.payment}
Totalsumma: {$orderList.sum} kr inkl. moms


Artiklar

{section name=orderItem loop=$orderItemList}
{strip}
{$orderItemList[orderItem].quantity} st. {$orderItemList[orderItem].item} {$orderItemList[orderItem].price} kr inkl. moms 
{/strip}
{/section}


Betalare

Företag: {$orderList.companyName}
Namn: {$orderList.payerName}
Ort: {$orderList.payerZipCode} {$orderList.payerCity}
Adress: {$orderList.payerAddress}
Land: {$orderList.payerCountry}
E-postadress: {$orderList.payerEmail}
Telefonnummer: {$orderList.payerPhone}
Mobilnummer: {$orderList.payerMobile}


Leverans

Företag: {$orderList.reciveCompanyName}
Namn: {$orderList.reciverName}
Ort: {$orderList.reciverZipCode}  {$orderList.reciverCity}  
Adress: {$orderList.reciverAddress}
Land: {$orderList.reciverCountry}
E-postadress: {$orderList.reciverEmail}
Telefonnummer: {$orderList.reciverPhone}
Mobilnummer: {$orderList.reciverMobile}


Tack för din beställning!
