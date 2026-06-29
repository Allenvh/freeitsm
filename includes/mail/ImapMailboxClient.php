<?php

class ImapMailboxClient {
    private array $mailbox;
    private $client = null;
    private $folder = null;

    public function __construct(array $mailbox) { $this->mailbox = $mailbox; }

    private function ensureDependencies(): void {
        if (!class_exists('Webklex\\PHPIMAP\\ClientManager')) {
            $autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
            if (is_readable($autoload)) {
                require_once $autoload;
            }
        }

        if (!class_exists('Webklex\\PHPIMAP\\ClientManager')) {
            throw new RuntimeException('IMAP intake requires Composer dependencies. Run composer install to install webklex/php-imap.');
        }
    }

    private function host(): string {
        return (string)($this->mailbox['imap_host'] ?? $this->mailbox['imap_server'] ?? '');
    }

    private function username(): string {
        return (string)($this->mailbox['imap_username'] ?? $this->mailbox['target_mailbox'] ?? '');
    }

    private function password(): string {
        return (string)($this->mailbox['imap_password_encrypted'] ?? '');
    }

    private function encryption(): string|false {
        $raw = $this->mailbox['imap_encryption'] ?? false;
        if ($raw === false || $raw === null) return false;
        $encryption = strtolower(trim((string)$raw));
        if ($encryption === '' || $encryption === 'none' || $encryption === 'false') return false;
        if ($encryption === 'ssl') return 'ssl';
        if ($encryption === 'tls' || $encryption === 'starttls') return 'tls';
        return false;
    }

    private function resolvedConfig(bool $includePassword = true): array {
        $config = [
            'host' => $this->host(),
            'port' => (int)($this->mailbox['imap_port'] ?? 993),
            'protocol' => 'imap',
            'encryption' => $this->encryption(),
            'validate_cert' => false,
            'username' => $this->username(),
            'password' => $this->password(),
            'folder' => $this->folderName(),
        ];
        if (!$includePassword) unset($config['password']);
        return $config;
    }

    private function folderName(): string {
        return (string)($this->mailbox['imap_folder'] ?? $this->mailbox['email_folder'] ?? 'INBOX');
    }

    public function connect(): void {
        $this->ensureDependencies();

        if ($this->host() === '' || $this->username() === '') {
            throw new InvalidArgumentException('IMAP host and username are required.');
        }
        if ($this->password() === '') {
            throw new InvalidArgumentException('IMAP password is required.');
        }

        $config = $this->resolvedConfig(true);
        error_log('IMAP resolved config before connect: ' . json_encode($this->resolvedConfig(false)));

        $manager = new Webklex\PHPIMAP\ClientManager();
        $this->client = $manager->make($config);

        try {
            $this->client->connect();
            $this->folder = $this->client->getFolder($this->folderName());
        } catch (Throwable $e) {
            $this->close();
            throw new RuntimeException('IMAP connection failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function test(): bool { $this->connect(); $this->close(); return true; }

    public function fetchUnseen(int $limit = 10): array {
        if (!$this->client || !$this->folder) $this->connect();

        try {
            $messages = $this->folder->messages()->unseen()->limit($limit)->get();
        } catch (Throwable $e) {
            throw new RuntimeException('IMAP fetch failed: ' . $e->getMessage(), 0, $e);
        }

        $out = [];
        foreach ($messages as $message) {
            $uid = $this->messageUid($message);
            $body = $this->messageBody($message);
            $from = $this->messageFrom($message);
            $subject = $this->attributeString($message->getSubject() ?? null, '(No Subject)');
            $date = $message->getDate() ?? null;
            $received = $date && method_exists($date, 'toDate') ? $date->toDate()->format('c') : date('c');

            $out[] = [
                'id' => $this->messageId($message, $uid),
                'imap_msgno' => $uid,
                'subject' => $subject,
                'from' => ['emailAddress' => ['address' => $from['address'], 'name' => $from['name']]],
                'receivedDateTime' => $received,
                'bodyPreview' => mb_substr(strip_tags($body), 0, 500),
                'body' => ['content' => $body, 'contentType' => 'text'],
                'hasAttachments' => method_exists($message, 'hasAttachments') ? (bool)$message->hasAttachments() : false,
                'importance' => 'normal',
                'isRead' => false,
                'toRecipients' => [], 'ccRecipients' => [],
                'metadata' => ['uid' => $uid, 'message_id' => $this->messageId($message, $uid)]
            ];
        }
        return $out;
    }

    public function markSeen(int $msgNo): void {
        if (!$this->client || !$this->folder) return;

        try {
            $message = $this->folder->messages()->getMessageByUid($msgNo);
            if ($message && method_exists($message, 'setFlag')) {
                $message->setFlag('Seen');
            }
        } catch (Throwable $e) {
            error_log('IMAP mark seen failed: ' . $e->getMessage());
        }
    }

    public function close(): void {
        if ($this->client && method_exists($this->client, 'disconnect')) {
            $this->client->disconnect();
        }
        $this->client = null;
        $this->folder = null;
    }

    private function messageUid($message): int {
        return (int)(method_exists($message, 'getUid') ? $message->getUid() : 0);
    }

    private function messageId($message, int $uid): string {
        $messageId = method_exists($message, 'getMessageId') ? $this->attributeString($message->getMessageId() ?? null, '') : '';
        return trim($messageId) !== '' ? trim($messageId) : 'imap-' . $uid;
    }

    private function messageBody($message): string {
        if (method_exists($message, 'getHTMLBody')) {
            $body = $message->getHTMLBody();
            if (is_string($body) && $body !== '') return $body;
        }
        if (method_exists($message, 'getTextBody')) {
            $body = $message->getTextBody();
            if (is_string($body)) return $body;
        }
        return '';
    }

    private function messageFrom($message): array {
        $from = method_exists($message, 'getFrom') ? $message->getFrom() : null;
        $first = $from && method_exists($from, 'first') ? $from->first() : null;

        return [
            'address' => $first && isset($first->mail) ? strtolower((string)$first->mail) : '',
            'name' => $first && isset($first->personal) ? (string)$first->personal : '',
        ];
    }

    private function attributeString($value, string $default = ''): string {
        if ($value === null) return $default;
        if (is_scalar($value)) return (string)$value;
        if (is_object($value) && method_exists($value, 'toString')) return (string)$value->toString();
        if (is_object($value) && method_exists($value, '__toString')) return (string)$value;
        return $default;
    }
}
