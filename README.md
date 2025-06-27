# WP SMTP Mailer Plugin

## Overview

The WP SMTP Mailer plugin is a robust and secure solution for sending emails from your WordPress site using a custom SMTP server. By default, WordPress uses PHP's `mail()` function, which can often lead to emails being marked as spam or not being delivered at all. This plugin bypasses that limitation by routing all outgoing WordPress emails through your specified SMTP server, ensuring higher deliverability and reliability.

## Goals

*   **Reliable Email Delivery:** Ensure all WordPress-generated emails are delivered successfully by utilizing a dedicated SMTP server.
*   **Enhanced Security:** Provide options for securely storing SMTP credentials, including the ability to use `wp-config.php` constants.
*   **Comprehensive Logging:** Log all email attempts (successes and failures) for easy monitoring and troubleshooting.
*   **Actionable Statistics:** Offer a dashboard widget to quickly view email delivery statistics over various timeframes.
*   **User-Friendly Configuration:** Provide an intuitive settings page within the WordPress admin for easy setup and management.
*   **Clean Uninstallation:** Ensure no traces of the plugin (database tables, options, scheduled tasks) are left behind upon uninstallation.

## Advantages

*   **Improved Deliverability:** Significantly reduces the chances of emails landing in spam folders.
*   **Better Reliability:** Leverages a dedicated SMTP service for consistent email sending.
*   **Centralized Control:** All email settings are managed from one place within your WordPress dashboard.
*   **Troubleshooting Made Easy:** Detailed logs and statistics help identify and resolve email delivery issues quickly.
*   **Flexible Credential Storage:** Choose between storing credentials in the database or more securely in `wp-config.php`.
*   **Lightweight & Efficient:** Designed to be performant with a focus on essential features.

## Capabilities

*   Configures WordPress to send all emails via SMTP.
*   Supports various SMTP encryption methods (SSL/TLS).
*   Allows custom "From" email address and name.
*   Provides a dedicated settings page for SMTP configuration.
*   Includes a test email sender on the settings page.
*   Logs every email attempt with recipient, subject, status, and error messages.
*   Offers a dashboard widget displaying successful and failed email counts for today, last 7 days, and last 30 days.
*   Implements a configurable log retention policy to manage database size.
*   Supports defining SMTP credentials as constants in `wp-config.php` for enhanced security.
*   Performs a clean uninstallation, removing all plugin data.

## Installation

1.  **Download the Plugin:** Download the plugin as a ZIP file from the GitHub repository.
2.  **Upload via WordPress:**
    *   Go to your WordPress admin dashboard.
    *   Navigate to `Plugins > Add New`.
    *   Click the "Upload Plugin" button at the top.
    *   Choose the downloaded ZIP file and click "Install Now".
3.  **Activate the Plugin:** After installation, click "Activate Plugin".

Alternatively, you can upload the `smtp-mailer` folder (after extracting the ZIP) directly to your `wp-content/plugins/` directory via FTP/SFTP.

## Usage

1.  **Configure SMTP Settings:**
    *   After activating the plugin, navigate to `Settings > SMTP Mailer` in your WordPress admin dashboard.
    *   Fill in your SMTP server details (Host, Port, Username, Password, Encryption, From Email, From Name).
    *   **Security Note:** For enhanced security, consider defining your SMTP credentials as constants in your `wp-config.php` file. Check the "Use wp-config.php Constants" option on the settings page if you choose this method.
        ```php
        // Example wp-config.php entries (add these above the `/* That's all, stop editing! Happy blogging. */` line)
        define('SMTP_MAILER_HOST', 'smtp.your-provider.com');
        define('SMTP_MAILER_PORT', 587);
        define('SMTP_MAILER_USERNAME', 'your_email@your-domain.com');
        define('SMTP_MAILER_PASSWORD', 'your_smtp_password');
        define('SMTP_MAILER_ENCRYPTION', 'tls'); // Options: 'ssl', 'tls', or '' for none
        define('SMTP_MAILER_FROM_EMAIL', 'your_email@your-domain.com');
        define('SMTP_MAILER_FROM_NAME', 'Your Website Name');
        ```
    *   Click "Save Settings".

2.  **Send a Test Email:**
    *   On the same `Settings > SMTP Mailer` page, scroll down to the "Send Test Email" section.
    *   Enter a recipient email address, subject, and message.
    *   Click "Send Test Email". A success or failure notice will appear, and the attempt will be logged.

3.  **Monitor Email Statistics:**
    *   Go to your WordPress admin dashboard (`Dashboard > Home`).
    *   Locate the "SMTP Mailer Statistics" widget to view a summary of successful and failed email attempts for today, the last 7 days, and the last 30 days.

4.  **Manage Log Retention:**
    *   On the `Settings > SMTP Mailer` page, under "Logging Settings", you can configure how many days email logs are retained in the database. This helps manage database size.

## Contributing

Contributions are welcome! If you find a bug, have a feature request, or want to contribute code, please visit the [GitHub repository](https://github.com/serhanco/wpsmtp) and open an issue or submit a pull request.

## License

This plugin is released under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html) license.