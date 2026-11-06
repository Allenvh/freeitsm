<?php
// Compatibility redirect: the custom AD implementation has been retired.
session_start();
require_once 'config.php';
header('Location: ' . BASE_URL . 'auth/login.php', true, 302);
exit;
