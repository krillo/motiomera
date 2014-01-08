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


  <table class="mmTableAdmin" border="0" cellpadding="0" cellspacing="0">
    <thead>
      <tr style="background-color:#fff;">
        <th>ID</th>
        <th style="padding-right: 5px;">Företagsnamn</th>
        <th style="padding-right: 5px;">FöretagsId</th>			
        <th>typ</th>			
        <th>Datum</th>
        <th>Veckor</th>
        <th>Tävlingsdatum</th>
        <th>Kanal</th>
        <th style="padding-right: 5px;">Valid</th>			
        <th style="padding-right: 5px;">Status</th>          			
        <th style="padding-right: 5px;">Antal</th>
        <th style="padding-right: 5px;">Quantity</th>
        <th>Pris</th>
        <th>Pris moms</th>
        <th style="padding-right: 5px;">Betalning</th>
        <th style="padding-right: 5px;">Namn</th>
        <th style="padding-right: 5px;">Epost</th>
        <th style="padding-right: 5px;">Telefon</th>
        <th style="padding-right: 5px;">Kampanj</th>
        <th>Kampanj med text</th>			
        <th>Filnamn</th> 			
        <th>Faktura filnamn</th> 	
        <th style="padding-right: 5px;">Faktura namn</th>
        <th style="padding-right: 5px;">Faktura epost</th>
        <th style="padding-right: 5px;">Faktura telefon</th>
      </tr>
    </thead>
    <tbody>
      {foreach from=$listOrder item=thisOrder}
        <tr>
          <td class="mmList1">{$thisOrder->getId()}.</td>
          {if $thisOrder->getTyp() eq 'foretag' or $thisOrder->getTyp() eq 'foretag_tillagg'}
            <td class="mmList2"><a style="text-decoration: underline; color: blue;" href="{$urlHandler->getUrl(Foretag, URL_EDIT, $thisOrder->getForetagId())}">{$thisOrder->getCompanyName()}</a></td>	
          {/if}          
          {if $thisOrder->getTyp() eq 'medlem' or $thisOrder->getTyp() eq 'medlem_extend'}
            <td class="mmList2"><a style="text-decoration: underline; color: blue;" href="/admin/pages/medlem.php?id={$thisOrder->getMedlemId()}">{$thisOrder->getCompanyName()}</a></td>	
          {/if}          
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