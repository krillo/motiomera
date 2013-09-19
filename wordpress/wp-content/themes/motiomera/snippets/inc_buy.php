<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//print_r($GLOBALS );               //display all wp-globals 
//global $mmStatus;                 
//print_r($mmStatus);               //display all mm-wp-globals 

//require_once(MM_ROOT_ABSPATH . "/init.php");

  require_once MM_ROOT_ABSPATH . "/classes/Mobject.php";
  require_once MM_ROOT_ABSPATH . "/classes/UserException.php";
  require_once MM_ROOT_ABSPATH . "/classes/Misc.php";
  require_once MM_ROOT_ABSPATH . "/classes/Order.php";
  require_once MM_ROOT_ABSPATH . "/classes/Kommun.php";
  //require_once MM_ROOT_ABSPATH . "/classes/DB.php";
  require_once MM_ROOT_ABSPATH . "/classes/Medlem.php";

$campaignCodes = Order::$campaignCodes;
$moms = Order::$moms;
Order::getMondays(15);

!empty($_REQUEST['buy']) ? $buy = $_REQUEST['buy'] : $buy = '';
?>


<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">    
  jQuery(function($) {
    var type = $("#type").val();
    if(type === "private"){
      showPrivateHideCompany();
    } else if(type === "company"){
      showCompanyHidePrivate();
    }
        
    /**
     * Custom Email ajax validation for the jQuery Validator plugin
     * Krillo 2012 
     */       
    var emailFree = true;
    $.validator.addMethod("mmEmail", function(email, element) {
      email = $('#email1').val();
      var data = {
        typ: 'epost',
        varde: email
      };          
      $.ajax({
        type: "POST",
        url: "/ajax/actions/validate.php",
        async: false,
        data: data,
        cache: false,
        timeout: 30000,
        error: function(){
          return true;
        }
      }).done(function ( data ) { 
        if(data == "1"){
          //console.log('true');
          emailFree =  true;
        }else{
          //console.log('false');
          emailFree = false;
        }
      }); return emailFree;
    }, "Upptagen epostadress");   
        

    /**
     * Custom alias ajax validation for the jQuery Validator plugin
     * Krillo 2012 
     */       
    var anamnFree = true;
    $.validator.addMethod("mmAnamn", function(anamn, element) {
      anamn = $('#anamn').val();
      var data = {
        typ: 'anamn',
        varde: anamn
      };          
      $.ajax({
        type: "POST",
        url: "/ajax/actions/validate.php",
        async: false,
        data: data,
        cache: false,
        timeout: 30000,
        error: function(){
          return true;
        }
      }).done(function ( data ) { 
        if(data == "1"){
          //console.log('true');
          anamnFree =  true;
        }else{
          //console.log('false');
          anamnFree = false;
        }
      }); return anamnFree;
    }, "Upptaget alias, välj ett annat");   


        
    /**
     * Do input validation
     * Fields that are hidden are not validated 
     * jQuery Validator plugin
     */ 
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
        }, 
        //private
        anamn: {
          required: true,
          maxlength: <?php echo Medlem::MAX_LENGTH_ANAMN; ?>, 
          minlength: <?php echo Medlem::MIN_LENGTH_ANAMN; ?>,
          mmAnamn: true          
        },
        firstname: {
          required: true
        },
        lastname: {
          required: true
        },
        email1: {
          required: true,
          email: true,
          mmEmail: true
        },
        email2: {
          email: '',
          equalTo: "#email1"
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
        },        
        //Private
        "anamn": {
          required: '',
          maxlength: 'För långt',
          minlength: 'För kort'
        },
        "firstname": {
          required: ''
        },
        "lastname": {
          required: ''
        },
        "street1": {
          required: ''
        },
        "zip": {
          required: ''
        },
        "city": {
          required: ''
        },
        "phone": {
          required: ''
        },
        "email1": {
          required: '', 
          email: 'Skriv en korrekt e-postadress'
        },
        "email2": {
          equalTo: '', 
          email: 'Skriv en korrekt e-postadress'
        },        
        "pass": {
          required: ''
          //min: 'minst 4 tecken'
        },        
        "pass2": {
          equalTo: ''
        }
      }
    });


    function showPrivateHideCompany(){
      type = 'private';      
      $('#type').val('private');
      $('.hide-company').addClass("hidden");
      $('#buy-company').addClass("hidden");
      $('#buy-company-top').removeClass("full-width");
      $('.hide-private').removeClass("hidden");
      $('#buy-private-top-left').addClass("full-width");
      $('#link-company').removeClass("hidden");  
      $('#buy-payment').removeClass("hidden");  
      $('#faktura').addClass("hidden");  
    }
    
    function showCompanyHidePrivate(){
      type = 'company';
      $('#type').val('company');
      $('.hide-company').removeClass("hidden");
      $('#buy-company').removeClass("hidden");
      $('#buy-company-top').addClass("full-width");
      $('.hide-private').addClass("hidden");
      $('#buy-private-top-left').removeClass("full-width");
      $('#buy-payment').removeClass("hidden");  
    }   


    /**
     * Sum company with and without stepcounter, add freight and moms
     */ 
    function sum_company(){
      var sumWith, sumWithout, sumTotal, countWith, countWithout, freight, sumTotalFreight, sumTotalFreightMoms;           
      showCompanyHidePrivate();
      
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
        

    /**
     * Sum private
     */ 
    function sum_private(){
      showPrivateHideCompany();
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
   
        $('#sum-short').html(sumShort);
        $('#sum-long').html(sumLong);
        $('#sum-freight').html(sumFreight);
        $('#sum-total').html(sumTotal);
        $('#priv-the-price').html(sumTotal);       
        $('#m_total').val(sumTotal);        
        $('#m_freight').val(sumFreight);      
      } else { //radiobutton is undfined do nothing     
        //alert('apa');
      }
    }


    
    /**
     * do ajax check if free to use     
    function inUseAlready(type, value, elementId){
      var data = {
        typ: type,
        varde: value
      };          
      $.ajax({
        type: "POST",
        url: "/ajax/actions/validate.php",
        data: data,
        cache: false,
        success: function(data){
          //console.log(data);
          if(data == "1"){
            $(elementId).addClass('hide');
          }else{
            $(elementId).removeClass('hide');
          }
        }
      });
      return false;
    }
*/



    /******************************
     * Catch events
     ******************************/
        
    //toggle from company to private    
    $('#link-private').click(function(event) {
      event.preventDefault();
      showPrivateHideCompany();
    });
        
    //toggle from private to company    
    $('#link-company').click(function(event) {
      event.preventDefault();
      showCompanyHidePrivate();
    });

    //company catch keyup where the ammount is submitted 
    $('#nbr-with').keyup(function() {
      sum_company();     
    });
    $('#nbr-without').keyup(function() {
      sum_company();
    });
    
    //copmany check also when leaving field
    $('#nbr-with').blur(function() {
      sum_company();     
    });
    $('#nbr-without').keyup(function() {
      sum_company();
    });
           
    //company show or hide delicery address       
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


    //private monitor changes on radio and checkbox  
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

 
    //send form to company or private
    $("#checkout").submit(function() {
      if (type == "company") {
        $("#checkout").attr("action", "/actions/payson_foretag.php");
        return true;
      } else {
        $("#checkout").attr("action", "/actions/payson_privat.php")
        return true;        
      }
    });
 
 
  });
    
    
  function updateStartRadio(){
    jQuery("#startdatumRadio2").attr('checked', true); 
  }
</script>    

<form action="/actions/wp_buy.php" method="post" id="checkout">
  <input type="hidden" name="type"      id="type" value="<?php echo $buy; ?>">
  <input type="hidden" name="m_exmoms"  id="m_exmoms" value=""> 
  <input type="hidden" name="m_freight" id="m_freight"  value="">         
  <input type="hidden" name="m_total"   id="m_total" value="">        
  <input type="hidden" name="m_incmoms" id="m_incmoms" value="">       
  <input type="hidden" name="m_priv3"   id="m_priv3" value="">        
  <input type="hidden" name="m_priv12"  id="m_priv12" value="">        
  <input type="hidden" name="m_steg01"  id="m_steg01" value="">        
  <input type="hidden" name="m_frakt02" id="m_frakt02" value="">  


  <div id="buy-private-2" class="buy-container hide-private hidden" >
    <div id="buy-private-top-left"><a href="#" id="link-company" name="company" class="hide-company hidden">För företag från 169 kr?</a></div>
    <div class="buy-box-outer">
      <div class="buy-box margin-top">
        <div class="buy-heading">Uppgifter</div>
        <ul class="buy-ul">
          <li><input type="text" name="anamn" id="anamn" class="" minlength="<?php echo Medlem::MIN_LENGTH_ANAMN; ?>" maxlength="<?php echo Medlem::MAX_LENGTH_ANAMN; ?>"  placeholder="Välj alias*"/>
            <span id="mmANamnError" class="invalid hide">Upptaget</span>
          </li>
          <li>
            <select name="sex">
              <option value="kvinna">Kvinna</option>
              <option value="man">Man</option>
            </select>
            <label for="sex" style="margin-left:10px;">Kön</label>
          </li>
          <li>
            <?php echo getKommuner(); ?>
            <label for="kid" style="margin-left:10px;">Startkommun</label>
          </li>
          <li>
            <input type="text" name="email1" id="email1" class="" placeholder="E-post"/>
            <label class="invalid" for="email1" generated="false" style="display: none;">Upptagen epostadress <a href="/pages/glomtlosen.php?email=" > glömt ditt lösenord?</a></label>
            <div id="mmEpostError" class="invalid hide">Upptagen, <a href="/pages/glomtlosen.php?email=" > glömt ditt lösenord?</a></div>
          </li>
          <li><input type="text" name="email2" id="email2" class="" placeholder="E-post igen*"/></li>
          <li><input type="password" name="pass" id="pass" class="" placeholder="Lösenord*"/></li>
          <li><input type="password" name="pass2" id="pass2" class="" placeholder="Lösenord igen*"/></li>
          <li><input type="text" name="firstname" id="firstname" class="required" placeholder="Förnamn*"/></li>
          <li><input type="text" name="lastname" id="lastname" class="required" placeholder="Efternamn*"/></li>
          <li><input type="text" name="co" id="co" class="" placeholder="c/o"/></li>
          <li><input type="text" name="phone" id="phone"  class="required" placeholder="Mobil/telefon*"/></li>
          <li><input type="text" name="street1" id="street1" class="required" placeholder="Adress*"/></li>
          <li><input type="text" name="street2" id="street2" placeholder=""/></li>
          <li><input type="text" name="zip" id="zip" class="" placeholder="Postnummer*"/></li>
          <li><input type="text" name="city" id="city" class="" placeholder="Ort*"/></li>
          <li><input type="text" name="country" id="country" class="" value="Sverige"/>
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
              <td class="kr">kr ex moms</td>
            </tr>
            <tr>
              <td><div id="nbr-without-text"><?php echo $campaignCodes['RE04']['text']; ?><br/><!--span style="color:black;text-decoration: line-through;">169 </span--><span><?php echo $campaignCodes['RE04']['pris']; ?> kr/person</span></div></td>
              <td><input type="text" name="RE04" id="nbr-without" /></td>
              <td><div id="nbr-without-sum"><span class="nbr">0</span></div></td>
              <td class="kr">kr ex moms</td>
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
              <td class="line kr">kr ex moms</td>
            </tr>
            <tr class="hide-company hidden">
              <td></td>
              <td></td>
              <td><div id="nbr-sum-total-freight-moms"><span class="nbr"> 0</span></div></td>
              <td class="kr">kr inkl moms</td>
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
        <div class="early-info">Ring Kristian på 0761-393855 om ni önskar fler veckor.</div>
      </div>
      <div class="buy-box hide-company hidden">
        <div class="buy-heading">Välj ert startdatum (valfri måndag)</div>            
        <!--input name="startdatumRadio" id="startdatumRadio1" type="radio" value="2012-09-24" checked><label for="startdatumRadio1" id="startdatumRadio-label" style="font-size: 13px;display: inline;">Den stora hösttävlingen 24 september</label-->
        <div class="clear"></div>
        <input name="startdatumRadio" id="startdatumRadio2" type="radio" value="egetdatum" checked >
        <select name="startdatum" id="startdatum" onchange="updateStartRadio();">
          <?php echo Order::getMondays(20); ?>
        </select>
        <div class="early-info">Ring Kristian på 0761-393855 om ni önskar tidigare datum.</div>    
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
        <div class="buy-heading">Leverans-adress</div>
        <ul class="buy-ul">
          <li><input type="text" name="del-company" id="del-company" class="required" placeholder="Företagets namn*"/></li>
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
  </div>

  <div id="buy-payment" class="buy-container hidden" >
    <div class="buy-heading">Betalning</div>
    <div id="buy-payment-buttons" >
      <input type="submit" id="payson" class="buy-payment-buttons" name="paytype" value="Betala med" />     
      <input type="submit" id="faktura" class="buy-payment-buttons" name="paytype" value="Betala med faktura" />
    </div>  
    <div id="buy-payment-options">Via Payson kan du betala med följande alternativ:</div>
    <div id="integrity">Genom att fortsätta betalningen godkänner jag <br/><a target="_blank" href="integritetspolicy.php">Motiomeras avtal samt integritetspolicy</a> och är över 18 år</div>
  </div>  

</form>