<?php
	$messages['Öka takten, sista kvarten!'] 
	= array(
		'title' => 'Öka takten, sista kvarten!',
		'message' => 'Hej kära MotioMera-deltagare,

Helgen är nära och fortfarande finns chansen att snygga till siffrorna i stegtävlingen. Söndag är tävlingens sista dag, men under hela måndagen kan du registrera dina steg. På tisdag presenteras sen slutesultatet. Glöm alltså inte att registrera dina steg senast under måndagen!

Efter företagstävlingens slut finns möjlighet för alla deltagare att fortsätta använda MotioMera som privatperson. Helt gratis. Du kommer att få ett mail med mer information vid tävlingens slut. Det är också möjligt för ditt företag att genast starta en ny tävlingsomgång om ni vill.

Kör så det ryker! MVH - alla i MotioMera-teamet

MotioMera - Sveriges roligaste stegtävling

www.motiomera.se');

	$messages['Så här gick det i MotioMera!'] 
	= array(
		'title' => 'Så här gick det i MotioMera!',
		'message' => 'Grattis '.$USER->getFNamn().'!

Du hör nu till en av dem som har klarat av en tävlingsomgång i stegtävlingen MotioMera! Sammanlagt gick du '.$USER->getStegTotal($USER->getForetag()->getStartDatum(), $USER->getForetag()->getSlutDatum()).' steg! Du kan se hela slutresultatet genom att gå in på denna sida: http://www.motiomera.se/pages/tavlingsresultat.php?id='.$USER->getId().'

Du vet väl att du kan fortsätta vara med i MotioMera som privatperson? Du registrerar dina steg precis som i företagstävlingen och du kan också skapa klubbar och bjuda in vänner. De steg som du gått under företagstävlingen följer automatiskt med. Vi har just nu ett gratiserbjudande på 1 vecka.

Hoppas att du har tyckt att tjänsten har varit givande och rolig. Maila oss gärna på kontakt@motiomera.se och säg vad du tyckte. Ris och ros. Vi lottar ut något passande bland er som tycker till.

Tack för denna gång och hoppas vi ses snart igen på MotioMera! Hälsn - alla i MotioMera-teamet

MotioMera - Sveriges roligaste stegtävling

www.motiomera.se');
?>
