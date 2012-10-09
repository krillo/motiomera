<?php
/**
  Template Name: FB js login
 */
get_header();
?>

<div id="primary">
  <div id="content" role="main">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('content', 'page'); ?>
      <?php the_content(); ?>



    
    
    
    

      <?php
      error_reporting(E_ALL);
      ini_set('display_errors', '1');

      require ABSPATH . '../lib/facebook-php-sdk/src/facebook.php';
      $facebook = new Facebook(array(
                  'appId' => '108867119251826',
                  'secret' => 'f8a8d39798810a4f5a51cdb867508ee6',
              ));
      $user = null;
      $user = $facebook->getUser();
      //print_r($user);
      if ($user) {
        try {
          // Proceed knowing you have a logged in user who's authenticated.
          $user_profile = $facebook->api('/me');
        } catch (FacebookApiException $e) {
          //echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
          $user = null;
        }
      }
      ?>


      <h3>PHP Session</h3>
      <pre><?php print_r($_SESSION); ?></pre>
      <?php echo $_SESSION['fb_'.$facebook->getAppID().'_access_token' ]; ?>



      <fb:login-button autologoutlink="true"></fb:login-button>
      <br/>
      <?php if ($user) { ?>
        Your user profile is
        <pre>
          <?php print htmlspecialchars(print_r($user_profile, true)) ?>
                    <fb:logout-button></fb:logout-button>
        </pre>




        <h3>You</h3>
        <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

        <h3>Your User Object (/me)</h3>
        <pre><?php print_r($user_profile); ?></pre>        
      <?php } ?>


      <div id="fb-root"></div>
      <script>
        window.fbAsyncInit = function() {
          FB.init({
            appId: '<?php echo $facebook->getAppID() ?>',
            cookie: true,
            xfbml: true,
            oauth: true
          });
          FB.Event.subscribe('auth.login', function(response) {
            window.location.reload();
          });
          FB.Event.subscribe('auth.logout', function(response) {
            window.location.reload();
          });
        };
        (function() {
          var e = document.createElement('script'); e.async = true;
          e.src = document.location.protocol +
            '//connect.facebook.net/en_US/all.js';
          document.getElementById('fb-root').appendChild(e);
        }());
      </script>






    <?php endwhile; // end of the loop.   ?>
  </div><!-- #content -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>