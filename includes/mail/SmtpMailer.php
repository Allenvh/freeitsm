<?php
class SmtpMailer {
    public static function test(array $mailbox): bool {
        $host = $mailbox['smtp_host'] ?? '';
        $port = (int)($mailbox['smtp_port'] ?? 587);
        $transport = (($mailbox['smtp_encryption'] ?? 'tls') === 'ssl') ? 'ssl://' : '';
        $fp = @fsockopen($transport . $host, $port, $errno, $errstr, 10);
        if (!$fp) throw new Exception("SMTP connection failed: $errstr");
        fclose($fp); return true;
    }
    // TODO: send ticket notifications/replies through authenticated SMTP.
}
