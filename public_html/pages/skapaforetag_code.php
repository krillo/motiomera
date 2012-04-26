<?php
$campaignCodes = Order::$campaignCodes;
$moms = Order::$moms;
Order::getMondays(15);
?>

<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">    
  $(function() {
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
      $('#nbr-sum-total-freight span').html(sumTotalFreight);    
        
          
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
      if($('#fields-hidden').hasClass("visible")){        
        $('#fields-hidden').toggleClass("visible");
        $('#fields-hidden').hide("slow");

      }else {
        $('#fields-hidden').toggleClass("visible");
        $('#fields-hidden').show("slow");       
        $('#firstname').val($('#del-firstname').val());
        $('#lastname').val($('#del-lastname').val());
        $('#email').val($('#del-email').val());
        $('#phone').val($('#del-phone').val());
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



<div id="infotext" style="position:absolute;left:530px;top:131px;border:2px solid;padding:6px;">
  <h3>TILLÄGGSBESTÄLLNING</h3>
  Är du redan kund och vill göra en<br/>tilläggsbeställning? Logga in på din<br/>administrationssida där du enkelt kan<br/>lägga till fler deltagare. <a href="/pages/foretaglogin.php">Klicka här</a>.
</div>


<div style="color:red;font-size: 22px;"> OBS 15% rabatt just nu! </div>


<!--form action="/actions/sendorder.php" method="get" --> <!--onsubmit="return motiomera_validateSkapaForetagForm(this)" -->
<form action="/actions/payson_foretag.php" method="get" id="checkout">
  <input type="hidden" name="type" value="foretag">
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

  </style>

  <ul id="checkout-ul">
    <li id="calc">
      <h2 >Antal deltagare för 5 veckors stegtävling</h2>
      <div class="clear"></div>
      <div id="nbr-with-text"><?php echo $campaignCodes['RE03'][text]; ?><br/><span style="color:black;text-decoration: line-through;">289 </span><span><?php echo $campaignCodes['RE03'][pris]; ?> kr/person</span></div>
      <input type="text" name="RE03" id="nbr-with"/>
      <div id="nbr-with-sum"><span class="nbr">0</span> kr ex moms</div>
      <div class="clear"></div>
      <div id="nbr-without-text"><?php echo $campaignCodes['RE04'][text]; ?><br/><span style="color:black;text-decoration: line-through;">169 </span><span><?php echo $campaignCodes['RE04'][pris]; ?> kr/person</span></div>
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
    <div class="clear"></div>

    <li><h2>Välj ert startdatum (valfri måndag)</h2>      
      <input name="startdatumRadio" id="startdatumRadio1" type="radio" value="2012-05-07" checked><label for="startdatumRadio1" id="startdatumRadio-label" style="font-size: 13px;display: inline;">Den 7 maj</label>
      <div class="clear"></div>
      <input name="startdatumRadio" id="startdatumRadio2" type="radio" value="egetdatum" checked >
      <select name="startdatum" id="startdatum" onchange="updateStartRadio();">
        <?php echo Order::getMondays(20); ?>
      </select>
      <div class="clear"></div>
      <div id="early-info">Ring Kristian 0761-393855 om ni önskar tidigare datum.</div>
    </li>

    <div class="clear"></div>

    <li><h2 id="payer-labelx">Adress</h2></li><div class="clear"></div>
    <li><label for="del-company">Företagets namn</label><input type="text" name="del-company" id="del-company" class=""/></li><div class="clear"></div>

    <div id="delivery" class="hidden">
      <li><label>c/o</label><input type="text" name="del-co" id="del-co" class=""/></li><div class="clear"></div>
      <li><label>Adress</label><input type="text" name="del-street1" id="del-street1"/></li><div class="clear"></div>
      <li><label>&nbsp;</label><input type="text" name="del-street2" id="del-street2"/></li><div class="clear"></div>
      <li><label>Postnummer</label><input type="text" name="del-zip" id="del-zip"/></li>
      <li><label>Ort</label><input type="text" name="del-city" id="del-city"/></li>
      <li><label>Land</label><input type="text" name="del-country" id="del-country" class="" value="Sverige"/></li>
      <li><label for="del-firstname">Förnamn</label><input type="text" name="del-firstname" id="del-firstname" class=""/></li><div class="clear"></div>
      <li><label for="del-lastname">Efternamn</label><input type="text" name="del-lastname" id="del-lastname" class=""/></li><div class="clear"></div>        
      <li><label>E-post</label><input type="text" name="del-email" id="del-email" class=""/></li><div class="clear"></div>
      <li><label>Telefon</label><input type="text" name="del-phone" id="del-phone"/></li>
      <li><label>Kostnställe/refkod</label><input type="text" name="refcode" id="refcode" class=""/></li>  
    </div>    

    <div class="clear"></div>
    <h2><a href="" id="delivery-toggle" class="hidden">Annan faktura-adress?</a></h2> 
    <div id="fields-hidden" class="hidden">      
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

<!--li><label><a href="" id="discount-toggle">Har du en rabattkod?</a></label><input type="text" name="discount" id="discount" class="hidden"/></li-->

      <!--li>
        Var hörde du talas om Motiomera
        <select name="channel" id="channel">
          <option value="email">Email</option>
          <option value="telefon">Telefon</option>
          <option value="mässa">Mässa</option>
          <option value="direktreklam">Direktreklam</option>
        </select>	
      </li-->
    </div>


    <style>
      #pay {font-size: 14px;margin-top: 45px;}
      #integrity{margin-bottom: 20px;width: 350px;}
      #pay div{float:left;}
      #pay input{font-size: 15px;width:200px;height:25px;}
      #pay ul{margin-left: 2px;padding-left: 15px;}    
      #or{font-size: 15px; margin:0 30px 0 30px;}
      #payalt{color:#494949;font-size:11px;margin:5px 0 0 10PX;}
    </style>


    <div id="pay" class="hidden">
      <div id="integrity">Genom att fortsätta betalningen godkänner jag <a href="/pages/integritetspolicy.php" target="_blank">Motiomeras integritetspolicy</a> och är över 18 år</div>
      <div class="clear"></div>

      <div >
        <input type="submit" value="Betalning - Payson" name="paytype" id="payson">
      </div>
      <div id="or">eller</div>
      <div >      
        <input type="submit" value="Faktura" name="paytype" id="faktura">
      </div>  
      <div class="clear"></div>
      <div id="payalt">
        På Payson kan du betala med:
        <ul>
          <li>VISA</li>
          <li>MasterCard </li>
          <li>Internetbank: Föreningssparbanken / Swedbank</li>
          <li>Internetbank: Handelsbanken </li>
          <li>Internetbank: SEB </li>
          <li>Internetbank: Nordea </li>
        </ul>
      </div>  
    </div>








  </ul>
</form>    
<div class="clear"></div>



<br>
<br/>
<br/>
<br/>
<div id="pers-service">
  <div id="pers-img" style="float:left;">
    <img src="/img/kristian_80x90.png" alt="Kristian"/>
  </div>
  <div id="pers-text" style="float:left;padding-left:15px;width:450px;padding-top:5px">
    <span  class="mmObs mmObsText">För mer information eller om du vill ha en powerpoint-presentation. Kontakta Kristian på:<br/>
      0761-393855<br/>
      <a href="mailto:kristian@motiomera.se" >kristian@motiomera.se</a>
    </span>
  </div>
</div>