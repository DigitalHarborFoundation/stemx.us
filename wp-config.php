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
define('DB_NAME', 'stemx_wp');

/** MySQL database username */
define('DB_USER', 'stemx_wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'iDev12');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
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
define('AUTH_KEY',         '[sPbtNK_AHF~f/k{0/bLcz)B_+-QSK9^WxR8Iyo-HLE#`qoE6_1h1bUI_u|UpN-_');
define('SECURE_AUTH_KEY',  ',]Z!k<(V]*,D-XG`f^Fq^xaG~-`uM&50M6.Hs(:lf,)9|D>CRRimZfeX0<ABLPfO');
define('LOGGED_IN_KEY',    '~zS,1uB5AoeIETX**2jV.QIYlu,l|*2X^k=l>63.!Hpu x|AkY]Npv`o_##E2w5k');
define('NONCE_KEY',        ']!*n-d:N!:A.+;3keo#6Wp.7QMBt&dT^^R[dj@SIqQ!RwMuj4rMR]S%-y58K+79i');
define('AUTH_SALT',        'u}YUr.[|^+E.Ty:#@=W`|W&yE.]MN+-b(6&Ef[~7U~6NnqZXo2B8~mL$y0T_.!d%');
define('SECURE_AUTH_SALT', 'wY*e;q9%u^.ritP5p4;F1>1OlR{{!xhk=||;c.&_=XuX$itD(CNZfA_4>#<X(S[R');
define('LOGGED_IN_SALT',   'vqP*{s|wmL7z0o::}9x0DF+k%7b1$t%cETVfpL5Z_ |^:}9~5/T}u?=0`6)efa:<');
define('NONCE_SALT',       'p2cwsU:REc:C.J`0{G4nEoixKKD,f3A_gqE6~Gx;,3~?}mZrIa{y=X)6uLjw_#Se');

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
define('WPLANG', '');

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
