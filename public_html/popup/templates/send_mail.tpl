<h1>Skicka ett meddelande</h1>
				<table id="the_content">
					<tr>
						<td>
							<b>Till:</b>
						</td>
					</tr>
					<tr>
						<td>
							{$medlem_to_send->getANamn()}
						</td>
					</tr>
					<tr>
						<td>
							<b>Ämne:</b>
						</td>
					</tr>
					<tr>
						<td>
							<input 
								class="mailAmne" 
								maxlength="80" 
								type="text" 
								name="amne" 
								id="amne" 
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
							<textarea class="mailTextarea" name="msg" id="msg">{$text_message}</textarea>
							{else}
							<textarea class="mailTextarea" name="msg" id="msg"></textarea>
							{/if}
						</td>
					</tr>

					<tr>
						<td>
							<input type="button" value="Skicka" onclick="motiomera_mail_send_action()" />
							<input type="hidden" value="{$mid}" name="mid" id="mid" />
						</td>
					</tr>
				</table>
				<table id="the_content_sent" class="hide">
					<tr>
						<td>
							Meddelandet har skickats till {$medlem_to_send->getANamn()}
						</td>
					</tr>
				</table>
