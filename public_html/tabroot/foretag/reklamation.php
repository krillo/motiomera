<?php
$keyhelp = Help::loadById(29);
$urlHandler = new UrlHandler();

$campaignCodes = Order::getCampaignCodes("foretag");
$fid = $foretag->getId();


?>  
<br/>
<h3>Har ni haft otur och fått en eller flera stegräknare som inte fungerar som de ska?</h3>
<p>
Kontrollera först att placeringen av stegräknaren varit korrekt. Idealiska placeringen är i byxlinning eller livrem vid höftbenet. För bästa funktion ska stegräknaren fästas helt upprätt, dvs den ska inte luta.
</p>
<p>
Om problemet ändå inte avhjälps så ersätter vi den defekta stegräknaren med en ny. Beställ en ny nedan och skicka tillbaka den defekta stegräknaren till oss på denna adress. Märk kuvertet "stegräknare":
</p>

<p>
Motiomera AB<br/>
c/o Kristian Erendi<br/>
Finjagatan 6<br/>
252 51 Helsingborg<br/> 
</p> 
<br/>
    
<form action="<?= $urlHandler->getUrl(Foretag, URL_RECLAMATION) ?>" method="post">
<input type="hidden" name="fid" value="<?= $fid?>">
<input type="hidden" name="typ" value="foretag_reklamation">
<select name="nbr">
  <option>Välj antal</option>
  <?php  
    for ($i=1;$i<31;$i++){
      print("<option value=\"$i\">$i</option>");
    }
  ?>
</select>
<input type="submit" name="send" value="Skicka efter nya stegräknare">
</form>


  
<?php 
$reclArray = Reclamation::listByForetag($fid);
if( !empty($reclArray) ){ ?>

<br/>
<br/>
<h3>Reklamationer</h3>
<table class="sortable sorted">
<tr>
<th class="header">Datum</th>
<th class="header">Antal</th>
</tr>	
	
	
<?php foreach ($reclArray as $key => $value){ ?>
<tr >
<td class="mmList1"><?= $value[date]?></td>
<td class="mmList1"><?= $value[count]?></td>
</tr>
  
<?php }}?>
</table> 






