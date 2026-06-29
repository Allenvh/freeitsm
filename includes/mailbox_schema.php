<?php
/**
 * Runtime-safe mailbox schema repair helpers.
 *
 * The canonical mailbox configuration table is target_mailboxes. Setup/db-verify
 * remains the full schema repair path, but mailbox endpoints call this lightweight
 * guard before selecting/updating newer IMAP/SMTP columns so legacy installs do
 * not fail with "Unknown column auth_mode/provider_type" before an admin can run
 * db-verify manually.
 */

if (!function_exists('ensureTargetMailboxSchema')) {
    function ensureTargetMailboxSchema(PDO $conn): void {
        $dbName = defined('DB_NAME') ? DB_NAME : (string)$conn->query('SELECT DATABASE()')->fetchColumn();

        $tableCheck = $conn->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = 'target_mailboxes'");
        $tableCheck->execute([$dbName]);
        if ((int)$tableCheck->fetchColumn() === 0) {
            throw new Exception('Canonical mailbox table target_mailboxes is missing. Run setup/database verification.');
        }

        $columns = [
            'provider_type'           => ["VARCHAR(20) NOT NULL DEFAULT 'graph'", 'provider'],
            'auth_mode'               => ["VARCHAR(20) NOT NULL DEFAULT 'delegated'", 'target_mailbox'],
            'imap_host'               => ['TEXT NULL', 'imap_encryption'],
            'imap_username'           => ['TEXT NULL', 'imap_host'],
            'imap_password_encrypted' => ['TEXT NULL', 'imap_username'],
            'imap_folder'             => ["VARCHAR(100) NOT NULL DEFAULT 'INBOX'", 'imap_password_encrypted'],
            'smtp_host'               => ['TEXT NULL', 'imap_folder'],
            'smtp_port'               => ['INT NOT NULL DEFAULT 587', 'smtp_host'],
            'smtp_encryption'         => ["VARCHAR(10) NOT NULL DEFAULT 'tls'", 'smtp_port'],
            'smtp_username'           => ['TEXT NULL', 'smtp_encryption'],
            'smtp_password_encrypted' => ['TEXT NULL', 'smtp_username'],
            'smtp_from_address'       => ['TEXT NULL', 'smtp_password_encrypted'],
            'smtp_from_name'          => ['VARCHAR(255) NULL', 'smtp_from_address'],
            'intake_enabled'          => ['TINYINT(1) NOT NULL DEFAULT 1', 'smtp_from_name'],
            'outbound_enabled'        => ['TINYINT(1) NOT NULL DEFAULT 1', 'intake_enabled'],
            'last_checked_at'         => ['DATETIME NULL', 'last_checked_datetime'],
        ];

        foreach ($columns as $columnName => [$definition, $afterColumn]) {
            $colCheck = $conn->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = ? AND table_name = 'target_mailboxes' AND column_name = ?");
            $colCheck->execute([$dbName, $columnName]);
            if ((int)$colCheck->fetchColumn() > 0) {
                continue;
            }

            $safeColumn = str_replace('`', '``', $columnName);
            $safeAfter = str_replace('`', '``', $afterColumn);
            $afterCheck = $conn->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = ? AND table_name = 'target_mailboxes' AND column_name = ?");
            $afterCheck->execute([$dbName, $afterColumn]);
            $afterSql = ((int)$afterCheck->fetchColumn() > 0) ? " AFTER `$safeAfter`" : '';
            $conn->exec("ALTER TABLE `target_mailboxes` ADD `$safeColumn` $definition$afterSql");
        }

        $conn->exec("UPDATE target_mailboxes SET provider_type = CASE WHEN provider = 'google' THEN 'gmail' WHEN provider = 'imap_smtp' THEN 'imap_smtp' ELSE 'graph' END WHERE provider_type IS NULL OR provider_type = ''");
        $conn->exec("UPDATE target_mailboxes SET auth_mode = 'delegated' WHERE auth_mode IS NULL OR auth_mode = '' OR auth_mode = 'oauth'");
        $conn->exec("UPDATE target_mailboxes SET imap_folder = COALESCE(NULLIF(imap_folder, ''), email_folder, 'INBOX') WHERE imap_folder IS NULL OR imap_folder = ''");
    }
}
?>
