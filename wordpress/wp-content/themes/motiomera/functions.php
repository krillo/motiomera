<?php

/**
 * Motiomera functions and definitions
 *
 * @package Motiomera
 * @since Motiomera 1.0
 */
function favicon() {
  echo '<link rel="shortcut icon" href="', get_template_directory_uri(), '/faviconadmin.ico" />', "\n";
}

add_action('admin_head', 'favicon');


if (!function_exists('motiomera_setup')):

  function motiomera_setup() {
    require( get_template_directory() . '/inc/template-tags.php' );
    load_theme_textdomain('motiomera', get_template_directory() . '/languages');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'motiomera'),
        'footer' => __('Footer Menu', 'motiomera'),
        'header_logged_in', __('Header Logged In Menu'),
    ));
    add_theme_support('post-formats', array('aside',));
  }

endif; // motiomera_setup
add_action('after_setup_theme', 'motiomera_setup');



/**
 * Add the "contact us"-mailto link to the footer menu
 * Submit user details
 * 
 * @param string $items
 * @param type $args
 * @return string
 */
function add_contact_to_footer_menu($items, $args) {
  if ($args->menu == 'footer') {
    global $mmStatus;
    $body = '';
    if ($mmStatus->mm_mid != 0) {
      $body = '?body=Namn:%20'. $mmStatus->mm_fnamn . ' ' . $mmStatus->mm_enamn . '%0AEmail:%20' . $mmStatus->mm_email . '%0AId:%20' . $mmStatus->mm_mid . '%0A------------ LÅT STÅ ---------------';
    }
    $contactlink = '<li class=""><a href="mailto:support@motiomera.se' . $body . '" target="_blank">Kontakta oss</a></li>';
    $items = $items . $contactlink;
  }
  return $items;
}

add_filter('wp_nav_menu_items', 'add_contact_to_footer_menu', 10, 2);

/**
 * Clean up the admin panel
 */
function hide_wp_welcome_panel() {
  if (current_user_can('edit_theme_options'))
    $ah_clean_up_option = update_user_meta(get_current_user_id(), 'show_welcome_panel', false);
}

function remove_dashboard_widgets() {
  // Ta bort widgets i vänsterkolumnen
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); // Inkommande länkar
  remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); // Tillägg
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Senaste kommentarer
  remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Just nu
  // Ta bort widgets i högerkolumnen
  remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress Blogg
  remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // SnabbPress
  remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); // Senaste utkasten
  remove_meta_box('dashboard_secondary', 'dashboard', 'side'); // Andra WordPressnyheter
}

add_action('wp_dashboard_setup', 'hide_wp_welcome_panel');
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

/**
 * Enqueue javascripts and styles
 */
function enqueue_motiomera_scripts() {
  //global $post;
  //print_r($post);
  global $mmStatus;
  //print_r($mmStatus);

  wp_enqueue_style('style', get_stylesheet_uri());


  wp_register_script('jquery-ui', get_bloginfo('template_url') . '/js/jquery-ui-1.9.2.custom.min.js', array('jquery'));
  wp_enqueue_script('jquery-ui');
  if (1 == 2) {  //this is only needed for steg and graph not in wp right now..
    wp_register_script('jquery-flot', get_bloginfo('template_url') . '/js/jquery.flot.js');
    wp_enqueue_script('jquery-flot');
    wp_register_script('jquery-flot-stack', get_bloginfo('template_url') . '/js/jquery.flot.stack.js');
    wp_enqueue_script('jquery-flot-stack');
    wp_register_script('jquery-mmwp-steps', get_bloginfo('template_url') . '/js/jquery.mmwp.steps.js');
    wp_enqueue_script('jquery-mmwp-steps');
  }
  if ($mmStatus->front_page == 1) {
    wp_register_script('jquery-validate', get_bloginfo('template_url') . '/js/jquery.validate.min.js');
    wp_enqueue_script('jquery-validate');
    wp_register_script('jquery-mmwp-buy', get_bloginfo('template_url') . '/js/jquery.mmwp.buy.js');
    wp_enqueue_script('jquery-mmwp-buy');
  }


  //styles
  wp_register_style('wp-mm-style', get_bloginfo('template_url') . '/css/wp_mm_common.css');
  wp_enqueue_style('wp-mm-style');
  wp_register_style('ui-lightness-style', get_bloginfo('template_url') . '/css/ui-lightness/jquery-ui-1.9.2.custom.min.css');
  wp_enqueue_style('ui-lightness-style');
}

add_action('wp_enqueue_scripts', 'enqueue_motiomera_scripts');


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
}



// krillo - set this variable so that old motiomera can figure out if wp is initiated or not 
define('MM_WP_INIT', true);

$mmStatus = new stdClass();
$mmStatus->url = get_bloginfo('url');
$mmStatus->front_page = 0;
$mmStatus->wp_page = 0;
$mmStatus->mm_logged_in = 0;
$mmStatus->mm_mid = 0;
$mmStatus->mm_sid = 0;
$mmStatus->mm_fnamn = null;
$mmStatus->mm_enamn = null;
$mmStatus->mm_anamn = null;
$mmStatus->mm_email = null;
$mmStatus->fb_user_id = 0;
$mmStatus->fb_name = null;
$mmStatus->fb_first_name = null;
$mmStatus->fb_last_name = null;
$mmStatus->fb_link = null;
$mmStatus->fb_gender = null;
$mmStatus->fb_email = null;
$mmStatus->fb_login_url = null;
$facebook = null;
mm_status();

/**
 * Set some statuses. If logged in then run the mm init.php to get even more data
 */
function mm_status() {
  global $mmStatus;
  global $facebook;
  global $fbLoginUrl;
  
  
  
  
  @session_start();  //just to be able to read mm variables (logged in or not)
  
  /*
  echo 'session - ';  
  print_r( $_SESSION);
  echo ' - '; 
   */
  
  if (empty($_SESSION["mm_mid"]) && empty($_SESSION["mm_sid"])) {

  } else {
    $mmStatus->mm_logged_in = 1;
    $mmStatus->mm_mid = $_SESSION["mm_mid"];
    $mmStatus->mm_sid = $_SESSION["mm_sid"];
    $mm_user = getUserData($mmStatus->mm_mid);
    $mmStatus->mm_fnamn = $mm_user->fnamn;
    $mmStatus->mm_enamn = $mm_user->enamn;
    $mmStatus->mm_anamn = $mm_user->anamn;
    $mmStatus->mm_email = $mm_user->epost;
  }
  if (is_page() && !is_front_page()) {
    $mmStatus->wp_page = 1;
  }
  if (is_front_page()) {
    $mmStatus->front_page = 1;
  }
  //facebookInit();
  //print_r($mmStatus);
}

/*
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
 * 
 */

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
    case 'inc_login_area.php':   //if not logged - show login area
      if ($mmStatus->mm_logged_in == 0) {
        include 'snippets/' . $file;
      }
      break;
    case 'inc_big_logo_area.php':    //on front page show big logo area, else small area  
      if ($mmStatus->front_page == 1) {
        include 'snippets/' . $file;
      } else {
        include 'snippets/inc_margin.php';
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
      if ($mmStatus->wp_page == 1 && $mmStatus->mm_logged_in == 0) {
        include 'snippets/' . $file;
      }
      break;
    case 'inc_page_promo_footer.php':  //normal page, not logged in - show the promo area
      if ($mmStatus->wp_page == 1 && $mmStatus->mm_logged_in == 0) {
        include 'snippets/' . $file;
      }
      break;
    case 'inc_buy.php':
      include 'snippets/' . $file;
      break;
    case 'inc_graph.php':
      include 'snippets/' . $file;
      break;
    case 'inc_steps.php':
      include 'snippets/' . $file;
      break;
    default:
      break;
  }
}

function getKommuner($html = true) {
  global $wpdb;
  $sql = "SELECT id, namn FROM mm_kommun WHERE abroad = 'false' order by namn asc";
  $result = $wpdb->get_results($sql);
  if ($html) {
    $option = '<select name="kid" id="kid">';
    foreach ($result as $kommun) {
      $option .= '<option label="' . $kommun->namn . '" value="' . $kommun->id . '">' . $kommun->namn . '</option>';
    }
    $option .= '</select>';
    $result = $option;
  }
  return $result;
}

function getUserData($mid) {
  global $wpdb;
  $sql = "SELECT id, fnamn, enamn, anamn, epost FROM mm_medlem WHERE id = $mid ";
  $result = $wpdb->get_results($sql);
  return $result[0];
}

