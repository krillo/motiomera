<form action="/pages/testmail.php" method="POST">
	{if $notify}
		<h3>{$notify}</h3>
	{/if}
	Email att skicka till:<br />
	<input type="text" name="email" value="" id="email">
	<p>
		Tillgängliga mail:<br />
		<select name="message"> 
			{foreach from=$messages item=message}
				<option value="{$message.title}">{$message.message|truncate:110}</option>
			{/foreach}
		</select>
	</p>
	<p><input type="submit" value="Skicka iväg mailet"></p>
</form>