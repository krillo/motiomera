<?php
//global $mmStatus;
//print_r($mmStatus);

require_once MM_ROOT_ABSPATH . "/classes/Mobject.php";
require_once MM_ROOT_ABSPATH . "/classes/UserException.php";
require_once MM_ROOT_ABSPATH . "/classes/Misc.php";
require_once MM_ROOT_ABSPATH . "/classes/Order.php";


$campaignCodes = Order::$campaignCodes;
$moms = Order::$moms;
Order::getMondays(15);
?>




<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">    
  jQuery(function($) {
    //do input validation
    var validator = $("#checkout").validate({
      errorClass: "invalid",
      validClass: "valid",
      rules: {
        "del-company": {
          required: true
        },
        "del-firstname": {
          required: true
        },
        "del-lastname": {
          required: true
        },
        "del-email": {
          required: true,
          email: true
        },
        "del-street1": {
          required: true
        },
        "del-zip": {
          required: true
        },
        "del-city": {
          required: true
        },
        "del-phone": {
          required: true
        }
      },  
      messages: {
        "del-company": {
          required: ''
        },
        "del-firstname": {
          required: ''
        },
        "del-lastname": {
          required: ''
        },
        "del-street1": {
          required: ''
        },
        "del-zip": {
          required: ''
        },
        "del-city": {
          required: ''
        },
        "del-phone": {
          required: ''
        },
        "del-email": {
          required: '', 
          email: ''
        }         
      }
    });

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
    function sum(){
      var sumWith, sumWithout, sumTotal, countWith, countWithout, freight, sumTotalFreight, sumTotalFreightMoms;
           
      //$('.hide-company').toggleClass("hidden");     
      $('.hide-company').removeClass("hidden");
      $('.hide-company').show("slow");
      $('#buy-company-top').addClass("full-width");
      
      $('.hide-private').addClass("hidden");
      
      //$('.hidden').removeClass("hidden");
      
      countWith = $('#nbr-with').val();
      sumWith =  countWith * <?php echo $campaignCodes['RE03']['pris']; ?>;
      $('#nbr-with-sum span').html(sumWith);
  
      if(sumWith == 0){
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
      sumWithout =  countWithout * <?php echo $campaignCodes['RE04']['pris']; ?>;
      $('#nbr-without-sum span').html(sumWithout);
        
      sumTotal = sumWith + sumWithout;  
      $('#nbr-sum-total span').html(sumTotal);
          
      freight = $('#freight span').html();
      sumTotalFreight = parseInt(freight) + parseInt(sumTotal);
      $('#nbr-sum-total-freight-nbr').html(sumTotalFreight);    
      $('#nbr-sum-total-freight span.nbr').html(sumTotalFreight);    
        
          
      sumTotalFreightMoms = sumTotalFreight * <?php echo $moms['percent']; ?>;
      sumTotalFreightMoms = Math.ceil(sumTotalFreightMoms);
      $('#nbr-sum-total-freight-moms span').html(sumTotalFreightMoms);


      $('#m_exmoms').val(sumTotal);          
      $('#m_total').val(sumTotalFreight);        
      $('#m_incmoms').val(sumTotalFreightMoms);
    }
        
        
        
    //toggle from company to private    
    $('#link-private').click(function(event) {
      event.preventDefault();
      $('.hide-company').addClass("hidden");
      $('#buy-company').addClass("hidden");
      $('#buy-company-top').removeClass("full-width");
      $('.hide-private').removeClass("hidden");
      $('#buy-private-top-left').addClass("full-width");
    });
        
    //toggle from private to company    
    $('#link-company').click(function(event) {
      event.preventDefault();
      $('.hide-company').removeClass("hidden");
      $('#buy-company').removeClass("hidden");
      $('#buy-company-top').addClass("full-width");
      $('.hide-private').addClass("hidden");
      $('#buy-private-top-left').removeClass("full-width");
    });
           
        
        
        
        
        
        
    //catch keyup where the companyname is submitted 
    $('#del-company').keypress(function() {
      
      $('#delivery').toggleClass("visible");
      $('#delivery').show("slow");
      $('#pay').toggleClass("visible");
      $('#pay').show("slow");
      $('#delivery-toggle').addClass("h3");      
      //$('#delivery-toggle').toggleClass("visible");
      $('#delivery-toggle').removeClass("hidden");            
      $('#delivery-toggle').show("slow");
    });
        
        
    $('#delivery-toggle').click(function(event) {
      event.preventDefault();
      if($('#delivery-address').hasClass("visible")){        
        $('#delivery-address').toggleClass("visible");
        $('#delivery-address').hide("slow");

      }else {
        $('#delivery-address').toggleClass("visible");
        $('#delivery-address').show("slow");
      }
    });

 
 
 
 
 
 
 
    /******************************
     * private
     ******************************/
    $('#short-radio').change(function() {
      sum_private(); 
   
    });
    $('#long-radio').change(function() {
      sum_private(); 
    });
    $('#short-check').change(function() {
      sum_private(); 
    });
    $('#long-check').change(function() {
      sum_private(); 
    });

    function sum_private(){
      alert("sum_private");
      
      radio  = $('input:radio[name=radio-priv]:checked').val();
      if(typeof radio != 'undefined'){  //one of the radios are checked
        shortRadio = <?php echo $campaignCodes['PRIV3']['pris']; ?>;
        longRadio = <?php echo $campaignCodes['PRIV12']['pris']; ?>;
     
        if(radio == <?php echo $campaignCodes['PRIV3']['pris']; ?>){          
          $('#long-check').attr('checked', false);
          longRadio = 0;
          $('#m_priv3').val(1);   
          $('#m_priv12').val(0);   
        }
        if(radio == <?php echo $campaignCodes['PRIV12']['pris']; ?>){            
          $('#short-check').attr('checked', false);
          shortRadio = 0;
          $('#m_priv12').val(1);
          $('#m_priv3').val(0);
        }
        shortCheck = $('#short-check:checked').val();
        longCheck = $('#long-check:checked').val();        
        if(typeof shortCheck == 'undefined'){
          shortCheck = 0;
        }
        if(typeof longCheck == 'undefined'){
          longCheck = 0;
        } 
        //alert('radio: ' + radio + ' shortCheck: ' + shortCheck+ ' longCheck: ' + longCheck);
        if(shortCheck != 0 || longCheck != 0){
          sumFreight = parseInt(<?php echo $campaignCodes['FRAKT02']['pris']; ?>);
          $('#m_frakt02').val(1);
          $('#m_steg01').val(1);          
        } else{
          sumFreight = 0;
          $('#m_frakt02').val(0);
          $('#m_steg01').val(0);
        }
       
        sumShort = parseInt(shortRadio) + parseInt(shortCheck);
        sumLong = parseInt(longRadio) + parseInt(longCheck);        
        sumTotal = sumShort + sumLong + sumFreight;
   
        $('#sum-short span').html(sumShort);
        $('#sum-long span').html(sumLong);
        $('#sum-freight span').html(sumFreight);
        $('#sum-total span').html(sumTotal);
        $('#priv-the-price').html(sumTotal);
     
   
       
        $('#m_total').val(sumTotal);        
        $('#m_freight').val(sumFreight);      
   
     
      } else { //radiobutton is undfined do nothing     
        //alert('apa');
      }
    }

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
  });
    
    
  function updateStartRadio(){
    jQuery("#startdatumRadio2").attr('checked', true); 
  }
</script>    





<form action="/actions/payson_foretag.php" method="post" id="checkout">
  <input type="hidden" name="type" value="foretag">
  <input type="hidden" name="m_exmoms"  id="m_exmoms" value=""> 
  <input type="hidden" name="m_freight" id="m_freight"  value="">         
  <input type="hidden" name="m_total"   id="m_total" value="">        
  <input type="hidden" name="m_incmoms" id="m_incmoms" value="">       





  <div id="buy-private-2" class="buy-container hide-private hidden" >
    <div id="buy-private-top-left"><a href="#" id="link-company" name="company" class="hide-company hidden">För företag från 169 kr?</a></div>
    <div class="buy-box-outer">
      <div class="buy-box margin-top">
        <div class="buy-heading">Adress</div>
        <ul class="buy-ul">
          <li><input type="text" name="del-company" id="del-company" class="" placeholder="Företagets namn*"/></li>
          <li><input type="text" name="del-co" id="del-co" class="" placeholder="c/o"/></li>
          <li><input type="text" name="del-street1" id="del-street1" placeholder="Adress*"/></li>
          <li><input type="text" name="del-street2" id="del-street2" placeholder=""/></li>
          <li><input type="text" name="del-zip" id="del-zip" placeholder="Postnummer*"/></li>
          <li><input type="text" name="del-city" id="del-city" placeholder="Ort*"/></li>
          <li><input type="text" name="del-country" id="del-country" class="" value="Sverige" placeholder=""/></li>
          <li><input type="text" name="del-firstname" id="del-firstname" class="" placeholder="Förnamn*"/></li>
          <li><input type="text" name="del-lastname" id="del-lastname" class="" placeholder="Efternamn*"/></li>
          <li><input type="email" name="del-email" id="del-email" class="" placeholder="E-post*"/></li>
          <li><input type="text" name="del-phone" id="del-phone" placeholder="Mobil/telefon*"/></li>
          <li><input type="text" name="refcode" id="refcode" class="" placeholder="Kostnadsställe/Refkod"/></li>  
        </ul>
      </div>
    </div>
  </div> 



  <div id="buy-company" class="buy-container">
    <div id="buy-company-top">FÖRETAGSPAKET <a href="#" id="link-private" name="privat" class="hide-company hidden">Privat deltagare från 79 kr?</a> <a href="/stegtavling" name="stegtavling" id="link-stegtavling">läs mer om vår stegtävling</a></div>
    <div id="buy-company-price">
      5 - 8 veckors stegtävling från 
      <div id="nbr-sum-total-freight">      
        <div><span id="nbr-sum-total-freight-nbr">169</span><span class="currency">kr ex moms</span></div>
      </div>    
    </div>
    <div id="buy-company-calc" class="buy-box-outer">
      <div id="buy-company-calc-text">Är du företagsledare eller personalansvarig? Anmäl dig och dina anställda  till Sveriges roligaste stegtävling för företag. </div>
      <div id="buy-company-calc-input" class="buy-box">

        <div class="buy-heading">Välj antal deltagare</div>
        <table class="buy-table">
          <tbody>
            <tr>
              <td><div id="nbr-with-text"><?php echo $campaignCodes['RE03']['text']; ?><br/><!--span style="color:black;text-decoration: line-through;">289 </span--><span><?php echo $campaignCodes['RE03']['pris']; ?> kr/person</span></div></td>
              <td><input type="text" name="RE03" id="nbr-with"/></td>
              <td><div id="nbr-with-sum"><span class="nbr">0</span></div></td>
              <td>kr ex moms</td>
            </tr>
            <tr>
              <td><div id="nbr-without-text"><?php echo $campaignCodes['RE04']['text']; ?><br/><!--span style="color:black;text-decoration: line-through;">169 </span--><span><?php echo $campaignCodes['RE04']['pris']; ?> kr/person</span></div></td>
              <td><input type="text" name="RE04" id="nbr-without" /></td>
              <td><div id="nbr-without-sum"><span class="nbr">0</span></div></td>
              <td>kr ex moms</td>
            </tr>
            <tr class="hide-company hidden">
              <td colspan="2" ><div id="freight-label">Frakt</div></td>
              <td><div id="freight"><span class="nbr">0</span></div></td>          
              <td><span id="freight-text"></span></td>          
            </tr>
            <tr class="hide-company hidden">
              <td></td>
              <td></td>
              <td class="line"><div id="nbr-sum-total-freight"><span class="nbr">0</span></div></td>
              <td class="line">kr ex moms</td>
            </tr>
            <tr class="hide-company hidden">
              <td></td>
              <td></td>
              <td><div id="nbr-sum-total-freight-moms"><span class="nbr"> 0</span></div></td>
              <td>kr inkl moms</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="buy-box hide-company hidden">
        <div class="buy-heading">Välj antal veckor för stegtävlingen</div>      
        <select name="veckor" id="veckor" style="float:left;">
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
        </select>
      </div>
      <div class="buy-box hide-company hidden">
        <div class="buy-heading">Välj ert startdatum (valfri måndag)</div>            
        <!--input name="startdatumRadio" id="startdatumRadio1" type="radio" value="2012-09-24" checked><label for="startdatumRadio1" id="startdatumRadio-label" style="font-size: 13px;display: inline;">Den stora hösttävlingen 24 september</label-->
        <div class="clear"></div>
        <input name="startdatumRadio" id="startdatumRadio2" type="radio" value="egetdatum" checked >
        <select name="startdatum" id="startdatum" onchange="updateStartRadio();">
          <?php echo Order::getMondays(20); ?>
        </select>
        <div class="clear"></div>
        <div id="early-info">Ring Kristian 0761-393855 om ni önskar tidigare datum.</div>    
      </div>
    </div>
    <div id="buy-contact" class="buy-container hide-company hidden">
      <img src="/img/kristian_80x90.png" alt="Kristian Erendi"  title="Kristian Erendi"/>
      <div id="bubble">
        Ladda ner en <a href="/presentation">presentation</a><br/>
        Eller för mer information kontakta Mig på:<br/>
        <a href="tel:+46761393855" id="buy-contact-tel">0761-393855</a><br/>
        <a href="mailto:kristian@motiomera.se" id="buy-contact-email">kristian@motiomera.se</a> 
      </div>    
    </div>    
  </div>


  <div id="buy-company-2" class="buy-container hide-company hidden" >
    <div class="buy-box-outer">
      <div class="buy-box margin-top">
        <div class="buy-heading">Adress</div>
        <ul class="buy-ul">
          <li><input type="text" name="del-company" id="del-company" class="" placeholder="Företagets namn*"/></li>
          <li><input type="text" name="del-co" id="del-co" class="" placeholder="c/o"/></li>
          <li><input type="text" name="del-street1" id="del-street1" placeholder="Adress*"/></li>
          <li><input type="text" name="del-street2" id="del-street2" placeholder=""/></li>
          <li><input type="text" name="del-zip" id="del-zip" placeholder="Postnummer*"/></li>
          <li><input type="text" name="del-city" id="del-city" placeholder="Ort*"/></li>
          <li><input type="text" name="del-country" id="del-country" class="" value="Sverige" placeholder=""/></li>
          <li><input type="text" name="del-firstname" id="del-firstname" class="" placeholder="Förnamn*"/></li>
          <li><input type="text" name="del-lastname" id="del-lastname" class="" placeholder="Efternamn*"/></li>
          <li><input type="email" name="del-email" id="del-email" class="" placeholder="E-post*"/></li>
          <li><input type="text" name="del-phone" id="del-phone" placeholder="Mobil/telefon*"/></li>
          <li><input type="text" name="refcode" id="refcode" class="" placeholder="Kostnadsställe/Refkod"/></li>  
        </ul>
      </div>
    </div>
    <div class="buy-box-outer">
      <div class="buy-box">
        <a href="" id="delivery-toggle" class=" buy-heading">Annan faktura-adress?</a>
        <ul id="delivery-address" class="buy-ul hidden">
          <li><input type="text" name="company" id="company" class="" placeholder="Företagets namn"/></li>
          <li><input type="text" name="co" id="co" placeholder="c/o"/></li>        
          <li><input type="text" name="street1" id="street1" placeholder="Adress"/></li>
          <li><input type="text" name="street2" id="del-street2" placeholder=""/></li>
          <li><input type="text" name="zip" id="zip" placeholder="Postnummer"/></li>
          <li><input type="text" name="city" id="city" placeholder="Ort"/></li>
          <li><input type="text" name="country" id="country" class="" value="Sverige" placeholder="Sverige"/></li>
          <li><input type="text" name="firstname" id="firstname" class="" placeholder="Kontakt förnamn"/></li>
          <li><input type="text" name="lastname" id="lastname" class="" placeholder="Kontakt efternamn"/></li>
          <li><input type="email" name="email" id="email" class="" placeholder="E-post"/></li>
          <li><input type="text" name="phone" id="phone" placeholder="Mobil/telefon"/></li>
        </ul>
      </div>
    </div>
  </div>
  <div id="buy-payment" class="buy-container hide-company hidden" >
    <div class="buy-heading">Betalning</div>
    <div id="buy-payment-buttons" >
      <input type="submit" id="payson" class="buy-payment-buttons" name="paytype" value="Betala med">     
      <input type="submit" id="faktura" class="buy-payment-buttons" name="paytype" value="Betala med faktura">
    </div>  
    <div id="buy-payment-options">Via Payson kan du betala med följande alternativ:</div>
    <div id="integrity">Genom att fortsätta betalningen godkänner jag <br/><a target="_blank" href="integritetspolicy.php">Motiomeras avtal samt integritetspolicy</a> och är över 18 år</div>
  </div>  


  <!-- private --> 
  <div id="buy-private" class="hide-private">
    <div id="buy-private-top">UTMANA DIG SJÄLV OCH DINA VÄNNER</div>
    <div id="buy-private-price">
      3 månaders MotioMera från 
      <div id="nbr-sum-total-freight">      
        <div><span id="priv-the-price">79</span><span class="currency">kr</span></div>
      </div>    
    </div>









    <div id="buy-company-calc" class="buy-box-outer">
      <div id="buy-company-calc-text">För dig som vill röra på dig och samtidigt ha kul! Det är enkelt, allt du behöver är en stegräknare.</div>
      <div id="buy-company-calc-input" class="buy-box">
        <div id="short">
          <input type="radio" id="short-radio" name="radio-priv" value="<?php echo $campaignCodes['PRIV3']['pris']; ?>" /><div id="short-text"><?php echo $campaignCodes['PRIV3']['text']; ?><span > <?php echo $campaignCodes['PRIV3']['pris']; ?> kr</span></div>
          <div class="clear"></div>
          <div id="" class="step-check"><input type="checkbox" id="short-check" name="short-check-step" value="<?php echo $campaignCodes['STEG01']['pris']; ?>" /><div id="short-text"><?php echo $campaignCodes['STEG01']['text']; ?><span> +<?php echo $campaignCodes['STEG01']['pris']; ?> kr</span></div></div>    
        </div>
        <div id="sum-short"><span class="nbr">0</span> kr</div>
        <div class="clear"></div>
        <div id="long">
          <input type="radio" id="long-radio" name="radio-priv" value="<?php echo $campaignCodes['PRIV12']['pris']; ?>" /><div id="long-text"><?php echo $campaignCodes['PRIV12']['text']; ?><span > <?php echo $campaignCodes['PRIV12']['pris']; ?> kr</span></div>
          <div class="clear"></div>
          <div id=""class="step-check"><input type="checkbox" id="long-check" name="long-check-step" value="<?php echo $campaignCodes['STEG01']['pris']; ?>" /><div id="long-text"><?php echo $campaignCodes['STEG01']['text']; ?><span> +<?php echo $campaignCodes['STEG01']['pris']; ?> kr</span></div></div>    
        </div>
        <div id="sum-long"><span class="nbr">0</span> kr</div>
        <div class="clear"></div>
        <div id="freight">Frakt (<?php echo $campaignCodes['FRAKT02']['pris']; ?> kr)</div><div id="sum-freight"><span class="nbr">0</span> kr</div>
        <div class="clear"></div>
      </div>
      <div id="sum-total"><span class="nbr">0</span> kr</div>
      <div class="clear"></div>






    </div>
  </div>


</div>


</form>