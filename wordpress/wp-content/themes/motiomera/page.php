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
          <h1><?php the_title(); ?></h1>
          <?php the_content(); ?>
        </article>
				<?php endwhile; // end of the loop. ?>
			</div><!-- #content -->
		</div><!-- #primary .site-content -->
    <div class="clear"></div>
    <?php includeSnippet("inc_page_promo_footer.php");?>
<?php get_footer(); ?>