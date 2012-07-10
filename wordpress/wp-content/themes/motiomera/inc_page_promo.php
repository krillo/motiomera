<?php
/*
 * This page shows the "page-promo", an area on a normal page (not the front page)
 */
$page_promo = get_page_by_title("page-promo");
?>
<div id="page-promo">
  <div id="page-promo-left"><?php echo get_field("big_text", $page_promo->ID); ?></div>
  <div id="page-promo-right">
    <?php $image = wp_get_attachment_image_src(get_field('bild', $page_promo->ID), 'thumbnail');
    if (is_array($image)): ?>
      <img src="<?php echo $image[0]; ?>" alt="stegräknare" />
    <?php endif; ?>
    <div id="page-promo-right-one"><?php echo get_field("text_one_right", $page_promo->ID); ?></div>
    <div id="page-promo-right-two"><?php echo get_field("text_two_right", $page_promo->ID); ?></div>
    <a href="http://google.com"><div id="page-promo-right-private" class="page-promo-button"><?php echo get_field("link_private", $page_promo->ID); ?></div></a>
    <a href=""><div id="page-promo-right-company" class="page-promo-button"><?php echo get_field("link_company", $page_promo->ID); ?></div></a>
  </div>
</div>
<div class="clear"></div>
