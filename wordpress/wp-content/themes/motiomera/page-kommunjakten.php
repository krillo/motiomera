<?php
/**
 * Template Name: Motiomera-kommunjakten
 * 
 * If the user is NOT logged in then the normal post is displayed 
 * else all the kommuns are listed
 * 
 */
get_header();
?>
<div id="primary" class="site-content">
  <div id="content" role="main">
  <input type="hidden" name="mm_id" id="mm_id" value="<?php echo $mmStatus->mm_mid; ?>" />  
  <?php while (have_posts()) : the_post(); ?>
      <article>
        <h1><?php the_title(); ?></h1>
        <?php 
        if(empty($mmStatus->mm_mid)){
          the_field("logged_out_text");
          includeSnippet("inc_kommuner_logged_out.php");
        } else {
          the_field("logged_in_text");
          includeSnippet("inc_kommuner_logged_in.php");
        }
        ?>        
        <?php edit_post_link(); ?>
      </article>
    <?php endwhile;?>
  </div>
</div>
<div class="clear"></div>
<?php includeSnippet("inc_page_promo_footer.php"); ?>
<?php get_footer(); ?>
