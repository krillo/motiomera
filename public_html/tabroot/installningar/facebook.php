<?php global $USER ?>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    var actionLoginFb = "/actions/loginfb.php";

    //login with facebook
    $("#login-fb").click(function(event) {
      FB.login(function(response) {
        if (response.authResponse) {
          //console.log('Welcome!  Fetching your information.... ');
          FB.api('/me', function(response) {
            //console.log(response);
            var data = {
              fbid: response.id,
              email: response.email,
              type: 'connect'
            };
            $.ajax({
              type: "POST",
              url: actionLoginFb,
              data: data,
              cache: false,
              success: function(data) {
                //console.log(data);
                alert(data.msg);
                if(data.status === -1){  //allready connected to another account!? 
                  window.location = "/fragor-och-svar/"; 
                }
              }
            });
            return false;
          });
        } else {
          console.log('Avbrutet av användaren.');
        }
      }, {scope: 'email'});
    });
  });
</script>
<style>
  #login-fb {
    background-image: url("/wp-content/themes/motiomera/img/fb_button.png");
    background-repeat: no-repeat;
    margin-left: 5px;
    overflow: hidden;
    padding-left: 38px;
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #5A7919;
    border-color: #c4d5f3 #16223a #16223a #c4d5f3;
    border-image: none;
    border-radius: 5px 5px 5px 5px;
    border-style: solid;
    border-width: 1px;
    color: #FFFFFF;
    font-size: 21px;
    cursor: pointer;
    padding:8px 10px 12px 38px;
  }

  #login-fb:hover{
    border-color: #16223a #c4d5f3 #c4d5f3 #16223a;
  }


</style>
<h2>Koppla ditt konto till Facebook</h2>
<p>Genom att klicka på knappen och logga in på Facebook kopplar du ihop ditt Motiomera- och Facebookkonto. Du får förenklad inloggning genom Facebook</p>
<input type="button" id="login-fb" name="login-fb" value="Logga in »">