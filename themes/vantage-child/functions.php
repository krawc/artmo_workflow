<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


//allow redirection, even if my theme starts to send output to the browser
add_action('init', 'do_output_buffer');
function do_output_buffer() {
    ob_start();
}

/* THEME setup */

require_once( __DIR__ . '/includes/enqueues.php');
require_once( __DIR__ . '/includes/helpers.php');
require_once( __DIR__ . '/includes/crons.php');
require_once( __DIR__ . '/includes/custom-post-types.php');
require_once( __DIR__ . '/includes/customizer.php');
require_once( __DIR__ . '/includes/admin.php');
require_once( __DIR__ . '/includes/theme-settings.php');
require_once( __DIR__ . '/includes/ajax.php');

/* PLUGIN-specific modifications */

require_once( __DIR__ . '/includes/plugins/so.php');
require_once( __DIR__ . '/includes/plugins/um.php');
require_once( __DIR__ . '/includes/plugins/wcfm.php');
require_once( __DIR__ . '/includes/plugins/woo.php');

/* SPECIFIC FEATURES - KEEP THE ALPHABETICAL ORDER */

require_once( __DIR__ . '/includes/features/compression.php');
require_once( __DIR__ . '/includes/features/editor.php');
require_once( __DIR__ . '/includes/features/homepage.php');
require_once( __DIR__ . '/includes/features/inputs.php');
require_once( __DIR__ . '/includes/features/profile-embed.php');
require_once( __DIR__ . '/includes/features/profiles-carousel.php');
require_once( __DIR__ . '/includes/features/redirections.php');
require_once( __DIR__ . '/includes/features/share.php');
require_once( __DIR__ . '/includes/features/videos.php');
