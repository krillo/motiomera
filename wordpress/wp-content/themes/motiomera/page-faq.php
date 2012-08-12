<?php
/*
  Template Name: FAQ
 */
?>
<?php get_header(); ?>
<div id="primary" class="site-content">
  <div id="content" role="main">
    <?php while (have_posts()) : the_post(); ?>
      <article id="faq">
        <div class="faq-head">Fråga:</div>
        <h1><?php the_title(); ?>?</h1>
        <div class="clear"></div>
        <div class="faq-head">Svar:</div>
        <div class="clear"></div>
        <div><?php the_content(); ?></div>
        <div class="clear"></div>
        <a href="/faq/" class="faq-a">Vanliga frågor</a>
      </article>
    <?php endwhile; // end of the loop. ?>
  </div><!-- #content -->
</div><!-- #primary .site-content -->
<div class="clear"></div>
<?php if ($mmStatus->normal_page) {
  include "inc_page_promo_footer.php";
}  //normal page - show the promo area ?>
<?php get_footer(); ?>
