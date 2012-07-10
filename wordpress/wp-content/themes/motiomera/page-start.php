<?php
/**
  Template Name: Motiomera-start
 */
get_header();
?>
<div id="primary" class="site-content">
  <div id="content" role="main">
    <?php while (have_posts()) : the_post(); ?>
      <div id="info"><?php the_content(); ?></div>     
      <ul id="interest">
        <li><h2><?php the_field("short1"); ?></h2></li>
        <li><h2><?php the_field("short2"); ?></h2></li>
        <li><h2><?php the_field("short3"); ?></h2></li>
        <li><h2><?php the_field("short4"); ?></h2></li>
      </ul>



      <div id="splash">
        <div id="splash-head"><?php the_field("splash_head"); ?></div>
        <div id="splash-text"><?php the_field("splash_text"); ?></div>
      </div>
      
      
      
      <div id="buy">
        <div id="buy-company">
          <div id="buy-company-top">FÖRETAGSPAKET <a href="">läs mer om stegtävlingen</a></div>
          <div id="buy-company-price">
            5 veckors stegtävling från 
            <div id="nbr-sum-total-freight">
              <span class="nbr">179</span><div class="currency">kr<br/>ex moms</div>
            </div></div>
          <div id="buy-company-calc">
            <div id="buy-company-calc-text">Är du företagsledare eller peronalansvarig? Anmäl dig och dina anställda  till Sveriges roligaste stegtävling för företag. </div>
            <div id="buy-company-calc-input">
              välja antal <input type="text" id="" name="" value="" placeholder="4" /> <br>välja antal <input type="text" id="" name="" value="" placeholder="4" /> </div>
          </div>
        </div>
        <div id="buy-private">
          <div id="buy-private-top">UTMANA DIG SJÄLV OCH DINA VÄNNER</div>
          <div id="buy-private-price">
            3 månaders MotioMera från 
            <div id="nbr-sum-total-freight">
              <span id="priv-the-price">79</span>kr
            </div>
          </div>
          <?php include 'inc_private_calc.php'; ?>
          


          
          
        </div>
      </div>
      <div class="clear"></div>
      <div id="testimonial">        
      </div>
      <div id="counter">        
      </div>      
    <?php endwhile; // end of the loop.    ?>
  </div><!-- #content -->
</div><!-- #primary .site-content -->
<?php get_footer(); ?>