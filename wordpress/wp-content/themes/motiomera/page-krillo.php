<?php
/**
  Template Name: Motiomera-krillo
 */
get_header(); ?>

		<div id="container">
			<div id="content" role="main">
<article>
        <?php while (have_posts()) : the_post(); ?>
            <?php //get_template_part('content', 'page'); ?>
            <?php  //the_content(); ?>
            <?php
              //$userId = get_current_user_id();
              //if($userId != 0){
              //  echo "userid = " . $userId;
             // } else {
               // echo "Not allowed";
             // }
            ?>
        
<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');

//mm_init();
//print_r($USER);
?>

         
                <form action="<?php echo MM_SERVER_ROOT_URL; ?>/actions/login.php" method="post">
                  <table width="290" cellspacing="1" cellpadding="0" border="0">
                    <tr>
                      <td class="mmLoggaInTitle">E-postadress: </td>
                      <td><input name="username" id="username" value="" class="mmTextField" size="17" type="text" maxlength="96" tabindex="1" /></td>
                      <td class="mmLoggaInCheckbox"><input type="checkbox" id="autologin" name="autologin" value="on" tabindex="3" /> <label for="autologin">Kom ihåg mig</label></td>
                    </tr>
                    <tr>
                      <td class="mmLoggaInTitle">L&ouml;senord: </td>
                      <td><input name="password" id="password" value="" size="17" class="mmTextField" type="password" maxlength="96" tabindex="2"/></td>
                      <td><input type="hidden" name="login" value="Login"/><input type="image" src="/img/icons/LoggaInIcon.gif" alt="Logga in" tabindex="4" /></td>
                    </tr>
                  </table>
                </form>
              </article>












        
        
        <?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #container -->
    <div class="clear"></div>
<?php get_footer(); ?>