<div id="mmApply">
	<h1>Ansök om medlemsskap</h1>
	<form action="/actions/joingroup.php" method="post">
		Eget meddelande:
		<input type="hidden" name="gid" value="{$gid}" />
		<textarea name="ownMsg" style="width: 411px; height: 114px;"></textarea><br />
		<input type="submit" value="Ansök" name="submit" />
	</form>
</div>
