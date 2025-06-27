<?php
/**
 * The main plugin class for SMTP Mailer.
 * Orchestrates other classes and handles PHPMailer configuration.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class SMTP_Mailer {

    /**
     * Constructor.
     */
    public function __construct() {
        new SMTP_Mailer_Settings();
        new SMTP_Mailer_Dashboard_Widget();
    }

    /**
     * Runs the plugin.
     */
    public function run() {
        add_action('phpmailer_init', array($this, 'configure_phpmailer'));
        add_filter('plugin_action_links_' . plugin_basename(SMTP_MAILER_PATH . 'smtp-mailer.php'), array($this, 'add_settings_link'));
    }

    /**
     * Configures PHPMailer with stored settings or wp-config.php constants.
     *
     * @param PHPMailer $phpmailer The PHPMailer instance.
     */
    public function configure_phpmailer(PHPMailer $phpmailer) {
        try {
            $options = get_option('smtp_mailer_options');

            $use_wp_config = isset($options['use_wp_config']) && $options['use_wp_config'] == '1';

            $host = '';
            $port = '';
            $username = '';
            $password = '';
            $encryption = '';
            $from_email = '';
            $from_name = '';

            if ($use_wp_config) {
                // Attempt to get credentials from wp-config.php constants
                if (defined('SMTP_MAILER_HOST')) $host = SMTP_MAILER_HOST;
                if (defined('SMTP_MAILER_PORT')) $port = SMTP_MAILER_PORT;
                if (defined('SMTP_MAILER_USERNAME')) $username = SMTP_MAILER_USERNAME;
                if (defined('SMTP_MAILER_PASSWORD')) $password = SMTP_MAILER_PASSWORD;
                if (defined('SMTP_MAILER_ENCRYPTION')) $encryption = SMTP_MAILER_ENCRYPTION;
                if (defined('SMTP_MAILER_FROM_EMAIL')) $from_email = SMTP_MAILER_FROM_EMAIL;
                if (defined('SMTP_MAILER_FROM_NAME')) $from_name = SMTP_MAILER_FROM_NAME;

            } else {
                // Fallback to database options
                $host = isset($options['host']) ? $options['host'] : '';
                $port = isset($options['port']) ? $options['port'] : '';
                $username = isset($options['username']) ? $options['username'] : '';
                $password = isset($options['password']) ? $options['password'] : '';
                $encryption = isset($options['encryption']) ? $options['encryption'] : '';
                $from_email = isset($options['from_email']) ? $options['from_email'] : '';
                $from_name = isset($options['from_name']) ? $options['from_name'] : '';
            }

            if (empty($host) || empty($username) || empty($password)) {
                // Don't attempt to send via SMTP if essential settings are missing
                return;
            }

            $phpmailer->isSMTP();
            $phpmailer->Host = sanitize_text_field($host);
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = absint($port);
            $phpmailer->Username = sanitize_email($username);
            $phpmailer->Password = $password; // Password is not sanitized as it needs to be exact
            $phpmailer->SMTPSecure = sanitize_text_field($encryption);

            // Set From email and From name if provided, otherwise PHPMailer will use defaults
            if (!empty($from_email)) {
                $phpmailer->From = sanitize_email($from_email);
            }
            if (!empty($from_name)) {
                $phpmailer->FromName = sanitize_text_field($from_name);
            }

            

            // Optional: Enable SMTP debugging for development
            // $phpmailer->SMTPDebug = 2; // Enable verbose debug output
            // $phpmailer->Debugoutput = 'error_log'; // Output debug messages to error log
        } catch (PHPMailerException $e) {
            error_log('SMTP Mailer Error: ' . $e->getMessage());
        }
    }

    /**
     * Adds a settings link to the plugin actions on the plugins page.
     *
     * @param array $links Existing plugin action links.
     * @return array Modified plugin action links.
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="' . esc_url(admin_url('options-general.php?page=smtp-mailer')) . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}
