<script type="text/javascript">
	var checked_ids = new Array();
</script>
<table width="100%" cellpadding="4" cellspacing="2">
	<tr class="mmMailCellGreen1">
		<td class="mmWidthTreNollPixlar">&nbsp;</td>
		<td class="mmWidthTreNollPixlar"><input type="checkbox" name="select_all_mail" id="select_all_mail" onclick="selectAllMails()" /></td>

{if $is_inbox}		<td class="mmWidthEttFyraNollPixlar">Från</td>
{else}		<td class="mmWidthEttFyraNollPixlar">Till</td>
{/if}		<td class="mmWidthTvaTreNollPixlar">&Auml;mne</td>
		<td class="mmWidthHundra">Datum</td>
	</tr>
	{assign var=row value=''}
	{foreach from=$box_mails key=k item=v}
		
	{assign var=amne value=$v.subject}
	
	{if $v.subject == ''}
		{assign var=amne value='(ingen rubrik)'}
	{/if}
	
	<script type="text/javascript">
		checked_ids[checked_ids.length] = '{$v.id}';
	</script>
	
	{if $row == ''}
	{assign var=row value='_greenBG'}
	<tr class="mmMailCellGreen2" id="mail_tr_{$v.id}">
	{else}
	{assign var=row value=''}
	<tr id="mail_tr_{$v.id}">
	{/if}
		<td class="mmWidthTreNollPixlar">
			{if $v.is_answered == '1' && $is_inbox}
				<img id="mail_img_{$v.id}" src="/img/icons/MailAnsweredIcon{$row}.gif" alt="Besvarad epost" />
			{elseif $v.is_read == '1'|| !$is_inbox}
				<img id="mail_img_{$v.id}" src="/img/icons/MailReadIcon{$row}.gif" alt="Läst epost" />
			{else}
				<img id="mail_img_{$v.id}" src="/img/icons/MailUnreadIcon{$row}.gif" alt="Oläst epost" />
			{/if}
		</td>
		<td class="mmWidthTreNollPixlar">
			<input type="checkbox" id="mail_selected_{$v.id}" />
		</td>
		<td class="mmWidthEttFyraNollPixlar">

			{if $is_inbox}
				{if $v.aNamn|count_characters > 15}
						{$v.aNamn|substr:0:15}...
				{else}
						{$v.aNamn}
				{/if}
			{else}
						{$v.to_name}
			{/if}
		</td>
		<td class="mmWidth230">
			{if $is_inbox}
				{if $amne|count_characters > 20}
					<a href="#" onclick="motiomera_mail_read('{$v.id}', '1'); return false;">
						{$amne|substr:0:20}...
					</a>
				{else}
					<a href="#" onclick="motiomera_mail_read('{$v.id}', '1'); return false;">
						{$amne}
					</a>
				{/if}
			{else}
				{if $amne|count_characters > 20}
					<a href="#" onclick="motiomera_mail_read('{$v.id}', '0'); return false;">
						{$amne|substr:0:20}...
					</a>
				{else}
					<a href="#" onclick="motiomera_mail_read('{$v.id}', '0'); return false;">
						{$amne}
					</a>
				{/if}
			{/if}	
		</td>
		<td class="mmWidth100">
			{$v.date_sent|substr:0:10}
		</td>
	</tr>	
	{/foreach}

</table>

</div>

<div>
<div class="mmClearBoth"></div>

{*}
				<table>
					<tr>
						<td colspan="5">
							{if $is_inbox}
							<input type="button" value="Ta bort" onclick="remove_multiple_mail('{$my_id}', 'to_deleted')" />
							<select id="move_to_folder" onchange="moveToFolder('{$folder_id}')">
								<option value="-1">
									Flytta till...
								</option>
								{if $folders|@count > 0}
								<option value="0">
									Inbox
								</option>	
								{/if}
								{foreach from=$folders key=k item=v}
								<option value="{$v.id}">
									{$v.folder_name}
								</option>		
								{/foreach}
							</select>
							{else}
							<input type="button" value="Ta bort" onclick="remove_multiple_mail('{$my_id}', 'from_deleted')" />
							{/if}
						</td>
					</tr>
					
					<tr class="mmBackGroundColorNioNioCCCC">
						<td class="mmWidthFyraNollPixlar">
							
						</td>
						<td class="mmWidthFyraNollPixlar">
							<input type="checkbox" name="select_all_mail" id="select_all_mail" onclick="selectAllMails()" />
						</td>
						<td class="mmWidthEttFemNollPixlar">
							{if $is_inbox}
							Fr&aring;n
							{else}
							Till
							{/if}
						</td>
						<td class="mmWidthEttSexNollPixlar">
							&Auml;mne
						</td>
						<td class="mmWidthNioNollPixlar">
							Datum
						</td>
					</tr>
					<script type="text/javascript">
						var checked_ids = new Array();
					</script>
					{foreach from=$box_mails key=k item=v}
					
					<script type="text/javascript">
						checked_ids[checked_ids.length] = '{$v.id}';
					</script>
					
					{if $v.is_read == '1' || !$is_inbox}
					<tr class="read_mail_row" id="mail_tr_{$v.id}">
					{else}
					<tr class="unread_mail_row" id="mail_tr_{$v.id}">
					{/if}
						<td class="mmWidthFyraNollPixlar mmAlignCenter">
							{if $v.is_answered == '1' && $is_inbox}
								<img id="mail_img_{$v.id}" src="/img/mail/answered_mail.gif" />
							{elseif $v.is_read == '1'|| !$is_inbox}
								<img id="mail_img_{$v.id}" src="/img/mail/read_mail.gif" />
							{else}
								<img id="mail_img_{$v.id}" src="/img/mail/unread_mail.gif" />
							{/if}
						</td>
						<td class="mmWidthFyraNollPixlar">
							<input type="checkbox" id="mail_selected_{$v.id}" />
						</td>
						<td class="mmWidthEttFemNollPixlar">

							{if $is_inbox}
								{if $v.aNamn|count_characters > 15}
									<a href="#" onclick="motiomera_mail_read('{$v.id}', '1'); return false;">
										{$v.aNamn|substr:0:15}...
									</a>
								{else}
									<a href="#" onclick="motiomera_mail_read('{$v.id}', '1'); return false;">
										{$v.aNamn}
									</a>
								{/if}
							{else}
									<a href="#" onclick="motiomera_mail_read('{$v.id}', '0'); return false;">
										{$v.to_name}
									</a>
							{/if}
						</td>
						<td class="mmWidthEttSexNollPixlar">
							{if $is_inbox}
								{if $v.subject|count_characters > 20}
									<a href="#" onclick="motiomera_mail_read('{$v.id}', '1'); return false;">
										{$v.subject|substr:0:20}...
									</a>
								{else}
									<a href="#" onclick="motiomera_mail_read('{$v.id}', '1'); return false;">
										{$v.subject}
									</a>
								{/if}
							{else}
								{if $v.subject|count_characters > 20}
									<a href="#" onclick="motiomera_mail_read('{$v.id}', '0'); return false;">
										{$v.subject|substr:0:20}...
									</a>
								{else}
									<a href="#" onclick="motiomera_mail_read('{$v.id}', '0'); return false;">
										{$v.subject}
									</a>
								{/if}
							{/if}	
						</td>
						<td class="mmWidthNioNollPixlar">
							{$v.date_sent|substr:0:10}
						</td>
					</tr>	
					{/foreach}
				</table>


{*}