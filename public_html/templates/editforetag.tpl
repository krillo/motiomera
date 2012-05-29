{if (isset($ADMIN)) }
<h1 class="mmAdminColor">Inloggad som Administrator - {$ADMIN->getANamn()}</h1>
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

<br/>
{if (isset($tid)) }
<br/>  
<div class="mmRed"><h3>Er tävling är nu slut och här kan du se all <a href="http://motiomera.dev/pages/tavlingsres.php?fid={$foretaget->getId()}&tid={$tid}" class="mmRed" style="text-decoration:underline; ">statistik för tävlingen</a></h3></div>
{/if}
<br/>
{if (isset($ADMIN)) }
<div class="mmAdminColor"><h3>Se alla <a href="/admin/pages/listorder.php?search=&field=id&offset=0&showValid=true&foretagid={$foretaget->getId()}" class="mmAdminColor" style="text-decoration:underline; ">ordrar</a></h3></div>
{/if}

<br/>
{$tabs->printTabBox()}
