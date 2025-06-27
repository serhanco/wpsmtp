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

            $phpmailer->isSMTP();
            $phpmailer->Host = 'smtp.office365.com';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 587;
            $phpmailer->Username = 'webapp@acibademimc.com';
            $phpmailer->Password = 'rlclqvbgbmmmxddc';
            $phpmailer->SMTPSecure = 'tls';

            // Set From email and From name
            $phpmailer->From = 'webapp@acibademimc.com';
            $phpmailer->FromName = 'Acibadem IMC';

            // Enable SMTP debugging and output to error log
            $phpmailer->SMTPDebug = 2;
            $phpmailer->Debugoutput = 'error_log';
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
