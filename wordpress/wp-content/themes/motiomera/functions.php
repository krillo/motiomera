<?php

/**
 * Motiomera functions and definitions
 *
 * @package Motiomera
 * @since Motiomera 1.0
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Motiomera 1.0
 */
if (!isset($content_width))
  $content_width = 640; /* pixels */

if (!function_exists('motiomera_setup')):

  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which runs
   * before the init hook. The init hook is too late for some features, such as indicating
   * support post thumbnails.
   *
   * @since Motiomera 1.0
   */
  function motiomera_setup() {

    /**
     * Custom template tags for this theme.
     */
    require( get_template_directory() . '/inc/template-tags.php' );

    /**
     * Custom functions that act independently of the theme templates
     */
    //require( get_template_directory() . '/inc/tweaks.php' );

    /**
     * Custom Theme Options
     */
    //require( get_template_directory() . '/inc/theme-options/theme-options.php' );

    /**
     * WordPress.com-specific functions and definitions
     */
    //require( get_template_directory() . '/inc/wpcom.php' );

    /**
     * Make theme available for translation
     * Translations can be filed in the /languages/ directory
     * If you're building a theme based on Motiomera, use a find and replace
     * to change 'motiomera' to the name of your theme in all the template files
     */
    load_theme_textdomain('motiomera', get_template_directory() . '/languages');

    /**
     * Add default posts and comments RSS feed links to head
     */
    add_theme_support('automatic-feed-links');

    /**
     * Enable support for Post Thumbnails
     */
    add_theme_support('post-thumbnails');

    /**
     * This theme uses wp_nav_menu() in one location.
     */
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'motiomera'),
        'footer' => __('Footer Menu', 'motiomera'),
    ));

    /**
     * Add support for the Aside Post Formats
     */
    add_theme_support('post-formats', array('aside',));
  }

endif; // motiomera_setup
add_action('after_setup_theme', 'motiomera_setup');

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Motiomera 1.0
 */
function motiomera_widgets_init() {
  register_sidebar(array(
      'name' => __('Sidebar', 'motiomera'),
      'id' => 'sidebar-1',
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => "</aside>",
      'before_title' => '<h1 class="widget-title">',
      'after_title' => '</h1>',
  ));
}

add_action('widgets_init', 'motiomera_widgets_init');

/**
 * Enqueue scripts and styles
 */
function motiomera_scripts() {
  global $post;
  wp_enqueue_style('style', get_stylesheet_uri());
  wp_enqueue_script('small-menu', get_template_directory_uri() . '/js/small-menu.js', array('jquery'), '20120206', true);
  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
  if (is_singular() && wp_attachment_is_image($post->ID)) {
    wp_enqueue_script('keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20120202');
  }



  //krillo added these
  wp_deregister_script('jquery');
  wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
  wp_enqueue_script('jquery');


  wp_register_script('jquery-ui', get_bloginfo('template_url') . '/js/jquery-ui-1.8.22.custom.min.js');
  wp_enqueue_script('jquery-ui');
}

add_action('wp_enqueue_scripts', 'motiomera_scripts');



/**
 * Enqueue some java scripts
 *
  function load_scripts() {
  wp_deregister_script('jquery');
  wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
  wp_enqueue_script('jquery');
  wp_register_script('lightbox', get_bloginfo('template_url') . '/js/jquery.lightbox-0.5.js');
  wp_enqueue_script('lightbox');

  if (is_page('start')) {
  wp_register_script('jcarousel', get_bloginfo('template_url') . '/js/jquery.jcarousel.min.js');
  wp_enqueue_script('jcarousel');
  wp_register_script('bigcarousel', get_bloginfo('template_url') . '/js/jquery.jcarousel.putsman.js');
  wp_enqueue_script('bigcarousel');
  }
  }
  add_action('wp_enqueue_scripts', 'load_scripts');
 */
//custom post type
add_action('init', 'create_post_type');

function create_post_type() {
  register_post_type('faq', array(
      'labels' => array(
          'name' => __('FAQ'),
          'singular_name' => __('FAQ')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title', 'editor', 'thumbnail'),
          )
  );
  register_post_type('interesting', array(
      'labels' => array(
          'name' => __('intresseväckare'),
          'singular_name' => __('intresseväckare')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title', 'editor'),
          )
  );
  register_post_type('readmore', array(
      'labels' => array(
          'name' => __('Läs mer'),
          'singular_name' => __('Läs mer')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title', 'editor'),
          )
  );
}

// krillo - set this variable so that old motiomera can figure out if wp is initiated or not 
define('MM_WP_INIT', true);

$mmStatus = new stdClass();
$mmStatus->front_page = 0;
$mmStatus->normal_page = 0;
$mmStatus->mm_logged_in = 0;
$mmStatus->mm_mid = 0;
$mmStatus->mm_sid = 0;
$mmStatus->fb_user_id = 0;
$mmStatus->fb_name = null;
$mmStatus->fb_first_name = null;
$mmStatus->fb_last_name = null;
$mmStatus->fb_link = null;
$mmStatus->fb_gender = null;
$mmStatus->fb_email = null;
$mmStatus->fb_login_url = null;
$facebook = null;

/**
 * Set some statuses that can be used in the pages
 */
function mm_status() {
  global $mmStatus;
  global $facebook;
  global $fbLoginUrl;
  @session_start();  //just to be able to read mm variables (logged in or not)

  if (empty($_SESSION["mm_mid"]) && empty($_SESSION["mm_sid"])) {
    
  } else {
    $mmStatus->mm_logged_in = 1;
    $mmStatus->mm_mid = $_SESSION["mm_mid"];
    $mmStatus->mm_sid = $_SESSION["mm_sid"];
  }
  if (is_page() && !is_front_page()) {
    $mmStatus->normal_page = 1;
  }
  if (is_front_page()) {
    $mmStatus->front_page = 1;
  }
  //facebookInit();
}

function facebookInit() {
  //Facebook login url
  require ABSPATH . '../lib/facebook-php-sdk/src/facebook.php';
  $facebook = new Facebook(array(
              'appId' => '108867119251826',
              'secret' => 'f8a8d39798810a4f5a51cdb867508ee6',
          ));

  $fb_params = array(
      'scope' => 'read_stream',
      'redirect_uri' => 'http://mm.dev/faq/',
      'display' => 'popup'
  );
  $mmStatus->fb_login_url = $facebook->getLoginUrl($fb_params);

  //Logged in on Facebook?
  $user = null;
  $user = $facebook->getUser();
  //print_r($user);
  if ($user) {
    try {
      // Proceed knowing you have a logged in user who's authenticated.
      $user_profile = $facebook->api('/me');
      $mmStatus->fb_user_id = $user_profile['id'];
      $mmStatus->fb_name = $user_profile['name'];
      $mmStatus->fb_first_name = $user_profile['first_name'];
      $mmStatus->fb_last_name = $user_profile['last_name'];
      $mmStatus->fb_link = $user_profile['link'];
      $mmStatus->fb_gender = $user_profile['gender'];
      $mmStatus->fb_email = $user_profile['email'];
    } catch (FacebookApiException $e) {
      //echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
      $user = null;
    }
  }

  echo '<div style="color:#ccc;">' . print_r($mmStatus, true) . '</div>';
  //echo '<br/><img src="https://graph.facebook.com/' . $user . '/picture">';
}

/**
 * Include snippets
 * The pages show different snippets due to mmStatus
 *  
 * @global stdClass $mmStatus
 * @param type $file 
 */
function includeSnippet($file) {
  global $mmStatus;
  //print_r($mmStatus);

  switch ($file) {
    //if not logged - show login area
    case 'inc_login_area.php':
      if ($mmStatus->mm_logged_in == 0) {
        include 'snippets/' . $file;
      }
      break;
    //on front page show big logo area, else small area  
    case 'inc_big_logo_area.php':
      if ($mmStatus->front_page == 1) {
        include 'snippets/' . $file;
      }
      break;
    case 'inc_login_area.php':
      if ($mmStatus->mm_logged_in == 0) {
        include 'snippets/' . $file;
      }
      break;
    case 'inc_logged_in_menu.php':
      if ($mmStatus->mm_logged_in == 1) {
        include 'snippets/' . $file;
      }
      break;
    case 'inc_page_promo_header.php':  //normal page, not logged in - show the promo area
      if ($mmStatus->normal_page == 1) { // && $mmStatus->mm_logged_in == 0) {
        include 'snippets/' . $file;
      }
      break;
    case 'inc_page_promo_footer.php':  //normal page, not logged in - show the promo area
      if ($mmStatus->normal_page == 1) { // && $mmStatus->mm_logged_in == 0) {
        include 'snippets/' . $file;
      }
      break;
    case 'inc_private_calc.php':  //
      if ($mmStatus->normal_page == 1) { // not correct include....!!!!!!!!!!!!!!!!!!!!!!!!!
        include 'snippets/' . $file;
      }
      break;
    case 'inc_buy.php':
      include 'snippets/' . $file;
      break;







    default:
      break;
  }
}

