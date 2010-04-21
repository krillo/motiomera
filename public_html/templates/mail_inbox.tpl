<script type="text/javascript">
	var checked_ids = new Array();
</script>
<table width="100%" cellpadding="4" cellspacing="2">
	<tr class="mmMailCellGreen1">
		<td class="mmWidthTreNollPixlar">&nbsp;</td>
		<td class="mmWidthTreNollPixlar"><input type="checkbox" name="select_all_mail" id="select_all_mail" onclick="selectAllMails()" /></td>
		<td class="mmWidthEttFyraNollPixlar">Från</td>
		<td class="mmWidthTvaTreNollPixlar">&Auml;mne</td>
		<td class="mmWidthEttNollNollPixlar">Datum</td>
	</tr>
	{assign var=row value='0'}
	{foreach from=$box_mails key=k item=v}
	
	<script type="text/javascript">
		checked_ids[checked_ids.length] = '{$v.id}';
	</script>
	
	{if $row == '0'}
	{assign var=row value='1'}
	<tr class="mmMailCellGreen2" id="mail_tr_{$v.id}">
	{else}
	{assign var=row value='0'}
	<tr id="mail_tr_{$v.id}">
	{/if}
		<td class="mmWidthTreNollPixlar mmTextAlignCenter">
			{if $v.is_answered == '1' && $is_inbox}
				<img id="mail_img_{$v.id}" src="/img/mail/answered_mail.gif" alt="Besvarad mail" />
			{elseif $v.is_read == '1'|| !$is_inbox}
				<img id="mail_img_{$v.id}" src="/img/mail/read_mail.gif" alt="Läst mail" />
			{else}
				<img id="mail_img_{$v.id}" src="/img/mail/unread_mail.gif" alt="Oläst mail" />
			{/if}
		</td>
		<td class="mmWidthFyraNollPixlar">
			<input type="checkbox" id="mail_selected_{$v.id}" />
		</td>
		<td class="mmWidthEttFemNollPixlar;">

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

</div>


<div class="mmClearBoth"></div>
<div class="mmGrayLineBottom"></div>




{*}


				<table>
					<tr>
						<td colspan="5">
							{if $is_inbox}
							<input type="button" value="Ta bort" onclick="remove_multiple_mail('{$my_id}')" />
							{else}
							<input type="button" value="Ta bort" onclick="remove_multiple_mail('{$my_id}')" />
							{/if}
						</td>
					</tr>
					
					<tr class=".mmBackGroundColorNioNioCCCC">
						<td class="mmWidthFyraNollPixlar">
							
						</td>
						<td class="mmWidthFyraNollPixlar">
							<input type="checkbox" name="select_all_mail" id="select_all_mail" onclick="selectAllMails()" />
						</td>
						<td class="mmWidthEttFemNollPixlar">
							{if $is_inbox}
							<a href="">Fr&aring;n</a>
							{else}
							<a href="">Till</a>
							{/if}
						</td>
						<td class="mmWidthEttSexNollPixlar">
							<a href="">&Auml;mne</a>
						</td>
						<td class="mmWidthNioNollPixlar">
							<a href="">Datum</a>
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
						<td class="mmWidthFyraNollPixlar mmTextAlignCenter">
							{if $v.is_answered == '1' && $is_inbox}
								<img id="mail_img_{$v.id}" src="/img/mail/answered_mail.gif" alt="Besvarad epost" />
							{elseif $v.is_read == '1'|| !$is_inbox}
								<img id="mail_img_{$v.id}" src="/img/mail/read_mail.gif" alt="Läst epost" />
							{else}
								<img id="mail_img_{$v.id}" src="/img/mail/unread_mail.gif" alt="Oläst epost" />
							{/if}
						</td>
						<td class="mmWidthFyraNollPixlar">
							<input type="checkbox" id="mail_selected_{$v.id}" />
						</td>
						<td class="mmWidthEttFemNollPixlar">
							{if $is_inbox}
								{if $v.aNamn|count_characters > 15}
									<a href="#" onclick="motiomera_mail_read('{$v.id}'); return false;">
										{$v.aNamn|substr:0:15}...
									</a>
								{else}
									<a href="#" onclick="motiomera_mail_read('{$v.id}'); return false;">
										{$v.aNamn}
									</a>
								{/if}
							{else}
									<a href="#" onclick="motiomera_mail_read('{$v.id}'); return false;">
										{$v.to_name}
									</a>
							{/if}
						</td>
						<td class="mmWidthEttSexNollPixlar">
							{if $v.subject|count_characters > 20}
								<a href="#" onclick="motiomera_mail_read('{$v.id}'); return false;">
									{$v.subject|substr:0:20}...
								</a>
							{else}
								<a href="#" onclick="motiomera_mail_read('{$v.id}'); return false;">
									{$v.subject}
								</a>
							{/if}
						</td>
						<td class="mmWidthNioNollPixlar">
							{$v.date_sent|substr:0:10}
						</td>
					</tr>	
					{/foreach}
				</table>

{*}