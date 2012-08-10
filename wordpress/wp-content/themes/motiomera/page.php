<?php
/**
 * The template for displaying normal pages.
 * *
 * @package Motiomera
 * @since Motiomera 1.0
 */?>
<?php get_header(); ?>
		<div id="primary" class="site-content">
			<div id="content" role="main">
				<?php while ( have_posts() ) : the_post(); ?>
        
        <article>
          <?php the_content(); ?>
        </article>
				<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #primary .site-content -->
    <div class="clear"></div>
<?php if($mmStatus->normal_page){ include "inc_page_promo_footer.php"; }  //normal page - show the promo area ?>
<?php get_footer(); ?>