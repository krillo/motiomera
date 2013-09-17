<?php
/*
 * The FAQ custom posttype page
 */
?>
<?php get_header(); ?>
<div id="primary" class="site-content">
  <div id="content" role="main">
    <?php while (have_posts()) : the_post(); ?>
      <article id="faq">
        <h1><?php the_title(); ?></h1>
        <div><?php the_content(); ?></div>
        <div class="faq-head">Fråga:</div>
        <ul>
        <?php
        $mypages = get_pages(array('child_of' => $post->ID, 'sort_column' => 'post_date', 'sort_order' => 'desc'));
        foreach ($mypages as $page) {
          $content = $page->post_content;
          if (!$content) { // Check for empty page
            continue;
          }
          $content = apply_filters('the_content', $content);
          ?>
          <li><h2><a href="<?php echo get_page_link($page->ID); ?>"><?php echo $page->post_title; ?></a></h2></li>
          <li><div class=""><?php echo $content; ?></div></li>
          <?php
        }
        ?>
</ul>



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
<?php
if ($mmStatus->normal_page) {
  include "inc_page_promo_footer.php";
}  //normal page - show the promo area 
?>
<?php get_footer(); ?>