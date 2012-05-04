<?php
$keyhelp = Help::loadById(29);
if(Security::authorized(ADMIN)) {
?>
<span class="mmRed hide" id="mmForetagsnyckelError"></span>
<p>
<strong class="mmAdminColor">Lägg till nycklar till <?=$foretag->getNamn()?>:</strong><br />

<form>
  <input type="hidden" name="table" value="newkeys" />
</form>


<form method="get" action="/actions/save.php" name="keyform" id="keyform">
 
<input type="hidden" name="table" value="newkeys" />
<input type="hidden" name="foretagsid" value="<?=$foretag->getId();?>" />
<input type="hidden" name="orderid" value="1" />
<select name="numkeys">
<option value="0">Välj antal</option>
<?
$i=1;
for(;$i<=10;$i++) { ?>

<option value="<?=$i?>">
<? 
echo $i." nyck";
echo ($i>1?"lar":"el");
?>
</option>
<? } ?>
</select>
&nbsp;<input type="submit" value="Lägg till nya nycklar" />
</form>

<br /><br />

</p><hr size="1" /><br />
<? } ?>

<a href="javascript:;" onclick="mm_rapportera_show_help(29,<?= $keyhelp->getSizeX() ?>,<?= $keyhelp->getSizeY() ?>,'topleft')" title="Hj&auml;lp"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" class="mmFloatRight" /></a>
<p>
Varje deltagare har i stegräknarpaketet fått en företagsnyckel som de ska använda för att registrera sig på MotioMera. 
Nedan kan du se de företagsnycklar som ännu inte registrerats på sajten. 
Om någon av deltagarna har fått förhinder kan alltså nycklarna användas av någon annan om så önskas.
</p>

<?php 

$nycklar = $foretag->listNycklar(true);
?>
<h2>Nycklar som inte har använts ännu (<?=count($nycklar); ?> st):</h2>
<?

if(count($nycklar) > 0){ ?>
<p>
	<?php foreach($nycklar as $nyckel){ ?>
	
		<?= $nyckel["nyckel"] ?><br />
	
	<?php } ?>
</p>
<?php }else{ ?>
<p>
	Inga lediga nycklar.
</p>
<?php } ?>
<?php

/*
<h3>Beställ nya nycklar</h3>
<p>
	<form action="<?= $urlHandler->getUrl("Foretag", URL_GENERATE_KEYS) ?>" method="post">
		<select name="antal">
			<?php for($i = 1; $i < 101; $i++){ ?>
			<option value="<?= $i ?>"><?=$i?></option>
			<?php }?>
		</select>
		st
		<input type="submit" value="Beställ" />
	</form>
</p>

*/

?>