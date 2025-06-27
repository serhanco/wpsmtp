<?php
/**
 * Handles the admin settings page for the SMTP Mailer plugin.
 */
class SMTP_Mailer_Settings {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('admin_init', array($this, 'handle_test_email'));
    }

    /**
     * Adds the SMTP Mailer settings page to the WordPress admin menu.
     */
    public function add_admin_menu() {
        add_options_page(
            'SMTP Mailer Settings',
            'SMTP Mailer',
            'manage_options',
            'smtp-mailer',
            array($this, 'settings_page_callback')
        );
    }

    /**
     * Registers SMTP Mailer settings.
     */
    public function settings_init() {
        register_setting('smtp_mailer_group', 'smtp_mailer_options');

        add_settings_section(
            'smtp_mailer_section_smtp',
            'SMTP Settings',
            array($this, 'section_smtp_callback'),
            'smtp-mailer'
        );

        add_settings_field(
            'smtp_mailer_host',
            'SMTP Host',
            array($this, 'text_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_smtp',
            array(
                'name' => 'host',
                'label_for' => 'smtp_mailer_host',
                'placeholder' => 'smtp.example.com'
            )
        );

        add_settings_field(
            'smtp_mailer_port',
            'SMTP Port',
            array($this, 'text_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_smtp',
            array(
                'name' => 'port',
                'label_for' => 'smtp_mailer_port',
                'type' => 'number',
                'placeholder' => '587'
            )
        );

        add_settings_field(
            'smtp_mailer_username',
            'Username',
            array($this, 'text_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_smtp',
            array(
                'name' => 'username',
                'label_for' => 'smtp_mailer_username',
                'placeholder' => 'your_email@example.com'
            )
        );

        add_settings_field(
            'smtp_mailer_password',
            'Password',
            array($this, 'text_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_smtp',
            array(
                'name' => 'password',
                'label_for' => 'smtp_mailer_password',
                'type' => 'password',
                'placeholder' => 'your_smtp_password'
            )
        );

        add_settings_field(
            'smtp_mailer_encryption',
            'Encryption',
            array($this, 'select_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_smtp',
            array(
                'name' => 'encryption',
                'label_for' => 'smtp_mailer_encryption',
                'options' => array(
                    '' => 'None',
                    'ssl' => 'SSL',
                    'tls' => 'TLS'
                )
            )
        );

        add_settings_field(
            'smtp_mailer_from_email',
            'From Email',
            array($this, 'text_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_smtp',
            array(
                'name' => 'from_email',
                'label_for' => 'smtp_mailer_from_email',
                'type' => 'email',
                'placeholder' => 'your_email@example.com'
            )
        );

        add_settings_field(
            'smtp_mailer_from_name',
            'From Name',
            array($this, 'text_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_smtp',
            array(
                'name' => 'from_name',
                'label_for' => 'smtp_mailer_from_name',
                'placeholder' => 'Your Name'
            )
        );

        add_settings_section(
            'smtp_mailer_section_security',
            'Security Settings',
            array($this, 'section_security_callback'),
            'smtp-mailer'
        );

        add_settings_field(
            'smtp_mailer_use_wp_config',
            'Use wp-config.php Constants',
            array($this, 'checkbox_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_security',
            array(
                'name' => 'use_wp_config',
                'label_for' => 'smtp_mailer_use_wp_config',
                'description' => 'Check this box to use SMTP credentials defined as constants in your wp-config.php file. If checked, the settings above will be ignored.'
            )
        );

        add_settings_section(
            'smtp_mailer_section_logging',
            'Logging Settings',
            array($this, 'section_logging_callback'),
            'smtp-mailer'
        );

        add_settings_field(
            'smtp_mailer_enable_logging',
            'Enable Email Logging',
            array($this, 'checkbox_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_logging',
            array(
                'name' => 'enable_logging',
                'label_for' => 'smtp_mailer_enable_logging',
                'description' => 'Check this box to enable logging of all email attempts.'
            )
        );

        add_settings_field(
            'smtp_mailer_log_retention',
            'Log Retention (Days)',
            array($this, 'text_field_callback'),
            'smtp-mailer',
            'smtp_mailer_section_logging',
            array(
                'name' => 'log_retention',
                'label_for' => 'smtp_mailer_log_retention',
                'type' => 'number',
                'placeholder' => '30',
                'min' => '0',
                'description' => 'Number of days to keep email logs. Set to 0 to keep logs indefinitely.'
            )
        );
    }

    /**
     * Settings section callback for SMTP.
     */
    public function section_smtp_callback() {
        echo '<p>Enter your SMTP server details below.</p>';
    }

    /**
     * Settings section callback for security.
     */
    public function section_security_callback() {
        echo '<p>Configure security-related settings.</p>';
        echo '<p><strong>Recommendation:</strong> For enhanced security, define your SMTP credentials as constants in your <code>wp-config.php</code> file. This keeps them out of the database.</p>';
        echo '<p>Example <code>wp-config.php</code> entries:</p>';
        echo '<pre><code>define(\'SMTP_MAILER_HOST\', \'smtp.example.com\');
define(\'SMTP_MAILER_PORT\', 587);
define(\'SMTP_MAILER_USERNAME\', \'your_email@example.com\');
define(\'SMTP_MAILER_PASSWORD\', \'your_smtp_password\');
define(\'SMTP_MAILER_ENCRYPTION\', \'tls\'); // or \'ssl\' or empty for none
define(\'SMTP_MAILER_FROM_EMAIL\', \'your_email@example.com\');
define(\'SMTP_MAILER_FROM_NAME\', \'Your Name\');</code></pre>';
    }

    /**
     * Settings section callback for logging.
     */
    public function section_logging_callback() {
        echo '<p>Configure settings related to email logging.</p>';
    }

    /**
     * Text field callback for settings
     */
    public function text_field_callback($args) {
        $options = get_option('smtp_mailer_options');
        $value = isset($options[$args['name']]) ? $options[$args['name']] : '';
        $type = isset($args['type']) ? $args['type'] : 'text';
        $placeholder = isset($args['placeholder']) ? ' placeholder="' . esc_attr($args['placeholder']) . '"' : '';
        $min = isset($args['min']) ? ' min="' . esc_attr($args['min']) . '"' : '';
        $description = isset($args['description']) ? '<p class="description">' . esc_html($args['description']) . '</p>' : '';
        echo '<input type="' . esc_attr($type) . '" id="' . esc_attr($args['label_for']) . '" name="smtp_mailer_options[' . esc_attr($args['name']) . ']" value="' . esc_attr($value) . '"' . $placeholder . $min . ' class="regular-text" />' . $description;
    }

    /**
     * Select field callback for settings
     */
    public function select_field_callback($args) {
        $options = get_option('smtp_mailer_options');
        $selected = isset($options[$args['name']]) ? $options[$args['name']] : '';
        $description = isset($args['description']) ? '<p class="description">' . esc_html($args['description']) . '</p>' : '';
        echo '<select id="' . esc_attr($args['label_for']) . '" name="smtp_mailer_options[' . esc_attr($args['name']) . ']" class="regular-text">';
        foreach ($args['options'] as $value => $label) {
            echo '<option value="' . esc_attr($value) . '"' . selected($selected, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>' . $description;
    }

    /**
     * Checkbox field callback for settings
     */
    public function checkbox_field_callback($args) {
        $options = get_option('smtp_mailer_options');
        $checked = isset($options[$args['name']]) ? checked(1, $options[$args['name']], false) : '';
        $description = isset($args['description']) ? '<p class="description">' . esc_html($args['description']) . '</p>' : '';
        echo '<input type="checkbox" id="' . esc_attr($args['label_for']) . '" name="smtp_mailer_options[' . esc_attr($args['name']) . ']" value="1" ' . $checked . ' />' . $description;
    }

    /**
     * SMTP Mailer settings page content
     */
    public function settings_page_callback() {
        ?>
        <div class="wrap">
            <h1>SMTP Mailer Settings</h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('smtp_mailer_group');
                do_settings_sections('smtp-mailer');
                submit_button('Save Settings');
                ?>
            </form>

            <h2>Send Test Email</h2>
            <form method="post" action="">
                <?php wp_nonce_field('smtp_mailer_send_test_email', 'smtp_mailer_test_email_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="smtp_mailer_test_recipient">Recipient Email</label></th>
                        <td><input type="email" id="smtp_mailer_test_recipient" name="smtp_mailer_test_recipient" value="<?php echo esc_attr(get_option('admin_email')); ?>" class="regular-text" placeholder="test@example.com" required /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="smtp_mailer_test_subject">Subject</label></th>
                        <td><input type="text" id="smtp_mailer_test_subject" name="smtp_mailer_test_subject" value="SMTP Mailer Test Email" class="regular-text" required /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="smtp_mailer_test_message">Message</label></th>
                        <td><textarea id="smtp_mailer_test_message" name="smtp_mailer_test_message" rows="5" class="large-text" required>This is a test email sent from the WordPress SMTP Mailer plugin.</textarea></td>
                    </tr>
                </table>
                <?php submit_button('Send Test Email', 'secondary', 'smtp_mailer_send_test_email_button'); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Handle test email submission
     */
    public function handle_test_email() {
        if (isset($_POST['smtp_mailer_send_test_email_button']) && current_user_can('manage_options')) {
            if (!isset($_POST['smtp_mailer_test_email_nonce']) || !wp_verify_nonce($_POST['smtp_mailer_test_email_nonce'], 'smtp_mailer_send_test_email')) {
                wp_die('Security check failed.');
            }

            $to = sanitize_email($_POST['smtp_mailer_test_recipient']);
            $subject = sanitize_text_field($_POST['smtp_mailer_test_subject']);
            $message = wp_kses_post($_POST['smtp_mailer_test_message']);

            // wp_mail will trigger the phpmailer_init hook and our send_callback
            $sent = wp_mail($to, $subject, $message);

            // Manually call the send_callback for logging
            SMTP_Mailer_Logger::send_callback($sent, $to, $subject, $message);

            // Admin notices are handled by the send_callback now, but we can add a generic one here
            if ($sent) {
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-success is-dismissible"><p>Test email initiated. Check logs for status.</p></div>';
                });
            } else {
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-error is-dismissible"><p>Test email failed to initiate. Check your SMTP settings.</p></div>';
                });
            }
        }
    }

    /**
     * Deletes plugin options upon uninstallation.
     */
    public static function delete_options() {
        delete_option('smtp_mailer_options');
    }
}