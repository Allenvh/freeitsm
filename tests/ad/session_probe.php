<?php
// CLI-only subprocess probe for actual request middleware. Never a test login HTTP endpoint.
if (PHP_SAPI!=='cli' || !preg_match('/^freeitsm_ad_test[a-z0-9_]*$/D',getenv('AD_TEST_DB_NAME') ?: '')) exit(2);
session_start();
define('DB_SERVER',getenv('DB_SERVER'));define('DB_NAME',getenv('AD_TEST_DB_NAME'));
define('DB_USERNAME',getenv('DB_USERNAME'));define('DB_PASSWORD',getenv('DB_PASSWORD'));define('BASE_URL','/');
$_SESSION=['ad_profile_id'=>(int)$argv[1],'ad_provider_id'=>(int)$argv[2],'analyst_id'=>(int)$argv[3],'ss_user_id'=>(int)$argv[4]];
$_SERVER['SCRIPT_NAME']=$argv[5];$_SERVER['REQUEST_METHOD']=$argv[6]??'GET';
require __DIR__.'/../../includes/ad_session.php';
echo 'ALLOWED';
