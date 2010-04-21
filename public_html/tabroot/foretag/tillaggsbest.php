<?php
$keyhelp = Help::loadById(29);
$urlHandler = new UrlHandler();

$campaignCodes = Order::getCampaignCodes("foretag");
?>	

<h3>Tilläggsbeställning</h3>
<p>
Här kan du anmäla fler deltagare till tävlingen. Vi skickar ut nya stegräknare och deltagarbrev så fort vi hinner. Glöm inte att lägga in de nya deltagarna i rätt lag efter att de har aktiverat sina MotioMera-konton.
</p>
<br/>
		
<form action="<?= $urlHandler->getUrl(Order, URL_SEND) ?>" method="post">
<input type="hidden" name="typ" value="foretag_tillagg">
<input type="hidden" name="fid" value="<?= $foretag->getId()?>">


<table border="0" cellpadding="0" cellspacing="0" class="motiomera_form_table" >
	<tr>
	  <td></td>   
		<td>Antal deltagare</td>
    <td></td>
    <td></td>
    <td style="border-bottom:none;"></td>    
	</tr>

	<?php  $i = 0; foreach($campaignCodes as $campaign => $arr){  ?>
	<tr>
	  <td style="border-bottom:none;"><?=$arr["text"]?></td>
		<td style="border-bottom:none;">
			<input type="hidden" name="$camparray[<?=$i?>][kampanjkod]" value="<?=$campaign?>">
			<input type="text" name="$camparray[<?=$i?>][antal]" size="4" maxlength="4" onblur="mm_krillo_foretag_uppdateraPriser(this.value, '<?=$campaign?>');" />
			(<?=$arr["pris"]?>:-)						
		</td>		
    <td style="border-bottom:none;"></td>       
		<td style="border-bottom:none;">
			<span id="mmForetagVisaPris<?=$campaign?>">0</span>&nbsp;kr&nbsp;ex.&nbsp;moms
			<div id="mmForetagKampanjPris<?=$campaign?>" class="mmDisplayNone"><?=$arr["pris"]?></div>
      <div id="antal<?=$campaign?>" class="mmDisplayNone">-1</div>			 
		</td>
    <td style="border-bottom:none;"></td>		
	</tr>
	<?php $i++; } ?>
	
	<tr>
	  <td style="border-bottom:none;"></td>
		<td style="border-bottom:none;"></td>
    <td style="border-bottom:none;width:50px;">Summa:</td>
		<td style="border-bottom:none;"><span id="mmTotPrice">0</span> &nbsp;kr&nbsp;ex.&nbsp;moms</td>
    <td style="border-bottom:none;"></td>		
	</tr>
	<tr>
    <td style="border-bottom:none;"></td>
    <td style="border-bottom:none;"></td>
    <td style="border-bottom:none;"></td>
    <td style="border-bottom:none;"></td>		
    <td style="border-bottom:none;"><input type="submit" value="Fortsätt" /></td>
	</tr>
</table>
</form>


<a href="javascript:;" onclick="mm_rapportera_show_help(29,<?= $keyhelp->getSizeX() ?>,<?= $keyhelp->getSizeY() ?>,'topleft')" title="Hj&auml;lp"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" class="mmFloatRight" /></a>



