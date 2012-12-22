<?php
/**
  Template Name: Motiomera-api-menu
 */
?>

<!--link rel='stylesheet' id='style-css'  href='http://mm.dev/wp-content/themes/motiomera/style.css?ver=3.3.2' type='text/css' media='all' /-->
<style>
  #site-info{width:960px;margin:auto;margin-top: 30px;}

  #motiomera-logo-mini {
    background-image: url("<?php echo bloginfo('template_url'); ?>/img/motiomera_logo_mini.png");
    background-repeat: no-repeat;
    float: left;
    height: 40px;
    width: 180px;
  } 
  .main-navigation {
    clear: both;
    display: block;
    float: left;
    width: 100%;
  }
  nav {
    background-color: #314D00;
    height: 40px;
    margin: auto;
    width: 1800px;
    z-index: 100;
  }
  #menu {
    margin: auto;
    width: 960px;
  }
  .main-navigation a {
    color: white;
    display: block;
    text-decoration: none;
  }
  #wp-nav-menu {
    float: right;
    font-family: Verdana;
    font-size: 12px;
    margin-bottom: 12px;
    padding-top: 10px;
    text-transform: uppercase;
  }
  .main-navigation ul {
    list-style-image: url("<?php echo bloginfo('template_url'); ?>/img/bullet.png");
    margin: 0;
    padding-left: 0;
  }
  .main-navigation li:first-child {
    list-style: none outside none;
  }
  .main-navigation li {
    float: left;
    margin-right: 36px;
    padding-left: 10px;
    position: relative;
  }
 
  .main-navigation a {
    color: white;
    display: block;
    text-decoration: none;
  } 
  .fixed {position: fixed;}
  
  
  
#logged-in-menu{margin: auto;width: 960px;}
#logged-in-menu ul{float:right;list-style: none;padding-left: 0;background-color: #314D00;color:#fff;font-family: arial;font-size: 12px;
   margin: 0 0 15px 0;
  -webkit-border-bottom-left-radius: 10px;
  -webkit-border-bottom-right-radius: 10px;
  -moz-border-radius-bottomleft: 10px;
  -moz-border-radius-bottomright: 10px;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
  border-bottom: #768d4a solid 1px;  
  border-right: #768d4a solid 1px;  
  border-top: #768d4a dashed 1px;  
}

#logged-in-menu ul a{display: inline;    color: white;text-decoration: none;}
#logged-in-menu ul a:hover{color: #768d4a;}

#logged-in-menu ul li{float: left;margin:0;padding:10px 15px 10px 40px;border-right:#768d4a dashed 1px;}
#logged-in-menu ul li:last-child{border-right: none;}
#logged-in-friend li{background-image:url('<?php echo bloginfo('template_url'); ?>/img/friends_icon.png');background-repeat:no-repeat;background-position:10px 7px;}
#logged-in-email li{background-image:url('<?php echo bloginfo('template_url'); ?>/img/email_icon.png');background-repeat:no-repeat;background-position:10px 7px;}
#logged-in-logout li{background-image:url('<?php echo bloginfo('template_url'); ?>/img/logout_icon.png');background-repeat:no-repeat;background-position:10px 7px;}
#logged-in-friend li:hover{background-image:url('<?php echo bloginfo('template_url'); ?>/img/friends_icon_hover.png');}
#logged-in-email li:hover{background-image:url('<?php echo bloginfo('template_url'); ?>/img/email_icon_hover.png');}
#logged-in-logout li:hover{background-image:url('<?php echo bloginfo('template_url'); ?>/img/logout_icon_hover.png');}  





</style>


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
    <div id="wp-nav-menu"><?php wp_nav_menu(array('theme_location' => 'primary')); ?></div>
  </div>
  <?php includeSnippet("inc_logged_in_menu.php"); ?>           
</nav>
<div class="clear"></div>
 