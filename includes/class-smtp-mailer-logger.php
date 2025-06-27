<?php
/**
 * Handles email logging and log cleanup for the SMTP Mailer plugin.
 */
class SMTP_Mailer_Logger {

    /**
     * Creates the database table for email logs.
     */
    public static function create_log_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'smtp_mailer_logs';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            recipient varchar(255) NOT NULL,
            subject varchar(255) NOT NULL,
            status varchar(20) NOT NULL,
            error_message text,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Deletes the database table for email logs.
     */
    public static function delete_log_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'smtp_mailer_logs';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

    /**
     * Logs an email attempt.
     *
     * @param string $recipient The email recipient.
     * @param string $subject The email subject.
     * @param string $status The status of the email (e.g., 'success', 'failed').
     * @param string $error_message Optional. Any error message if the email failed.
     */
    public static function log_email($recipient, $subject, $status, $error_message = '') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'smtp_mailer_logs';

        $wpdb->insert(
            $table_name,
            array(
                'recipient' => $recipient,
                'subject' => $subject,
                'status' => $status,
                'error_message' => $error_message,
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );
    }

    /**
     * PHPMailer send callback to log email results.
     *
     * @param bool $result True on success, false on failure.
     * @param array $to Array of recipients.
     * @param array $cc Array of CC recipients.
     * @param array $bcc Array of BCC recipients.
     * @param string $subject The email subject.
     * @param string $body The email body.
     * @param string $from The email sender.
     * @param mixed $error The error object or string if any.
     */
    public static function send_callback($result, $to, $cc, $bcc, $subject, $body, $from, $error) {
        $recipient = implode(', ', array_column($to, 0)); // Get primary recipients
        $status = $result ? 'success' : 'failed';
        $error_message = '';
        if (is_a($error, 'PHPMailer\PHPMailer\Exception')) {
            $error_message = $error->getMessage();
        } else if (is_string($error)) {
            $error_message = $error;
        }

        self::log_email($recipient, $subject, $status, $error_message);
    }

    /**
     * Schedules the log cleanup event.
     */
    public static function schedule_cleanup_event() {
        if (!wp_next_scheduled('smtp_mailer_cleanup_logs_event')) {
            wp_schedule_event(time(), 'daily', 'smtp_mailer_cleanup_logs_event');
        }
    }

    /**
     * Clears the scheduled cleanup event.
     */
    public static function clear_cleanup_event() {
        wp_clear_scheduled_hook('smtp_mailer_cleanup_logs_event');
    }

    /**
     * Cleans up old email logs based on retention settings.
     */
    public static function cleanup_logs() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'smtp_mailer_logs';
        $options = get_option('smtp_mailer_options');
        $retention_days = isset($options['log_retention']) ? absint($options['log_retention']) : 30; // Default to 30 days

        if ($retention_days > 0) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $table_name WHERE timestamp < DATE_SUB(NOW(), INTERVAL %d DAY)",
                    $retention_days
                )
            );
        }
    }
}

// Hook the cleanup function to the scheduled event
add_action('smtp_mailer_cleanup_logs_event', 'SMTP_Mailer_Logger::cleanup_logs');
