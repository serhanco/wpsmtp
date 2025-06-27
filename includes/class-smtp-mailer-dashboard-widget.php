<?php
/**
 * Handles the dashboard widget for SMTP Mailer statistics.
 */
class SMTP_Mailer_Dashboard_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets'));
    }

    /**
     * Adds the dashboard widget for email statistics.
     */
    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'smtp_mailer_dashboard_widget',
            'SMTP Mailer Statistics',
            array($this, 'dashboard_widget_callback')
        );
    }

    /**
     * Callback function for the dashboard widget.
     */
    public function dashboard_widget_callback() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'smtp_mailer_logs';

        // Today's stats
        $today_success = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'success' AND DATE(timestamp) = CURDATE()");
        $today_failed = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'failed' AND DATE(timestamp) = CURDATE()");

        // Last 7 days stats
        $week_success = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'success' AND timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
        $week_failed = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'failed' AND timestamp >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");

        // Last 30 days stats
        $month_success = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'success' AND timestamp >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
        $month_failed = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'failed' AND timestamp >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");

        echo '<div class="smtp-mailer-stats">';
        echo '<h3>Today</h3>';
        echo '<p>Successful: <strong style="color: green;">' . esc_html($today_success) . '</strong></p>';
        echo '<p>Failed: <strong style="color: red;">' . esc_html($today_failed) . '</strong></p>';

        echo '<h3>Last 7 Days</h3>';
        echo '<p>Successful: <strong style="color: green;">' . esc_html($week_success) . '</strong></p>';
        echo '<p>Failed: <strong style="color: red;">' . esc_html($week_failed) . '</strong></p>';

        echo '<h3>Last 30 Days</h3>';
        echo '<p>Successful: <strong style="color: green;">' . esc_html($month_success) . '</strong></p>';
        echo '<p>Failed: <strong style="color: red;">' . esc_html($month_failed) . '</strong></p>';
        echo '</div>';
    }
}
