<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
$campaignCodes = Order::$campaignCodes;
$email = '';
$fname = '';
$lname = '';
!empty($_REQUEST['mmForetagsnyckel']) ? $nyckel = $_REQUEST['mmForetagsnyckel'] : $nyckel = '';
$user = Medlem::getInloggad();
if(!empty($user)){
  $email = $user->getEpost();
  $fname = $user->getFNamn();
  $lname = $user->getENamn();
}

?>


<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">    
  $(function() {
    //do input validation
    var validator = $("#checkout").validate({
      errorClass: "invalid",
      validClass: "valid",
      rules: {
        firstname: {
          required: true
        },
        lastname: {
          required: true
        },
        mailone: {
          required: true,
          email: true
        }       
      },  
      messages: {
        firstname: {
          required: ''
        },
        lastname: {
          required: ''
        },
        mailone: {
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
        shortRadio = <?php echo $campaignCodes['PRIV3']['pris']; ?>;
        longRadio = <?php echo $campaignCodes['PRIV12']['pris']; ?>;
        
        if(radio == <?php echo $campaignCodes['PRIV3']['pris']; ?>){          
          $('input:#long-check').attr('checked', false);
          longRadio = 0;
          $('#m_priv3').val(1);   
          $('#m_priv12').val(0);   
        }
        if(radio == <?php echo $campaignCodes['PRIV12']['pris']; ?>){            
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
      
          
        $('#m_total').val(sumTotal);        
        $('#m_freight').val(sumFreight);      
      
        
      } else { //radiobutton is undfined do nothing     
        //alert('apa');
      }
    }

 
 
 
  });
    

</script>    


<style>
  #key{ background: none repeat scroll 0 0 #EAF4E2;padding: 4px;
       -moz-border-radius: 10px;
       -webkit-border-radius: 10px;
       -khtml-border-radius: 10px;
       border-radius: 10px;border: #DFEED4 1px solid;
  }
  #key input{font-size: 15px;height: 25px;width: 100px;margin-right: 15px;}
  #key ul{list-style: none;margin-left: 0;padding-left: 0;font-size: 13px;}
  #key li label{margin-left: 15px;margin-right: 10px;padding-left: 0;font-size: 16px;}
</style>

<form action="/actions/medlem_foretagsnyckel.php" method="post" id="key">
  <input type="hidden" name="type" value="foretagsnyckel">  
  <ul id="">
    <li><label for="foretagsnyckel">Företagsnyckel?</label>
      <input id="mmForetagsnyckel" type="text" name="mmForetagsnyckel" value="<?php echo $nyckel; ?>" onfocus="getById('mmFNyckelError').style.display = 'none';" onblur="mm_ajaxValidera('mmForetagsnyckelError', 'foretagsnyckel', this.value);"  />
      <span class="mmFormError mmRed" id="mmForetagsnyckelError">Ogiltig företagsnyckel</span>
      <input id="extend" name="extend" type="hidden" value="true">
      <input id="" type="submit" value="ok">
    </li>
  </ul>
</form >  






<style>
  #checkout-ul h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
  #checkout-ul .h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
  #checkout-ul li{margin-top: 5px;}

  #checkout-ul label{width:130px;font-size: 14px;float:left}
  #checkout-ul{list-style: none;margin-left: 0;padding-left: 0;font-size: 13px;}
  #checkout-ul input{height:18px;font-size: 14px;}
  #checkout-ul a{text-decoration: underline;}
</style>


<style>
  #calc {margin-top: 50px;margin-bottom: 10px;font-size: 14px;border-bottom: 1px solid black;float:left;}
  #calc input{width:18px;height:18px;font-size: 14px;float:left;}
  #calc div{font-size: 14px;float:left;}
  .step-check{margin-left: 20px;}
  #short, #long{margin-bottom: 25px;width:280px;}
  #freight{margin: 0 0 10px 28px;width:253px;}
  #sum-short, #sum-long, #freight-sum{margin-left: 20px;}
  .nbr{font-size: 22px; font-weight: bold;}
  #sum-freight{margin-left:18px;}
  #sum-total{margin-left:300px; float:left;margin-bottom:40px;color:#427F10;}

  #pay {font-size: 14px;margin-top: 45px;}
  #integrity{margin-bottom: 20px;width: 350px;}

  #pay div{float:left;}
  #pay input{font-size: 15px;width:200px;height:25px;}
  #pay ul{margin-left: 2px;padding-left: 15px;}          
</style> 



<div id="member-private" class="">
  <form action="/actions/payson_privat.php" method="post" id="checkout">
    <input type="hidden" name="type" value="medlem_extend">         
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


    <ul id="checkout-ul">
      <li><label for="mailone">E-post</label><input type="text" name="mailone" id="mailone" class="" value="<?php echo $email; ?>" /></li>        
      <li><label for="firstname">Förnamn</label><input type="text" name="firstname" id="firstname" class="" value="<?php echo $fname; ?>"/></li>
      <li><label for="lastname">Efternamn</label><input type="text" name="lastname" id="lastname" class="" value="<?php echo $lname; ?>"/></li>


      <li>
        <div id="pay">
          <div id="integrity">Genom att fortsätta betalningen godkänner jag <a href="/pages/integritetspolicy.php" target="_blank">Motiomeras integritetspolicy</a> och är över 18 år</div>
          <div class="clear"></div>
          <div ><input type="submit" value="Betala" name="paytype" id="payson"></div>
          <div class="clear"></div>
          <div id="payalt">
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
    </ul>




</div> <!-- end member-private -->  