<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$campaignCodes = Order::$campaignCodes;
$kommuner = Misc::arrayKeyMerge(array("" => "Välj..."), Kommun::listNamn());

!empty($_REQUEST['mmForetagsnyckel']) ? $nyckel = $_REQUEST['mmForetagsnyckel'] : $nyckel = '';
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
        pass: "required",
        pass2: {
          equalTo: "#pass"
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
        },
        email2: {
          equalTo: ''
        },        
        pass: {
          required: ''
        },        
        pass2: {
          equalTo: ''
        }        
      }
    });


    if('' != '<?php echo $nyckel; ?>'){
      $('#fields-hidden').toggleClass("visible");
      $('#fields-hidden').show("slow");
    }


    //catch keyup where the alias is submitted 
    $('#mmForetagsnyckel').keyup(function() {
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
  #checkout-ul{list-style: none;margin-left: 0; margin-top: 35px;padding-left: 0;font-size: 13px;}
  #checkout-ul input{height:18px;font-size: 14px;}
  #checkout-ul a{text-decoration: underline;}


  #pay {font-size: 14px;margin-top: 35px;}
  #integrity{margin-bottom: 20px;width: 350px;}

  #pay div{float:left;}
  #pay input{font-size: 15px;width:200px;height:25px; margin-left:130px;}
  #pay ul{margin-left: 2px;padding-left: 15px;}    
  .margin{margin-top:30px;}
</style>  




<div id="member-private" class="">
  <form action="/actions/medlem_foretagsnyckel.php" method="post" id="checkout">
    <input type="hidden" name="type" value="foretagsnyckel">  


    <ul id="checkout-ul">
      <li><label for="foretagsnyckel">Din företagsnyckel</label>
        <input id="mmForetagsnyckel" type="text" name="mmForetagsnyckel" value="<?php echo $nyckel; ?>" onfocus="getById('mmFNyckelError').style.display = 'none';" onblur="mm_ajaxValidera('mmForetagsnyckelError', 'foretagsnyckel', this.value);"  />
        <span class="mmFormError mmRed" id="mmForetagsnyckelError">Ogiltig företagsnyckel</span>        
      </li>
      <div class="clear margin"></div>
      <div id="fields-hidden" class="hidden">
        <li><label for="alias">Välj alias</label>
          <input type="text" name="anamn" id="anamn" class="" onfocus="getById('mmANamnError').style.display = 'none';" onblur="mm_ajaxValidera('mmANamnError', 'anamn', this.value);"/>
          <span id="mmANamnError" class="mmRed mmFormError">Upptaget</span>
        </li>      
        <li><label for="mailone">E-post</label>
          <input type="text" name="mailone" id="mailone" class="" onfocus="getById('mmEpostError').style.display = 'none';" onblur="mm_ajaxValidera('mmEpostError', 'epost', this.value);"/>
          <span id="mmEpostError" class="mmRed mmFormError">Upptagen</span><br />
        </li>
        <li><label for="email2">E-post igen</label><input type="text" name="email2" id="email2" class=""/></li><div class="clear"></div>
        <li><label for="pass">Lösenord</label><input type="password" name="pass" id="pass" class=""/></li><div class="clear"></div>
        <li><label for="pass2">Lösenord igen</label><input type="password" name="pass2" id="pass2" class=""/></li><div class="clear"></div>

        <li><label for="firstname">Förnamn</label><input type="text" name="firstname" id="firstname" class=""/></li><div class="clear"></div>
        <li><label for="lastname">Efternamn</label><input type="text" name="lastname" id="lastname" class=""/></li><div class="clear"></div>      

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

        <div class="margin"></div>
        <div id="pay">
          <input type="submit" value="Ok" name="ok" id="payson">
        </div>
      </div> 
    </ul>




</div> <!-- end member-private -->  