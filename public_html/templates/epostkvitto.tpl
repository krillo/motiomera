Kvitto

Köpet genomfört: {$order->getDate()|date_format:"%Y-%m-%d %H:%m"}
Orderid: {$order->getOrderId()}
Betalsätt: {$order->getPayment()}
Totalsumma: {$order->getSum()} inkl. moms

Betalare

Namn: {$order->getPayerName()}
Ort: {$order->getPayerCity()}
Adress: {$order->getPayerAddress()}
Land: {$order->getPayerCountry()}
c/o: {$order->getPayerCo()}
E-postadress: {$order->getPayerEmail()}
Telefonnummer: {$order->getPayerPhone()}
Mobilnummer: {$order->getPayerMobile()}

Tack för din beställning!
