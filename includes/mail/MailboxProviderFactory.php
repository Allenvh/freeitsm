<?php
require_once __DIR__ . '/ImapMailboxClient.php';
require_once __DIR__ . '/SmtpMailer.php';
class MailboxProviderFactory {
    public static function isImapSmtp(array $mailbox): bool { return ($mailbox['provider_type'] ?? $mailbox['provider'] ?? '') === 'imap_smtp'; }
    public static function imap(array $mailbox): ImapMailboxClient { return new ImapMailboxClient($mailbox); }
}
