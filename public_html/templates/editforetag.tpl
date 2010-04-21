{if (isset($ADMIN)) }
<h1 style="color:red;">Inloggad som Administrator - {$ADMIN->getANamn()}</h1>
<h2>Administrationssida för {if isset($foretaget)}{$foretaget->getNamn()}{else}Nytt företag{/if}</h2>
{else}
<h1>Administrationssida för {if isset($foretaget)}{$foretaget->getNamn()}{else}Nytt företag{/if}</h1>
{/if}


<p>
Så fort dina deltagare aktiverat sina MotioMera-konton kan du se dem under fliken <b>Deltagare</b> nedan.
Deltagarna blir automatiskt indelade i lag. Du kan ändra lagindelningen genom att klicka på fliken <b>Lag</b>.
Vill du anmäla fler deltagare till tävlingen gör du det under fliken <b>Tilläggsbeställning</b>.
</p>


<br/>
<h3>Viktiga datum</h3>
<table class="sortable sorted">
  {section name=record loop=$datesArray}
  <tr>
    <td class="mmList1">{$datesArray[record][0]}</td>         
    <td class="mmList1">{$datesArray[record][1]}</td>         
    <td class="mmList1">{$datesArray[record][2]}</td>         
  </tr>
  {/section} 
</table> 


<br/><br/>
{$tabs->printTabBox()}
