<?php
/**
 * Template Name: Motiomera-api-menu
 * Use this page to get get an show the wp-menu in core mm-pages
 */
?>
<script type="text/javascript">
  jQuery(document).ready(function($) {  
    var offset = $('#wp-nav').offset();  
    $(window).scroll(function () {  
      var scrollTop = $(window).scrollTop(); // check the visible top of the browser  

      if (offset.top<scrollTop){
        $('#wp-nav').addClass('fixed');
        $('#masthead').addClass('follow-menu');
      } else {
        $('#wp-nav').removeClass('fixed');
        $('#masthead').removeClass('follow-menu');
      }
    });  
  });  
</script>

<nav role="navigation" class="site-navigation main-navigation" id="wp-nav">
  <div id="menu">
    <a href="<?php echo home_url('/'); ?>" title="http://motiomera.se" rel="home"><div id="motiomera-logo-mini" class=""></div></a>
    <!--div id="wp-nav-menu">< ?php wp_nav_menu(array('theme_location' => 'primary')); ? ></div-->
    <div id="wp-nav-menu"><?php wp_nav_menu(array('theme_location' => 'header_logged_in')); ?></div>
  </div>
  <?php includeSnippet("inc_logged_in_menu.php"); ?>           
</nav>
<div class="clear"></div> 