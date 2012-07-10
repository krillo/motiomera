<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the id=main div and all content after
 *
 * @package Motiomera
 * @since Motiomera 1.0
 */
?>
</div><!-- #main -->
</div><!-- #page .hfeed .site -->
<footer id="colophon" class="site-footer" role="contentinfo">  
  <div id="site-info" class="">
    <a href="<?php echo home_url('/'); ?>" title="http://motiomera.se" rel="home"><div class="motiomera-logo-small"></div></a>
      <?php wp_nav_menu(array('menu' => 'footer')); ?>
  </div><!-- .site-info -->
</footer><!-- .site-footer .site-footer -->
<div id="footer-last"></div>
<?php wp_footer(); ?>
</body>
</html>