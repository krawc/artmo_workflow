<?php

define('FS_METHOD', 'direct');








ini_set('log_errors','On');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);


/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db787058094' );

/** MySQL database username */
define( 'DB_USER', 'dbo787058094' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ExUEoKkmqLZqoDYsIHIz' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:/tmp/mysqld.sock' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'T4~&x.9NeXFZgMCj[+mO~j`554*uD>tAj>|$ChpG}`+^LO%!Kn3]c4gEz-!UrxM.');
define('SECURE_AUTH_KEY',  'OrffjAK{287|-QRi@FKQay9_Xh?c?)+w)5-N(^Qzu_+BG>qL{b|T/ *2y60;5J7H');
define('LOGGED_IN_KEY',    '3-1B]Nv$)buF|!<0[]JH9[}mvDTP> GDDo^<8-:R D|dIF`fV3UA<M~#TO.*02q$');
define('NONCE_KEY',        'VRFi+jf^.e->K&OM/$Gj:cW6m2y{/;l;-7uFF=bm-.|HG/:QBk}SL)lJ`mQW<NqW');
define('AUTH_SALT',        'Go#Ln]*W3.4AOCedND5v~xz,2^f@3<`5Z.4-8-c(mIJJCQnB+keef_%~!Z|1wT.]');
define('SECURE_AUTH_SALT', '{%RbfvA38OKlv$#_-)u[wK.ctn6*.W~Rfld[-Pt{%(:Lz&ql4!RjjT.<92@(|Si4');
define('LOGGED_IN_SALT',   'sA{|ZXut?VMw]*:~Y>26AvSM0xLrm.R`D2pt+b>wv qNPG2]j,+}5YRyIZ*9Zh;9');
define('NONCE_SALT',       'G_-#kIppo|_|gKL~FuU_OA%N,t:M5aC{x2?j|(g95w^6z9sc]QWwIp>D%R4o[9Hp');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'nyi6ltd8nd';


define('WP_MEMORY_LIMIT', '2024M');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';