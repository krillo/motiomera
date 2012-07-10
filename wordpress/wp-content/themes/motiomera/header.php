<?php
/**
 * The Header for Motiomera.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Motiomera
 * @since Motiomera 1.0
 */
mm_set_pagestatus();
echo $mmPageStatus->normal_page;

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
            $j('#motiomera-logo-mini').show();
          } else {
            $j('#wp-nav').removeClass('fixed');
            $j('#masthead').removeClass('follow-menu');
            $j('#motiomera-logo-mini').hide();
          }
        });  
      });  
    </script>
    <style>
      .fixed {  
        position: fixed;   
        /*top: 25px; */  
        /*margin-left: 720px;  
        background-color: #0f0 ! important; */
      }
      .follow-menu{padding-top: 40px;}
    </style>

    <nav role="navigation" class="site-navigation main-navigation" id="wp-nav">
      <div id="menu">
        <a href="<?php echo home_url('/'); ?>" title="http://motiomera.se" rel="home"><div id="motiomera-logo-mini" class="hide"></div></a>
        <div id="wp-nav-menu"><?php wp_nav_menu(array('theme_location' => 'primary')); ?></div>
      </div>
    </nav>

    <div id="page" class="hfeed site">  
      <header id="masthead" class="site-header" role="banner">
        <hgroup id="head-logo">
          <a href="<?php echo home_url('/'); ?>" title="http://motiomera.se" rel="home"><div id="logo"></div></a>
          <h1 id="site-description"><?php bloginfo('description'); ?></h1>
        </hgroup>
        <div class="clear"></div>
        <div id="login">
          <div id="login-input">
            <input type="text" id="user" name="user" value="" placeholder="E-postadress" />
            <input type="text" id="pass" name="pass" value="" placeholder="Lösenord" />
          </div>
          <div id="login-checkbox">
            <ul>
              <li><input type="checkbox" id="new-pass" name="new-pass" value="" /><label for="new-pass" >Nytt lösen tack!</label></li>
              <li><input type="checkbox" id="remember-me" name="remember-me" value="" /><label for="remember-me" >Kom ihåg mig</label></li>
            </ul>
          </div>
          <div id="login-buttons">
            <input type="submit" id="login-submit" name="login" value="Logga in &raquo;" />
            <input type="button" id="login-fb" name="login-fb" value="Logga in &raquo;" />
          </div>

        </div>
      </header>
      
      <?php if (is_page() && !is_front_page()){ 
        include "inc_page_promo.php";        
        }?>

      <div id="main">