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
  <p>Detta görs normalt av cronscript varje vecka, men om något har gått fel kan man göra detta retroaktivt. Kör denna bara en vecka åt gången och då ingen är inne på sajten för denna är så tung att den hänger sajten</p>  
  År: <input type="text" name="year" value="" /><br/>
  från och med, startvecka: <input type="text" name="weekstart" value="" /><br/>
  till och med, slutvecka: <input type="text" name="weekstop" value="" /><br/>
  <input type="submit" value="Skapa medaljer" />
</form>
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

</div>