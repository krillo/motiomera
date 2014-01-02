<?php /**
  Template Name: Sida 2 kolumner
 */ ?>
<?php get_header(); ?>
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
        if ($("#" + hash).length) {  //does selector exist on page
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
    <?php while (have_posts()) : the_post(); ?>
      <article>
        <h1><?php the_title(); ?></h1>
        <div class="twocol">
          <?php the_field("col1"); ?>
          <?php edit_post_link(); ?>
        </div>
        <div class="twocol twocol-right">
          <?php the_field("col2"); ?>
        </div>
      </article>
    <?php endwhile; // end of the loop.   ?>
  </div><!-- #content -->
</div><!-- #primary .site-content -->
<div class="clear"></div>
<?php includeSnippet("inc_page_promo_footer.php"); ?>
<?php get_footer(); ?>