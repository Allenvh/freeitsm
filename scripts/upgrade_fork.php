<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/fork_upgrade.php';
try {
    echo json_encode(forkUpgrade(connectToDatabase(),in_array('--apply',$argv,true),in_array('--finish',$argv,true)),JSON_PRETTY_PRINT).PHP_EOL;
} catch (Throwable $e) {
    // Database/library exceptions may reflect ciphertext or credentials. Never print them.
    $safe=$e instanceof RuntimeException && !($e instanceof PDOException);
    fwrite(STDERR,$safe ? $e->getMessage().PHP_EOL : "Upgrade failed; check the schema and encryption key. No credential values are displayed.\n");
    exit(1);
}
