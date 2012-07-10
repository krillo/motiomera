<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


      <ul id = "interest" >
        <?php
        $args = array('post_type' => 'interesting', 'posts_per_page' => 4);
        $loop = new WP_Query($args);
        while ($loop->have_posts()) : $loop->the_post(); ?> 
          <li><?php echo the_content(); ?></li>
        <?php endwhile; ?>
      </ul>