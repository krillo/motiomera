<script type="text/javascript">
  // toggle between login and new password
  var actionNyttLosen = "<?php echo MM_SERVER_ROOT_URL; ?>/actions/nyttlosen.php";
  var actionLogin = "<?php echo MM_SERVER_ROOT_URL; ?>/actions/login.php";
  var $j = jQuery.noConflict();
  $j(document).ready(function() {
    $j('#new-pass').change(function() {  
      var newPassCheck = $j('#new-pass:checked').val();   
      if(typeof newPassCheck == 'undefined'){
        $j('#login-submit').val('Logga in »');
        $j("#login-form").attr("action", actionLogin);
        $j("#username").attr("name", 'username');
      } else {
        $j('#login-submit').val('Nytt lösen »');
        $j("#login-form").attr("action", actionNyttLosen);
        $j("#username").attr("name", 'epost');        
      }
    });
  });  
</script>
<div id="login">
  <form id="login-form" action="<?php echo MM_SERVER_ROOT_URL; ?>/actions/login.php" method="post">
    <div id="login-input">
      <input type="text" id="username" name="username" value="" placeholder="E-postadress" />
      <input type="password" id="password" name="password" value="" placeholder="Lösenord" />
    </div>
    <div id="login-checkbox">
      <ul>
        <li><input type="checkbox" id="new-pass" name="new-pass" value="1" /><label for="new-pass" >Nytt lösen tack!</label></li>
        <li><input type="checkbox" id="autologin" name="autologin" value="on" /><label for="autologin" >Kom ihåg mig</label></li>
      </ul>
    </div>
    <div id="login-buttons">
      <input type="submit" id="login-submit" name="login" value="Logga in &raquo;" />
      <input type="button" id="login-fb" name="login-fb" value="Logga in &raquo;" />
    </div>
  </form>
</div>      



<?php
//global $mmStatus;
//print_r($mmStatus);
  echo '<a href="'. $mmStatus->fb_login_url . '"> Facebook login </a> ';
  ?>