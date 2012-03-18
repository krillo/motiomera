<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
$campaignCodes = Order::$campaignCodes;
$moms = Order::$moms;
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


    $('#short-radio').change(function() {
      sum(); 
    });
    $('#long-radio').change(function() {
      sum(); 
    });
    $('#short-check').change(function() {
      sum(); 
    });
    $('#long-check').change(function() {
      sum(); 
    });





 
    function sum(){
      radio  = $('input:radio[name=radio-priv]:checked').val();
      if(typeof radio != 'undefined'){  //one of the radios are checked
        shortRadio = <?php echo $campaignCodes['PRIV3'][pris]; ?>;
        longRadio = <?php echo $campaignCodes['PRIV12'][pris]; ?>;
        
        if(radio == <?php echo $campaignCodes['PRIV3'][pris]; ?>){          
          $('input:#long-check').attr('checked', false);
          longRadio = 0;
          $('#m_priv3').val(1);   
          $('#m_priv12').val(0);   
        }
        if(radio == <?php echo $campaignCodes['PRIV12'][pris]; ?>){            
          $('input:#short-check').attr('checked', false);
          shortRadio = 0;
          $('#m_priv12').val(1);
          $('#m_priv3').val(0);
        }
        shortCheck = $('input:#short-check:checked').val();
        longCheck = $('input:#long-check:checked').val();        
        if(typeof shortCheck == 'undefined'){
          shortCheck = 0;
        }
        if(typeof longCheck == 'undefined'){
          longCheck = 0;
        } 
        //alert('radio: ' + radio + ' shortCheck: ' + shortCheck+ ' longCheck: ' + longCheck);  
        if(shortCheck != 0 || longCheck != 0){
          sumFreight = parseInt(<?php echo $campaignCodes['FRAKT02'][pris]; ?>);
          $('#m_frakt02').val(1);
        } else{
          sumFreight = 0;
          $('#m_frakt02').val(0);
        }
          
        sumShort = parseInt(shortRadio) + parseInt(shortCheck);
        sumLong = parseInt(longRadio) + parseInt(longCheck);        
        sumTotal = sumShort + sumLong + sumFreight;
      
        $('#sum-short span').html(sumShort);
        $('#sum-long span').html(sumLong);
        $('#sum-freight span').html(sumFreight);
        $('#sum-total span').html(sumTotal);
      
          
        $('#m_total').val(sumTotal);        
        $('#m_freight').val(sumFreight);      
      
        
      } else { //radiobutton is undfined do nothing     
        //alert('apa');
      }
    }


    $('#private-toggle').click(function(event) {
      event.preventDefault(); 
      if($('#member-private').hasClass("visible")){
        $('#member-private').toggleClass("visible");
        $('#member-private').hide("slow");
      }else {
        $('#member-private').toggleClass("visible");
        $('#member-private').show("slow");
      }       
    });   


    //sum with and without stepcounter, add freight and moms
    function sum_x(){
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
        
        
        
    //catch keyup where the companyname is submitted 
    $('#company').keyup(function() {
      $('#fields-hidden').toggleClass("visible");
      $('#fields-hidden').show("slow");
    });
        
        
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
 

 
 
  });
    

</script>    









<style>
  #type {font-size: 14px;margin-top: 40px;}
  #type div{float:left;}
  #type input{font-size: 15px;width:200px;}
  #or{font-size: 15px; margin:0 30px 0 30px;}
</style>
<div id="type">
  <div >
    <form name="foretagsnyckel-form" action="/pages/foretagsnyckel.php">
      <input type="submit" value="Företagsnyckel" name="foretagsnyckel" />
    </form>
  </div>
  <div id="or">
    eller
  </div>
  <div>
    <input type="button" value="Privatperson" name="foretagsnyckel" id="private-toggle"/>
  </div>
</div>
<div class="clear"></div>



<div id="member-private" class="hide">
  <form action="/actions/payson_privat.php" method="get" id="checkout">
    <input type="hidden" name="type" value="foretag">  
    <input type="hidden" name="m_freight" id="m_freight"  value="">         
    <input type="hidden" name="m_total"   id="m_total" value="">        
    <input type="hidden" name="m_frakt02"   id="m_frakt02" value="">        
    <input type="hidden" name="m_priv3"   id="m_priv3" value="">        
    <input type="hidden" name="m_priv12"   id="m_priv12" value="">        



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


    <style>
      #calc {margin-top: 100px;margin-bottom: 10px;font-size: 14px;border-bottom: 1px solid black;float:left;}
      #calc input{width:18px;height:18px;font-size: 14px;float:left;}
      #calc div{font-size: 14px;float:left;}
      .step-check{margin-left: 20px;}
      #short, #long{margin-bottom: 25px;width:280px;}
      #freight{margin: 0 0 10px 28px;width:253px;}
      #sum-short, #sum-long, #freight-sum{margin-left: 100px;}
      .nbr{font-size: 22px; font-weight: bold;}
      #sum-freight{margin-left:98px;}
      #sum-total{margin-left:379px; float:left;margin-bottom:40px;}
    </style>  
    <div id="calc">


      <div id="short">
        <input type="radio" id="short-radio" name="radio-priv" value="<?php echo $campaignCodes['PRIV3'][pris]; ?>" /><div id="short-text"><?php echo $campaignCodes['PRIV3'][text]; ?><span > <?php echo $campaignCodes['PRIV3'][pris]; ?> kr</span></div>
        <div class="clear"></div>
        <div id="" class="step-check"><input type="checkbox" id="short-check" name="short-check-step" value="<?php echo $campaignCodes['STEG01'][pris]; ?>" /><div id="short-text"><?php echo $campaignCodes['STEG01'][text]; ?><span> +<?php echo $campaignCodes['STEG01'][pris]; ?> kr</span></div></div>    
      </div>
      <div id="sum-short"><span class="nbr">0</span> kr</div>
      <div class="clear"></div>
      <div id="long">
        <input type="radio" id="long-radio" name="radio-priv" value="<?php echo $campaignCodes['PRIV12'][pris]; ?>" /><div id="long-text"><?php echo $campaignCodes['PRIV12'][text]; ?><span > <?php echo $campaignCodes['PRIV12'][pris]; ?> kr</span></div>
        <div class="clear"></div>
        <div id=""class="step-check"><input type="checkbox" id="long-check" name="long-check-step" value="<?php echo $campaignCodes['STEG01'][pris]; ?>" /><div id="long-text"><?php echo $campaignCodes['STEG01'][text]; ?><span> +<?php echo $campaignCodes['STEG01'][pris]; ?> kr</span></div></div>    
      </div>
      <div id="sum-long"><span class="nbr">0</span> kr</div>
      <div class="clear"></div>
      <div id="freight">Frakt (<?php echo $campaignCodes['FRAKT02'][pris]; ?> kr)</div><div id="sum-freight"><span class="nbr">0</span> kr</div>
      <div class="clear"></div>
    </div>
    <div id="sum-total"><span class="nbr">0</span> kr</div>



    <ul id="checkout-ul">
      <div id="fields-hidden" class="">

        <li><label for="firstname">Förnamn</label><input type="text" name="firstname" id="firstname" class=""/></li><div class="clear"></div>
        <li><label for="lastname">Efternamn</label><input type="text" name="lastname" id="lastname" class=""/></li><div class="clear"></div>
        <li><label><a href="" id="co-toggle">c/o ?</a></label><input type="text" name="co" id="co" class="hidden"/></li><div class="clear"></div>         
        <li><label for="email">E-post</label><input type="text" name="email" id="email" class=""/></li><div class="clear"></div>
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
      </div>
    </ul>


    
  <style>
    #pay {font-size: 14px;margin-top: 45px;}
    #pay div{float:left;}
    #pay input{font-size: 15px;width:200px;height:25px;}
    #pay ul{  list-style: none outside none;margin-left: 2px;padding-left: 15px;}    
    #or{font-size: 15px; margin:0 30px 0 30px;}
  </style>


  <div id="pay">
    <div >
      <input type="submit" value="Direktbetalning" name="paytype" id="payson">

    </div>
    <div id="or">eller</div>
    <div >      
      <input type="submit" value="Faktura" name="paytype" id="faktura">
    </div>  
    <div class="clear"></div>
    <div id="payalt">
      <ul>
        <li>VISA / MasterCard </li>
        </li>Internetbank:</li> 
        <ul>
          <li>Föreningssparbanken</li> 
          <li>Swedbank</li>
          <li>Handelsbanken </li>
          <li>SEB </li>
          <li>Nordea </li>
        </ul>
      </ul>
    </div>  
  </div>    


</div> <!-- end member-private -->  