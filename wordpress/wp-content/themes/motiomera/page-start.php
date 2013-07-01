<?php
/**
  Template Name: Motiomera-start
 */
$page_promo = get_page_by_title("page-promo");
$pageId = $page_promo->ID;
get_header();
?>
<div id="primary" class="site-content">
  <div id="content" role="main">
    <?php while (have_posts()) : the_post(); ?>
      <div id="info"><?php the_content(); ?></div>     
      <ul id="interest">
        <li><h2><?php the_field("short1", $pageId); ?></h2></li>
        <li><h2><?php the_field("short2", $pageId); ?></h2></li>
        <li><h2><?php the_field("short3", $pageId); ?></h2></li>
        <li><h2><?php the_field("short4", $pageId); ?></h2></li>
      </ul>

      <div id="splash">
        <div id="splash-head"><?php the_field("splash_head", $pageId); ?></div>
        <div id="splash-text"><?php the_field("splash_text", $pageId); ?></div>
      </div>
      
      <div id="buy">
        <?php includeSnippet("inc_buy.php"); ?>
      </div>
      
      <div class="clear"></div>
      <div id="testimonial">        
      </div>
      <div id="counter">        
      </div>      
    <?php endwhile; // end of the loop.    ?>
  </div><!-- #content -->
</div><!-- #primary .site-content -->
<?php get_footer(); ?>