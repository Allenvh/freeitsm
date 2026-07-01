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
            $body = $this->messageBody($message);
            $from = $this->messageFrom($message);
            $toRecipients = $this->messageRecipients($message, 'to');
            $subject = $this->attributeString($message->getSubject() ?? null, '(No Subject)');
            $date = $message->getDate() ?? null;
            $received = $date && method_exists($date, 'toDate') ? $date->toDate()->format('c') : date('c');
            $uid = $this->messageUid($message);
            $messageId = $this->messageId($message);
            $externalMessageId = $this->externalMessageId($uid, $messageId, $subject, $from, $received, $body);

            $out[] = [
                'id' => $externalMessageId,
                'imap_msgno' => $uid,
                'subject' => $subject,
                'from' => ['emailAddress' => ['address' => $from['address'], 'name' => $from['name']]],
                'receivedDateTime' => $received,
                'bodyPreview' => mb_substr(strip_tags($body), 0, 500),
                'body' => ['content' => $body, 'contentType' => 'text'],
                'hasAttachments' => method_exists($message, 'hasAttachments') ? (bool)$message->hasAttachments() : false,
                'importance' => 'normal',
                'isRead' => false,
                'toRecipients' => $toRecipients, 'ccRecipients' => [],
                'metadata' => [
                    'uid' => $uid,
                    'message_id' => $messageId,
                    'external_message_id' => $externalMessageId,
                    'webklex_attributes' => $this->messageDebugAttributes($message),
                ]
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

    private function messageUid($message): ?int {
        foreach ([
            fn() => method_exists($message, 'getUid') ? $message->getUid() : null,
            fn() => method_exists($message, 'get') ? $message->get('uid') : null,
            fn() => method_exists($message, 'getMessageId') ? $message->getMessageId() : null,
            fn() => method_exists($message, 'getMessageNo') ? $message->getMessageNo() : null,
            fn() => $this->valueFromAttributes($message, 'uid'),
            fn() => $this->valueFromHeader($message, 'uid'),
        ] as $reader) {
            try {
                $value = $reader();
                $uid = $this->positiveInt($value);
                if ($uid !== null) return $uid;
            } catch (Throwable $e) {
                continue;
            }
        }
        return null;
    }

    private function messageId($message): string {
        $messageId = method_exists($message, 'getMessageId') ? $this->attributeString($message->getMessageId() ?? null, '') : '';
        return trim($messageId);
    }

    private function externalMessageId(?int $uid, string $messageId, string $subject, array $from, string $received, string $body): string {
        if ($uid !== null) {
            return 'imap:' . (int)($this->mailbox['id'] ?? 0) . ':' . $uid;
        }
        if ($messageId !== '') {
            return $messageId;
        }
        $preview = mb_substr(strip_tags($body), 0, 500);
        return 'imap-hash:' . hash('sha256', implode('|', [
            (string)($this->mailbox['id'] ?? 0),
            $subject,
            $from['address'] ?? '',
            $received,
            $preview,
        ]));
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
        $from = $this->extractAddressList($message, 'from');
        return $from[0] ?? ['address' => '', 'name' => ''];
    }

    private function messageRecipients($message, string $field): array {
        return array_map(
            fn(array $recipient) => ['emailAddress' => ['address' => $recipient['address'], 'name' => $recipient['name']]],
            $this->extractAddressList($message, $field)
        );
    }

    private function extractAddressList($message, string $field): array {
        $field = strtolower($field);
        $method = 'get' . ucfirst($field);
        $candidates = [];

        if (method_exists($message, $method)) {
            try {
                $candidates[] = $message->{$method}();
            } catch (Throwable $e) {
                // Fall through to Webklex attributes below.
            }
        }

        $attribute = $this->valueFromAttributes($message, $field);
        if ($attribute !== null) $candidates[] = $attribute;

        $addressAttribute = $this->valueFromAttributes($message, $field . 'address');
        if ($addressAttribute !== null) $candidates[] = $addressAttribute;

        $out = [];
        foreach ($candidates as $candidate) {
            foreach ($this->addressCandidates($candidate) as $addr) {
                $normalized = $this->normalizeAddressObject($addr);
                if ($normalized['address'] === '') continue;
                $key = strtolower($normalized['address']);
                if (!isset($out[$key])) $out[$key] = $normalized;
            }
            if ($out !== []) break;
        }

        return array_values($out);
    }

    private function addressCandidates($value): array {
        if ($value === null) return [];
        if (is_object($value)) {
            if (method_exists($value, 'all')) {
                $all = $value->all();
                if (is_array($all)) return $all;
            }
            if ($value instanceof Traversable) return iterator_to_array($value);
            if (method_exists($value, 'toArray')) {
                $array = $value->toArray();
                if (is_array($array)) return $array;
            }
            if (method_exists($value, 'first')) {
                $first = $value->first();
                if ($first !== null) return [$first];
            }
            return [$value];
        }
        if (is_array($value)) return array_values($value);
        if (is_string($value)) return preg_split('/\s*,\s*/', $value, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        return [$value];
    }

    private function normalizeAddressObject($addr): array {
        $address = '';
        $name = '';

        if (is_array($addr)) {
            $address = $this->firstString($addr, ['mail', 'address', 'email']);
            $name = $this->firstString($addr, ['personal', 'name', 'full']);
            if ($address === '' && isset($addr[0]) && is_string($addr[0])) $address = $addr[0];
        } elseif (is_object($addr)) {
            foreach (['mail', 'address', 'email'] as $key) {
                if (isset($addr->{$key})) {
                    $address = $this->attributeString($addr->{$key}, '');
                    if ($address !== '') break;
                }
            }
            foreach (['personal', 'name', 'full'] as $key) {
                if (isset($addr->{$key})) {
                    $name = $this->attributeString($addr->{$key}, '');
                    if ($name !== '') break;
                }
            }
            if ($address === '') $address = $this->attributeString($addr, '');
        } else {
            $address = $this->attributeString($addr, '');
        }

        if (preg_match('/^(.*?)<([^>]+)>$/', $address, $matches)) {
            if ($name === '') $name = trim($matches[1], " \t\n\r\0\x0B\"'");
            $address = $matches[2];
        }

        $address = trim(strtolower($address), " \t\n\r\0\x0B<>");
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) $address = '';
        if (strcasecmp($name, $address) === 0) $name = '';

        return ['address' => $address, 'name' => $name];
    }

    private function firstString(array $values, array $keys): string {
        foreach ($keys as $key) {
            if (isset($values[$key])) {
                $value = $this->attributeString($values[$key], '');
                if ($value !== '') return $value;
            }
        }
        return '';
    }

    private function attributeString($value, string $default = ''): string {
        if ($value === null) return $default;
        if (is_scalar($value)) return (string)$value;
        if (is_object($value) && method_exists($value, 'toString')) return (string)$value->toString();
        if (is_object($value) && method_exists($value, '__toString')) return (string)$value;
        return $default;
    }

    private function positiveInt($value): ?int {
        $text = trim($this->attributeString($value, ''));
        if ($text === '' || !preg_match('/^\d+$/', $text)) return null;
        $int = (int)$text;
        return $int > 0 ? $int : null;
    }

    private function valueFromAttributes($message, string $key) {
        if (!method_exists($message, 'getAttributes')) return null;
        $attributes = $message->getAttributes();
        if (is_array($attributes)) return $attributes[$key] ?? null;
        if (is_object($attributes)) {
            if (isset($attributes->{$key})) return $attributes->{$key};
            if (method_exists($attributes, 'get')) return $attributes->get($key);
            if (method_exists($attributes, 'toArray')) {
                $array = $attributes->toArray();
                return is_array($array) ? ($array[$key] ?? null) : null;
            }
        }
        return null;
    }

    private function valueFromHeader($message, string $key) {
        if (!method_exists($message, 'getHeader')) return null;
        $header = $message->getHeader();
        return $header && method_exists($header, 'get') ? $header->get($key) : null;
    }

    private function messageDebugAttributes($message): array {
        return [
            'getUid' => method_exists($message, 'getUid') ? $this->attributeString($message->getUid(), '') : null,
            'get_uid' => method_exists($message, 'get') ? $this->attributeString($message->get('uid'), '') : null,
            'getMessageId' => method_exists($message, 'getMessageId') ? $this->attributeString($message->getMessageId(), '') : null,
            'getMessageNo' => method_exists($message, 'getMessageNo') ? $this->attributeString($message->getMessageNo(), '') : null,
            'attributes' => $this->debugValue(method_exists($message, 'getAttributes') ? $message->getAttributes() : null),
            'header_uid' => $this->attributeString($this->valueFromHeader($message, 'uid'), ''),
        ];
    }

    private function debugValue($value) {
        if ($value === null || is_scalar($value)) return $value;
        if (is_object($value) && method_exists($value, 'toArray')) $value = $value->toArray();
        if (is_array($value)) return array_map(fn($item) => $this->debugValue($item), $value);
        return $this->attributeString($value, get_debug_type($value));
    }
}
