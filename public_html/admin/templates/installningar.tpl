<div>
<form action="/admin/actions/save.php?table=debug&amp;id={$ADMIN->getId()}" method="post">
	<h3> Aktivera debugl&auml;ge</h3>
	<input type="checkbox" name="debug"{$isdebug} />
	<input type="submit" value="Spara" />
</form>
<br/>
<br/>
<br/>

<form action="/admin/actions/sent_test_email.php?id={$ADMIN->getId()}" method="post">
  <h3>Skicka testmail till:</h3>
  <input type="text" name="email" value="" />
  <input type="submit" value="Skicka" />
</form>
<br/>
<br/>
<br/>

<h3><a href="krillo_testar_slutmail_datum.php" style="text-decoration:underline; color:blue;">skriv ut på skärmen vilka mail som skickas vid tävlingsslut</a></h3>
<br/>
<br/>
<br/>


<form action="/admin/actions/manuell_medalj.php?id={$ADMIN->getId()}" method="get">
  <h3>Batcha ut medaljer till alla melemmar mellan specade veckor</h3>


  <p style="text-decoration: line-through;">Detta görs normalt av cronscript varje vecka, men om något har gått fel kan man göra detta retroaktivt. Kör denna bara en vecka åt gången och då ingen är inne på sajten för denna är så tung att den hänger sajten</p>
  År: <input type="text" name="year" value="" disabled /><br/>
  från och med, startvecka: <input type="text" name="weekstart" value="" disabled /><br/>
  till och med, slutvecka: <input type="text" name="weekstop" value="" disabled /><br/>
  <input type="submit" value="Skapa medaljer" disabled />
</form>
<p>Denna php timar ut så detta måste köras från command line på servern, en vecka i taget. Såhär: <br/>
cd /var/www/motiomera/current/bin <br/>
php medal_from_cmd.php 2010 24
</p>
<br/>
<br/>
<br/>


<form action="/admin/actions/manuell_pokal.php?id={$ADMIN->getId()}" method="get">
  <h3>Dela ut en pokal till en användare</h3>
  <p>Normalt gås alla medlemmar igenom varje dag av cronscript för att se ifall någon har förtjänat en ny pokal, men här kan man göra det manuellt med dagens datum. Man kan bara ha en pokal per samma datum</p>
  användar-id: <input type="text" name="memberid" value="" /><br/>
  silver: <input type="radio" name="pokal" value="silver" /><br/>
  guld: <input type="radio" name="pokal" value="guld" /><br/>    
  <input type="submit" value="Skapa pokal" />
</form>
<br/>
<br/>
<br/>



<h3>Hantera företagsfiler manuellt</h3>
<p>Normalt hanteras detta varje natt av cronscript. Kör dem i tur och ordning och titta i logfilen att det har gått ok</p>
<form action="/admin/actions/manuell_foretagsfiler.php?id={$ADMIN->getId()}" method="get">
  <input type="hidden" name="action" value="kundnummer" />
  <input type="submit" value="Hämta kundnummer från AS400" />
</form>
<form action="/admin/actions/manuell_foretagsfiler.php?id={$ADMIN->getId()}" method="get">
  <input type="hidden" name="action" value="pdf" />
  <input type="submit" value="Skapa pdf:er" />
</form>
<form action="/admin/actions/manuell_foretagsfiler.php?id={$ADMIN->getId()}" method="get">
  <input type="hidden" name="action" value="ftp" />
  <input type="submit" value="Lägg pdf:er på Postpacs ftp" />
</form>
<br/>
<br/>
<br/>



<h3>Stäng en tävling manuellt</h3>
<p>Normalt hanteras detta varje tisdag natt av cronscript (cron_foretagstavling_slut2.php). Detta är steg 2 i hanteringen av att avsluta en tävling.
   Det är detta steg som tävlingsresultatet räknas ut och sparas i egen tabell i db, dvs utan detta steg finns det inget resultat av tävlingen.
   För att stänga en tävling var vänlig och mata in tidagens datum efter tävlingsslut med formatet 20110817</p>
<form action="/admin/actions/manuell_foretagstavling_slut2.php" method="get">
  datum: <input type="text" name="date" value="{$smarty.now|date_format:"%Y%m%d"}" /><br/>
  <input type="submit" value="Avsluta tävling" />
</form>
<br/>
<br/>
<br/>



</div>