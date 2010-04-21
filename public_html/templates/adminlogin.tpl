<h1>Logga in som admin</h1>
<form action="/admin/actions/login.php" method="post">
	<p>
		Användarnamn:<br />
		<input type="text" name="username" />
	</p>
	<p>
		Lösenord:<br />
		<input type="password" name="password" />
	</p>
	<p>
		<input type="checkbox" value="true" name="autoligon" /> Kom ihåg mig
	</p>
	<p>
		<input type="submit" value="Logga in" />
	</p>
</form>