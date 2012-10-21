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
           
      $('.hide-calc').removeClass("hidden");
      
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
      alert("apa");
      if($('#delivery-address').hasClass("visible")){        
        $('#delivery-address').toggleClass("visible");
        $('#delivery-address').hide("slow");

      }else {
        $('#delivery-address').toggleClass("visible");
        $('#delivery-address').show("slow");
        /*        
        $('#firstname').val($('#del-firstname').val());
        $('#lastname').val($('#del-lastname').val());
        $('#email').val($('#del-email').val());
        $('#phone').val($('#del-phone').val()); */
      }
    });

    $('#address-toggle').click(function(event) {
      event.preventDefault(); 
      if($('#extra-address').hasClass("visible")){
        $('#extra-address').toggleClass("visible");
        $('#extra-address').hide("slow");
      }else {
        $('#extra-address').toggleClass("visible");
        $('#extra-address').show("slow");
      }       
    });
        
        
    $('#discount-toggle').click(function(event) {
      event.preventDefault(); 
      if($('#discount').hasClass("visible")){
        $('#discount').toggleClass("visible");
        $('#discount').hide("slow");
      }else {
        $('#discount').toggleClass("visible");
        $('#discount').show("slow");
      }       
    });        

    $('#refcode-toggle').click(function(event) {
      event.preventDefault(); 
      if($('#refcode').hasClass("visible")){
        $('#refcode').toggleClass("visible");
        $('#refcode').hide("slow");
      }else {
        $('#refcode').toggleClass("visible");
        $('#refcode').show("slow");
      }       
    });   

    $('#co-toggle').click(function(event) {
      event.preventDefault(); 
      if($('#del-co').hasClass("visible")){
        $('#del-co').toggleClass("visible");
        $('#del-co').hide("slow");
      }else {
        $('#del-co').toggleClass("visible");
        $('#del-co').show("slow");
      }       
    });   
 
    $('#country-toggle').click(function(event) {
      event.preventDefault(); 
      if($('#country').hasClass("visible")){
        $('#country').toggleClass("visible");
        $('#country').hide("slow");
      }else {
        $('#country').toggleClass("visible");
        $('#country').show("slow");
      }       
    });   
 
 
  });
    
    
  function updateStartRadio(){
    jQuery("#startdatumRadio2").attr('checked', true); 
  }
</script>    














<div id="buy-company">
  <div id="buy-company-top">FÖRETAGSPAKET <a href="/stegtavling" name="stegtavling">läs mer om vår stegtävling</a></div>
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
          <tr class="hide-calc hidden">
            <td colspan="2" ><div id="freight-label">Frakt</div></td>
            <td><div id="freight"><span class="nbr">0</span></div></td>          
            <td><span id="freight-text"></span></td>          
          </tr>
          <tr class="hide-calc hidden">
            <td></td>
            <td></td>
            <td class="line"><div id="nbr-sum-total-freight"><span class="nbr">0</span></div></td>
            <td class="line">kr ex moms</td>
          </tr>
          <tr class="hide-calc hidden">
            <td></td>
            <td></td>
            <td><div id="nbr-sum-total-freight-moms"><span class="nbr"> 0</span></div></td>
            <td>kr inkl moms</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="buy-box">
      <div class="buy-heading">Välj antal veckor för stegtävlingen</div>      
      <select name="veckor" id="veckor" style="float:left;">
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
      </select>
    </div>
    <div class="buy-box">
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
</div>


<div id="buy-company-2">
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
        <li><input type="text" name="del-phone" id="del-phone" placeholder="Telefon*"/></li>
        <li><input type="text" name="refcode" id="refcode" class="" placeholder="Kostnadsställe/Refkod"/></li>  
      </ul>
    </div>
  </div>


  <div class="buy-box-outer">
    <div class="buy-box">
      <a href="" id="delivery-toggle" class=" buy-heading">Annan faktura-adress?</a>
      <ul id="delivery-address" class="buy-ul hidden">
        <li><label for="company">Företagets namn</label><input type="text" name="company" id="company" class=""/></li><div class="clear"></div>
        <li><label>c/o</label><input type="text" name="co" id="co" /></li><div class="clear"></div>          
        <li><label for="street1">Adress</label><input type="text" name="street1" id="street1"/></li><div class="clear"></div>
        <li><label for="street2">&nbsp;</label><input type="text" name="street2" id="del-street2"/></li>
        <li><label for="zip">Postnummer</label><input type="text" name="zip" id="zip"/></li><div class="clear"></div>
        <li><label for="city">Ort</label><input type="text" name="city" id="city"/></li><div class="clear"></div>
        <li><label for="country">Land</label><input type="text" name="country" id="country" class="" value="Sverige"/></li><div class="clear"></div>       
        <li><label for="firstname">Kontakt förnamn</label><input type="text" name="firstname" id="firstname" class=""/></li><div class="clear"></div>
        <li><label for="lastname">Kontakt efternamn</label><input type="text" name="lastname" id="lastname" class=""/></li><div class="clear"></div>
        <li><label for="email">E-post</label><input type="text" name="email" id="email" class=""/></li><div class="clear"></div>
        <li><label for="phone">Mobil/telefon</label><input type="text" name="phone" id="phone"/></li><div class="clear"></div>
      </ul>
    </div>
  </div>










</div>



<!-- private --> 
<div id="buy-private" style="display:none;">
  <div id="buy-private-top">UTMANA DIG SJÄLV OCH DINA VÄNNER</div>
  <div id="buy-private-price">
    3 månaders MotioMera från 
    <div id="nbr-sum-total-freight">
      <span id="priv-the-price">79</span>kr
    </div>
  </div>
  <?php includeSnippet("inc_private_calc.php"); ?>
</div>