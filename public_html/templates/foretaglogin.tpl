<h1>Logga in som tävlingsansvarig</h1>
<form action="/actions/loginforetag.php" method="post">
	<p>
		Användarnamn:<br />
		<input type="text" name="username" value="{$u}"/>
	</p>
	<p>
		Lösenord:<br />
		<input type="password" name="password" value="{$p}"/>
	</p>
	<p>
		<input type="checkbox" value="true" name="autoligon" /> Kom ihåg mig
	</p>
	<p>
		<input type="submit" value="Logga in" />
	</p>
</form>