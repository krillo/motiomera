<h1>Slå samman order/företag</h1>
{if $status == 'missing_params'}
    <p style="color:red;font-size:14px;font-weight:bold;">En eller flera parametrar saknas</p> 
{elseif $status == 'db_error'}
    <p style="color:red;font-size:14px;font-weight:bold;">Ett fel uppstod i databasen</p>
{elseif $status == 'no_change'}
    <p style="color:green;font-size:14px;font-weight:bold;">Inget uppdaterades i databasen</p>
{elseif $status == 'success'}
    <p style="color:green;font-size:14px;font-weight:bold;">Uppdateringen lyckades!</p>
{elseif $status == 'no'}
    <p style="color:blue;font-size:14px;font-weight:bold;">apa</p>
{/if}




<p>
Det händer att företag lägger flera ordrar fast vill att alla deltagarna ska tillhöra samma tävling. 
Du kan slå ihop dessa ordrar genom att ange företags id för båda företagen. <br>
Det som händer är att alla nycklarna för det första föreataget får det andra företagets id.
<br>
<br>
De nya lagmedlemmarna kommer inte att hamna i lag! Man får gå in i företagets admin och avgöra 
ifall administratören har börjat med en lagindelning eller ifall man bör slumpa nya lag. 
</p>
<p style="font-size:14px;font-weight:bold;">
OBS Det går inte att ångra!!    
</p>

<form action="{$urlHandler->getUrl(MergeOrder, URL_ADMIN_SAVE)}" method="post">
		<p>
			<b>Från - FöretagId </b><br />
			<input type="text" name="foretagid_from" />
		</p>
		<p>
			<b>Till - FöretagId </b><br />
			<input type="text" name="foretagid_to" />
		</p>
		<p>
			<input type="submit" value="Slå ihop" />
		</p>
</form>


