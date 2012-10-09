<?php
/**
  Template Name: FB login js
 */
get_header();
?>

<div id="primary">
  <div id="content" role="main">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('content', 'page'); ?>
      <?php the_content(); ?>


      <script type="text/javascript">
        var js$ = jQuery.noConflict();
        js$(document).ready(function(){
          js$("#login-fb-x").click(function(event) {

       

 FB.login(function(response) {
   if (response.authResponse) {
     console.log('Welcome!  Fetching your information.... ');
     FB.api('/me', function(response) {
       console.log('Good to see you, ' + response.name + '.');
     });
   } else {
     console.log('User cancelled login or did not fully authorize.');
   }
 });


FB.api('/me', function(response) {
  alert('Your name is ' + response.email);
});

          });
        });

      </script>

    
    
      <div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '108867119251826', // App ID
            channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
          });

          // Additional initialization code here
        };

        // Load the SDK Asynchronously
        (function(d){
          var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
          if (d.getElementById(id)) {return;}
          js = d.createElement('script'); js.id = id; js.async = true;
          js.src = "//connect.facebook.net/en_US/all.js";
          ref.parentNode.insertBefore(js, ref);
        }(document));
      </script>




      <input type="button" id="login-fb-x" name="login-fb" value="F Logga in &raquo;" />


    <?php endwhile; // end of the loop.   ?>
  </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>