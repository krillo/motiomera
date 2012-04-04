<?php
$keyhelp = Help::loadById(29);
$urlHandler = new UrlHandler();

//$campaignCodes = Order::getCampaignCodes("foretag");
$campaignCodes = Order::$campaignCodes;
$moms = Order::$moms;
?>	

<h3>Tilläggsbeställning</h3>
<p>
  Här kan du anmäla fler deltagare till tävlingen. Vi skickar ut nya stegräknare och deltagarbrev så fort vi hinner. <b>Glöm inte att lägga in de nya deltagarna i rätt lag</b> efter att de har aktiverat sina MotioMera-konton.
</p>
<br/>



<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">    
  $(function() {

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

      $('#m_exmoms').val(sumTotal);
          
      $('#m_total').val(sumTotalFreight);        
      $('#m_incmoms').val(sumTotalFreightMoms);
    }
    
    
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
    
    
  });   
</script>   





<form action="/actions/payson_foretag_tillagg.php" method="get" id="checkout">
  <input type="hidden" name="typ" value="foretag_tillagg">
  <input type="hidden" name="fid" value="<?= $foretag->getId() ?>">
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

    #refcode-div{margin-top: 20px;margin-bottom: 35px;}
    #refcode-toggle{font-size: 13px;margin-right: 20px;text-decoration: underline;}

  </style>




  <ul id="checkout-ul">
    <li id="calc">
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
  </ul>  

  <div class="clear"></div>  
  <div id="refcode-div"><label><a href="" id="refcode-toggle">Referenskod på kvittot?</a></label><input type="text" name="refcode" id="refcode" class="hidden"/></div>
  <div class="clear"></div>


  <style>
    #pay {font-size: 14px;}
    #pay div{float:left;}
    #pay input{font-size: 15px;width:200px;}
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
  </form>

  <a href="javascript:;" onclick="mm_rapportera_show_help(29,<?= $keyhelp->getSizeX() ?>,<?= $keyhelp->getSizeY() ?>,'topleft')" title="Hj&auml;lp"><img src="/img/icons/FaqCircleRed.gif" alt="Hjälp" class="mmFloatRight" /></a>



