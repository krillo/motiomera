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
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'motiomera_setup' ) ):
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
	load_theme_textdomain( 'motiomera', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'motiomera' ),
		'footer' => __( 'Footer Menu', 'motiomera' ),
	) );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // motiomera_setup
add_action( 'after_setup_theme', 'motiomera_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Motiomera 1.0
 */
function motiomera_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'motiomera' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'motiomera_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function motiomera_scripts() {
	global $post;
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

  
  
  //krillo added these
  wp_deregister_script('jquery');
  wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
  wp_enqueue_script('jquery');
  
}
add_action( 'wp_enqueue_scripts', 'motiomera_scripts' );






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
  register_post_type('interesting',
          array(
              'labels' => array(
                  'name' => __('intresseväckare'),
                  'singular_name' => __('intresseväckare')
              ),
              'public' => true,
              'has_archive' => false,
              'supports' => array('title', 'editor'),
          )
  );  
  register_post_type('readmore',
          array(
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

$mmPageStatus->normal_page = 0;

function mm_set_pagestatus(){
  global $mmPageStatus;
  if (is_page() && !is_front_page()){
    $mmPageStatus->normal_page = 1;
  } 
  print_r($mmPageStatus);
}










/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );
