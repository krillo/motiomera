<?php
/**
  Template Name: Motiomera-links
 */
get_header();
?>

<div id="container">
  <div id="content" role="main">

    <?php while (have_posts()) : the_post(); ?>

    <article>
      <?php
      /*
      $userId = get_current_user_id();
      if ($userId != 0) {
        echo "userid = " . $userId;
      } else {
        echo "Not allowed";
      }
       * 
       */
      ?>

      <?php
      require_once($_SERVER["DOCUMENT_ROOT"] . "/../public_html/php/init.php");

      print_r($USER);
      ?>


      <ul>
        <li><a href="/">HEM</a></li>
        <li class="mmMenuBG"><a class="mmMarkedMenu" href="/pages/minsida.php">MIN SIDA</a><img src="/img/ftag/minsida_icon.gif" class="mmMarginLeft5" alt=""></li>
        <li class="mmMenuBG"><a href="/pages/lag.php?lid=9125">MITT LAG</a><img src="/img/ftag/mittlag_icon.gif" class="mmMarginLeft5" alt=""></li>
        <li class="mmMenuBG"><a href="/pages/foretag.php?fid=2965">MITT FÖRETAG</a><img src="/img/ftag/mittforetag_icon.gif" class="mmMarginLeft2" alt=""></li>
        <li class="mmMenuBG"><a href="/pages/foretagstavling.php">FÖRETAGSTÄVLING</a></li>

        <li><a href="/pages/mail.php">MOTIOMERAMAIL</a></li>
        <li><a href="/pages/fotoalbum.php">FOTOALBUM</a></li>
        <li><a href="/pages/minaquiz.php">MINA QUIZ</a></li>
        <li><a href="/pages/adressbok.php">MINA VÄNNER</a></li>
        <li><a href="/pages/klubbar.php">KLUBBAR</a></li>
        <li><a href="/pages/installningar.php">INSTÄLLNINGAR</a></li>
      </ul>









    <?php endwhile; // end of the loop.  ?>
      </article>
  </div><!-- #content -->
</div><!-- #container -->
<div class="clear"></div>
<?php get_footer(); ?>