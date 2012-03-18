<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
$campaignCodes = Order::$campaignCodes;
$kommuner = Misc::arrayKeyMerge(array("" => "Välj..."), Kommun::listNamn());
?>


<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">    
  $(function() {
    //do input validation
    var validator = $("#checkout").validate({
      errorClass: "invalid",
      validClass: "valid",
      rules: {
        anamn: {
          required: true
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
        anamn: {
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
        mailone: {
          required: '', 
          email: ''
        }         
      }
    });



    //catch keyup where the alias is submitted 
    $('#anamn').keyup(function() {
      $('#fields-hidden').toggleClass("visible");
      $('#fields-hidden').show("slow");
    });


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
 
 
  });
    

</script>    


<style>
  #checkout-ul h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
  #checkout-ul .h2{margin-bottom: 12px;display:block;margin-top: 45px;font-size:18px;}
  #checkout-ul li{margin-top: 5px;}

  #checkout-ul label{width:130px;font-size: 14px;float:left}
  #checkout-ul{list-style: none;margin-left: 0;padding-left: 0;font-size: 13px;}
  #checkout-ul input{height:18px;font-size: 14px;}
  #checkout-ul a{text-decoration: underline;}


  #pay {font-size: 14px;margin-top: 45px;}
  #integrity{margin-bottom: 20px;width: 350px;}

  #pay div{float:left;}
  #pay input{font-size: 15px;width:200px;height:25px;}
  #pay ul{margin-left: 2px;padding-left: 15px;}          
</style>  




<div id="member-private" class="">
  <form action="/actions/payson_privat.php" method="get" id="checkout">
    <input type="hidden" name="type" value="foretag">  


    <ul id="checkout-ul">
      <li><label for="alias">Din företagsnyckel</label>
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

        <li><label for="firstname">Förnamn</label><input type="text" name="firstname" id="firstname" class=""/></li><div class="clear"></div>
        <li><label for="lastname">Efternamn</label><input type="text" name="lastname" id="lastname" class=""/></li><div class="clear"></div>
        <li><label><a href="" id="co-toggle">c/o ?</a></label><input type="text" name="co" id="co" class="hidden"/></li><div class="clear"></div>         
        <li><label for="email">E-post</label>
          <input type="text" name="mailone" id="mailone" class="" onfocus="getById('mmEpostError').style.display = 'none';" onblur="mm_ajaxValidera('mmEpostError', 'epost', this.value);"/>
          <span id="mmEpostError" class="mmRed mmFormError">Upptagen</span><br />
        </li>
        <div class="clear"></div>
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
        <li><input type="submit" value="Ok" name="ok" id="payson"></li>

      </div> 
    </ul>




</div> <!-- end member-private -->  