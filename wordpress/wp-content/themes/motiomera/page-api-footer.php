<?php
/**
  Template Name: Motiomera-api-footer
 */
?>
<!--link rel='stylesheet' id='style-css'  href='http://mm.dev/wp-content/themes/motiomera/style.css?ver=3.3.2' type='text/css' media='all' /-->
<style>
  footer{background-color: #314D00; height:70px;color:white;}
  footer a, footer a:visited{color:white;} 

  #site-info{width:960px;margin:auto;margin-top: 30px;}
  .motiomera-logo-small{background-image:url('<?php echo bloginfo('template_url'); ?>/img/motiomera_logo_small.png');background-repeat:no-repeat;background-position:0 8px;width: 265px;height: 60px; float:left;}
  #site-info ul{list-style-image: url("<?php echo bloginfo('template_url'); ?>/img/bullet.png");margin: 0;padding-left: 0;}
  #site-info ul li{float: left;margin-right: 25px;padding-left: 0px;}
  #site-info ul li:first-child {list-style: none outside none;}
  #footer-last{background-image:url('<?php echo bloginfo('template_url'); ?>/img/footer.png');background-repeat:no-repeat;background-position: center bottom; height: 360px; }

  .menu-footer-container{padding-top:25px;font-family:verdana;font-size: 11px; float: left;margin-left: 30px;}
  .menu-footer-container a{text-decoration: none;}
</style>
<?php get_footer(); ?>