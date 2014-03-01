<?php
$keyhelp = Help::loadById(29);
$urlHandler = new UrlHandler();

//$campaignCodes = Order::getCampaignCodes("foretag");
$campaignCodes = Order::$campaignCodes;
$moms = Order::$moms;
?>	
<p>
  Här kan du anmäla fler deltagare till tävlingen. Vi skickar ut nya stegräknare och deltagarbrev så fort vi hinner. <b>Glöm inte att lägga in de nya deltagarna i rätt lag</b> efter att de har aktiverat sina MotioMera-konton.
</p>
<br/>


<form action="/actions/payson_privat.php" method="post" id="checkout" novalidate="novalidate">
  <input type="hidden" name="" id="m_r3_price" value="289">
  <input type="hidden" name="" id="m_r4_price" value="169">
  <input type="hidden" name="" id="m_frakt00_price" value="0">
  <input type="hidden" name="" id="m_frakt00_extra" value=" kr - fraktfritt, instruktioner mailas till dig.">
  <input type="hidden" name="" id="m_frakt01_price" value="80">
  <input type="hidden" name="" id="m_frakt01_extra" value=" kr ex. moms.">
  <input type="hidden" name="" id="m_frakt02_price" value="20">  
  <input type="hidden" name="" id="m_priv3_price" value="98">
  <input type="hidden" name="" id="m_priv12_price" value="249">
  <input type="hidden" name="" id="m_moms_percent" value="1.25">
  <input type="hidden" name="type" id="type" value="private">
  <input type="hidden" name="m_exmoms" id="m_exmoms" value=""> 
  <input type="hidden" name="m_freight" id="m_freight" value="">         
  <input type="hidden" name="m_total" id="m_total" value="">        
  <input type="hidden" name="m_incmoms" id="m_incmoms" value="">       
  <input type="hidden" name="m_priv3" id="m_priv3" value="">        
  <input type="hidden" name="m_priv12" id="m_priv12" value="">        
  <input type="hidden" name="m_steg01" id="m_steg01" value="">        
  <input type="hidden" name="m_frakt02" id="m_frakt02" value="">
  <input type="hidden" name="private-type" id="private-type" value="medlem_extend">

  <!-- private --> 
  <div id="buy"></div>
  <div id="buy-private" class="">
    <span id="priv-the-price" class="hide"></span>




    <div id="buy-copmany" class="buy-container">
      <div class="buy-box-outer">
        <div id="buy-company-calc-input" class="buy-box">
          <div class="buy-heading">Välj antal deltagare</div>
          <table class="buy-table">
            <tbody>
              <tr>
                <td><div id="nbr-with-text">Med stegräknare<br><!--span style="color:black;text-decoration: line-through;">289 </span--><span>289 kr/person</span></div></td>
                <td><input type="text" name="RE03" id="nbr-with" class="valid"></td>
                <td><div id="nbr-with-sum"><span class="nbr">289</span></div></td>
                <td class="kr">kr</td>
              </tr>
              <tr>
                <td><div id="nbr-without-text">Utan stegräknare<br><!--span style="color:black;text-decoration: line-through;">169 </span--><span>169 kr/person</span></div></td>
                <td><input type="text" name="RE04" id="nbr-without"></td>
                <td><div id="nbr-without-sum"><span class="nbr">0</span></div></td>
                <td class="kr">kr</td>
              </tr>
              <tr class="hide-company">
                <td colspan="2"><div id="freight-label">Frakt</div></td>
                <td><div id="freight"><span class="nbr">80</span></div></td>          
                <td><span id="freight-text"> kr</span></td>          
              </tr>
              <tr class="hide-company">
                <td></td>
                <td></td>
                <td class="line"><div id="nbr-sum-total-freight"><span class="nbr">369</span></div></td>
                <td class="line kr">kr ex<br/>moms</td>
              </tr>
              <tr class="hide-company tr-extra-margin">
                <td></td>
                <td></td>
                <td ><div id="nbr-sum-total-freight-moms"><span class="nbr">462</span></div></td>
                <td class="kr total-freight-moms">kr inkl<br/>moms</td>
              </tr>
            </tbody>
          </table>
        </div>    
      </div>
    </div>    



    <div id="buy-company-2" class="buy-container">
      <div class="buy-box-outer">
        <div class="buy-box">
          <input type="submit" id="faktura" class="buy-payment-buttons" name="paytype" value="Betala med faktura">
          <div class="mmClearBoth"></div>
          <div id="integrity">Genom att fortsätta betalningen godkänner jag <a target="_blank" href="/integritetspolicy/">Motiomeras avtal och integritetspolicy</a> samt är över 18 år</div>
        </div>
      </div>
    </div>    



    <div id="buy-contact" class="buy-container">
      <div class="buy-box-outer">
        <img src="/img/kristian_80x90.png" alt="Kristian Erendi" title="Kristian Erendi">
        <div id="bubble">
          Har du frågor så kontakta <br/>Mig på:<br>
          <a href="tel:+46761393855" id="buy-contact-tel">0761-393855</a><br>
          <a href="mailto:kristian@motiomera.se" id="buy-contact-email">kristian@motiomera.se</a> 
        </div>
      </div>
    </div>   



  </div>
  <div style="clear:left;"></div>
</form>