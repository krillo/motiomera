<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mm_copy');
define('DB_USER', 'motiomera');
define('DB_PASSWORD', 't5fugds');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'aAA}lREa`|--%rsDHUu.,WM?rA|p:DKh)oI/Lbv,3*u| %3JbL5/ZNr<OG#3:sBB-');
define('SECURE_AUTH_KEY',  'aFL2eG9j=`?eAZ-IAiI,7z8~#`-$_NsfVr2815t~#EJA%@0{J_i~9{/fZ13:js.57');
define('LOGGED_IN_KEY',    'afI-80(-t9$lP+WLW-ZGXl8+|k:[$Z/5K2$QDC|#num+n21|$%*a+Od;<uV7|#G2G');
define('NONCE_KEY',        'acDCc]_jZW5(R~7;1nq/rv@PU0h^Q=[Yig*cboZXOVq&h4ds]+_J/e7w}&7$`{LU[');
define('AUTH_SALT',        'a|&=L*iowo:gW}gxR#` jk0wI6r$S%;H:I01vcCcY9[ yRi+db;]H+akEC&tFn 7%');
define('SECURE_AUTH_SALT', 'a( y;wF$~v)8U4:S:$>.h~4`!|3}ye:EmG7/Q@&M>Guxv6h4nK2& ^|X-gx,i>P x');
define('LOGGED_IN_SALT',   'au%Gs-}?UTcm@cu>lie}t{|;lCkXo@f<[}NCj1<TW.AHGoJyFqnyEkNA$^kf}-I/l');
define('NONCE_SALT',       'aNE%*[)IX>x:9Kh:.wFATk`UHe>u:E~2UEAUZ=j4U5Rx/ZfW8|BkWPq d2v!MVBXp');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'sv_SE');


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');



/* Motiomera stuff */
if ( !defined('MM_ROOT_ABSPATH') ){
	define('MM_ROOT_ABSPATH', dirname(__FILE__) . '/../public_html/php');
}
if ( !defined('MM_SERVER_ROOT_URL') ){
	define('MM_SERVER_ROOT_URL', 'http://mm.dev');
}

