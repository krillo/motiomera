	<div id="mmFotoalbumTopRight">
	{if $is_inbox}
		<a href="{$urlHandler->getUrl(MotiomeraMail, URL_VIEW, "outbox")}"><img src="/img/icons/MailSkickadeIcon.gif" alt="" /></a> <a href="{$urlHandler->getUrl(MotiomeraMail, URL_VIEW, "outbox")}">Skickade meddelanden</a>
	{else}
		<a href="{$urlHandler->getUrl(MotiomeraMail, URL_VIEW, "inbox")}"><img src="/img/icons/MailInboxIcon.gif" alt="" /></a> <a href="{$urlHandler->getUrl(MotiomeraMail, URL_VIEW, "inbox")}">Mottagna meddelanden</a>
	{/if}
		{if $action == "manage_folders"}
			<a href="{$urlHandler->getUrl(MotiomeraMail, URL_VIEW, "outbox")}"><img src="/img/icons/MailSkickadeIcon.gif" alt="" class="mmMarginLeft20" /></a> <a href="{$urlHandler->getUrl(MotiomeraMail, URL_VIEW, "outbox")}">Skickade meddelanden</a>
		{else}
			<a href="{$urlHandler->getUrl(MotiomeraMail, URL_EDIT)}"><img src="/img/icons/MailHanteraMappIcon.gif" alt="" class="mmMarginLeft20" /></a> <a href="{$urlHandler->getUrl(MotiomeraMail, URL_EDIT)}">Hantera mappar</a>
		{/if}


		<a href="#"></a>
	</div>

		<div class="mmh1 mmMarginBottom">Mail<span class="mmh2">: {if $is_inbox}Mottagna meddelanden{elseif $action =="manage_folders"}Hantera mappar{else}Skickade meddelanden{/if}</span>
		
		{if $is_inbox}
			<br/>
			<strong class="mmh3">Mappar:</strong>
				<a href="{$urlHandler->getUrl(MotiomeraMail, URL_VIEW, "inbox")}" class="mmMarginLeft10">Inbox</a>
			{foreach from=$folders key=k item=v}
				<a href="{$urlHandler->getUrl(MotiomeraMail, URL_LIST, $v.id)}" class="mmMarginLeft10">{$v.folder_name}</a>
			{/foreach}
		{/if}
		
		</div>

<br />
	{if $is_inbox}
		<a href="javascript:;" onclick="motiomera_mail_write_new(); return false;"><img src="/img/icons/MailSkrivNyttIcon.gif" alt="" /></a> <a href="javascript:;" onclick="motiomera_mail_write_new(); return false;">Skriv nytt</a>
		<a href="javascript:;" onclick="remove_multiple_mail('{$my_id}', 'to_deleted')"><img src="/img/icons/MailDeleteIcon.gif" alt="" class="mmMarginLeft20" /></a> <a href="javascript:;" onclick="remove_multiple_mail('{$my_id}', 'to_deleted')">Ta bort</a>
		<img src="/img/icons/MailFlyttaMappIcon.gif" alt="" class="mmMarginLeft20 mmVerticalAlignMiddle" /> <select id="move_to_folder" onchange="moveToFolder('{$folder_id}')">
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
							 {*}<img src="/img/icons/MailArrowDown.gif" alt="" />{*}
		
	{elseif $action=="manage_folders"}
	
		<strong>Skapa en mapp:</strong>
		<div class="mmFolderExists" id="folder_exists">
			&nbsp;
		</div>
		<div>
			<input type="text" name="folder_name" id="folder_name" />

			<a href="javascript:;" onclick="validateCreateFoldere('{$my_id}')"><img src="/img/icons/MailSkapaMappIcon.gif" alt="" /></a> <a href="javascript:;" onclick="validateCreateFoldere('{$my_id}')">Skapa</a>
		</div>	
					
	{else}
		<a href="javascript:;" onclick="motiomera_mail_write_new(); return false;"><img src="/img/icons/MailSkrivNyttIcon.gif" alt="" /></a> <a href="javascript:;" onclick="motiomera_mail_write_new(); return false;">Skriv nytt</a>
		<a href="javascript:;" onclick="remove_multiple_mail('{$my_id}', 'from_deleted')"><img src="/img/icons/MailDeleteIcon.gif" alt="" class="mmMarginLeft20" /></a> <a href="javascript:;" onclick="remove_multiple_mail('{$my_id}', 'from_deleted')">Ta bort</a>
	{/if}

{include file="$to_include"}

{*}
<h1>Mail</h1>
<table>
	<tr>
		<td class="mmWidthEttSexNollPixlar">
			&nbsp;
		</td>
		<td>
			&nbsp;
		</td>

		<tr>
		<td class="mmWidthEttSexNollPixlar">
			<table>
				{if $my_contacts|@count > 0}
				<tr>
					<td>
						<a href="#" onclick="motiomera_mail_write_new(); return false;">
							Skriv Nytt
						</a>
					</td>
				</tr>
				{/if}
				
				<tr>
					<td>
						{if $folders|@count > 0}
						<a href="#" onclick="showFolders()" class="mmTextField">+</a>	
						{/if}
						{if $action == "inbox" && $folder_id == 0}
							<i>Inbox</i>
						{else}
							<a href="{$urlHandler->getUrl(InternMail, URL_VIEW, 0)}">Inbox</a>
						{/if}
						
					</td>
				</tr>
				
				{if $action == "inbox" && $folder_id != 0}
				<tr id="subfolders">
				{else}
				<tr id="subfolders" class="mmDisplayNone">
				{/if}
				<td>
				
				{foreach from=$folders key=k item=v}
				<div>
						{if $action == "inbox" && $folder_id == $v.id}
							<i>{$v.folder_name}</i>
						{else}
							&nbsp;&nbsp;<a href="/pages/mail.php?do=inbox&folder_id={$v.id}">{$v.folder_name}</a>
						{/if}
				</div>		
					
				{/foreach}
					</td>
				</tr>
				<tr>
					<td>
						{if $action == "outbox"}
							<i>Skickade meddelande</i>
						{else}
							<a href="{$urlHandler->getUrl(MailSentItems, URL_VIEW, 0)}">Skickade meddelande</a>
						{/if}
					</td>
				</tr>
				<tr>
					<td>
						{if $action == "manage_folders"}
							<i>Hantera mappar</i>
						{else}
							<a href="{$urlHandler->getUrl(MailManageFolder, URL_VIEW, 0)}">Hantera mappar</a>
						{/if}
					</td>
				</tr>
			</table>
		</td>
		<td>
			{include file="$to_include"}
		</td>
	</tr>
</table>
{*}