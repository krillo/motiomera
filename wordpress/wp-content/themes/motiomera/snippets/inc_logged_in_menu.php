<?php
//echo $_SERVER["DOCUMENT_ROOT"] . "../public_html/php/init.php";
//require_once($_SERVER["DOCUMENT_ROOT"] . "../public_html/php/init.php");  

global $USER;
//print_r($USER);
/*
$USER = Medlem::getInloggad();
if (!$USER) {
  unset($USER);
}
 * 
 */
//global $mmStatus;
//print_r($mmStatus);
?>
<div id="mm_id" class="hidden"><?php echo $mmStatus->mm_mid; ?></div>
      <script type="text/javascript">
        jQuery(document).ready(function($){    
          
          var mm_id = $("#mm_id").html(); 
          if(mm_id !== undefined){  
            //MotiomeraMail
            var data = {mm_id : mm_id};
            $.post('/ajax/includes/mailcount.php', data, function(response){
            $("#logged-in-email-id li").append(response);
            });

            //Mina vanner
            var data = {mm_id : mm_id};
            $.post('/ajax/includes/addresscount.php', data, function(response){
            $("#logged-in-friend li").append(response);
            });
          }
            
        });  
      </script>  

    <div id="logged-in-menu">
      <ul>
        <a href="/pages/adressbok.php?tab=3" id="logged-in-friend"><li>Mina vänner</li></a>
        <a href="/pages/mail.php?do=inbox" id="logged-in-email-id" class="logged-in-email"><li><span class="">Motiomeramail</span></li></a>        
        <a href="/actions/logout.php" id="logged-in-logout"><li>Logga ut</li></a>
      </ul>
    </div> 