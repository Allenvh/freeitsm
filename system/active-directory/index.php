<?php
// Compatibility redirect: the custom AD implementation has been retired.
session_start();
require_once '../../config.php';
header('Location: ' . BASE_URL . 'system/sso/', true, 302);
exit;
