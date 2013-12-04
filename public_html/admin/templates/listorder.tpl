<script type="text/javascript">
  {literal}
	$(document).ready(function()
	{
		addSorting();
	});
  {/literal}
</script>
<h1>Order</h1>

<form method="get" action="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">
  <input type="text" name="search" value="{$search}" />
  {html_options name=field options=$searchOpt selected=$searchSel}<br/>
  <input type="text" name="limit" value="{$limit}" size="4"/> Antal <br/>
  <input type="checkbox" name="showComp" value="true" {$checked} />Visa bara företag<br/>    
  <input type="checkbox" name="showValid" value="true" {$checked} />Visa bara valida<br/> 
  <input type="submit" name="Sök" value="Sök" /><br />
</form>
<br />

{if $listOrder eq null}
  <div>Inga träffar!</div>
{else}


  <table class="sortable" border="0" cellpadding="0" cellspacing="0">
    <thead>
      <tr>
        <th>
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">ID</a>
        </th>

        <th style="padding-right: 5px;">
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Företagsnamn</a>
        </th>
        <th style="padding-right: 5px;">
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">FöretagsId</a>
        </th>			
        <th>
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">typ</a>
        </th>			
        <th>	
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Datum</a>
        </th>
        <th>	
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Veckor </a>
        </th>
        <th>	
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Tävlingsdatum</a>
        </th>
        <th>  
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Kanal</a>
        </th>
        <th style="padding-right: 5px;">
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Valid </a>
        </th>			
        <th style="padding-right: 5px;">	
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Status</a>
        </th>          			
        <th  style="padding-right: 5px;">
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Antal</a>
        </th>
        <th  style="padding-right: 5px;">
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Quantity</a>
        </th>
        <th>
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Pris</a>
        </th>
        <th>
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Pris moms</a>
        </th>
        <th style="padding-right: 5px;">	
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Betalning</a>
        </th>
        <th style="padding-right: 5px;">  
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Namn</a>
        </th>
        <th style="padding-right: 5px;">  
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Epost</a>
        </th>
        <th style="padding-right: 5px;">  
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Telefon</a>
        </th>

        <th style="padding-right: 5px;">
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Kampanj</a>
        </th>
        <th>
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Kampanj med text</a>
        </th>			
        <th>
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Filnamn</a>
        </th> 			
        <th>
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Faktura filnamn</a>
        </th> 	
        <th style="padding-right: 5px;">  
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Faktura namn</a>
        </th>
        <th style="padding-right: 5px;">  
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Faktura epost</a>
        </th>
        <th style="padding-right: 5px;">  
          <a href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}">Faktura telefon</a>
        </th>


      </tr>
    </thead>
    <tbody>
      {foreach from=$listOrder item=thisOrder}
        <tr>
          <td class="mmList1">{$thisOrder->getId()}.</td>
          <td class="mmList2"><a style="text-decoration: underline; color: blue;" href="{$urlHandler->getUrl(Foretag, URL_EDIT, $thisOrder->getForetagId())}">{$thisOrder->getCompanyName()}</a></td>	
          <td class="mmList1"><a style="text-decoration: underline; color: blue;" href="{$urlHandler->getUrl(Order, URL_ADMIN_ORDER_LIST)}&foretagid={$thisOrder->getForetagId()}">{$thisOrder->getForetagId()}</a></td>						
          <td class="mmList2">{$thisOrder->getTyp()}</td>
          <td class="mmList1">{$thisOrder->getSkapadDatum()}</td>
          <td class="mmList2 ">{$thisOrder->getVeckor()}</td>
          <td class="mmList1 {$thisOrder->isActiveCompetitionCSS()}">{$thisOrder->getStartdatum()} - {$thisOrder->getSlutdatum()}</td>           
          <td class="mmList2">{$thisOrder->getKanal()}</td>								
          <td class="mmList1">{$thisOrder->getIsValid()}</td>	            
          <td class="mmList2">{$thisOrder->getOrderStatus()}</td> 			
          <td class="mmList1">{$thisOrder->getAntal()}</td>
          <td class="mmList2">{$thisOrder->getQuantity()}</td>			
          <td class="mmList1">{$thisOrder->getPrice()}</td>						
          <td class="mmList2">{$thisOrder->getSumMoms()}</td>						
          <td class="mmList1">{$thisOrder->getPayment()}</td>		
          <td class="mmList2">{$thisOrder->getReciverName()}</td> 
          <td class="mmList1">{$thisOrder->getReciverEmail()}</td>     		      									
          <td class="mmList2">{$thisOrder->getReciverPhone()}</td>     		      									
          <td class="mmList1">{$thisOrder->getCampaignId()}</td>
          <td class="mmList2">{$thisOrder->getItem()}</td>	
          <td class="mmList1"><a style="text-decoration: underline; color: blue;" href="/admin/pages/showfile.php?file={$thisOrder->getFilnamn()}"  target="_blank">{$thisOrder->getFilnamn()}</a></td>  								
          <td class="mmList2"><a style="text-decoration: underline; color: blue;" href="/admin/pages/showfile.php?file={$thisOrder->getFilnamnFaktura()}"  target="_blank">{$thisOrder->getFilnamnFaktura()}</a></td>
          <td class="mmList1">{$thisOrder->getPayerName()}</td> 
          <td class="mmList2">{$thisOrder->getPayerEmail()}</td>   				
          <td class="mmList1">{$thisOrder->getPayerPhone()}</td>   				
        </tr>	
      {/foreach}
    </tbody>
  </table>
{/if}