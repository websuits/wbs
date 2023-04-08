<?php
/*
* GoSMTP
* https://gosmtp.net
* (c) Softaculous Team
*/

// We need the ABSPATH
if (!defined('ABSPATH')) exit;

define('GOSMTP_BASE', plugin_basename(GOSMTP_FILE));
define('GOSMTP_PRO_BASE', 'gosmtp-pro/gosmtp-pro.php');
define('GOSMTP_VERSION', '1.0.0');
define('GOSMTP_DIR', dirname(GOSMTP_FILE));
define('GOSMTP_PRO_DIR', GOSMTP_DIR .'/main/premium');
define('GOSMTP_SLUG', 'gosmtp');
define('GOSMTP_URL', plugins_url('', GOSMTP_FILE));
define('GOSMTP_CSS', GOSMTP_URL.'/css');
define('GOSMTP_JS', GOSMTP_URL.'/js');
define('GOSMTP_PRO_URL', 'https://gosmtp.net/pricing?from=plugin');
define('GOSMTP_WWW_URL', 'https://gosmtp.net/');
define('GOSMTP_DOCS', 'https://gosmtp.net/docs/');
define('GOSMTP_API', 'https://api.gosmtp.net/');
define('GOSMTP_DB_PREFIX', 'gosmtp_');

include_once(GOSMTP_DIR.'/main/functions.php');

spl_autoload_register('gosmtp_autoload_register');
function gosmtp_autoload_register($class){
	
	if(!preg_match('/GOSMTP\\\\/', $class)){
		return;
	}
	
	$file = strtolower(str_replace( array('GOSMTP', '\\'), array('', DIRECTORY_SEPARATOR), $class)); 
	$file = trim(strtolower($file), '/').'.php';

	// For Free
	if(file_exists(GOSMTP_DIR.'/main/'.$file)){
		include_once(GOSMTP_DIR.'/main/'.$file);
	}
	
	// For Pro
	if(file_exists(GOSMTP_PRO_DIR.'/'.$file)){
		include_once(GOSMTP_PRO_DIR.'/'.$file);
	}
	
}

function gosmtp_died(){
	print_r(error_get_last());
}
//register_shutdown_function('gosmtp_died');

// Ok so we are now ready to go
register_activation_hook(GOSMTP_FILE, 'gosmtp_activation');

// Is called when the ADMIN enables the plugin
function gosmtp_activation(){
	global $wpdb;

	$sql = array();

	add_option('gosmtp_version', GOSMTP_VERSION);
	
	if(class_exists('\GOSMTP\Logger')){
		$logger = new \GOSMTP\Logger();
		$logger->create_table();
	}

}

// Checks if we are to update ?
function gosmtp_update_check(){
	global $wpdb;

	$sql = array();
	$current_version = get_option('gosmtp_version');
	$version = (int) str_replace('.', '', $current_version);

	// No update required
	if($current_version == GOSMTP_VERSION){
		return true;
	}

	// Is it first run ?
	if(empty($current_version)){

		// Reinstall
		gosmtp_activation();

		// Trick the following if conditions to not run
		$version = (int) str_replace('.', '', GOSMTP_VERSION);

	}

	// Save the new Version
	update_option('gosmtp_version', GOSMTP_VERSION);
	
}

// The function that will be called when the plugin is loaded
add_action('wp_mail', 'gosmtp_load_phpmailer');
function gosmtp_load_phpmailer($atts){
	global $gosmtp, $phpmailer;
	
	if(!empty($gosmtp)){
		return $atts;
	}
	
	if($phpmailer instanceof GOSMTP_PHPMailer){
		return $atts;
	}
	
	// Load PHPMailer class, so we can subclass it.
	require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
	require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
	require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
	
	class GOSMTP_PHPMailer extends \PHPMailer\PHPMailer\PHPMailer {

		// Modify the default send() behaviour of PHPMailer.
		public function send(){
			
			global $gosmtp;
			
			// Define a custom header, that will be used to identify the plugin and the mailer.
			$this->XMailer = 'GOSMTP/Mailer/' . $gosmtp->_mailer . ' ' . GOSMTP_VERSION;
			
			do_action( 'gosmtp_mailer_mail_pre_send' );
			
			// If mailer not exists or send function not exists
			if(!method_exists($gosmtp->mailer, 'send')){
				return parent::send();
			}
			
			do_action( 'gosmtp_mailer_mail_send_before' );
		
			// Are we to enforce from ?
			$gosmtp->mailer->set_from();
			
			/*
			 * Send the actual email.
			 * We reuse everything, that was preprocessed for usage in \PHPMailer.
			 */
			$is_sent = $gosmtp->mailer->send();

			do_action( 'gosmtp_mailer_mail_send_after' );

			return $is_sent;
		}

	}
	
	$gosmtp = new stdClass();
	$gosmtp->options = get_option('gosmtp_options', array());
	
	// Load all mailer
	$gosmtp->mailer_list = gosmtp_get_mailer_list();
	
	// For PHP Email dont do anything
	if(empty($gosmtp->options['mailer'][0]['mail_type']) || $gosmtp->options['mailer'][0]['mail_type'] == 'mail'){
		return $atts;
	}
	
	$mailer = sanitize_key( $gosmtp->options['mailer'][0]['mail_type'] );			
	$class = $gosmtp->mailer_list[$mailer]['class'];
	
	if(!class_exists($class)){
		return $atts;
	}
	
	$gosmtp->_mailer = $mailer;
	$gosmtp->mailer = new $class();
	
	// Handle the from email name
	add_filter('wp_mail_from', [$gosmtp->mailer, 'set_from'], 100, 1);

	$phpmailer = new GOSMTP_PHPMailer(true);
	
	return $atts;
}

// This adds the left menu in WordPress Admin page
add_action('admin_menu', 'gosmtp_admin_menu', 5);
function gosmtp_admin_menu() {

	global $wp_version;

	$capability = 'activate_plugins';// TODO : Capability for accessing this page

	// Add the menu page
	add_menu_page(__('GoSMTP'), __('GoSMTP'), $capability, 'gosmtp', 'gosmtp_page_handler', 'dashicons-email-alt');
	
	// Settings Page
	add_submenu_page( 'gosmtp', __('Settings'), __('Settings'), $capability, 'gosmtp', 'gosmtp_page_handler');
	
	// Test Mail Page
	add_submenu_page( 'gosmtp', 'Test Mail', 'Test Mail', $capability, 'gosmtp#test-mail', 'gosmtp_page_handler');
	
	if(defined('GOSMTP_PREMIUM')){
		
		// Logs Page
		add_submenu_page( 'gosmtp', __('Logs'), __('Logs'), $capability, 'gosmtp-logs', 'gosmtp_logs_handler');

		// License Page
		add_submenu_page( 'gosmtp', __('License'), __('License'), $capability, 'gosmtp-license', 'gosmtp_license_handler');
		
	}
	
	// Support
	add_submenu_page( 'gosmtp', __('Support'), __('Support'), $capability, 'gosmtp#support', 'gosmtp_page_handler');
}

// SMTP page Handler
function gosmtp_page_handler(){
	include_once GOSMTP_DIR .'/main/settings.php';
	gosmtp_settings_page();
}

function gosmtp_logs_handler(){
    include_once GOSMTP_PRO_DIR .'/smtp-logs.php';
}

function gosmtp_license_handler(){
    include_once GOSMTP_PRO_DIR .'/license.php';
}

if(wp_doing_ajax()){	
	include_once GOSMTP_DIR.'/main/ajax.php';
}

add_action( 'admin_init', 'gosmtp_admin_init');
function gosmtp_admin_init(){
	wp_register_style( 'gosmtp-admin', GOSMTP_URL .'/css/admin.css', array(), GOSMTP_VERSION);
	wp_register_script( 'gosmtp-admin', GOSMTP_URL .'/js/admin.js', array('jquery'), GOSMTP_VERSION);
}