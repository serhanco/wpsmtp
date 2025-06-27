<?php
/**
 * Plugin Name: SMTP Mailer
 * Description: Sends WordPress emails via SMTP using configurable credentials, with logging and dashboard statistics.
 * Version: 1.7
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SMTP_MAILER_PATH', plugin_dir_path(__FILE__));
define('SMTP_MAILER_URL', plugin_dir_url(__FILE__));

// Include necessary classes
require_once SMTP_MAILER_PATH . 'includes/class-smtp-mailer-logger.php';
require_once SMTP_MAILER_PATH . 'includes/class-smtp-mailer-settings.php';
require_once SMTP_MAILER_PATH . 'includes/class-smtp-mailer-dashboard-widget.php';
require_once SMTP_MAILER_PATH . 'includes/class-smtp-mailer.php';

/**
 * Plugin Activation Hook
 * Creates the database table for email logs and schedules the cleanup event.
 */
function smtp_mailer_activate() {
    SMTP_Mailer_Logger::create_log_table();
    SMTP_Mailer_Logger::schedule_cleanup_event();
}
register_activation_hook(__FILE__, 'smtp_mailer_activate');

/**
 * Plugin Deactivation Hook
 * Clears the scheduled cleanup event.
 */
function smtp_mailer_deactivate() {
    SMTP_Mailer_Logger::clear_cleanup_event();
}
register_deactivation_hook(__FILE__, 'smtp_mailer_deactivate');

/**
 * Plugin Uninstall Hook
 * Cleans up all plugin data upon uninstallation.
 */
function smtp_mailer_uninstall() {
    SMTP_Mailer_Logger::delete_log_table();
    SMTP_Mailer_Settings::delete_options();
    SMTP_Mailer_Logger::clear_cleanup_event();
}
register_uninstall_hook(__FILE__, 'smtp_mailer_uninstall');

// Initialize the plugin
function run_smtp_mailer() {
    $smtp_mailer = new SMTP_Mailer();
    $smtp_mailer->run();
}
run_smtp_mailer();
