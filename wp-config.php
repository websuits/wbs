<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'websuits_wbs' );

/** Database username */
define( 'DB_USER', 'websuits_wbs' );

/** Database password */
define( 'DB_PASSWORD', '8p@SHk3p[5' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '4xk6qd9fhhc0ktswneysr8s3cmc21aydmcpifqpr0ifeqzhtytthbqq1holo3czu' );
define( 'SECURE_AUTH_KEY',  'wzlckvz9uqdp6uw8cargn3xpsr6iqbosntbvphmlzlafk4idwwmrcgkqjdhvldpr' );
define( 'LOGGED_IN_KEY',    'kxwsr7khokk1i0eqcxjevvuwltkyw16vntxeqi285efhbssarrdlkz24ibrnvjyb' );
define( 'NONCE_KEY',        'vdvswzrldevfyrd50wzakan7xpc2mog30w7s3omitqlccmlqdj6yb2tkvbb5ytkv' );
define( 'AUTH_SALT',        '0028e5tvrlu5mp5ofkmjyloprtbl4pljdxfbmg4vgtyiwnlwuymz5z5zwbyi2uyq' );
define( 'SECURE_AUTH_SALT', 'krwrpljkc6jerykobfgj69lm3ee62fadlbwkokp3glrgbcvg46uu53b1ioo1oije' );
define( 'LOGGED_IN_SALT',   'kuk4f9dzi5tslocdf6yppgf8fywbupl6sgqkpzkomvvxjtdxizeqwyaoxlyqnrlo' );
define( 'NONCE_SALT',       '13qwyfhbzch8zazgilvjl7anb4ew8mby82iqylj1jbjmhlkg6wjbisy8stmwosrl' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wbs_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

/** Headless configs */
// define( 'HEADLESS_FRONTEND_URL', 'https://localhost:3000/' );
// define( 'PREVIEW_SECRET_TOKEN', '|1jfw`]0&sEnpguY`+M.gWf_}u@xTH6*B4KG(Q!n#Q1aD08TL+7+q6R nRiw:H6>');
// define( 'GRAPHQL_JWT_AUTH_SECRET_KEY', 'rkql[c6!4czwj#.|;qte;Y@N*4Z0YB0em01GK=RYhK0.Qz<%F=-6@y$[kJBBdbG-' );


define( 'NEXT_PUBLIC_WORDPRESS_API_URL', 'http://localhost:3000/' );
define( 'GRAPHQL_JWT_AUTH_SECRET_KEY', 'rkql[c6!4czwj#.|;qte;Y@N*4Z0YB0em01GK=RYhK0.Qz<%F=-6@y$[kJBBdbG-' );
