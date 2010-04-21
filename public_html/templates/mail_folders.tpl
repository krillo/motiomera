<table width="490px" cellpadding="4" cellspacing="2">
	<tr class="mmMailCellGreen1">
		<td class="mmWidth30"></td>
		<td class="mmWidth140">Namn</td>
		<td class="mmWidth100"></td>
	</tr>
	{assign var=row value=''}
	{foreach from=$folders key=k item=v}
		
	{assign var=amne value=$v.subject}
	
	{if $v.subject == ''}
		{assign var=amne value='(ingen rubrik)'}
	{/if}
	
	
	{if $row == ''}
	{assign var=row value='_greenBG'}
	<tr class="mmMailCellGreen2" id="folder_tr_{$v.id}">
	{else}
	{assign var=row value=''}
	<tr id="folder_tr_{$v.id}">
	{/if}
		<td class="mmWidth30">
			<img src="/img/icons/MailHanteraMappIcon.gif" alt="Hantera mappar" />
		</td>
		<td class="mmWidth230">
			{$v.folder_name}
		</td>
		<td class="mmWidth230">
			<a href="javascript:;" onclick="remove_one_folders('{$v.id}', '{$my_id}')" ><img src="/img/icons/MailDeleteMappIcon.gif" alt="Ta bort mapp" /></a> <a href="javascript:;" onclick="remove_one_folders('{$v.id}', '{$my_id}')" >Ta bort</a>
		</td>
	</tr>	
	{/foreach}

</table>

</div>
<div>

<div class="mmClearBoth"></div>

{*}

				<div>
					<b>Skapa en mapp:</b>
				</div>
				<div id="folder_exists">
					&nbsp;
				</div>
				<div>
					<input type="text" name="folder_name" id="folder_name" />
				</div>
				<div>
					<input type="button" value="Skapa" onclick="validateCreateFoldere('{$my_id}')" />
				</div>	
					
				<hr />

				<div>
					<b>Existerande mappar:</b>
				</div>
				<br />
				
				<div>
					<div>
						<input type="button" value="Ta bort" onclick="remove_multiple_folders('{$my_id}')" />
					</div>
				</div>

				<div id="mmDeleteFolderSelectDiv">
					<div class="mmDeleteFolderInput">
						<input type="checkbox" name="select_all_folders" id="select_all_folders" onclick="select_all_folders()" />
					</div>
					<div class="mmDeleteFolderName ">
						<b>Namn</b>
					</div>
					<div class="mmDeleteFolderNbsp">
						&nbsp;
					</div>
					<div class="mmClearBoth"></div>
				</div>

				
				<script type="text/javascript">
					var folders_checked_ids = new Array();
				</script>
				
				
				{foreach from=$folders key=k item=v}
				<script type="text/javascript">
					folders_checked_ids[folders_checked_ids.length] = '{$v.id}';
				</script>
				<div class="mmFolderForeachLoop" id="folder_tr_{$v.id}">
					<div class="mmFolderCheckboxDiv">
						<input type="checkbox" id="folder_selected_{$v.id}" />
					</div>
					<div class="mmFolderNameDiv">
						{$v.folder_name}
					</div>
					<div class="mmFolderInputDiv">
						<input class="mmHeightTvaNollPixlar mmWidthSjuNollPixlar" type="button" value="Ta bort" onclick="remove_one_folders('{$v.id}', '{$my_id}')" />
					</div>
					<div class="mmClearBoth"></div>
				</div>
				{/foreach}
				
{*}