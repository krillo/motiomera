<div id="reading_div">
<h1>Läs meddelande</h1>
<table>
	<tr>
		<td class="mmVerticalAlignBottom">
			{if $is_inbox == '1'}
			<strong>Fr&aring;n:</strong>
			<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $mail_to_read->getSentFrom())}">{$mail_to_read->getFromName($id)}</a>
			{else}
			<strong>Till:</strong>
			<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $mail_to_read->getSendTo())}">{$mail_to_read->getToName($mail_to_read->getId())}</a>
			{/if}
			<br/><br/>
			<b>&Auml;mne:</b> {$mail_to_read->getSubject()}

		</td>
		<td class="mmTextAlignRight">
			{if $USER->inAdressbok($medlem)}
			<img src="/img/icons/AdressbokIcon.gif" alt="" class="mmMarginLeft10" /> <a href="{$urlHandler->getUrl(Adressbok, URL_DELETE, $medlem->getId())}" title="Ta bort som vän">Ta bort som vän</a>
			{elseif $replyable}
			<img src="/img/icons/AdressbokIcon.gif" alt="" class="mmMarginLeft10" /> <a href="{$urlHandler->getUrl(Adressbok, URL_SAVE, $medlem->getId())}" title="Lägg till som vän">Lägg till som vän</a>
			{/if}
			{if $blockerad eq 1}
			<img src="/img/icons/AdressbokIcon.gif" alt="" class="mmMarginLeft20" /> Avsändaren spärrad
			{elseif $replyable && !$USER->inAdressbok($medlem)}
			<img src="/img/icons/AdressbokIcon.gif" class="mmMarginLeft20"> <a href="{$urlHandler->getUrl(Medlem, URL_BLOCK_MEMBER, $from_id)}" title="Spärra" class="class="mmMarginRight20">Spärra</a>
			{/if}
			<br>
			{if $is_inbox == '1' && $replyable}
			<a href="javascript:;" onclick="answer_mail()"><img src="/img/icons/MailSkickadeIcon.gif"></a> <a href="javascript:;" onclick="answer_mail()">Svara</a>
			{else}
				Går ej svara på
			{/if}
			{if $is_inbox == '1'}
			<a href="javascript:;" onclick="remove_one_mail('{$id}', '{$my_id}', 'from_deleted')"><img src="/img/icons/MailDeleteIcon.gif" class="mmMarginLeft20" /></a> <a href="javascript:;" onclick="remove_one_mail('{$id}', '{$my_id}', 'to_deleted')">Ta bort</a>
			{else}
			<a href="javascript:;" onclick="remove_one_mail('{$id}', '{$my_id}', 'from_deleted')"><img src="/img/icons/MailDeleteIcon.gif" class="mmMarginLeft20" /></a> <a href="javascript:;" onclick="remove_one_mail('{$id}', '{$my_id}', 'from_deleted')">Ta bort</a>
			{/if}
			<br>
			{if $my_contacts|@count > 0 && $is_inbox == '1'}
			<a href="javascript:;" onclick="vb_mail()"><img src="/img/icons/MailSkickadeIcon.gif" class="mmMarginLeft20"></a> <a href="javascript:;" onclick="vb_mail()">Vidarebefordra</a><br/>
			{/if}
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<hr/>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			{$mail_message}
		</td>
	</tr>
</table>
</div>

<div id="remove_div" class="mmDisplayNone">
	<div>
		Meddelandet har tagits bort
	</div>
</div>


{if $my_contacts|@count > 0}
<div id="fwded_div" class="mmDisplayNone">
	<div>
		Meddelandet har vidarebefodrats
	</div>
</div>

<div id="fw_div" class="mmDisplayNone">
<h1>Skicka meddelande</h1>

<table>
	<tr>
		<td>
			<b>Till:</b>
			<select id="vb_to">
			<option value="0">
				--- V&auml;lj ---
			</option>
			{foreach from=$my_contacts key=k item=v}
			<option value="{$k}">
				{$v}
			</option>
			{/foreach}
		</select>		</td>
	</tr>
	<tr>
		<td>
			<b>&Auml;mne:</b>
			<input type="text" id="vb_amne" value="{$vb_text}" />
		</td>
	</tr>
	<tr>
		<td>
			<b>Meddelande:</b>
		</td>
	</tr>
	<tr>
		<td>
			<textarea id="vb_msg" class="mmReadMailMeddelande">{$nl}{$mail_msg}</textarea>
		</td>
	</tr>

	<tr>
		<td>
			<a href="javascript:;" onclick="send_vb_mail()"><img src="/img/icons/MailSkickadeIcon.gif"></a> <a href="javascript:;" onclick="send_vb_mail()">Skicka</a>

		</td>
	</tr>
</table>
</div>
{/if}


<div id="answering_div" class="mmDisplayNone">
<h1>Skicka meddelande</h1>
<table id="new_div">
	<tr>
		<td>
			<b>Till:</b>
			<a href="{$urlHandler->getUrl(Medlem, URL_VIEW, $mail_to_read->getSentFrom())}">{$mail_to_read->getFromName($id)}</a>
		</td>
	</tr>
	<tr>
		<td>
			<b>&Auml;mne:</b>
			<input type="text" id="amne" value="{$re_text}" />
		</td>
	</tr>
	<tr>
		<td>
			<b>Meddelande:</b>
		</td>
	</tr>
	<tr>
		<td>
			<textarea id="msg" class="mmReadMailMsg">{$nl}{$mail_msg}</textarea>
		</td>
	</tr>

	<tr>
		<td>
			<a href="javascript:;" onclick="send_answer_mail('{$mail_to_read->getSentFrom()}','{$mail_to_read->getId()}')"><img src="/img/icons/MailSkickadeIcon.gif"></a> <a href="javascript:;" onclick="send_answer_mail('{$mail_to_read->getSentFrom()}','{$mail_to_read->getId()}')">Skicka</a>

		</td>
	</tr>
</table>
</div>

<div id="answered_div" class="mmDisplayNone">
	<div>
		Meddelandet har skickats till {$mail_to_read->getFromName($id)}
	</div>
</div>


{*}
				<div id="reading_div" class="mmWidthHundraProcent">
					<div id="buttons_div" class="mmReadMailButtonsDiv">
						{if $is_inbox == '1'}
						<input type="button" value="Svara" onclick="answer_mail()" />
						{/if}
						{if $is_inbox == '1'}
						<input type="button" value="Ta bort" onclick="remove_one_mail('{$id}', '{$my_id}', 'to_deleted')" />
						{else}
						<input type="button" value="Ta bort" onclick="remove_one_mail('{$id}', '{$my_id}', 'from_deleted')" />
						{/if}
						{if $my_contacts|@count > 0 && $is_inbox == '1'}
						<input type="button" value="Vidarebefodra" onclick="vb_mail()" />
						{/if}
					</div>
													
					<div>
						<div id="mmFranBox">
							{if $is_inbox == '1'}
							<b>Fr&aring;n:</b>
							{else}
							<b>Till:</b>
							{/if}		
						</div>
						<div class="mmFloatLeft;">
							{if $is_inbox == '1'}
							{$mail_to_read->getFromName($id)}
							{else}
							{$mail_to_read->getToName($mail_to_read->getId())}
							{/if}
							
						</div>
						<div class="mmClearBoth"></div>
					</div>
					<div>
						<div  class="mmFloatLeft mmWidthSexNollPixlar">
							<b>&Auml;mne:</b>
						</div>
						<div class="mmFloatLeft">
							{$mail_to_read->getSubject()} 
						</div>
						<div class="mmClearBoth"></div>
					</div>
					<div>
						<b>Meddelande:</b>
					</div>
					<div>
						{$mail_message}
					</div>
				</div>

				{if $my_contacts|@count > 0}
				<div id="fwded_div" class="hide">
					<div>
						Meddelandet har vidarebefodrats
					</div>
				</div>
				
				<div id="fw_div" class="hide">
					<div  class="mmFloatLeft mmWidthSexNollPixlar">
						Till:
					</div>
					<div  class="mmFloatLeft mmWidth160">
						<select id="vb_to">
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
					<div class="mmFloatLeft">
						<input type="text" id="vb_amne" value="{$vb_text}" />
					</div>
					<div class="mmClearBoth"></div>

					<div>
						<b>Meddelande:</b>
					</div>
					<div>
						<textarea id="vb_msg" class="nyttMeddelande">{$nl}{$mail_msg}</textarea>
					</div>
					<div>
						<input type="button" value="Skicka" onclick="send_vb_mail()" />
					</div>
				</div>	
				{/if}

				<div id="answering_div" class="hide">
					<div>
						<div  class="mmFloatLeft mmWidthSexNollPixlar">
							<b>Till:</b>
						</div>
						<div class="mmFloatLeft">
							{$mail_to_read->getFromName($id)}
						</div>
						<div class="mmClearBoth"></div>
					</div>
					<div>
						<div  class="mmFloatLeft mmWidthSexNollPixlar">
							<b>&Auml;mne:</b>
						</div>
						<div class="mmFloatLeft">
							<input type="text" id="amne" value="{$re_text}" />
						</div>
						<div class="mmClearBoth"></div>
					</div>
					<div>
						<b>Meddelande:</b>
					</div>
					<div>
						<textarea id="msg" class="nyttMeddelande">{$nl}{$mail_msg}</textarea>
					</div>
					<div>
						<input type="button" value="Skicka" onclick="send_answer_mail('{$mail_to_read->getSentFrom()}','{$mail_to_read->getId()}')" />
					</div>
				</div>

				<div id="answered_div" class="hide">
					<div>
						Meddelandet har skickats till {$mail_to_read->getFromName($id)}
					</div>
				</div>

				<div id="remove_div" class="hide">
					<div>
						Meddelandet har tagits bort
					</div>
				</div>

{*}