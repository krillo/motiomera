<?php
$wp = true; 
require_once($_SERVER["DOCUMENT_ROOT"] . "/../public_html/php/init.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$campaignCodes = Order::$campaignCodes;
$kommuner = Misc::arrayKeyMerge(array("" => "Välj..."), Kommun::listNamn());
?>

<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">
  var $j = jQuery.noConflict();
  
  $j(function() {
    //do input validation
    var validator = $j("#checkout").validate({
      errorClass: "invalid",
      validClass: "valid",
      rules: {
        anamn: {
          required: true,
          maxlength: <?php echo Medlem::MAX_LENGTH_ANAMN; ?>, 
          minlength: <?php echo Medlem::MIN_LENGTH_ANAMN; ?>          
        },
        firstname: {
          required: true
        },
        lastname: {
          required: true
        },
        mailone: {
          required: true,
          email: true
        },
        email2: {
          equalTo: "#mailone"
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
        },
        pass: {
          required: true
          //min: 4
        },
        pass2: {
          equalTo: "#pass"
        }       
      },  
      messages: {
        anamn: {
          required: '',
          maxlength: 'För långt',
          minlength: 'För kort'
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
        mailone: {
          required: '', 
          email: ''
        },
        email2: {
          equalTo: ''
        },        
        pass: {
          required: ''
          //min: 'minst 4 tecken'
        },        
        pass2: {
          equalTo: ''
        }           
      }
    });


    $j('#short-radio').change(function() {
      sum(); 
    });
    $j('#long-radio').change(function() {
      sum(); 
    });
    $j('#short-check').change(function() {
      sum(); 
    });
    $j('#long-check').change(function() {
      sum(); 
    });
 
    function sum(){
      radio  = $j('input:radio[name=radio-priv]:checked').val();
      if(typeof radio != 'undefined'){  //one of the radios are checked
        shortRadio = <?php echo $campaignCodes['PRIV3']['pris']; ?>;
        longRadio = <?php echo $campaignCodes['PRIV12']['pris']; ?>;
        
        if(radio == <?php echo $campaignCodes['PRIV3']['pris']; ?>){          
          $j('input:#long-check').attr('checked', false);
          longRadio = 0;
          $j('#m_priv3').val(1);   
          $j('#m_priv12').val(0);   
        }
        if(radio == <?php echo $campaignCodes['PRIV12']['pris']; ?>){            
          $j('input:#short-check').attr('checked', false);
          shortRadio = 0;
          $j('#m_priv12').val(1);
          $j('#m_priv3').val(0);
        }
        shortCheck = $j('input:#short-check:checked').val();
        longCheck = $j('input:#long-check:checked').val();        
        if(typeof shortCheck == 'undefined'){
          shortCheck = 0;
        }
        if(typeof longCheck == 'undefined'){
          longCheck = 0;
        } 
        //alert('radio: ' + radio + ' shortCheck: ' + shortCheck+ ' longCheck: ' + longCheck);
        if(shortCheck != 0 || longCheck != 0){
          sumFreight = parseInt(<?php echo $campaignCodes['FRAKT02']['pris']; ?>);
          $j('#m_frakt02').val(1);
          $j('#m_steg01').val(1);          
        } else{
          sumFreight = 0;
          $j('#m_frakt02').val(0);
          $j('#m_steg01').val(0);
        }
          
        sumShort = parseInt(shortRadio) + parseInt(shortCheck);
        sumLong = parseInt(longRadio) + parseInt(longCheck);        
        sumTotal = sumShort + sumLong + sumFreight;
      
        $j('#sum-short span').html(sumShort);
        $j('#sum-long span').html(sumLong);
        $j('#sum-freight span').html(sumFreight);
        $j('#sum-total span').html(sumTotal);
        $j('#priv-the-price').html(sumTotal);
        
      
          
        $j('#m_total').val(sumTotal);        
        $j('#m_freight').val(sumFreight);      
      
        
      } else { //radiobutton is undfined do nothing     
        //alert('apa');
      }
    }


    //catch keyup where the alias is submitted 
    $j('#anamn').keyup(function() {
      $j('#fields-hidden').toggleClass("visible");
      $j('#fields-hidden').show("slow");
    });


    $j('#private-toggle').click(function(event) {
      event.preventDefault(); 
      if($j('#member-private').hasClass("visible")){
        $j('#member-private').toggleClass("visible");
        $j('#member-private').hide("slow");
      }else {
        $j('#member-private').toggleClass("visible");
        $j('#member-private').show("slow");
      }       
    });   

        
    $j('#address-toggle').click(function(event) {
      event.preventDefault(); 
      if($j('#extra-address').hasClass("visible")){
        $j('#extra-address').toggleClass("visible");
        $j('#extra-address').hide("slow");
      }else {
        $j('#extra-address').toggleClass("visible");
        $j('#extra-address').show("slow");
      }       
    });
        
        
    $j('#discount-toggle').click(function(event) {
      event.preventDefault(); 
      if($j('#discount').hasClass("visible")){
        $j('#discount').toggleClass("visible");
        $j('#discount').hide("slow");
      }else {
        $j('#discount').toggleClass("visible");
        $j('#discount').show("slow");
      }       
    });        


    $j('#co-toggle').click(function(event) {
      event.preventDefault(); 
      if($j('#co').hasClass("visible")){
        $j('#co').toggleClass("visible");
        $j('#co').hide("slow");
      }else {
        $j('#co').toggleClass("visible");
        $j('#co').show("slow");
      }       
    });   
 
    $j('#country-toggle').click(function(event) {
      event.preventDefault(); 
      if($j('#country').hasClass("visible")){
        $j('#country').toggleClass("visible");
        $j('#country').hide("slow");
      }else {
        $j('#country').toggleClass("visible");
        $j('#country').show("slow");
      }       
    });   
 
 
  });
</script>



<style>
  #calc {width:100%;font-size: 14px;border-bottom: 1px solid black;float:left;}
  #calc input{width:18px;height:18px;font-size: 14px;float:left;}
  #calc div{font-size: 14px;}
  .step-check{margin-left: 20px;}
  #short, #long, #freight{float:left;margin-bottom: 25px;width: 75%;}
  #freight{margin: 0 0 10px 28px;width:253px;}
  #sum-short, #sum-long, #freight-sum{float:right;}
  .nbr{font-size: 22px; font-weight: normal;}
  #nbr-sum-total-freight .currency{width:90px;font-size: 20px;}
  #sum-freight{float:right;}
  #sum-total{float:right;margin-bottom:40px;}

  #pay {font-size: 14px;margin-top: 45px;}
  #integrity{margin-bottom: 20px;width: 350px;}

  #pay div{float:left;}
  #pay input{font-size: 15px;width:200px;height:25px;}
  #pay ul{margin-left: 2px;padding-left: 15px;}
  #payalt{color:#494949;font-size:11px;margin:5px 0 0 10PX;}
</style> 
<style>

  #checkout-ul h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
  #checkout-ul .h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
  #checkout-ul li{margin-top: 5px;}

  #checkout-ul label{width:130px;font-size: 14px;float:left}
  #checkout-ul{list-style: none;margin-left: 0;padding-left: 0;font-size: 13px;}
  #checkout-ul input{height:18px;font-size: 14px;}
  #checkout-ul a{text-decoration: underline;}

</style>




<div id="buy-private-calc">
  <div id="buy-private-calc-text">För dig som vill röra på dig och samtidigt ha kul! Det är enkelt, allt du behöver är en stegräknare.</div>
  <div id="buy-private-calc-input">








    <form action="/actions/payson_privat.php" method="post" id="checkout">
      <input type="hidden" name="type" value="medlem">         
      <input type="hidden" name="m_total"   id="m_total" value="">                    
      <input type="hidden" name="m_priv3"   id="m_priv3" value="">        
      <input type="hidden" name="m_priv12"  id="m_priv12" value="">        
      <input type="hidden" name="m_steg01"  id="m_steg01" value="">        
      <input type="hidden" name="m_frakt02" id="m_frakt02" value="">
      <div id="calc">

        <div id="short">
          <input type="radio" id="short-radio" name="radio-priv" value="<?php echo $campaignCodes['PRIV3']['pris']; ?>" /><div id="short-text"><?php echo $campaignCodes['PRIV3']['text']; ?><span > <?php echo $campaignCodes['PRIV3']['pris']; ?> kr</span></div>
          <div class="clear"></div>
          <div id="" class="step-check"><input type="checkbox" id="short-check" name="short-check-step" value="<?php echo $campaignCodes['STEG01']['pris']; ?>" /><div id="short-text"><?php echo $campaignCodes['STEG01']['text']; ?><span> +<?php echo $campaignCodes['STEG01']['pris']; ?> kr</span></div></div>    
        </div>
        <div id="sum-short"><span class="nbr">0</span> kr</div>
        <div class="clear"></div>
        <div id="long">
          <input type="radio" id="long-radio" name="radio-priv" value="<?php echo $campaignCodes['PRIV12']['pris']; ?>" /><div id="long-text"><?php echo $campaignCodes['PRIV12'][text]; ?><span > <?php echo $campaignCodes['PRIV12']['pris']; ?> kr</span></div>
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


      <div id="member-private" class="hide">
        <ul id="checkout-ul">
          <li><label for="alias">Välj alias</label>
            <input type="text" name="anamn" id="anamn" class="" onfocus="getById('mmANamnError').style.display = 'none';" onblur="mm_ajaxValidera('mmANamnError', 'anamn', this.value);"/>
            <span id="mmANamnError" class="mmRed mmFormError">Upptaget</span>
          </li>
          <div class="clear"></div>
          <div id="fields-hidden" class="hidden">
            <li>
              <label for="sex">Kön</label>
              <select name="sex">
                <option value="kvinna">Kvinna</option>
                <option value="man">Man</option>
              </select>
            </li>
            <li>
              <label for="kid">Startkommun</label>          
              <select name="kid" id="kid">
                <?php
                foreach ($kommuner as $key => $value) {
                  echo '<option label="' . $value . '" value="' . $key . '">' . $value . '</option>';
                }
                ?>
              </select>
            </li>

            <li><label for="mailone">E-post</label>
              <input type="text" name="mailone" id="mailone" class="" onfocus="getById('mmEpostError').style.display = 'none';" onblur="mm_ajaxValidera('mmEpostError', 'epost', this.value);"/>
              <span id="mmEpostError" class="mmRed mmFormError">Upptagen, <a href="/pages/glomtlosen.php?email="  class="mmRed" >glömt lösenord?</a></span><br />
            </li>
            <div class="clear"></div>
            <li><label for="email2">E-post igen</label><input type="text" name="email2" id="email2" class=""/></li><div class="clear"></div>
            <li><label for="pass">Lösenord</label><input type="password" name="pass" id="pass" class=""/></li><div class="clear"></div>
            <li><label for="pass2">Lösenord igen</label><input type="password" name="pass2" id="pass2" class=""/></li><div class="clear"></div>        
            <li><label for="firstname">Förnamn</label><input type="text" name="firstname" id="firstname" class=""/></li><div class="clear"></div>
            <li><label for="lastname">Efternamn</label><input type="text" name="lastname" id="lastname" class=""/></li><div class="clear"></div>
            <li><label><a href="" id="co-toggle">c/o ?</a></label><input type="text" name="co" id="co" class="hidden"/></li><div class="clear"></div>                 
            <li><label for="phone">Mobil/telefon</label><input type="text" name="phone" id="phone"/></li><div class="clear"></div>
            <li><label for="street1">Postadress</label><input type="text" name="street1" id="street1"/></li><div class="clear"></div>
            <li><label><a href="" id="address-toggle">Fler rader ?</a></label> 
              <div id="extra-address"class="hidden">
                <input type="text" name="street2" id="street2"/><div class="clear"></div>
                <label>&nbsp;</label><input type="text" name="street3" id="street3"/>
              </div>
            </li>
            <div class="clear"></div>
            <li><label for="zip">Postnummer</label><input type="text" name="zip" id="zip"/></li><div class="clear"></div>
            <li><label for="city">Ort</label><input type="text" name="city" id="city"/></li><div class="clear"></div>
            <li><label><a href="" id="country-toggle">Inte Sverige ?</a></label><input type="text" name="country" id="country" class="hidden" value="Sverige"/></li><div class="clear"></div>           
            <!--li><label><a href="" id="discount-toggle">Har du en rabattkod?</a></label><input type="text" name="discount" id="discount" class="hidden"/></li-->
            <div id="margin"></div>


            <li>
              <div id="pay">
                <div id="integrity">Genom att fortsätta betalningen godkänner jag <a href="/pages/integritetspolicy.php" target="_blank">Motiomeras integritetspolicy</a> och är över 18 år</div>
                <div class="clear"></div>
                <div ><input type="submit" value="Betala - Payson" name="paytype" id="payson"></div>
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
            </li>
          </div> 
        </ul>
      </div> <!-- end member-private -->      
    </form>    







  </div> <!-- end buy-private-calc-input -->
</div>