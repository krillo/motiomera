<script type="text/javascript">
	{literal}
	$(document).ready(function()
	{
		addSorting();
	});
	{/literal}
</script>
<h1>Företag</h1>
{*}<p>
	<a href="{$urlHandler->getUrl(Foretag, URL_CREATE)}">Registrera nytt företag</a>
</p>{*}
<form method="get" action="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">
   <input type="text" name="search" value="{$search}" />
   {html_options name=field options=$searchOpt selected=$searchSel}<br/>
   <input type="text" name="limit" value="{$limit}" size="4"/> Antal <br/>
   <input type="checkbox" name="showValid" value="true" {$checked} />Visa bara valida<br/> 
   <input type="submit" name="Sök" value="Sök" /><br />
</form>
<br />

{if $listForetag eq null}
<div>Inga träffar!</div>
{else}

<table class="sortable" border="0" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th>
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">ID</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Företagsnamn</a>
			</th>
			<th>	
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Start</a>
			</th>
			<th style="padding-right: 5px;">
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Valid </a>
			</th>			
			<th>	
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Kanal</a>
			</th>
			<th style="padding-right: 5px;">	
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Kampanj</a>
			</th>
      <th>
        <a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Reklamationer</a>
      </th>
			<th>
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">E-mail</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Namn</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Adress</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Postnr</a>
			</th>
			<th>
				<a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Ort</a>
			</th>
      <th>
        <a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Användarnamn</a>
      </th>
      <th>
        <a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Kundnummer</a>&nbsp;
      </th>
      <th>
        <a href="{$urlHandler->getUrl(Foretag, URL_ADMIN_LIST)}">Uppdaterat</a>
      </th>

		</tr>
	</thead>
	<tbody>
		{foreach from=$listForetag item=thisForetag}
		<tr>
			<td class="mmList1"><a style="text-decoration: underline; color: blue;" href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}&foretagid={$thisForetag->getId()}">{$thisForetag->getId()}</a></td>
			<td class="mmList2"><a style="text-decoration: underline; color: blue;" href="{$urlHandler->getUrl(Foretag, URL_EDIT, $thisForetag->getId())}">{$thisForetag->getNamn()}</a></td>
			<td class="mmList1">{$thisForetag->getStartDatum()}</td>
			<td class="mmList2">{$thisForetag->getIsValid()}</td>			
			<td class="mmList1">{$thisForetag->getKanal()}</td>
			<td class="mmList2">{$thisForetag->getCompAffCode()}</td>	
      <td class="mmList1">{$thisForetag->getSumReclamations()}</td> 			
			<td class="mmList2">{$thisForetag->getPayerEmail()}</td>
			<td class="mmList1">{$thisForetag->getPayerName()}</td>
			<td class="mmList2">{$thisForetag->getPayerAddress()}</td>
			<td class="mmList1">{$thisForetag->getPayerZipCode()}</td>
			<td class="mmList2">{$thisForetag->getPayerCity()}</td>
      <td class="mmList1">{$thisForetag->getANamn()}</td>
      <td class="mmList2">{$thisForetag->getKundnummer()}</td>
      <td class="mmList1">{$thisForetag->getUpdatedDate()}</td>
			
		</tr>	
		{/foreach}
	</tbody>
</table>
{/if}


