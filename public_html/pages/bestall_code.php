<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
$campaignCodes = Order::$campaignCodes;
$moms = Order::$moms;
$email = '';
$fname = '';
$lname = '';
!empty($_REQUEST['mmForetagsnyckel']) ? $nyckel = $_REQUEST['mmForetagsnyckel'] : $nyckel = '';
!empty($_REQUEST['buy']) ? $buy = $_REQUEST['buy'] : $buy = 'private';
$user = Medlem::getInloggad();
if (!empty($user)) {
  $email = $user->getEpost();
  $fname = $user->getFNamn();
  $lname = $user->getENamn();
  $co = $user->getCo();
  $address = $user->getENamn();
  $zip = $user->getZip();
  $city = $user->getCity();
  $phone = $user->getPhone();
  $country = $user->getCountry();
//[address:protected] => fgatan 3 [co:protected] => [zip:protected] => 252 23 [city:protected] => helsingborg [phone:protected] => 46761393833 [country:protected] => Sverige     
}
?>





<style>
  #key{ background: none repeat scroll 0 0 #EAF4E2;padding: 4px;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        -khtml-border-radius: 10px;
        border-radius: 10px;border: #DFEED4 1px solid;
  }
  #key input{font-size: 15px;height: 25px;width: 100px;margin-right: 15px;}
  #key ul{list-style: none;margin-left: 0;padding-left: 0;font-size: 13px;}
  #key li label{margin-left: 15px;margin-right: 10px;padding-left: 0;font-size: 16px;}
</style>


<form action="/actions/medlem_foretagsnyckel.php" method="post" id="key">
  <input type="hidden" name="type" value="foretagsnyckel">  
  <ul id="">
    <li><label for="foretagsnyckel">Om du har du en företagsnyckel?</label>
      <input id="mmForetagsnyckel" type="text" name="mmForetagsnyckel" value="<?php echo $nyckel; ?>" onfocus="getById('mmFNyckelError').style.display = 'none';" onblur="mm_ajaxValidera('mmForetagsnyckelError', 'foretagsnyckel', this.value);"  />
      <span class="mmFormError mmRed" id="mmForetagsnyckelError">Ogiltig företagsnyckel</span>
      <input id="extend" name="extend" type="hidden" value="true">
      <input id="" type="submit" value="ok">
    </li>
  </ul>
</form>  





<div style="clear:both;"></div>
<br>
<hr>
<br>



<!--script src="/js/jquery.validate.min.js" type="text/javascript"></script-->


<form action="/actions/wp_buy.php" method="post" id="checkout">
  <input type="hidden" name=""      id="m_r3_price" value="<?php echo $campaignCodes['RE03']['pris']; ?>">
  <input type="hidden" name=""      id="m_r4_price" value="<?php echo $campaignCodes['RE04']['pris']; ?>">
  <input type="hidden" name=""      id="m_frakt00_price" value="<?php echo $campaignCodes['FRAKT00']['pris']; ?>">
  <input type="hidden" name=""      id="m_frakt00_extra" value="<?php echo $campaignCodes['FRAKT00']['extra']; ?>">
  <input type="hidden" name=""      id="m_frakt01_price" value="<?php echo $campaignCodes['FRAKT01']['pris']; ?>">
  <input type="hidden" name=""      id="m_frakt01_extra" value="<?php echo $campaignCodes['FRAKT01']['extra']; ?>">
  <input type="hidden" name=""      id="m_frakt02_price" value="<?php echo $campaignCodes['FRAKT02']['pris']; ?>">  
  <input type="hidden" name=""      id="m_priv3_price" value="<?php echo $campaignCodes['PRIV3']['pris']; ?>">
  <input type="hidden" name=""      id="m_priv12_price" value="<?php echo $campaignCodes['PRIV12']['pris']; ?>">
  <input type="hidden" name=""      id="m_moms_percent" value="<?php echo $moms['percent']; ?>">
  <input type="hidden" name="type"      id="type" value="<?php echo $buy; ?>">
  <input type="hidden" name="m_exmoms"  id="m_exmoms" value=""> 
  <input type="hidden" name="m_freight" id="m_freight"  value="">         
  <input type="hidden" name="m_total"   id="m_total" value="">        
  <input type="hidden" name="m_incmoms" id="m_incmoms" value="">       
  <input type="hidden" name="m_priv3"   id="m_priv3" value="">        
  <input type="hidden" name="m_priv12"  id="m_priv12" value="">        
  <input type="hidden" name="m_steg01"  id="m_steg01" value="">        
  <input type="hidden" name="m_frakt02" id="m_frakt02" value="">  




  <!-- private --> 
  <div id="buy"></div>



  <div id="buy-private" class="">
    <div id="buy-private-top">Välj hur du vill förlänga ditt medlemskap</div>
    <span id="priv-the-price" class="hide">79</span>



    <div id="buy-private-2" class="buy-container" >
      <div class="buy-box-outer">
        <div class="buy-box margin-top">
          <div class="buy-heading">Om dina adressuppgifter är fel eller saknas så var vänlig och rätta till det.</div>
          <ul class="buy-ul">
            <li><label class="buy-ul-label">Förnamn *</label><input type="text" name="firstname" id="firstname" class="required"  value="<?php echo $fname; ?>" /></li>
            <li><label class="buy-ul-label">Efternamn *</label><input type="text" name="lastname" id="lastname" class="required"  value="<?php echo $lname; ?>" /></li>
            <li><label class="buy-ul-label">c/o</label><input type="text" name="co" id="co" class=""  value="<?php echo $co; ?>" /></li>
            <li><label class="buy-ul-label">Telefon *</label><input type="text" name="phone" id="phone"  class="required"  value="<?php echo $phone; ?>" /></li>
            <li><label class="buy-ul-label">Adress *</label><input type="text" name="street1" id="street1" class="required"  value="<?php echo $address; ?>" /></li>
            <li><label class="buy-ul-label">Adress</label><input type="text" name="street2" id="street2"   /></li>
            <li><label class="buy-ul-label">Postnummer *</label><input type="text" name="zip" id="zip" class=""  value="<?php echo $zip; ?>" /></li>
            <li><label class="buy-ul-label">Ort *</label><input type="text" name="city" id="city" class=""  value="<?php echo $city; ?>" /></li>
            <li><label class="buy-ul-label">Land *</label><input type="text" name="country" id="country" class="" value="<?php echo $country; ?>" /></li>
            <!--li><label class="buy-ul-label">&nbsp;</label><input type="submit" id="payson" class="buy-payment-buttons" name="paytype" value="Betala med"></li-->     
          </ul>
        </div>
      </div>
    </div>





    <div class="buy-container">         
      <div  class="buy-box">
        <table class="buy-table"> 
          <tbody >
            <tr>
              <td><input type="radio" id="short-radio" name="radio-priv" value="<?php echo $campaignCodes['PRIV3']['pris']; ?>" /></td>
              <td colspan="2"><div id="short-text"><?php echo $campaignCodes['PRIV3']['text']; ?><span > <?php echo $campaignCodes['PRIV3']['pris']; ?> kr</span></div></td>
              <td rowspan="2" style="width:100px;"><div id="sum-short" class="nbr">0</div></td>
              <td rowspan="2" style="width:100px;" class="kr">kr</td>
            </tr>
            <tr>
              <td></td>
              <td style="width:25px;"><div id="" class="step-check"><input type="checkbox" id="short-check" name="short-check-step" value="<?php echo $campaignCodes['STEG01']['pris']; ?>" /></td>
              <td><div id="short-text" style="margin-bottom:20px;"><?php echo $campaignCodes['STEG01']['text']; ?><span> +<?php echo $campaignCodes['STEG01']['pris']; ?> kr</span></div></td>
            </tr>
            <tr>            
              <td><input type="radio" id="long-radio" name="radio-priv" value="<?php echo $campaignCodes['PRIV12']['pris']; ?>" /></td>
              <td colspan="2"><div id="long-text"><?php echo $campaignCodes['PRIV12']['text']; ?><span > <?php echo $campaignCodes['PRIV12']['pris']; ?> kr</span></div></td>
              <td rowspan="2"><div id="sum-long" class="nbr">0</span></td>
              <td rowspan="2" style="width:100px;" class="kr">kr</td>
            </tr>
            <tr>
              <td></td>
              <td><div id=""class="step-check"><input type="checkbox" id="long-check" name="long-check-step" value="<?php echo $campaignCodes['STEG01']['pris']; ?>" /></td>
              <td><div id="long-text" style="margin-bottom:20px;"><?php echo $campaignCodes['STEG01']['text']; ?><span> +<?php echo $campaignCodes['STEG01']['pris']; ?> kr</span></div></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="kr"><div id="freight_private">Frakt (<?php echo $campaignCodes['FRAKT02']['pris']; ?> kr)</div></td>
              <td><div id="sum-freight"class="nbr">0</div></td>
              <td class="kr">kr</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td ></td>
              <td class="line"><div id="sum-total" class="nbr">0</div></td>
              <td class="line kr"> kr</td>
            </tr>
          </tbody>
        </table>      
      </div> 
    </div>


    <div id="buy-payment" class="buy-container">
      <div id="buy-payment-buttons">
        <input type="submit" id="payson" class="buy-payment-buttons" name="paytype" value="Betala med">     
        <!--input type="submit" id="faktura" class="buy-payment-buttons" name="paytype" value="Betala med faktura" /-->
      </div>  
      <div class="buy-box">
        <div id="buy-payment-options">Via Payson kan du betala med följande alternativ:</div>
        <div id="integrity">Genom att fortsätta betalningen godkänner jag <br><a target="_blank" href="http://mm.dev/integritetspolicy/">Motiomeras avtal och integritetspolicy</a> samt är över 18 år</div>
      </div>
    </div>
  </div>




  <div style="clear:left;"></div>




</div>
</form>
