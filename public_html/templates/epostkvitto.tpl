Kvitto
------
Köpet genomfört: {$order->getDate()|date_format:"%Y-%m-%d %H:%m"}
Orderid: {$order->getId()}
Betalsätt: {$order->getPayment()}
Totalsumma: {$order->getSum()} inkl. moms


Betalare
--------
Namn: {$medlem->getFNamn()} {$medlem->getENamn()}
c/o: {$medlem->getCo()}
Adress: {$medlem->getAddress()}
Adress: {$medlem->getZip()}
Ort: {$medlem->getCity()}
Land: {$medlem->getCountry()}

E-postadress: {$medlem->getEpost()}
Telefonnummer: {$medlem->getPhone()}


Tack för din beställning - Motiomeragänget

