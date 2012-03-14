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
        company: {
          required: true
        },
        firstname: {
          required: true
        },
        lastname: {
          required: true
        },
        email: {
          required: true,
          email: true
        },
        street1: {
          required: true
        },
        zip: {
          required: true
        },
        city: {
          required: true
        },
        phone: {
          required: true
        }
      },  
      messages: {
        company: {
          required: ''
        },
        firstname: {
          required: ''
        },
        lastname: {
          required: ''
        },
        street1: {
          required: ''
        },
        zip: {
          required: ''
        },
        city: {
          required: ''
        },
        phone: {
          required: ''
        },
        email: {
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


          
      //  
      $('#m_exmoms').val(sumTotal);
          
      $('#m_total').val(sumTotalFreight);        
      $('#m_incmoms').val(sumTotalFreightMoms);
    }
        
        
        
    $('#delivery-toggle').click(function(event) {
      event.preventDefault();
      if($('#delivery').hasClass("visible")){
        $('#delivery').toggleClass("visible");
        $('#delivery').hide("slow");
      }else {
        $('#delivery').toggleClass("visible");
        $('#delivery').show("slow");
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
      if($('#co').hasClass("visible")){
        $('#co').toggleClass("visible");
        $('#co').hide("slow");
      }else {
        $('#co').toggleClass("visible");
        $('#co').show("slow");
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



<!--form action="/actions/sendorder.php" method="get" --> <!--onsubmit="return motiomera_validateSkapaForetagForm(this)" -->
<form action="/actions/payson_foretag.php" method="get" id="checkout">
  <input type="hidden" name="type" value="foretag">
  <input type="hidden" name="m_exmoms"  id="m_exmoms" value=""> 
  <input type="hidden" name="m_freight" id="m_freight"  value="">         
  <input type="hidden" name="m_total"   id="m_total" value="">        
  <input type="hidden" name="m_incmoms" id="m_incmoms" value="">            

  <style>
    #checkout-ul label{margin-bottom: 10px;display:block;margin-top: 40px;font-size:18px;}
    #checkout-ul{list-style: none;margin-left: 0;padding-left: 0;font-size: 13px;}
    #checkout-ul input{height:18px;font-size: 14px;}
    #checkout-ul a{text-decoration: underline;}
    #calc{border-bottom: solid black 1px;}

    #nbr-with-text, #nbr-without-text{width:120px;margin-bottom: 15px;}
    #nbr-with-text span, #nbr-without-text span{color:#427F10;}

    #nbr-with, #nbr-without{width:40px;height:20px;font-size: 18px;}

    #nbr-with-sum, #nbr-without-sum {margin-left: 30px;}
    .nbr{font-size: 18px;font-weight: bold;}
    #freight-label{display:block;width:190px;float:left;}
    #freight{float:left;}
    #nbr-sum-total-freight{margin-left: 190px;float:left;}
    #nbr-sum-total-freight-moms{margin-left: 65px;float:left;color:#427F10;}

    #startdatumRadio-label{font-size: 13px;display: inline;}
    #startdatumRadio1, #startdatumRadio2{width:40px;} 
    #startdatum{width:175px;height:22px;font-size: 12px;}
    #payer li{margin-bottom: 2px; margin-top: 0;display:block;float:left;}
    #payer li div{width:130px;font-size: 14px;float:left}
    #payer li input{width:180px;float:left;}
    #payer #payer-label{margin-bottom: 10px;display:block;margin-top: 40px;font-size:18px;}

  </style>

  <ul id="checkout-ul">
    <li id="calc">
      <label >Antal deltagare för 5 veckors stegtävling</label>
      <div class="clear"></div>
      <div id="nbr-with-text"><?php echo $campaignCodes['RE03'][text]; ?><span > <?php echo $campaignCodes['RE03'][pris]; ?> kr / person</span></div>
      <input type="text" name="RE03" id="nbr-with"/>
      <div id="nbr-with-sum"><span class="nbr">0</span> kr ex moms</div>
      <div class="clear"></div>
      <div id="nbr-without-text"><?php echo $campaignCodes['RE04'][text]; ?><span > <?php echo $campaignCodes['RE04'][pris]; ?> kr / person</span></div>
      <input type="text" name="RE04" id="nbr-without" />       
      <div id="nbr-without-sum"><span class="nbr">0</span> kr ex moms</div>
      <div class="clear"></div>
      <div id="freight-label">Frakt</div><div id="freight"><span class="nbr">0</span><span id="freight-text"> kr ex moms</div>
      <div class="clear"></div>
    </li>
    <li>
      <div id="nbr-sum-total-freight"><span class="nbr">0</span> kr ex moms</div>
      <div id="nbr-sum-total-freight-moms"><span class="nbr"> 0</span> kr inkl. moms </div>
    </li>
    <div class="clear"></div>

    <li><label>Välj ert startdatum (valfri måndag)</label>      
      <input name="startdatumRadio" id="startdatumRadio1" type="radio" value="2012-04-23" checked><label for="startdatumRadio1" id="startdatumRadio-label" style="font-size: 13px;display: inline;">Den stora vårtävlingen 23 april</label><br/>
      <input name="startdatumRadio" id="startdatumRadio2" type="radio" value="egetdatum"  >
      <select name="startdatum" id="startdatum" onchange="updateStartRadio();">
        <?php echo Order::getMondays(20); ?>
      </select>		
    </li>

    <div class="clear"></div>
    <div id="payer">
      <li><div id="payer-label">Faktureringsadress</div></li><div class="clear"></div>
      <li><div>Företagets namn</div><input type="text" name="company" id="company" class=""/></li><div class="clear"></div>
      <li><div><a href="" id="co-toggle">c/o ?</a></div><input type="text" name="co" id="co" class="hidden"/></li><div class="clear"></div>          
      <li><div>Förnamn</div><input type="text" name="firstname" id="firstname" class=""/></li><div class="clear"></div>
      <li><div>Efternamn</div><input type="text" name="lastname" id="lastname" class=""/></li><div class="clear"></div>
      <li><div>E-post</div><input type="text" name="email" id="email" class=""/></li><div class="clear"></div>
      <li><div>Mobil/telefon</div><input type="text" name="phone" id="phone"/></li><div class="clear"></div>
      <li><div>Postadress</div><input type="text" name="street1" id="street1"/></li><div class="clear"></div>
      <li><div><a href="" id="address-toggle">Fler rader?</a></div></li> 
      <div id="extra-address"class="hidden">
        <li><div></div><input type="text" name="street2" id="street2"/></li><div class="clear"></div>
        <li><div>&nbsp;</div><input type="text" name="street3" id="street3"/></li>
      </div>
      <div class="clear"></div>
      <li><div>Postnummer</div><input type="text" name="zip" id="zip"/></li><div class="clear"></div>
      <li><div>Ort</div><input type="text" name="city" id="city"/></li><div class="clear"></div>
      <li><div><a href="" id="country-toggle">Inte Sverige?</a></div><input type="text" name="country" id="country" class="hidden" value="Sverige"/></li><div class="clear"></div>   

      <li>
        <label><a href="" id="discount-toggle">Har du en rabattkod?</a></label><input type="text" name="discount" id="discount" class="hidden"/>         
      </li>
      <li>
        <label><a href="" id="refcode-toggle">Referenskod på kvittot?</a></label><input type="text" name="refcode" id="refcode" class="hidden"/>         
      </li>      
    </div>


    <a href="" id="delivery-toggle"> Ange en leveransadress</a> 
    <div id="delivery" class="hidden">
      Leveransadress
      <li><label>Företagets namn</label><input type="text" name="del-company" id="del-company" class=""/></li>
      <li><label>c/o</label><input type="text" name="del-co" id="del-co" class=""/></li>
      <li><label>Kontaktperson</label><input type="text" name="del-name" id="del-name" class=""/></li>
      <li><label>E-post</label><input type="text" name="del-email" id="del-email" class=""/></li>
      <li><label>Telefon</label><input type="text" name="del-phone" id="del-phone"/></li>        
      <li><label>Postadress</label><input type="text" name="del-street1" id="del-street1"/></li>
      <li><label>Postadress 2</label><input type="text" name="del-street2" id="del-street2"/></li>
      <li><label>Postadress 3</label><input type="text" name="del-street2" id="del-street3"/></li>
      <li><label>Postnummer</label><input type="text" name="del-zip" id="del-zip"/></li>
      <li><label>Ort</label><input type="text" name="del-city" id="del-city"/></li>
      <li><label>Land</label><input type="text" name="del-country" id="del-country" class="" value="Sverige"/></li>
    </div>


    <!--li>
      Var hörde du talas om Motiomera
      <select name="channel" id="channel">
        <option value="email">Email</option>
        <option value="telefon">Telefon</option>
        <option value="mässa">Mässa</option>
        <option value="direktreklam">Direktreklam</option>
      </select>	
    </li-->


    <li>Genom att fortsätta köpet godkänner jag <a href="/pages/integritetspolicy.php" target="_blank">Motiomeras integritetspolicy</a> och är över 16 år</li>

    Välj hur du vill betala:
    <div style="border:solid 1px grey; float:left;">  
      VISA / MasterCard <br/>
      Internetbank Föreningssparbanken / Swedbank <br/>
      Internetbank Handelsbanken <br/>
      Internetbank SEB <br/>
      Internetbank Nordea
      <input type="submit" value="Direkt" name="paytype" id="payson">
    </div>
    <div style="border:solid 1px grey; float:left;">  
      Faktura <br/>
      <input type="submit" value="Faktura" name="paytype" id="faktura">
    </div>           

  </ul>
</form>    