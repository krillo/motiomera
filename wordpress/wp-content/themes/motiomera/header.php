<?php
/**
 * The Header for Motiomera.
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Motiomera
 * @since Motiomera 1.0
 */
mm_status();
global $mmStatus;
//print_r($mmStatus);
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width" />
    <link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" type="image/x-icon" />
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
    <div id="mm_url" style="display: none;"><?php echo home_url(''); ?></div>
    <?php includeSnippet("inc_fb_root.php"); ?>       

    <script type="text/javascript">
      jQuery(document).ready(function($) {  
        var offset = $('#wp-nav').offset();  
        $(window).scroll(function () {  
          var scrollTop = $(window).scrollTop(); // check the visible top of the browser  

          if (offset.top<scrollTop){
            $('#wp-nav').addClass('fixed');
            $('#masthead').addClass('follow-menu');
            //$('#motiomera-logo-mini').show();
          } else {
            $('#wp-nav').removeClass('fixed');
            $('#masthead').removeClass('follow-menu');
            //$('#motiomera-logo-mini').show();
          }
        });  
      });  
    </script>

    <nav role="navigation" class="site-navigation main-navigation <?php isLoggedIn(); ?>" id="wp-nav">
      <div id="menu">
        <a href="<?php echo home_url('/'); ?>" title="MotioMera.se stegtävling med stegräknare" rel="home"><div id="motiomera-logo-mini" class=""></div></a>
        <div id="wp-nav-menu"><?php includeSnippet("main_menu"); ?> </div>
      </div>
      <?php includeSnippet("inc_logged_in_menu.php"); ?>           
    </nav>
    <div class="clear"></div>

    <div id="page" class="hfeed site">  
      <header id="masthead" class="site-header" role="banner">
        <?php includeSnippet("inc_big_logo_area.php"); ?>        
        <div class="clear"></div>
        <?php includeSnippet("inc_login_area.php"); ?>
      </header>
      <?php includeSnippet("inc_page_promo_header.php"); ?>         
      <div id="main">