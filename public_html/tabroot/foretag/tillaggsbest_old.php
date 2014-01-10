<?php
$keyhelp = Help::loadById(29);
$urlHandler = new UrlHandler();

//$campaignCodes = Order::getCampaignCodes("foretag");
$campaignCodes = Order::$campaignCodes;
$moms = Order::$moms;
?>	

<h3>Tilläggsbeställning</h3>
<p>
  Här kan du anmäla fler deltagare till tävlingen. Vi skickar ut nya stegräknare och deltagarbrev så fort vi hinner. <b>Glöm inte att lägga in de nya deltagarna i rätt lag</b> efter att de har aktiverat sina MotioMera-konton.
</p>
<br/>



<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">
  $(function() {

    //catch keyup where the ammount is submitted 
    $('#nbr-with').keyup(function() {
      sum();
    });
    $('#nbr-without').keyup(function() {
      sum();
    });

    //check also when leaving field
    $('#nbr-with').blur(function() {
      sum();
    });
    $('#nbr-without').keyup(function() {
      sum();
    });


    //sum with and without stepcounter, add freight and moms
    function sum() {
      var sumWith, sumWithout, sumTotal, countWith, countWithout, freight, sumTotalFreight, sumTotalFreightMoms;
      countWith = $('#nbr-with').val();
      sumWith = countWith * <?php echo $campaignCodes['RE03']['pris']; ?>;
      $('#nbr-with-sum span').html(sumWith);

      if (sumWith == 0) {
        //FRAKT00 is 0 kr
        $('#freight span').html(<?php echo $campaignCodes['FRAKT00']['pris']; ?>);
        $('#freight-text').html('<?php echo $campaignCodes['FRAKT00']['extra']; ?>');
        $('#m_freight').val('FRAKT00');
      } else {
        $('#freight span').html(<?php echo $campaignCodes['FRAKT01']['pris']; ?>);
        $('#freight-text').html('<?php echo $campaignCodes['FRAKT01']['extra']; ?>');
        $('#m_freight').val('FRAKT01');
      }

      countWithout = $('#nbr-without').val();
      sumWithout = countWithout * <?php echo $campaignCodes['RE04']['pris']; ?>;
      $('#nbr-without-sum span').html(sumWithout);

      sumTotal = sumWith + sumWithout;
      $('#nbr-sum-total span').html(sumTotal);

      freight = $('#freight span').html();
      sumTotalFreight = parseInt(freight) + parseInt(sumTotal);
      $('#nbr-sum-total-freight span').html(sumTotalFreight);


      sumTotalFreightMoms = sumTotalFreight * <?php echo $moms['percent']; ?>;
      sumTotalFreightMoms = Math.ceil(sumTotalFreightMoms);
      $('#nbr-sum-total-freight-moms span').html(sumTotalFreightMoms);

      $('#m_exmoms').val(sumTotal);

      $('#m_total').val(sumTotalFreight);
      $('#m_incmoms').val(sumTotalFreightMoms);
    }


    $('#refcode-toggle').click(function(event) {
      event.preventDefault();
      if ($('#refcode').hasClass("visible")) {
        $('#refcode').toggleClass("visible");
        $('#refcode').hide("slow");
      } else {
        $('#refcode').toggleClass("visible");
        $('#refcode').show("slow");
      }
    });


  });
</script>   





<form action="/actions/payson_foretag_tillagg.php" method="get" id="checkout">
  <input type="hidden" name="typ" value="foretag_tillagg">
  <input type="hidden" name="fid" value="<?= $foretag->getId() ?>">
  <input type="hidden" name="m_exmoms"  id="m_exmoms" value=""> 
  <input type="hidden" name="m_freight" id="m_freight"  value="">         
  <input type="hidden" name="m_total"   id="m_total" value="">        
  <input type="hidden" name="m_incmoms" id="m_incmoms" value="">


  <style>

    #checkout-ul h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
    #checkout-ul .h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
    #checkout-ul li{margin-top: 5px;}

    #checkout-ul label{width:130px;font-size: 14px;float:left}
    #checkout-ul{list-style: none;margin-left: 0;padding-left: 0;font-size: 13px;}
    #checkout-ul input{height:18px;font-size: 14px;}
    #checkout-ul a{text-decoration: underline;}
    #calc{border-bottom: solid black 1px;}

    #nbr-with-text, #nbr-without-text{width:120px;margin-bottom: 15px;}
    #nbr-with-text span, #nbr-without-text span{color:#427F10;}

    #nbr-with, #nbr-without{width:40px;height:20px;font-size: 18px;}

    #nbr-with-sum, #nbr-without-sum {margin-left: 30px;}
    .nbr{font-size: 18px;font-weight: bold;}
    #freight-label{display:block;width:197px;float:left;}
    #freight{float:left;}
    #nbr-sum-total-freight{margin-left: 197px;float:left;}
    #nbr-sum-total-freight-moms{margin-left: 65px;float:left;color:#427F10;}

    #checkout-ul #startdatumRadio-label{font-size: 13px;display: inline;width:300px;}
    #startdatumRadio1, #startdatumRadio2{width:40px;float:left;} 
    #startdatum{width:175px;height:22px;font-size: 12px;}
    #early-info{margin-left: 20px; margin-top: 10px;}

    #payer li{margin-bottom: 2px; margin-top: 0;display:block;float:left;}
    #payer li div{width:130px;font-size: 14px;float:left}
    #payer li input{width:180px;float:left;}
    #payer #payer-label{margin-bottom: 10px;display:block;margin-top: 40px;font-size:18px;}
    #checkout-ul #refcode-row{margin-top: 20px;}

    #refcode-div{margin-top: 20px;margin-bottom: 35px;}
    #refcode-toggle{font-size: 13px;margin-right: 20px;text-decoration: underline;}

  </style>




  <ul id="checkout-ul">
    <li id="calc">
      <div class="clear"></div>
      <div id="nbr-with-text"><?php echo $campaignCodes['RE03']['text']; ?><span > <?php echo $campaignCodes['RE03']['pris']; ?> kr / person</span></div>
      <input type="text" name="RE03" id="nbr-with"/>
      <div id="nbr-with-sum"><span class="nbr">0</span> kr ex moms</div>
      <div class="clear"></div>
      <div id="nbr-without-text"><?php echo $campaignCodes['RE04']['text']; ?><span > <?php echo $campaignCodes['RE04']['pris']; ?> kr / person</span></div>
      <input type="text" name="RE04" id="nbr-without" />       
      <div id="nbr-without-sum"><span class="nbr">0</span> kr ex moms</div>
      <div class="clear"></div>
      <div id="freight-label">Frakt</div><div id="freight"><span class="nbr">0</span><span id="freight-text"> kr ex moms</span></div>
      <div class="clear"></div>
    </li>
    <li>
      <div id="nbr-sum-total-freight"><span class="nbr">0</span> kr ex moms</div>
      <div id="nbr-sum-total-freight-moms"><span class="nbr"> 0</span> kr inkl. moms </div>
    </li>
  </ul>  

  <div class="clear"></div>  
  <div id="refcode-div"><label><a href="" id="refcode-toggle">Kostnadsställe/Refkod/Id mm?</a></label><input type="text" name="refcode" id="refcode" class="hidden"/></div>
  <div class="clear"></div>


  <style>
    #pay {font-size: 14px;}
    #pay div{float:left;}
    #pay input{font-size: 15px;width:200px;}  
    #or{font-size: 15px; margin:0 30px 0 30px;}
    #payalt{color:#494949;font-size:11px;margin:5px 0 0 10PX;}
    #payalt ul{padding-left: 15px;}
  </style>


  <div id="pay">
    <div >      
      <input type="submit" value="Faktura" name="paytype" id="faktura">
    </div>  
  </div>
</form>







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
    <div id="buy-private-top">Välj hur du vill förlänga ditt medlemskap</div>
    <span id="priv-the-price" class="hide"></span>



    
    <div id="buy-private-2" class="buy-container">
      <div class="buy-box-outer">
        <div id="buy-company-calc-input" class="buy-box">
          <div class="buy-heading">Välj antal deltagare</div>
          <table class="buy-table">
            <tbody>
              <tr>
                <td><div id="nbr-with-text">Med stegräknare<br><!--span style="color:black;text-decoration: line-through;">289 </span--><span>289 kr/person</span></div></td>
                <td><input type="text" name="RE03" id="nbr-with" class="valid"></td>
                <td><div id="nbr-with-sum"><span class="nbr">289</span></div></td>
                <td class="kr">kr ex moms</td>
              </tr>
              <tr>
                <td><div id="nbr-without-text">Utan stegräknare<br><!--span style="color:black;text-decoration: line-through;">169 </span--><span>169 kr/person</span></div></td>
                <td><input type="text" name="RE04" id="nbr-without"></td>
                <td><div id="nbr-without-sum"><span class="nbr">0</span></div></td>
                <td class="kr">kr ex moms</td>
              </tr>
              <tr class="hide-company">
                <td colspan="2"><div id="freight-label">Frakt</div></td>
                <td><div id="freight"><span class="nbr">80</span></div></td>          
                <td><span id="freight-text"> kr ex. moms.</span></td>          
              </tr>
              <tr class="hide-company">
                <td></td>
                <td></td>
                <td class="line"><div id="nbr-sum-total-freight"><span class="nbr">369</span></div></td>
                <td class="line kr">kr ex moms</td>
              </tr>
              <tr class="hide-company">
                <td></td>
                <td></td>
                <td><div id="nbr-sum-total-freight-moms"><span class="nbr">462</span></div></td>
                <td class="kr">kr inkl moms</td>
              </tr>
            </tbody>
          </table>
        </div>    
      </div>
    </div>    
    
    
    
    

    <div id="buy-private-2" class="buy-container">
      <div class="buy-box-outer">
        <div class="buy-box">
          <div class="buy-heading">Om dina adressuppgifter är fel eller saknas så var vänlig och rätta till det.</div>
          <ul class="buy-ul">
            <li><label class="buy-ul-label">E-post *</label><input type="text" name="email1" id="email" class="required" value="krillo@gmail.com"></li>
            <li><label class="buy-ul-label">Förnamn *</label><input type="text" name="firstname" id="firstname" class="required" value="kapten"></li>
            <li><label class="buy-ul-label">Efternamn *</label><input type="text" name="lastname" id="lastname" class="required" value="krillo"></li>
            <li><label class="buy-ul-label">c/o</label><input type="text" name="co" id="co" class="" value=""></li>
            <li><label class="buy-ul-label">Telefon *</label><input type="text" name="phone" id="phone" class="required" value=""></li>
            <li><label class="buy-ul-label">Adress *</label><input type="text" name="street1" id="street1" class="required" value=""></li>
            <li><label class="buy-ul-label">Adress</label><input type="text" name="street2" id="street2"></li>
            <li><label class="buy-ul-label">Postnummer *</label><input type="text" name="zip" id="zip" class="" value=""></li>
            <li><label class="buy-ul-label">Ort *</label><input type="text" name="city" id="city" class="" value=""></li>
            <li><label class="buy-ul-label">Land *</label><input type="text" name="country" id="country" class="" value=""></li>  
          </ul>
        </div>
      </div>
    </div>

    <div class="buy-container">         
      <div class="buy-box buy-box-extra-width">
        <table class="buy-table"> 
          <tbody>
            <tr>
              <td><input type="radio" id="short-radio" name="radio-priv" value="98"></td>
              <td colspan="2" style="width:100%;"><div id="short-text">3 månader MotioMera<span> 98 kr</span></div></td>
              <td rowspan="2" style="width:100px;"><div id="sum-short" class="nbr">0</div></td>
              <td rowspan="2" style="width:100px;" class="kr">kr</td>
            </tr>
            <tr>
              <td></td>
              <td style="width:25px;"><div id="" class="step-check"><input type="checkbox" id="short-check" name="short-check-step" value="129"></div></td>
              <td><div id="short-text" style="margin-bottom:20px;">med stegräknare <span> +129 kr</span></div></td>
            </tr>
            <tr>            
              <td><input type="radio" id="long-radio" name="radio-priv" value="249"></td>
              <td colspan="2" style="width:100%;"><div id="long-text">12 månader MotioMera<span> 249 kr</span></div></td>
              <td rowspan="2"><div id="sum-long" class="nbr">0</div></td>
              <td rowspan="2" style="width:100px;" class="kr">kr</td>
            </tr>
            <tr>
              <td></td>
              <td><div id="" class="step-check"><input type="checkbox" id="long-check" name="long-check-step" value="129"></div></td>
              <td><div id="long-text" style="margin-bottom:20px;">med stegräknare <span> +129 kr</span></div></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="2" class="kr"><div id="freight_private">Frakt (20 kr)</div></td>
              <td><div id="sum-freight" class="nbr">0</div></td>
              <td class="kr">kr</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
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
      </div>  
      <div class="buy-box margin-top">
        <div id="buy-payment-options">Via Payson kan du betala med följande:</div>
        <div id="integrity">Genom att fortsätta betalningen bekräftar jag att jag är över 18 år och godkänner <br><a target="_blank" href="http://mm.dev/integritetspolicy/">Motiomeras avtal och integritetspolicy</a></div>
      </div>
    </div>
  </div>
  <div style="clear:left;"></div>
</form>