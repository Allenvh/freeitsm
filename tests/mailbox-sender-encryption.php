<?php
/** Standalone regression: legacy sender overrides must decrypt like the target. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$keyPath = tempnam(sys_get_temp_dir(), 'freeitsm-sender-');
define('ENCRYPTION_KEY_PATH', $keyPath);
file_put_contents($keyPath, bin2hex(random_bytes(32)));
require_once __DIR__ . '/../includes/encryption.php';
try {
    $address = 'helpdesk@freeitsm.test';
    foreach ([encryptValue($address), $address, '', null] as $stored) {
        $row = decryptMailboxRow(['smtp_from_address'=>$stored, 'target_mailbox'=>encryptValue($address)]);
        $from = trim((string)($row['smtp_from_address'] ?? '')) ?: $row['target_mailbox'];
        if ($from !== $address || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Legacy sender did not resolve to a valid address.');
        }
    }
    echo "PASS encrypted, plaintext, empty and null legacy SMTP sender overrides\n";
} finally {
    unlink($keyPath);
}
