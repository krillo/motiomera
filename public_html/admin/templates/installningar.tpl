<div>
<form action="/admin/actions/save.php?table=debug&amp;id={$ADMIN->getId()}" method="post">
	* Aktivera debugl&auml;ge:&nbsp;<input type="checkbox" name="debug"{$isdebug} />
	<input type="submit" value="Spara" />
</form>
<br/>
<br/>

<form action="/admin/actions/sent_test_email.php?id={$ADMIN->getId()}" method="post">
  * Skicka testmail till:&nbsp;<input type="text" name="email" value="" />
  <input type="submit" value="Skicka" />
</form>
<br/>
<br/>

* <a href="krillo_testar_slutmail_datum.php" style="text-decoration:underline; color:blue;">skriv ut på skärmen vilka mail som skickas vid tävlingsslut</a>

</div>