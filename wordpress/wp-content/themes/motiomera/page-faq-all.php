<?php
/*
  Template Name: FAQ all jquery
 */
?>
<?php get_header(); ?>
<script>
  //var $j = jQuery.noConflict();
  $j(function() {    
    $j("#faq-common" ).accordion({ active: false });
  });
  $j(function() {    
    $j("#faq-company" ).accordion({ active: false });
  });
</script>


<div id="primary" class="site-content">
  <div id="content" role="main">
    <?php while (have_posts()) : the_post(); ?>
      <article id="faq">
        <h1><?php the_title(); ?></h1>
        <div id="faq-header"><?php the_content(); ?></div>
        <div class="faq-head">Almänna frågor:</div>
        <div id="faq-common" class="indent">
          <?php
          $args = array('post_type' => 'faq');
          $loop = new WP_Query($args);
          while ($loop->have_posts()) : $loop->the_post();
            if (!get_field("company")):
              ?>
              <h2 class="question"><?php the_title(); ?>?</h2> 
              <div class="faq-answer"><?php the_content(); ?></div>
            <?php endif; ?>
          <?php endwhile; ?>
        </div>
        <div class="clear"></div>
        <div class="faq-head">Frågor om stegtävling för företag:</div>
        <div id="faq-company" class="indent">
          <?php
          $args = array('post_type' => 'faq');
          $loop = new WP_Query($args);
          while ($loop->have_posts()) : $loop->the_post();
            if (get_field("company")):
              ?>
              <h2 class="question"><?php the_title(); ?>?</h2>
              <div class="faq-answer"><?php the_content(); ?></div>
            <?php endif; ?>
          <?php endwhile; ?>

        </div>

      </article>
    <?php endwhile; // end of the loop.   ?>


  </div><!-- #content -->
</div><!-- #primary .site-content -->
<div class="clear"></div>
<?php includeSnippet("inc_page_promo_footer.php");?>
<?php get_footer(); ?>


