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

    public static function sendReadiness(array $mailbox): array {
        $providerType = trim((string)($mailbox['provider_type'] ?? ''));
        if ($providerType === '') $providerType = (string)($mailbox['provider'] ?? '');
        $authMode = (string)($mailbox['auth_mode'] ?? '');
        $outboundEnabled = (bool)($mailbox['outbound_enabled'] ?? false);
        $host = trim((string)($mailbox['smtp_host'] ?? ''));
        $port = (int)($mailbox['smtp_port'] ?? 0);
        $username = trim((string)($mailbox['smtp_username'] ?? ''));
        $password = (string)($mailbox['smtp_password_encrypted'] ?? '');
        $from = trim((string)($mailbox['smtp_from_address'] ?? $mailbox['target_mailbox'] ?? ''));
        $requiresPassword = $username !== '';

        $sendCapable = $outboundEnabled
            && $host !== ''
            && $port > 0
            && $from !== ''
            && (!$requiresPassword || $password !== '');

        return [
            'provider_type' => $providerType,
            'auth_mode' => $authMode,
            'outbound_enabled' => $outboundEnabled,
            'smtp_host_present' => $host !== '',
            'smtp_port' => $port,
            'smtp_username_present' => $username !== '',
            'smtp_password_present' => $password !== '',
            'smtp_from_address' => $from,
            'send_capable' => $sendCapable,
        ];
    }

    public static function assertSendReady(array $mailbox): void {
        $readiness = self::sendReadiness($mailbox);
        if ($readiness['send_capable']) return;
        $missing = [];
        if (!$readiness['outbound_enabled']) $missing[] = 'outbound is disabled';
        if (!$readiness['smtp_host_present']) $missing[] = 'SMTP host is missing';
        if ($readiness['smtp_port'] <= 0) $missing[] = 'SMTP port is missing';
        if ($readiness['smtp_from_address'] === '') $missing[] = 'SMTP from address is missing';
        if ($readiness['smtp_username_present'] && !$readiness['smtp_password_present']) $missing[] = 'SMTP password is missing';
        throw new Exception('Mailbox "' . ($mailbox['target_mailbox'] ?? $readiness['smtp_from_address']) . '" is not SMTP send-capable: ' . implode(', ', $missing));
    }

    public static function send(array $mailbox, string $to, string $subject, string $htmlBody, string $cc = ''): bool {
        self::assertSendReady($mailbox);

        $host = trim((string)$mailbox['smtp_host']);
        $port = (int)$mailbox['smtp_port'];
        $encryption = strtolower(trim((string)($mailbox['smtp_encryption'] ?? 'tls')));
        $transport = $encryption === 'ssl' ? 'ssl://' : '';
        $fp = @fsockopen($transport . $host, $port, $errno, $errstr, 20);
        if (!$fp) throw new Exception("SMTP connection failed: $errstr ($errno)");
        stream_set_timeout($fp, 20);

        try {
            self::expect($fp, [220]);
            self::command($fp, 'EHLO freeitsm', [250]);
            if ($encryption === 'tls') {
                self::command($fp, 'STARTTLS', [220]);
                if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new Exception('SMTP STARTTLS negotiation failed');
                }
                self::command($fp, 'EHLO freeitsm', [250]);
            }

            $username = trim((string)($mailbox['smtp_username'] ?? ''));
            $password = (string)($mailbox['smtp_password_encrypted'] ?? '');
            if ($username !== '') {
                self::command($fp, 'AUTH LOGIN', [334]);
                self::command($fp, base64_encode($username), [334]);
                self::command($fp, base64_encode($password), [235]);
            }

            $from = trim((string)($mailbox['smtp_from_address'] ?? $mailbox['target_mailbox'] ?? ''));
            $fromName = trim((string)($mailbox['smtp_from_name'] ?? $mailbox['mailbox_name'] ?? $mailbox['name'] ?? ''));
            $recipients = self::parseRecipients($to . ',' . $cc);
            if ($recipients === []) throw new Exception('No valid SMTP recipients');

            self::command($fp, 'MAIL FROM:<' . $from . '>', [250]);
            foreach ($recipients as $recipient) {
                self::command($fp, 'RCPT TO:<' . $recipient . '>', [250, 251]);
            }
            self::command($fp, 'DATA', [354]);
            fwrite($fp, self::messageData($from, $fromName, $to, $cc, $subject, $htmlBody));
            self::expect($fp, [250]);
            self::command($fp, 'QUIT', [221]);
        } finally {
            fclose($fp);
        }

        return true;
    }

    private static function parseRecipients(string $value): array {
        $out = [];
        foreach (preg_split('/[;,]/', $value) ?: [] as $candidate) {
            $candidate = trim($candidate);
            if (preg_match('/<([^>]+)>/', $candidate, $matches)) $candidate = trim($matches[1]);
            if (filter_var($candidate, FILTER_VALIDATE_EMAIL)) $out[strtolower($candidate)] = $candidate;
        }
        return array_values($out);
    }

    private static function messageData(string $from, string $fromName, string $to, string $cc, string $subject, string $htmlBody): string {
        $headers = [
            'Date: ' . date(DATE_RFC2822),
            'From: ' . self::formatAddress($from, $fromName),
            'To: ' . $to,
        ];
        if (trim($cc) !== '') $headers[] = 'Cc: ' . $cc;
        $headers = array_merge($headers, [
            'Subject: ' . self::encodeHeader($subject),
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
        ]);

        return self::dotStuff(implode("\r\n", $headers) . "\r\n\r\n" . $htmlBody) . "\r\n.\r\n";
    }

    private static function formatAddress(string $address, string $name): string {
        if ($name === '') return '<' . $address . '>';
        return self::encodeHeader($name) . ' <' . $address . '>';
    }

    private static function encodeHeader(string $value): string {
        if (preg_match('/[^\x20-\x7E]/', $value)) return '=?UTF-8?B?' . base64_encode($value) . '?=';
        return str_replace(["\r", "\n"], '', $value);
    }

    private static function dotStuff(string $data): string {
        $data = str_replace(["\r\n", "\r"], "\n", $data);
        $data = preg_replace('/^\./m', '..', $data);
        return str_replace("\n", "\r\n", $data);
    }

    private static function command($fp, string $command, array $expected): string {
        fwrite($fp, $command . "\r\n");
        return self::expect($fp, $expected);
    }

    private static function expect($fp, array $expected): string {
        $response = '';
        do {
            $line = fgets($fp, 4096);
            if ($line === false) throw new Exception('SMTP server closed the connection');
            $response .= $line;
        } while (isset($line[3]) && $line[3] === '-');

        $code = (int)substr($response, 0, 3);
        if (!in_array($code, $expected, true)) {
            throw new Exception('SMTP command failed: ' . trim($response));
        }
        return $response;
    }
}
