<?php
// Direct calls to this file are Forbidden when core files are not present
if ( ! function_exists ('add_action') ) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}

function bulletproof_security_admin_init() {
global $wpdb, $wp_version, $blog_id;

	if ( is_multisite() && $blog_id != 1 ) {

	$Ltable_name = $wpdb->prefix . "bpspro_login_security";

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $Ltable_name ) ) != $Ltable_name ) {
	
	$sql = "CREATE TABLE $Ltable_name (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  status VARCHAR(60) DEFAULT '' NOT NULL,
  user_id VARCHAR(60) DEFAULT '' NOT NULL,
  username VARCHAR(60) DEFAULT '' NOT NULL,
  public_name VARCHAR(250) DEFAULT '' NOT NULL,
  email VARCHAR(100) DEFAULT '' NOT NULL,
  role VARCHAR(15) DEFAULT '' NOT NULL,
  human_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  login_time VARCHAR(10) DEFAULT '' NOT NULL,
  lockout_time VARCHAR(10) DEFAULT '' NOT NULL,
  failed_logins VARCHAR(2) DEFAULT '' NOT NULL,
  ip_address VARCHAR(45) DEFAULT '' NOT NULL,
  hostname VARCHAR(60) DEFAULT '' NOT NULL,
  request_uri VARCHAR(255) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
    );";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}	

	} else {

	$Stable_name = $wpdb->prefix . "bpspro_seclog_ignore";
	$Ltable_name = $wpdb->prefix . "bpspro_login_security";
	$DBBtable_name = $wpdb->prefix . "bpspro_db_backup";

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $Stable_name ) ) != $Stable_name ) {	
	
	$sql = "CREATE TABLE $Stable_name (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  user_agent_bot text NOT NULL,
  UNIQUE KEY id (id)
    );";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}

	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $Ltable_name ) ) != $Ltable_name ) {	
	
	$sql = "CREATE TABLE $Ltable_name (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  status VARCHAR(60) DEFAULT '' NOT NULL,
  user_id VARCHAR(60) DEFAULT '' NOT NULL,
  username VARCHAR(60) DEFAULT '' NOT NULL,
  public_name VARCHAR(250) DEFAULT '' NOT NULL,
  email VARCHAR(100) DEFAULT '' NOT NULL,
  role VARCHAR(15) DEFAULT '' NOT NULL,
  human_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  login_time VARCHAR(10) DEFAULT '' NOT NULL,
  lockout_time VARCHAR(10) DEFAULT '' NOT NULL,
  failed_logins VARCHAR(2) DEFAULT '' NOT NULL,
  ip_address VARCHAR(45) DEFAULT '' NOT NULL,
  hostname VARCHAR(60) DEFAULT '' NOT NULL,
  request_uri VARCHAR(255) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
    );";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}

	// last job, next job is updated by the cron - job size is the total size of all tables selected in that job
	if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $DBBtable_name ) ) != $DBBtable_name ) {	
	
	$sql = "CREATE TABLE $DBBtable_name (
  bps_id bigint(20) NOT NULL auto_increment,
  bps_table_name text default '' NOT NULL,
  bps_desc text default '' NOT NULL,
  bps_job_type varchar(9) default '' NOT NULL,
  bps_frequency varchar(7) default '' NOT NULL,
  bps_last_job varchar(30) default '' NOT NULL,
  bps_next_job varchar(30) default '' NOT NULL,
  bps_next_job_unix varchar(10) default '' NOT NULL,  
  bps_email_zip varchar(10) default '' NOT NULL,
  bps_job_created datetime default '0000-00-00 00:00:00' NOT NULL,
  UNIQUE KEY bps_id (bps_id)
    );";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	}
	}
	
	// whitelist BPS DB options 
	register_setting('bulletproof_security_options', 'bulletproof_security_options', 'bulletproof_security_options_validate');
	register_setting('bulletproof_security_options_DBB_log', 'bulletproof_security_options_DBB_log', 'bulletproof_security_options_validate_DBB_log');
	register_setting('bulletproof_security_options_autolock', 'bulletproof_security_options_autolock', 'bulletproof_security_options_validate_autolock');
	register_setting('bulletproof_security_options_db_backup', 'bulletproof_security_options_db_backup', 'bulletproof_security_options_validate_db_backup');
	register_setting('bulletproof_security_options_wpt_nodes', 'bulletproof_security_options_wpt_nodes', 'bulletproof_security_options_validate_wpt_nodes');
	register_setting('bulletproof_security_options_customcode', 'bulletproof_security_options_customcode', 'bulletproof_security_options_validate_customcode');
	register_setting('bulletproof_security_options_wizard_free', 'bulletproof_security_options_wizard_free', 'bulletproof_security_options_validate_wizard_free');	
	register_setting('bulletproof_security_options_pop_uninstall', 'bulletproof_security_options_pop_uninstall', 'bulletproof_security_options_validate_pop_uninstall');
	register_setting('bulletproof_security_options_customcode_WPA', 'bulletproof_security_options_customcode_WPA', 'bulletproof_security_options_validate_customcode_WPA');
	register_setting('bulletproof_security_options_status_display', 'bulletproof_security_options_status_display', 'bulletproof_security_options_validate_status_display');
	register_setting('bulletproof_security_options_login_security', 'bulletproof_security_options_login_security', 'bulletproof_security_options_validate_login_security');
	register_setting('bulletproof_security_options_idle_session', 'bulletproof_security_options_idle_session', 'bulletproof_security_options_validate_idle_session');
	register_setting('bulletproof_security_options_htaccess_res', 'bulletproof_security_options_htaccess_res', 'bulletproof_security_options_validate_htaccess_res');
	register_setting('bulletproof_security_options_auth_cookie', 'bulletproof_security_options_auth_cookie', 'bulletproof_security_options_validate_auth_cookie');	
	register_setting('bulletproof_security_options_maint_mode', 'bulletproof_security_options_maint_mode', 'bulletproof_security_options_validate_maint_mode');
	register_setting('bulletproof_security_options_theme_skin', 'bulletproof_security_options_theme_skin', 'bulletproof_security_options_validate_theme_skin');
	register_setting('bulletproof_security_options_spinner', 'bulletproof_security_options_spinner', 'bulletproof_security_options_validate_spinner');
	register_setting('bulletproof_security_options_mynotes', 'bulletproof_security_options_mynotes', 'bulletproof_security_options_validate_mynotes');
	register_setting('bulletproof_security_options_email', 'bulletproof_security_options_email', 'bulletproof_security_options_validate_email');			
	register_setting('bulletproof_security_options_GDMW', 'bulletproof_security_options_GDMW', 'bulletproof_security_options_validate_GDMW');
	
	// Create BPS Backup Folder
	if ( ! is_dir( WP_CONTENT_DIR . '/bps-backup' ) ) {
		@mkdir( WP_CONTENT_DIR . '/bps-backup', 0755, true );
		@chmod( WP_CONTENT_DIR . '/bps-backup/', 0755 );
	}
	
	// Create master backups folder
	if ( ! is_dir( WP_CONTENT_DIR . '/bps-backup/master-backups' ) ) {
		@mkdir( WP_CONTENT_DIR . '/bps-backup/master-backups', 0755, true );
		@chmod( WP_CONTENT_DIR . '/bps-backup/master-backups/', 0755 );
	}

	// Create Deny all .htaccess files - /bps-backup htaccess file is recursive and will protect all subfolders
	$bps_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/deny-all.htaccess';
	$bps_denyall_htaccess_renamed = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/.htaccess';
	$security_log_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/security-log/.htaccess';
	$system_info_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/system-info/.htaccess';
	$login_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/login/.htaccess';
	$MMode_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/maintenance/.htaccess';
	$DBB_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/db-backup-security/.htaccess';
	$core_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/core/.htaccess';
	$wizard_denyall_htaccess = WP_PLUGIN_DIR . '/bulletproof-security/admin/wizard/.htaccess';	
	$bps_ARHtaccess = WP_CONTENT_DIR . '/bps-backup/.htaccess';
	
	// put the files in an array and create a foreach for these at some point
	if ( ! file_exists($bps_ARHtaccess) ) {
		@copy($bps_denyall_htaccess, $bps_ARHtaccess);
	}
	if ( ! file_exists($bps_denyall_htaccess_renamed) ) {
		@copy($bps_denyall_htaccess, $bps_denyall_htaccess_renamed);	
	}
	if ( ! file_exists($security_log_denyall_htaccess) ) {
		@copy($bps_denyall_htaccess, $security_log_denyall_htaccess);	
	}
	if ( ! file_exists($system_info_denyall_htaccess) ) {
		@copy($bps_denyall_htaccess, $system_info_denyall_htaccess);
	}
	if ( ! file_exists($login_denyall_htaccess) ) {
		@copy($bps_denyall_htaccess, $login_denyall_htaccess);
	}
	if ( ! file_exists($MMode_denyall_htaccess) ) {
		@copy($bps_denyall_htaccess, $MMode_denyall_htaccess);			
	}
	if ( ! file_exists($DBB_denyall_htaccess) ) {
		@copy($bps_denyall_htaccess, $DBB_denyall_htaccess);
	}
	if ( ! file_exists($core_denyall_htaccess) ) {
		@copy($bps_denyall_htaccess, $core_denyall_htaccess);
	}
	if ( ! file_exists($wizard_denyall_htaccess) ) {
		@copy($bps_denyall_htaccess, $wizard_denyall_htaccess);
	}
	
	// Create logs folder
	if( ! is_dir( WP_CONTENT_DIR . '/bps-backup/logs' ) ) {
		@mkdir( WP_CONTENT_DIR . '/bps-backup/logs', 0755, true );
		@chmod( WP_CONTENT_DIR . '/bps-backup/logs/', 0755 );
	}

	// Create backups folder with randomly generated folder name & save the backups folder name to the DB
	bpsPro_create_db_backup_folder();

	// Create the Security/HTTP error log in /logs
	$bpsProLog = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/http_error_log.txt';
	$bpsProLogARQ = WP_CONTENT_DIR . '/bps-backup/logs/http_error_log.txt';
	
	if ( ! file_exists($bpsProLogARQ) ) {
		@copy($bpsProLog, $bpsProLogARQ);
	}	

	// Create the DB Backup log in /logs
	$bpsProDBBLog = WP_PLUGIN_DIR . '/bulletproof-security/admin/htaccess/db_backup_log.txt';
	$bpsProDBBLogARQ = WP_CONTENT_DIR . '/bps-backup/logs/db_backup_log.txt';
	
	if ( ! file_exists($bpsProDBBLogARQ) ) {
		@copy($bpsProDBBLog, $bpsProDBBLogARQ);
	}
}

// BPS Menu
function bulletproof_security_admin_menu() {
global $blog_id;
	
	if ( current_user_can('manage_options') ) {
	
	// Network/Multisite display partial BPS menus
	if ( is_multisite() && $blog_id != 1 ) {

	add_menu_page(__('BulletProof Security Settings', 'bulletproof-security'), __('BPS Security', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/login/login.php', '', plugins_url('bulletproof-security/admin/images/bps-icon-small.png'));
	add_submenu_page('bulletproof-security/admin/login/login.php', __('Login Security', 'bulletproof-security'), __('Login Security', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/login/login.php' );
	
	// Do not display the Maintenance Mode menu for GDMW hosted sites
	$BPS_wpadmin_Options = get_option('bulletproof_security_options_htaccess_res');
	$GDMW_options = get_option('bulletproof_security_options_GDMW');
	if ( $BPS_wpadmin_Options['bps_wpadmin_restriction'] != 'disabled' || $GDMW_options['bps_gdmw_hosting'] != 'yes' ) {		
	add_submenu_page('bulletproof-security/admin/login/login.php', __('Maintenance Mode', 'bulletproof-security'), __('Maintenance Mode', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/maintenance/maintenance.php' );
	}
	
	add_submenu_page('bulletproof-security/admin/login/login.php', __('System Info', 'bulletproof-security'), __('System Info', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/system-info/system-info.php' );
	add_submenu_page('bulletproof-security/admin/login/login.php', __('UI|UX|Theme Skin|Processing Spinner|WP Toolbar', 'bulletproof-security'), __('UI|UX|Theme Skin<br>Processing Spinner<br>WP Toolbar', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/theme-skin/theme-skin.php' );
	
	} else {

	add_menu_page(__('BulletProof Security ~ htaccess Core', 'bulletproof-security'), __('BPS Security', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/core/options.php', '', plugins_url('bulletproof-security/admin/images/bps-icon-small.png'));
	add_submenu_page('bulletproof-security/admin/core/options.php', __('BulletProof Security ~ htaccess Core', 'bulletproof-security'), __('htaccess Core', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/core/options.php' );
	add_submenu_page('bulletproof-security/admin/core/options.php', __('Login Security ~ ISL ~ ACE', 'bulletproof-security'), __('Login Security', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/login/login.php' );
	add_submenu_page('bulletproof-security/admin/core/options.php', __('Login Security ~ ISL ~ ACE', 'bulletproof-security'), __('Idle Session Logout<br>Cookie Expiration', 'bulletproof-security'), 'manage_options', 'admin.php?page=bulletproof-security/admin/login/login.php#bps-tabs-2' );
	add_submenu_page('bulletproof-security/admin/core/options.php', __('DB Backup & Security', 'bulletproof-security'), __('DB Backup', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/db-backup-security/db-backup-security.php' );
	add_submenu_page('bulletproof-security/admin/core/options.php', __('Security Log', 'bulletproof-security'), __('Security Log', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/security-log/security-log.php' );
	
	// Do not display the Maintenance Mode menu for GDMW hosted sites
	$BPS_wpadmin_Options = get_option('bulletproof_security_options_htaccess_res');
	$GDMW_options = get_option('bulletproof_security_options_GDMW');
	if ( $BPS_wpadmin_Options['bps_wpadmin_restriction'] != 'disabled' || $GDMW_options['bps_gdmw_hosting'] != 'yes' ) {	
	add_submenu_page('bulletproof-security/admin/core/options.php', __('Maintenance Mode', 'bulletproof-security'), __('Maintenance Mode', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/maintenance/maintenance.php' );
	}
	
	add_submenu_page('bulletproof-security/admin/core/options.php', __('System Info', 'bulletproof-security'), __('System Info', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/system-info/system-info.php' );
	add_submenu_page('bulletproof-security/admin/core/options.php', __('UI|UX|Theme Skin|Processing Spinner|WP Toolbar', 'bulletproof-security'), __('UI|UX|Theme Skin<br>Processing Spinner<br>WP Toolbar', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/theme-skin/theme-skin.php' );
	add_submenu_page('bulletproof-security/admin/core/options.php', __('Setup Wizard', 'bulletproof-security'), __('Setup Wizard', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/wizard/wizard.php' );	
	
	// Do not display a submenu|link: jQuery UI Dialog Pop up Form Uninstaller Options for BPS free
	add_submenu_page( null, __('BPS Plugin Uninstall Options', 'bulletproof-security'), __('BPS Plugin Uninstall Options', 'bulletproof-security'), 'manage_options', 'bulletproof-security/admin/includes/uninstall.php' );
	
	}
	}
}

if ( is_admin() && preg_match( '/page=bulletproof-security/', esc_html($_SERVER['REQUEST_URI']), $matches ) ) {

add_filter( 'style_loader_tag', 'bps_roque_style_killer' );
add_filter( 'script_loader_tag', 'bps_roque_script_killer' );

}

// Nulls/Kills Rogue Styles from loading in BPS plugin pages
// Note: This does not stop the worst Rogue offenders who hard inject/embed their code 
// in your plugin pages, but it does stop your plugin from being visually trashed. TODO
function bps_roque_style_killer($tag){
	
	if ( preg_match( '/page=bulletproof-security/', esc_html($_SERVER['REQUEST_URI']), $matches) ) {

		if ( ! strpos( $tag, 'bulletproof-security' ) && ! strpos( $tag, 'wp-admin' ) && ! strpos( $tag, 'wp-includes' ) )

			$tag = preg_replace( '/\.css/', ".css-roque-script-nulled", $tag );
	
		return $tag;
	}
}

// Nulls/Kills Rogue Scripts from loading in BPS plugin pages
// Note: This does not stop the worst Rogue offenders who who hard inject/embed their code 
// in your plugin pages, but it does stop your plugin from being trashed. TODO
function bps_roque_script_killer($tag){

	if ( preg_match( '/page=bulletproof-security/', esc_html($_SERVER['REQUEST_URI']), $matches) ) {

		if ( ! strpos( $tag, 'bulletproof-security' ) && ! strpos( $tag, 'wp-admin' ) && ! strpos( $tag, 'wp-includes' ) )

			$tag = preg_replace( '/\.js/', ".js-roque-script-nulled", $tag );

		return $tag;
	}
}

add_action( 'admin_enqueue_scripts', 'bpsPro_register_enqueue_scripts_styles' );

// Register scripts and styles, Enqueue scripts and styles, Dequeue any plugin or theme scripts and styles loading in BPS plugin pages
function bpsPro_register_enqueue_scripts_styles() {
global $wp_scripts, $wp_styles, $bulletproof_security, $wp_version;

	// Register & Load BPS scripts and styles on BPS plugin pages ONLY
	if ( preg_match( '/page=bulletproof-security/', esc_html($_SERVER['REQUEST_URI']), $matches ) ) {

		$UIoptions = get_option('bulletproof_security_options_theme_skin');

		// Register BPS Scripts
		wp_register_script( 'bps-tabs', plugins_url( '/bulletproof-security/admin/js/bps-ui-tabs.js' ) );
		wp_register_script( 'bps-dialog', plugins_url( '/bulletproof-security/admin/js/bps-ui-dialog.js' ) );	
		wp_register_script( 'bps-accordion', plugins_url( '/bulletproof-security/admin/js/bps-ui-accordion.js' ) );
	
		// Register BPS Styles
		if ( version_compare( $wp_version, '3.8', '>=' ) ) {
		
			switch ( $UIoptions['bps_ui_theme_skin'] ) {
    			case 'blue':
					wp_register_style('bps-css-38', plugins_url('/bulletproof-security/admin/css/bps-blue-ui-theme.css'));
				break;
    			case 'grey':
					wp_register_style('bps-css-38', plugins_url('/bulletproof-security/admin/css/bps-grey-ui-theme.css'));
				break;
    			case 'black':
					wp_register_style('bps-css-38', plugins_url('/bulletproof-security/admin/css/bps-black-ui-theme.css'));
				break;
			default: 		
					wp_register_style('bps-css-38', plugins_url('/bulletproof-security/admin/css/bps-blue-ui-theme.css'));		
			}
		
		} else {
		
			wp_register_style('bps-css', plugins_url('/bulletproof-security/admin/css/bps-blue-ui-theme-old-wp-versions.css'));
		}

		// Enqueue BPS scripts & script dependencies
		wp_enqueue_script( 'jquery-ui-tabs', plugins_url( '/bulletproof-security/admin/js/bps-ui-tabs.js' ), array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-dialog', plugins_url( '/bulletproof-security/admin/js/bps-ui-dialog.js' ), array( 'jquery' ) );
		wp_enqueue_script( 'jquery-effects-blind', plugins_url( '/bulletproof-security/admin/js/bps-ui-dialog.js' ), array( 'jquery-effects-core' ) );		
		wp_enqueue_script( 'jquery-effects-explode', plugins_url( '/bulletproof-security/admin/js/bps-ui-dialog.js' ), array( 'jquery-effects-core' ) );	
		wp_enqueue_script( 'jquery-ui-accordion', plugins_url( '/bulletproof-security/admin/js/bps-ui-accordion.js' ), array( 'jquery' ) );
		wp_enqueue_script( 'bps-tabs' );
		wp_enqueue_script( 'bps-dialog' );
		wp_enqueue_script( 'bps-accordion' );	

		// Enqueue BPS stylesheets
		if ( version_compare( $wp_version, '3.8', '>=' ) ) {
		
			switch ( $UIoptions['bps_ui_theme_skin'] ) {
    			case 'blue':
					wp_enqueue_style('bps-css-38', plugins_url('/bulletproof-security/admin/css/bps-blue-ui-theme.css'));
				break;
    			case 'grey':
					wp_enqueue_style('bps-css-38', plugins_url('/bulletproof-security/admin/css/bps-grey-ui-theme.css'));;
				break;
    			case 'black':
					wp_enqueue_style('bps-css-38', plugins_url('/bulletproof-security/admin/css/bps-black-ui-theme.css'));
				break;
			default: 		
					wp_enqueue_style('bps-css-38', plugins_url('/bulletproof-security/admin/css/bps-blue-ui-theme.css'));		
			}
		
		} else {
		
			wp_enqueue_style('bps-css', plugins_url('/bulletproof-security/admin/css/bps-blue-ui-theme-old-wp-versions.css'));
		}	

		// Dequeue any other plugin or theme scripts that should not be loading on BPS plugin pages
		$script_handles = array( 'bps-tabs', 'bps-dialog', 'bps-accordion', 'admin-bar', 'jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-dialog', 'jquery-ui-accordion', 'jquery-effects-core', 'jquery-effects-blind', 'jquery-effects-explode', 'common', 'utils', 'svg-painter', 'wp-auth-check', 'debug-bar' );

		$style_handles = array( 'bps-css', 'bps-css-38', 'admin-bar', 'colors', 'ie', 'wp-auth-check', 'debug-bar' );

		foreach( $wp_scripts->queue as $handle ) {
		
			if ( ! in_array( $handle, $script_handles ) ) {
				wp_dequeue_script( $handle );
        		// uncomment line below to see all the script handles that are being blocked on BPS plugin pages
				//echo 'Script Dequeued: ' . $handle . ' | ';
			}
		}
	
		foreach( $wp_styles->queue as $handle ) {
        	
			if ( ! in_array( $handle, $style_handles ) ) {
				wp_dequeue_style( $handle );
				// uncomment line below to see all the style handles that are being blocked on BPS plugin pages
				//echo 'Style Dequeued: ' . $handle . ' | ';
			}	
		}
	}
}

add_action( 'wp_before_admin_bar_render', 'bpsPro_remove_non_wp_nodes_from_toolbar' );

// Removes any/all additional WP Toolbar nodes / menu items added by other plugins and themes
// in BPS plugin pages ONLY. Does NOT remove any of the default WP Toolbar nodes.
// Note: This file is loaded in the WP Dashboard. This function is ONLY processed in BPS plugin pages.
function bpsPro_remove_non_wp_nodes_from_toolbar() {
	
	if ( preg_match( '/page=bulletproof-security/', esc_html($_SERVER['REQUEST_URI']), $matches ) ) {
	
		$UIWPToptions = get_option('bulletproof_security_options_wpt_nodes');
	
		if ( $UIWPToptions['bps_wpt_nodes'] != 'allnodes' ) {
			
			global $wp_admin_bar;
			$all_toolbar_nodes = $wp_admin_bar->get_nodes();

			if ( $all_toolbar_nodes ) {
		
				if ( ! is_multisite() ) {
				
					$wp_default_nodes = array( 'user-actions', 'user-info', 'edit-profile', 'logout', 'menu-toggle', 'my-account', 'wp-logo', 'about', 'wporg', 'documentation', 'support-forums', 'feedback', 'site-name', 'view-site', 'updates', 'comments', 'new-content', 'new-post', 'new-media', 'new-page', 'new-user', 'top-secondary', 'wp-logo-external' );
				
					foreach ( $all_toolbar_nodes as $node ) {
						// For Testing: echo '<br>'; print_r($node->id); 
					
						if ( ! in_array( $node->id, $wp_default_nodes ) ) {
							// For Testing: echo '<br>'; print_r($node->id);
							$wp_admin_bar->remove_node( $node->id );	
						}
					}				
				
				
				} else {
				
					$wp_default_nodes = array( 'user-actions', 'user-info', 'edit-profile', 'logout', 'menu-toggle', 'my-account', 'wp-logo', 'about', 'wporg', 'documentation', 'support-forums', 'feedback', 'site-name', 'view-site', 'updates', 'comments', 'new-content', 'new-post', 'new-media', 'new-page', 'new-user', 'top-secondary', 'wp-logo-external', 'my-sites', 'my-sites-super-admin', 'network-admin', 'network-admin-d', 'network-admin-s', 'network-admin-u', 'network-admin-t', 'network-admin-p', 'my-sites-list', 'edit-site' );
				
					foreach ( $all_toolbar_nodes as $node ) {
						// For Testing: echo '<br>'; print_r($node->id); 
					
						if ( ! in_array( $node->id, $wp_default_nodes ) && ! preg_match( '/blog-[0-9]/', $node->id, $matches ) ) {
							// For Testing: echo '<br>'; print_r($node->id);
							$wp_admin_bar->remove_node( $node->id );	
						}
					}
				}
			}
		}
	}
}

// Create Backup folder with randomly generated folder name and update DB with folder name
function bpsPro_create_db_backup_folder() {
$options = get_option('bulletproof_security_options_db_backup');

	if ( $options['bps_db_backup_folder'] && $options['bps_db_backup_folder'] != '' || $_POST['Submit-DBB-Reset'] == true ) {
		return;	
	}
	
	$source = WP_CONTENT_DIR . '/bps-backup';

	if ( is_dir($source) ) {
		
		$iterator = new DirectoryIterator($source);
			
		foreach ( $iterator as $folder ) {
			if ( $folder->isDir() && ! $folder->isDot() && preg_match( '/backups_[a-zA-Z0-9]/', $folder ) ) {
				return;
			}
		}
				
		$str = '1234567890abcdefghijklmnopqrstuvxyzABCDEFGHIJKLMNOPQRSTUVWXYZU3xt8Eb9Qw422hG0yv1LCT2Pzub7';
		$folder_obs = substr( str_shuffle($str), 0, 15 );
		@mkdir( WP_CONTENT_DIR . '/bps-backup/backups_' . $folder_obs, 0755, true );
		@chmod( WP_CONTENT_DIR . '/bps-backup/backups_' . $folder_obs . '/', 0755 );
				
		//@mkdir( WP_CONTENT_DIR . '/bps-backup/backups_' . $folder_obs . '/db-diff', 0755, true );
		//@chmod( WP_CONTENT_DIR . '/bps-backup/backups_' . $folder_obs . '/db-diff/', 0755 );

		$dbb_options = 'bulletproof_security_options_db_backup';
		$bps_db_backup_folder = addslashes( WP_CONTENT_DIR . '/bps-backup/backups_' . $folder_obs );
		$bps_db_backup_download_link = ( WP_CONTENT_DIR . '/bps-backup/backups_' . $folder_obs );
		$bps_db_backup_download_link = content_url( '/bps-backup/backups_' ) . $folder_obs . '/';
		
		$DBB_Options = array(
		'bps_db_backup' => 'On', 
		'bps_db_backup_description' => '', 
		'bps_db_backup_folder' => $bps_db_backup_folder, 
		'bps_db_backup_download_link' => $bps_db_backup_download_link, 
		'bps_db_backup_job_type' => '', 
		'bps_db_backup_frequency' => '', 		 
		'bps_db_backup_start_time_hour' => '', 
		'bps_db_backup_start_time_weekday' => '', 
		'bps_db_backup_start_time_month_date' => '', 
		'bps_db_backup_email_zip' => '', 
		'bps_db_backup_delete' => '', 
		'bps_db_backup_status_display' => 'No DB Backups' 
		);	
	
		if ( ! get_option( $dbb_options ) ) {	
		
			foreach( $DBB_Options as $key => $value ) {
				update_option('bulletproof_security_options_db_backup', $DBB_Options);
			}
			
		} else {

			foreach( $DBB_Options as $key => $value ) {
				update_option('bulletproof_security_options_db_backup', $DBB_Options);
			}	
		}			
	}
}

function bulletproof_security_install() {
global $bulletproof_security, $bps_version;
$previous_install = get_option('bulletproof_security_options');
	
	if ( $previous_install ) {
	if ( version_compare($previous_install['version'], $bps_version, '<') )
		delete_transient( 'bulletproof-security_info' );
	}
}

// Deactivation - remove/delete nothing at this point
function bulletproof_security_deactivation() {
// nothing needs to removed on deactivation for now
}

// Delete the /bps-backup/ files and folder
// Note: SKIP_DOTS or isDot is unnecessary for this specific usage
function bpsPro_pop_uninstall_bps_backup_folder($source) {
	
	$source = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'bps-backup';
	
	if ( is_dir($source) ) {
		
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::CHILD_FIRST);
		
		foreach ( $iterator as $file ) {
			
			if ( $file->isDir() ) {
				rmdir( $file->getRealPath() );

			} else {			
		
				if ( $file->isFile() ) {
					unlink( $file->getRealPath() );
				}
			}
		}
	rmdir($source);	
	}
}

// Uninstallation: Conditional Uninstall based on bps_pop_uninstall value: 2 == Complete BPS Plugin Uninstall or 1 == BPS Pro Upgrade Uninstall
function bulletproof_security_uninstall() {
$POPoptions = get_option('bulletproof_security_options_pop_uninstall');

require_once( ABSPATH . 'wp-admin/includes/plugin.php');

	if ( $POPoptions['bps_pop_uninstall'] == 2 ) {
		
	global $wpdb, $current_user;	

	bpsPro_pop_uninstall_bps_backup_folder($source);

	$user_id = $current_user->ID;
	$Stable_name = $wpdb->prefix . "bpspro_seclog_ignore";
	$Ltable_name = $wpdb->prefix . "bpspro_login_security";
	$DBBtable_name = $wpdb->prefix . "bpspro_db_backup";
	$RootHtaccess = ABSPATH . '.htaccess';
	$RootHtaccessBackup = WP_CONTENT_DIR . '/bps-backup/master-backups/root.htaccess';
	$wpadminHtaccess = ABSPATH . 'wp-admin/.htaccess';
	$wpadminHtaccessBackup = WP_CONTENT_DIR . '/bps-backup/master-backups/wpadmin.htaccess';

	if ( file_exists($RootHtaccess) ) {
		copy($RootHtaccess, $RootHtaccessBackup);
	}
	if ( file_exists($wpadminHtaccess) ) {
		copy($wpadminHtaccess, $wpadminHtaccessBackup);
	}

	delete_transient( 'bulletproof-security_info' );
	
	delete_option('bulletproof_security_options');
	delete_option('bulletproof_security_options_customcode');
	delete_option('bulletproof_security_options_customcode_WPA');
	delete_option('bulletproof_security_options_maint');
	delete_option('bulletproof_security_options_maint_mode');
	delete_option('bulletproof_security_options_mynotes');
	delete_option('bulletproof_security_options_email');
	delete_option('bulletproof_security_options_autolock');
	delete_option('bulletproof_security_options_login_security');
	delete_option('bulletproof_security_options_theme_skin');
	delete_option('bulletproof_security_options_db_backup');
	delete_option('bulletproof_security_options_DBB_log');
	delete_option('bulletproof_security_options_htaccess_res');
	delete_option('bulletproof_security_options_net_correction');
	delete_option('bulletproof_security_options_spinner');
	delete_option('bulletproof_security_options_wpt_nodes');
	delete_option('bulletproof_security_options_status_display'); 
	delete_option('bulletproof_security_options_pop_uninstall'); 
	delete_option('bulletproof_security_options_GDMW');
	delete_option('bulletproof_security_options_wizard_free');
	delete_option('bulletproof_security_options_idle_session'); 	
	delete_option('bulletproof_security_options_auth_cookie'); 
	// will be adding this new upgrade notice option later
	// delete_option('bulletproof_security_options_upgrade_notice');	
	
	$wpdb->query("DROP TABLE IF EXISTS $Stable_name");
	$wpdb->query("DROP TABLE IF EXISTS $Ltable_name");
	$wpdb->query("DROP TABLE IF EXISTS $DBBtable_name");
	
	delete_user_meta($user_id, 'bps_ignore_iis_notice');
	delete_user_meta($user_id, 'bps_ignore_sucuri_notice');
	delete_user_meta($user_id, 'bps_ignore_BLC_notice');
	delete_user_meta($user_id, 'bps_ignore_PhpiniHandler_notice');
	delete_user_meta($user_id, 'bps_ignore_Permalinks_notice');
	delete_user_meta($user_id, 'bps_brute_force_login_protection_notice');
	delete_user_meta($user_id, 'bps_speed_boost_cache_notice');
	delete_user_meta($user_id, 'bps_xmlrpc_ddos_notice');
	delete_user_meta($user_id, 'bps_author_enumeration_notice');
	delete_user_meta($user_id, 'bps_ignore_wpfirewall2_notice');
	delete_user_meta($user_id, 'bps_hud_NetworkActivationAlert_notice');
	delete_user_meta($user_id, 'bps_referer_spam_notice');
	delete_user_meta($user_id, 'bps_sniff_driveby_notice');
	delete_user_meta($user_id, 'bps_iframe_clickjack_notice');

	@unlink($wpadminHtaccess);	
	
	if ( @unlink($RootHtaccess) || ! file_exists($RootHtaccess) ) {
		flush_rewrite_rules();
	}	

	} else {

		delete_option( 'bulletproof_security_options' );
		delete_option('bulletproof_security_options_wizard_free');
		delete_transient( 'bulletproof-security_info' );
	}
}

// was being used, no longer being used for anything
function bulletproof_security_options_validate($input) {  
	$options = get_option('bulletproof_security_options');  
	$options['bps_blank'] = wp_filter_nohtml_kses($input['bps_blank']);
			
	return $options;  
}

// Maintenance Mode
function bulletproof_security_options_validate_maint_mode($input) {  
	$options = get_option('bulletproof_security_options_maint_mode');  
	$options['bps_maint_on_off'] = wp_filter_nohtml_kses($input['bps_maint_on_off']);
	$options['bps_maint_countdown_timer'] = wp_filter_nohtml_kses($input['bps_maint_countdown_timer']);
	$options['bps_maint_countdown_timer_color'] = wp_filter_nohtml_kses($input['bps_maint_countdown_timer_color']);
	$options['bps_maint_time'] = wp_filter_nohtml_kses($input['bps_maint_time']);
	$options['bps_maint_retry_after'] = wp_filter_nohtml_kses($input['bps_maint_retry_after']);
	$options['bps_maint_frontend'] = wp_filter_nohtml_kses($input['bps_maint_frontend']);
	$options['bps_maint_backend'] = wp_filter_nohtml_kses($input['bps_maint_backend']);
	$options['bps_maint_ip_allowed'] = wp_filter_nohtml_kses($input['bps_maint_ip_allowed']);
	$options['bps_maint_text'] = esc_html($input['bps_maint_text']);
	$options['bps_maint_background_images'] = wp_filter_nohtml_kses($input['bps_maint_background_images']);
	$options['bps_maint_center_images'] = wp_filter_nohtml_kses($input['bps_maint_center_images']);
	$options['bps_maint_background_color'] = wp_filter_nohtml_kses($input['bps_maint_background_color']);
	$options['bps_maint_show_visitor_ip'] = wp_filter_nohtml_kses($input['bps_maint_show_visitor_ip']);
	$options['bps_maint_show_login_link'] = wp_filter_nohtml_kses($input['bps_maint_show_login_link']);
	$options['bps_maint_dashboard_reminder'] = wp_filter_nohtml_kses($input['bps_maint_dashboard_reminder']);	
	$options['bps_maint_countdown_email'] = wp_filter_nohtml_kses($input['bps_maint_countdown_email']);
	$options['bps_maint_email_to'] = trim(wp_filter_nohtml_kses($input['bps_maint_email_to']));
	$options['bps_maint_email_from'] = trim(wp_filter_nohtml_kses($input['bps_maint_email_from']));
	$options['bps_maint_email_cc'] = trim(wp_filter_nohtml_kses($input['bps_maint_email_cc']));
	$options['bps_maint_email_bcc'] = trim(wp_filter_nohtml_kses($input['bps_maint_email_bcc']));	
	$options['bps_maint_mu_entire_site'] = wp_filter_nohtml_kses($input['bps_maint_mu_entire_site']);
	$options['bps_maint_mu_subsites_only'] = wp_filter_nohtml_kses($input['bps_maint_mu_subsites_only']);
	
	return $options;  
}

// Root .htaccess file AutoLock 
function bulletproof_security_options_validate_autolock($input) {  
	$options = get_option('bulletproof_security_options_autolock');  
	$options['bps_root_htaccess_autolock'] = wp_filter_nohtml_kses($input['bps_root_htaccess_autolock']);
		
	return $options;  
}

// BPS Custom Code - Root .htaccess
function bulletproof_security_options_validate_customcode($input) {  
	$options = get_option('bulletproof_security_options_customcode');  
	// TOP PHP/PHP.INI HANDLER/CACHE CODE
	$options['bps_customcode_one'] = esc_html($input['bps_customcode_one']);
	$options['bps_customcode_server_signature'] = esc_html($input['bps_customcode_server_signature']);
	$options['bps_customcode_directory_index'] = esc_html($input['bps_customcode_directory_index']);
	// BRUTE FORCE LOGIN PAGE PROTECTION
	$options['bps_customcode_server_protocol'] = esc_html($input['bps_customcode_server_protocol']);	
	$options['bps_customcode_error_logging'] = esc_html($input['bps_customcode_error_logging']);
	$options['bps_customcode_deny_dot_folders'] = esc_html($input['bps_customcode_deny_dot_folders']);	
	$options['bps_customcode_admin_includes'] = esc_html($input['bps_customcode_admin_includes']);
	$options['bps_customcode_wp_rewrite_start'] = esc_html($input['bps_customcode_wp_rewrite_start']);
	$options['bps_customcode_request_methods'] = esc_html($input['bps_customcode_request_methods']);
	// PLUGIN/THEME SKIP/BYPASS RULES
	$options['bps_customcode_two'] = esc_html($input['bps_customcode_two']);
	$options['bps_customcode_timthumb_misc'] = esc_html($input['bps_customcode_timthumb_misc']);
	$options['bps_customcode_bpsqse'] = esc_html($input['bps_customcode_bpsqse']);
	if ( is_multisite() ) {
	$options['bps_customcode_wp_rewrite_end'] = esc_html($input['bps_customcode_wp_rewrite_end']);
	}
	$options['bps_customcode_deny_files'] = esc_html($input['bps_customcode_deny_files']);
	// BOTTOM HOTLINKING/FORBID COMMENT SPAMMERS/BLOCK BOTS/BLOCK IP/REDIRECT CODE
	$options['bps_customcode_three'] = esc_html($input['bps_customcode_three']);

	return $options;  
}


// BPS Custom Code - WP-admin .htaccess
function bulletproof_security_options_validate_customcode_WPA($input) {  
	$options = get_option('bulletproof_security_options_customcode_WPA');  
	$options['bps_customcode_deny_files_wpa'] = esc_html($input['bps_customcode_deny_files_wpa']);
	$options['bps_customcode_one_wpa'] = esc_html($input['bps_customcode_one_wpa']);
	$options['bps_customcode_two_wpa'] = esc_html($input['bps_customcode_two_wpa']);
	$options['bps_customcode_bpsqse_wpa'] = esc_html($input['bps_customcode_bpsqse_wpa']);		
	
	return $options;  
}

// BPS "My Notes" settings 
function bulletproof_security_options_validate_mynotes($input) {  
	$options = get_option('bulletproof_security_options_mynotes');  
	$options['bps_my_notes'] = esc_html($input['bps_my_notes']);
		
	return $options;  
}

// Login Security & Monitoring
function bulletproof_security_options_validate_login_security($input) {  
	$BPSoptions = get_option('bulletproof_security_options_login_security');  
	$BPSoptions['bps_max_logins'] = trim(wp_filter_nohtml_kses($input['bps_max_logins']));
	$BPSoptions['bps_lockout_duration'] = trim(wp_filter_nohtml_kses($input['bps_lockout_duration']));
	$BPSoptions['bps_manual_lockout_duration'] = trim(wp_filter_nohtml_kses($input['bps_manual_lockout_duration']));
	$BPSoptions['bps_max_db_rows_display'] = trim(wp_filter_nohtml_kses($input['bps_max_db_rows_display']));
	$BPSoptions['bps_login_security_OnOff'] = wp_filter_nohtml_kses($input['bps_login_security_OnOff']);
	$BPSoptions['bps_login_security_logging'] = wp_filter_nohtml_kses($input['bps_login_security_logging']);
	$BPSoptions['bps_login_security_errors'] = wp_filter_nohtml_kses($input['bps_login_security_errors']);
	$BPSoptions['bps_login_security_remaining'] = wp_filter_nohtml_kses($input['bps_login_security_remaining']);
	$BPSoptions['bps_login_security_pw_reset'] = wp_filter_nohtml_kses($input['bps_login_security_pw_reset']);
	$BPSoptions['bps_login_security_sort'] = wp_filter_nohtml_kses($input['bps_login_security_sort']);

	return $BPSoptions;  
}

// Idle Session Logout (ISL): Do not automatically set ISL up. This should be left up to users to choose whether to use this or not.
function bulletproof_security_options_validate_idle_session($input) {  
	$options = get_option('bulletproof_security_options_idle_session');  
	$options['bps_isl'] = wp_filter_nohtml_kses($input['bps_isl']);
	$options['bps_isl_timeout'] = trim(wp_filter_nohtml_kses($input['bps_isl_timeout']));
	$options['bps_isl_login_url'] = trim(wp_filter_nohtml_kses($input['bps_isl_login_url']));
	$options['bps_isl_user_account_exceptions'] = wp_filter_nohtml_kses($input['bps_isl_user_account_exceptions']);
	$options['bps_isl_administrator'] = wp_filter_nohtml_kses($input['bps_isl_administrator']);
	$options['bps_isl_editor'] = wp_filter_nohtml_kses($input['bps_isl_editor']);
	$options['bps_isl_author'] = wp_filter_nohtml_kses($input['bps_isl_author']);
	$options['bps_isl_contributor'] = wp_filter_nohtml_kses($input['bps_isl_contributor']);
	$options['bps_isl_subscriber'] = wp_filter_nohtml_kses($input['bps_isl_subscriber']);
	$options['bps_isl_tinymce'] = wp_filter_nohtml_kses($input['bps_isl_tinymce']);
	
	return $options;  
}

// Authentication Cookie Expiration (ACE): Do not automatically set ACE up. This should be left up to users to choose whether to use this or not.
function bulletproof_security_options_validate_auth_cookie($input) {  
	$options = get_option('bulletproof_security_options_auth_cookie');  
	$options['bps_ace'] = wp_filter_nohtml_kses($input['bps_ace']);
	$options['bps_ace_expiration'] = trim(wp_filter_nohtml_kses($input['bps_ace_expiration']));
	$options['bps_ace_rememberme_expiration'] = trim(wp_filter_nohtml_kses($input['bps_ace_rememberme_expiration']));
	$options['bps_ace_user_account_exceptions'] = wp_filter_nohtml_kses($input['bps_ace_user_account_exceptions']);
	$options['bps_ace_administrator'] = wp_filter_nohtml_kses($input['bps_ace_administrator']);
	$options['bps_ace_editor'] = wp_filter_nohtml_kses($input['bps_ace_editor']);
	$options['bps_ace_author'] = wp_filter_nohtml_kses($input['bps_ace_author']);
	$options['bps_ace_contributor'] = wp_filter_nohtml_kses($input['bps_ace_contributor']);
	$options['bps_ace_subscriber'] = wp_filter_nohtml_kses($input['bps_ace_subscriber']);

	return $options;  
}

// BPS Free Email Alerts
function bulletproof_security_options_validate_email($input) {  
	$options = get_option('bulletproof_security_options_email');  
	$options['bps_send_email_to'] = trim(wp_filter_nohtml_kses($input['bps_send_email_to']));
	$options['bps_send_email_from'] = trim(wp_filter_nohtml_kses($input['bps_send_email_from']));
	$options['bps_send_email_cc'] = trim(wp_filter_nohtml_kses($input['bps_send_email_cc']));
	$options['bps_send_email_bcc'] = trim(wp_filter_nohtml_kses($input['bps_send_email_bcc']));
	$options['bps_login_security_email'] = wp_filter_nohtml_kses($input['bps_login_security_email']);
	//$options['bps_upgrade_email'] = wp_filter_nohtml_kses($input['bps_upgrade_email']);		
	$options['bps_security_log_size'] = wp_filter_nohtml_kses($input['bps_security_log_size']);
	$options['bps_security_log_emailL'] = wp_filter_nohtml_kses($input['bps_security_log_emailL']);	
	$options['bps_dbb_log_email'] = wp_filter_nohtml_kses($input['bps_dbb_log_email']);	
	$options['bps_dbb_log_size'] = wp_filter_nohtml_kses($input['bps_dbb_log_size']);
	
	return $options;  
}

// UI Theme Skin 
function bulletproof_security_options_validate_theme_skin($input) {  
	$options = get_option('bulletproof_security_options_theme_skin');  
	$options['bps_ui_theme_skin'] = wp_filter_nohtml_kses($input['bps_ui_theme_skin']);
			
	return $options;  
}

// DB Backup
function bulletproof_security_options_validate_db_backup($input) {  
	$options = get_option('bulletproof_security_options_db_backup');  
	$options['bps_db_backup'] = wp_filter_nohtml_kses($input['bps_db_backup']);
	$options['bps_db_backup_description'] = trim(wp_filter_nohtml_kses($input['bps_db_backup_description']));		
	$options['bps_db_backup_folder'] = trim(wp_filter_nohtml_kses($input['bps_db_backup_folder']));
	$options['bps_db_backup_download_link'] = trim(wp_filter_nohtml_kses($input['bps_db_backup_download_link']));				
	$options['bps_db_backup_job_type'] = wp_filter_nohtml_kses($input['bps_db_backup_job_type']);	
	$options['bps_db_backup_frequency'] = wp_filter_nohtml_kses($input['bps_db_backup_frequency']);	
	$options['bps_db_backup_start_time_hour'] = wp_filter_nohtml_kses($input['bps_db_backup_start_time_hour']);
	$options['bps_db_backup_start_time_weekday'] = wp_filter_nohtml_kses($input['bps_db_backup_start_time_weekday']);
	$options['bps_db_backup_start_time_month_date'] = wp_filter_nohtml_kses($input['bps_db_backup_start_time_month_date']);
	$options['bps_db_backup_email_zip'] = wp_filter_nohtml_kses($input['bps_db_backup_email_zip']);		
	$options['bps_db_backup_delete'] = wp_filter_nohtml_kses($input['bps_db_backup_delete']);		
	$options['bps_db_backup_status_display'] = wp_filter_nohtml_kses($input['bps_db_backup_status_display']); // hidden form option
	
	return $options;  
}

// DB Backup Log Last Modified Time DB
function bulletproof_security_options_validate_DBB_log($input) {  
	$options = get_option('bulletproof_security_options_DBB_log');  
	$options['bps_dbb_log_date_mod'] = wp_filter_nohtml_kses($input['bps_dbb_log_date_mod']);
		
	return $options;  
}

// Hosting that does not allow wp-admin .htaccess files - Go Daddy Managed WordPress hosting
function bulletproof_security_options_validate_htaccess_res($input) {  
	$options = get_option('bulletproof_security_options_htaccess_res');  
	$options['bps_wpadmin_restriction'] = wp_filter_nohtml_kses($input['bps_wpadmin_restriction']);
		
	return $options;  
}

// Go Daddy Managed WordPress hosting
function bulletproof_security_options_validate_GDMW($input) {  
	$options = get_option('bulletproof_security_options_GDMW');  
	$options['bps_gdmw_hosting'] = wp_filter_nohtml_kses($input['bps_gdmw_hosting']);
	
	return $options;  
}

// Loading/Processing Spinner On/Off
function bulletproof_security_options_validate_spinner($input) {  
	$options = get_option('bulletproof_security_options_spinner');  
	$options['bps_spinner'] = wp_filter_nohtml_kses($input['bps_spinner']);
	
	return $options;  
}

// WP Toolbar remove or allow all nodes
function bulletproof_security_options_validate_wpt_nodes($input) {  
	$options = get_option('bulletproof_security_options_wpt_nodes');  
	$options['bps_wpt_nodes'] = wp_filter_nohtml_kses($input['bps_wpt_nodes']);
	
	return $options;  
}

// Inpage Status display - displays on BPS plugin pages only
function bulletproof_security_options_validate_status_display($input) {  
	$options = get_option('bulletproof_security_options_status_display');  
	$options['bps_status_display'] = wp_filter_nohtml_kses($input['bps_status_display']);
	
	return $options;  
}

// jQuery UI Dialog Uninstall Form Options
function bulletproof_security_options_validate_pop_uninstall($input) {  
	$options = get_option('bulletproof_security_options_pop_uninstall');  
	$options['bps_pop_uninstall'] = wp_filter_nohtml_kses($input['bps_pop_uninstall']);
	
	return $options;  
}

// Setup Wizard 
function bulletproof_security_options_validate_wizard_free($input) {  
	$options = get_option('bulletproof_security_options_wizard_free');  
	$options['bps_wizard_free'] = wp_filter_nohtml_kses($input['bps_wizard_free']);
	
	return $options;  
}

?>