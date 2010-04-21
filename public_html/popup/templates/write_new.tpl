<h1>Skicka ett meddelande</h1>
<table id="new_div">
	<tr>
	<td>
	<table width="100%">
	<tr>
		<td width="70%"><b>Till:</b></td>
		<td>{if !empty($my_contacts)}<b>Vänner:</b>{/if}</td>
	</tr>
	<tr>
		<td><input id="new_to" type="text" onchange="verify_member_by_string(this.value)"><br>
		<div id="validMember_ERR" class="mmRed hide">Ogiltig medlem</div><div id="validMember_OK" class="mmGreen hide">Giltig medlem</div>
		</td>
		<td>
			{if !empty($my_contacts)}
				<select id="friends" onchange="value2field(this.value, 'new_to', 'Välj');verify_member_by_string(this.value)">
				<option>Välj</option>
				{foreach from=$my_contacts key=k item=v}
				<option>{$v}</option>
				{/foreach}
				</select><br>
			{/if}
		</td>
	</tr>
	</table>
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
				class="amneInput" 
				maxlength="80" 
				type="text" 
				name="new_amne" 
				id="new_amne" 
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
			<textarea class="mailTextarea" name="new_msg" id="new_msg"></textarea>
		</td>
	</tr>

	<tr>
		<td>
			<input type="button" id='mailSubmitButton' value="Skicka" onclick="send_new_mail_freestr()" disabled/>
		</td>
	</tr>
</table>
<div id="new_div_none" class="hide">
	<div>
	Meddelandet har skickats
	</div>
</div>



			{*}	
				
				{if $my_contacts|@count > 0}
				<div id="new_div_none" class="hide">
					<div>
						Meddelandet har skickats
					</div>
				</div>
				
				<div id="new_div" class="block">
					<div  class="mmFloatLeft mmWidthSexNollPixlar">
						Till:
					</div>
					<div  class="mmFloatLeft mmWidth160">
						<select id="new_to">
							<option value="0">
								V&auml;lj
							</option>
							{foreach from=$my_contacts key=k item=v}
							<option value="{$k}">
								{$v}
							</option>
							{/foreach}
						</select>
					</div>
					<div class="mmClearBoth"></div>

					<div  class="mmFloatLeft mmWidthSexNollPixlar">
						<b>&Auml;mne:</b>
					</div>
					<div  class="mmFloatLeft" style="">
						<input type="text" id="new_amne" />
					</div>
					<div class="mmClearBoth"></div>

					<div>
						<b>Meddelande:</b>
					</div>
					<div>
						<textarea id="new_msg" clas="nyttMeddelande" style=""></textarea>
					</div>
					<div>
						<input type="button" value="Skicka" onclick="send_new_mail()" />
					</div>
				</div>	
				{/if}

				{*}
