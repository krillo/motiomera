<?php
/**
  Template Name: Motiomera-report-steps-new
 */
get_header();
?>
<div id="primary" class="site-content">
  <div id="content" role="main">
  <input type="hidden" name="mm_id" id="mm_id" value="<?php echo $mmStatus->mm_mid; ?>" />  
  <?php includeSnippet("inc_steps.php"); ?>
  </div><!-- #content -->
</div><!-- #primary .site-content -->
<div class="clear"></div>
<?php includeSnippet("inc_page_promo_footer.php"); ?>
<?php get_footer(); ?>