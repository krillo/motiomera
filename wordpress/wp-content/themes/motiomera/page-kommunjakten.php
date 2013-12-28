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
<script type="text/javascript">
  jQuery(document).ready(function($) {

    var hash = location.hash;
    hashHandler(hash);


    //if hash changes on url and still on same page
    $(window).on('hashchange', function() {
      var hash2 = location.hash;
      hashHandler(hash2);
    });


    function hashHandler(hash) {
      if (hash !== '') {
        hash = hash.replace('#', '');
        if ($("#" + hash).length ) {  //does selector exist on page
          $('html, body').animate({
            scrollTop: $("#" + hash).offset().top - 80
          }, 1000);
          return true;
        }
      }
    }
  });
</script>
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
