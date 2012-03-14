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
    jQuery("#mmForetagStartdatumRadio2").attr('checked', true); 
  }
</script>    



<!--form action="/actions/sendorder.php" method="get" --> <!--onsubmit="return motiomera_validateSkapaForetagForm(this)" -->
<form action="/actions/payson_foretag.php" method="get" id="checkout">
  <input type="hidden" name="type" value="foretag">
  <input type="hidden" name="m_exmoms"  id="m_exmoms" value=""> 
  <input type="hidden" name="m_freight" id="m_freight"  value="">         
  <input type="hidden" name="m_total"   id="m_total" value="">        
  <input type="hidden" name="m_incmoms" id="m_incmoms" value="">            

  <ul id="checkout-ul">
    <li><label>Startdatum</label>
      <div class="clear"></div>
      <input name="startdatumRadio" id="mmForetagStartdatumRadio1" type="radio" value="2012-04-23" checked><label for="mmForetagStartdatumRadio1">Den stora vårtävlingen 23 april</label><br/>
      <input name="startdatumRadio" id="mmForetagStartdatumRadio2" type="radio" value="egetdatum"  >
      <select name="startdatum" id="mmForetagStartdatum" onchange="updateStartRadio();">
        <?php echo Order::getMondays(20); ?>
      </select>		
    </li>

    <li>
        <label><a href="" id="discount-toggle">Har du en rabattkod?</a></label><input type="text" name="discount" id="discount" class="hidden"/>         
    </li>
    <li>
      <label><a href="" id="refcode-toggle">Referenskod på kvittot?</a></label><input type="text" name="refcode" id="refcode" class="hidden"/>         
    </li>        
    <li>
      <label id="nbr">Antal deltagare</label>
      <div class="clear"></div>
      <input type="text" name="RE03" id="nbr-with"/>
      <div id="nbr-with-text"><?php echo $campaignCodes['RE03'][text]; ?><span style="color:red;"> <?php echo $campaignCodes['RE03'][pris]; ?>kr</span><?php echo $campaignCodes['RE03']['extra']; ?></div>
      <div id="nbr-with-sum"><span>0</span> kr ex moms</div>
      <div class="clear"></div>
      <input type="text" name="RE04" id="nbr-without" /> 
      <div id="nbr-with-text"><?php echo $campaignCodes['RE04'][text]; ?><span style="color:red;"> <?php echo $campaignCodes['RE04'][pris]; ?>kr</span><?php echo $campaignCodes['RE04']['extra']; ?></div>
      <div id="nbr-without-sum"><span>0</span> kr ex moms</div>
    </li>
    <div class="clear"></div>
    <li>
      <label>Frakt</label>

      <div id="freight"><span></span><span id="freight-text"></span></div>
    </li>        
    <li>
      <div id="nbr-sum-total-freight"><span>0</span> kr ex moms</div>
    </li>
    <li>
      <div id="nbr-sum-total-freight-moms"><span>0</span> kr inkl. moms</div>
    </li>


    Faktureringsadress
    <li><label>Företagets namn</label><input type="text" name="company" id="company" class=""/></li>
    <li><label><a href="" id="co-toggle">c/o ?</a></label><input type="text" name="co" id="co" class="hidden"/></li>          
    <li><label>Förnamn</label><input type="text" name="firstname" id="firstname" class=""/><label>Efternamn</label><input type="text" name="lastname" id="lastname" class=""/></li>
    <li><label>E-post</label><input type="text" name="email" id="email" class=""/></li>
    <li><label>Mobil/telefon</label><input type="text" name="phone" id="phone"/></li>        
    <li><label>Postadress</label><input type="text" name="street1" id="street1"/></li><a href="" id="address-toggle"> Fler rader</a> 
    <div id="extra-address"class="hidden">
      <li><label>Postadress 2</label><input type="text" name="street2" id="street2"/></li>
      <li><label>Postadress 3</label><input type="text" name="street3" id="street3"/></li>
    </div>
    <li><label>Postnummer</label><input type="text" name="zip" id="zip"/></li>
    <li><label>Ort</label><input type="text" name="city" id="city"/></li>
    <li><label><a href="" id="country-toggle">Inte Sverige?</a></label><input type="text" name="country" id="country" class="hidden" value="Sverige"/></li>       
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