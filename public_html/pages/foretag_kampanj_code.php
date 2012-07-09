<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
global $SETTINGS;
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$campaignCodes = Order::$campaignCodes;
$kommuner = Misc::arrayKeyMerge(array("" => "Välj..."), Kommun::listNamn());
$actionFile = $SETTINGS["url"]."/api/api_comp_campaign_code.php";
?>


<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">    
  $(function() {
    
    
    /*
     *   const MIN_LENGTH_ANAMN = 3;
  const MIN_LENGTH_FNAMN = 2;
  const MIN_LENGTH_ENAMN = 2;
  const MIN_LENGTH_LOSEN = 4;
  const MAX_LENGTH_FNAMN = 40;
  const MAX_LENGTH_ENAMN = 40;
  const MAX_LENGTH_ANAMN = 20;
     */
    
    
    //do input validation
    var validator = $("#checkout").validate({
      errorClass: "invalid",
      validClass: "valid",
      rules: {
        anamn: {
          required: true,
          maxlength: <?php echo Medlem::MAX_LENGTH_ANAMN; ?>, 
          minlength: <?php echo Medlem::MIN_LENGTH_ANAMN; ?>          
        },
        firstname: {
          required: true,
          maxlength: <?php echo Medlem::MAX_LENGTH_FNAMN; ?>, 
          minlength: <?php echo Medlem::MIN_LENGTH_FNAMN; ?>            
        },
        lastname: {
          required: true,
          maxlength: <?php echo Medlem::MAX_LENGTH_ENAMN; ?>, 
          minlength: <?php echo Medlem::MIN_LENGTH_ENAMN; ?>            
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
          required: '',
          maxlength: 'För långt',
          minlength: 'För kort'          
        },
        lastname: {
          required: '',
          maxlength: 'För långt',
          minlength: 'För kort'
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


  


    //catch keyup where the alias is submitted 
    $('#anamn').keyup(function() {
      $('#fields-hidden').toggleClass("visible");
      $('#fields-hidden').show("slow");
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
 
 
 
 
    //ajax-check if campaign code is valid
    jQuery("#compcampcode").blur(function() {
      var dataString = "compcampcode=" + jQuery("#compcampcode").val();
      var self = jQuery(this);
      jQuery.ajax({
        type: "POST",
        url: "<?php echo $actionFile ?>",
        data: dataString,
        cache: false,
        success: function(id){
          if(id > 0){
            jQuery('#valid').show();
            //jQuery('#valid').html(id);
            jQuery('#invalid').hide();
          } else{
            jQuery('#valid').hide();
            jQuery('#invalid').show();            
          }
        }
      });

      return false;

    });

 
 
 
 
 
 
 
 
 
  });
</script>    









<div id="member-private" class="show">
  <form action="/actions/medlem_foretagskod.php" method="post" id="checkout">
    <input type="hidden" name="type" value="company_campaign">         

    <style>

      #checkout-ul h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
      #checkout-ul .h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
      #checkout-ul li{margin-top: 5px;}

      #checkout-ul label{width:130px;font-size: 14px;float:left}
      #checkout-ul{list-style: none;margin-left: 0;padding-left: 0;font-size: 13px;}
      #checkout-ul input{height:18px;font-size: 14px;}
      #checkout-ul a{text-decoration: underline;}
      
      #pay {font-size: 14px;margin-top: 35px;}
      #integrity{margin-bottom: 20px;width: 350px;}
      #pay div{float:left;}
      #pay input{font-size: 15px;width:200px;height:25px; margin-left:130px;}
      #pay ul{margin-left: 2px;padding-left: 15px;}
      .margin{margin-top:30px;} 
    </style>


    <p class="mmMarginBottomBig">
      För att verifiera dig så ska du slå in först slå in koden som du har fått från din tävlingsansvarig
      i rutan Verikationskod. Fyll därefter i alla registrerings-fält. 

      Ditt konto blir genast aktiverat och du kan börja registrera steg eller andra aktiviteter om du vill.
      Inga steg som registreras innan tävlingsstart kommer att räknas in i tävlingen.

      Motiomera skickar en stegräknare till dig (om ditt företag har beställt det).
    </p>

    <ul id="checkout-ul">
      <li class="mmMarginBottomBig"><label for="alias">Verifikationskod</label>
        <input type="text" name="compcampcode" id="compcampcode" class="" />
        <span id="valid" class="validicon"><img src="/img/icons/gronbock_25.png"></span>
        <span id="invalid" class="mmRed mmFormError">Felaktigt</span>        
      </li>
      <li><label for="alias">Välj alias</label>
        <input type="text" name="anamn" id="anamn" class="" onfocus="getById('mmANamnError').style.display = 'none';" onblur="mm_ajaxValidera('mmANamnError', 'anamn', this.value);"/>
        <span id="mmANamnError" class="mmRed mmFormError">Upptaget</span>
      </li>
      <div class="clear"></div>
      <div id="fields-hidden" class="show">
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
        <li><label for="street1">Gata</label><input type="text" name="street1" id="street1"/></li><div class="clear"></div>
        <li><label><a href="" id="address-toggle">Fler rader ?</a></label> 
          <div id="extra-address"class="hidden">
            <input type="text" name="street2" id="street2"/><div class="clear"></div>
            <label>&nbsp;</label><input type="text" name="street3" id="street3"/>
          </div>
        </li>
        <div class="clear"></div>
        <li><label for="zip">Postnummer</label><input type="text" name="zip" id="zip"/></li><div class="clear"></div>
        <li><label for="city">Ort</label><input type="text" name="city" id="city"/></li><div class="clear"></div>
        <li><label><a href="" id="country-toggle">Land</a></label><input type="text" name="country" id="country" class="" value="Sverige"/></li>
        <div class="clear"></div>           

        <div id="margin"></div>
        <div class="margin"></div>
        <div id="pay">
          <input id="payson" type="submit" name="ok" value="Ok">
        </div>

      </div> 
    </ul>




</div> <!-- end member-private -->  