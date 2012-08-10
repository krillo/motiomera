<?php
/**
 * The Header for Motiomera.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Motiomera
 * @since Motiomera 1.0
 */
mm_status();
global $mmStatus; 
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php
global $page, $paged;
wp_title('|', true, 'right');
bloginfo('name');
// Add the blog description for the home/front page.
$site_description = get_bloginfo('description', 'display');
if ($site_description && ( is_home() || is_front_page() )) {
  echo " | $site_description";
}
?>
    </title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->

    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php do_action('before'); ?>

    <script type="text/javascript">
      var $j = jQuery.noConflict();

      $j(document).ready(function() {  
        var offset = $j('#wp-nav').offset();  
        $j(window).scroll(function () {  
          var scrollTop = $j(window).scrollTop(); // check the visible top of the browser  

          if (offset.top<scrollTop){
            $j('#wp-nav').addClass('fixed');
            $j('#masthead').addClass('follow-menu');
            //$j('#motiomera-logo-mini').show();
          } else {
            $j('#wp-nav').removeClass('fixed');
            $j('#masthead').removeClass('follow-menu');
            //$j('#motiomera-logo-mini').show();
          }
        });  
      });  
    </script>

    <nav role="navigation" class="site-navigation main-navigation" id="wp-nav">
      <div id="menu">
        <a href="<?php echo home_url('/'); ?>" title="http://motiomera.se" rel="home"><div id="motiomera-logo-mini" class=""></div></a>
        <div id="wp-nav-menu"><?php wp_nav_menu(array('theme_location' => 'primary')); ?></div>
      </div>
    </nav>
 
    <div id="page" class="hfeed site">  
      <header id="masthead" class="site-header" role="banner">
        <?php includeSnippet("inc_big_logo_area.php");?>        
        <div class="clear"></div>
        <?php includeSnippet("inc_login_area.php");?>
      
      </header>
      <?php if($mmStatus->normal_page){ include "inc_page_promo_header.php"; }  //normal page - show the promo area ?>
      <div id="main">
        
