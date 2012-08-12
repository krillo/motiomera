<?php
/*
 * Show big logo area or smaller
 */
if ($mmStatus->front_page): ?>
  <hgroup id="head-logo">
    <a href="<?php echo home_url('/'); ?>" title="http://motiomera.se" rel="home"><div id="logo"></div></a>
    <h1 id="site-description"><?php bloginfo('description'); ?></h1>
  </hgroup>
<?php else: ?>
<div style="height: 30px; min-height: 100%; width: 960px; min-width: 100%;float:left;"></div>
<?php endif; ?>
