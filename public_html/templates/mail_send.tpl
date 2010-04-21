				<table>
					<tr>
						<td>
							<u>Skicka ett meddelande till {$medlem_to_send->getANamn()}</u>
						</td>
					</tr>

					<tr>
						<td>
							<br />
						</td>
					</tr>
					
					<form action="/actions/sendinternmail.php" method="post" onsubmit="motiomera_validateSkapaMail(this); return false;">
					<input type="hidden" name="mid" value="{$mid}" />
					<tr>
						<td>
							<b>&Auml;mne:</b>
						</td>
					</tr>
					<tr>
						<td>
							<input 
								id="mmMailAmnesInput" 
								maxlength="80" 
								type="text" 
								name="amne" 
								{if $is_replay == '1'}
								value="RE: {$mail_to_read->getSubject()}"
								{/if}
								/>
						</td>
					</tr>

					
					<tr>
						<td>
							<b>Meddelande:</b>
						</td>
					</tr>
					<tr>
						<td>
							{if $is_replay == '1'}
							<textarea class="mmMailMeddelandeText" name="msg">{$text_message}</textarea>
							{else}
							<textarea class="mmMailMeddelandeText" name="msg"></textarea>
							{/if}
						</td>
					</tr>

					<tr>
						<td>
							<input type="submit" value="Skicka" />
						</td>
					</tr>
					</form>
				</table>