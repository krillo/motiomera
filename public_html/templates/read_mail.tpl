<script type="text/javascript">
	{literal}
		onload = function(){ 
			dhtmlLoadScript('/js/read_mail.js');
		}
	{/literal}
</script>
<table cellpadding=0 cellspacing=0>
	<tr>
		<td class="mmWidthSjuNollPixlar">
			{if $re == '1'}
			<input type="button" value="Svara" onclick="location.href='/pages/mail.php?do=send&mid={$mail_to_read->getSentFrom()}&re={$id}'" />
			{/if}
		</td>
		<td class="mmWidthSjuNollPixlar">
			<form method="POST" action="/actions/delete_mail.php" id="remove_form">
				<input type="hidden" name="id_to_remove" value="{$id}" />
				<input 
					type="button" 
					value="Ta bort" 
					onclick="removeMail();"
					/>
			</form>
		</td>
		<td class="mmWidthNioNollPixlar">
			{if $re == '1'}
			<select>
				<option>
					Flytta till 
				</option>
			</select>
			{/if}
		</td>
	</tr>
</table>

<br />
<table cellpadding=0 cellspacing=3>
	<tr>
		<td class="mmWidthFemNollPixlar">
			<b>Fr&aring;n:</b>
		</td>
		<td>
			{$mail_to_read->getFromName($id)}
		</td>
	</tr>
	<tr>
		<td class="mmWidthFemNollPixlar">
			<b>&Auml;mne:</b>
		</td>
		<td>
			{$mail_to_read->getSubject()}
		</td>
	</tr>
	<tr>
		<td class="mmWidthFemNollPixlar" colspan=2=>
			<b>Meddelande:</b>
		</td>
	</tr>
	<tr>
		<td colspan=2=>
			{$mail_to_read->getMsg()}
		</td>
	</tr>
</table>