<script type="text/javascript">
  var actionNyttLosen = "<?php echo MM_SERVER_ROOT_URL; ?>/actions/nyttlosen.php";
  var actionLogin = "<?php echo MM_SERVER_ROOT_URL; ?>/actions/login.php";
  var actionLoginFb = "<?php echo MM_SERVER_ROOT_URL; ?>/actions/loginfb.php";
  jQuery(document).ready(function($) {
    
    // toggle between login and new password
    $('#new-pass').change(function() {  
      var newPassCheck = $('#new-pass:checked').val();   
      if(typeof newPassCheck == 'undefined'){
        $('#login-submit').val('Logga in »');
        $("#login-form").attr("action", actionLogin);
        $("#username").attr("name", 'username');
      } else {
        $('#login-submit').val('Nytt lösen »');
        $("#login-form").attr("action", actionNyttLosen);
        $("#username").attr("name", 'epost');        
      }
    });
        
    //login with facebook
    $("#login-fb").click(function(event) {
      FB.login(function(response) {
        if (response.authResponse) {
          console.log('Welcome!  Fetching your information.... ');
          FB.api('/me', function(response) {
            console.log(response);
            console.log('Good to see you, ' + response.name + '  Email: ' + response.email + '  Id: ' + response.id);            
            var dataString = "fbid=" + response.id + "&email=" + response.email;
            if(dataString==""){
            } else{
              $.ajax({
                type: "POST",
                url: actionLoginFb,
                data: dataString,
                cache: false,
                success: function(data){
                  console.log(data);
                  if(data.loggedin == 1){
                    window.location = "/pages/minsida.php";    
                  }                  
                }
              });
            }
            return false;            
          });
        } else {
          console.log('User cancelled login or did not fully authorize.');
        }
      },{scope: 'email'});
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