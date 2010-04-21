{if isset($ADMIN)}
<h1>Välkommen till admin, {$ADMIN->getANamn()}!</h1>
{else}

	<h1>Logga in</h1>

	<form action="/admin/actions/login.php" method="post">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="padding-right: 10px;">Användarnamn:</td>
				<td>
					<input type="text" name="username" size="28" />
				</td>
			</tr>
			<tr>
				<td>Lösenord:</td>
				<td>
					<input type="password" name="password" size="28" />
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
				<td>
					<input type="checkbox" name="autologin" value="on" /> Kom ihåg mig
					<input type="submit" value="Logga in" class="mmFloatRight" />
					<input type="hidden" name="login" value="Login"/>
				</td>
				
			</tr>
		</table>
	
	</form>


{/if}