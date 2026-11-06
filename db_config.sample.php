<?php
/**
 * Database Configuration SAMPLE - Store outside web root for security
 *
 * SETUP INSTRUCTIONS:
 * 1. Copy this file to: C:\wamp64\db_config.php (outside the web root)
 * 2. Update the values below with your actual database credentials
 * 3. Make sure config.php points to this file location
 */

// MySQL Connection Settings
define('DB_SERVER', 'localhost');                     // MySQL server hostname
define('DB_NAME', 'freeitsm');                       // Database name
define('DB_USERNAME', 'your_mysql_username');         // MySQL username
define('DB_PASSWORD', 'your_secure_password');        // MySQL password

// Show PHP errors in the browser - development boxes only. Errors are always
// logged, and the command line always shows them; this only decides whether a
// web page prints them too. It lives here rather than in config.php because
// config.php is replaced on every upgrade and this file is not.
// define('FREEITSM_DISPLAY_ERRORS', true);
?>
