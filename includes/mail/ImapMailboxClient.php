<?php
class ImapMailboxClient {
    private array $mailbox;
    private $stream = null;
    public function __construct(array $mailbox) { $this->mailbox = $mailbox; }
    private function mailboxString(): string {
        $enc = $this->mailbox['imap_encryption'] ?? 'ssl';
        $flags = $enc === 'ssl' ? '/imap/ssl' : ($enc === 'tls' ? '/imap/tls' : '/imap/notls');
        $folder = $this->mailbox['imap_folder'] ?? $this->mailbox['email_folder'] ?? 'INBOX';
        return sprintf('{%s:%d%s}%s', $this->mailbox['imap_host'] ?: $this->mailbox['imap_server'], (int)($this->mailbox['imap_port'] ?? 993), $flags, $folder);
    }
    public function connect(): void {
        $this->stream = @imap_open($this->mailboxString(), $this->mailbox['imap_username'] ?? $this->mailbox['target_mailbox'], $this->mailbox['imap_password_encrypted'] ?? '', 0, 1);
        if (!$this->stream) throw new Exception('IMAP connection failed: ' . imap_last_error());
    }
    public function test(): bool { $this->connect(); $this->close(); return true; }
    public function fetchUnseen(int $limit = 10): array {
        if (!$this->stream) $this->connect();
        $ids = imap_search($this->stream, 'UNSEEN') ?: [];
        $out = [];
        foreach (array_slice($ids, 0, $limit) as $msgNo) {
            $header = imap_headerinfo($this->stream, $msgNo);
            $messageId = trim($header->message_id ?? ('imap-' . imap_uid($this->stream, $msgNo)));
            $from = $header->from[0] ?? null;
            $body = imap_fetchbody($this->stream, $msgNo, '1.1') ?: imap_fetchbody($this->stream, $msgNo, '1') ?: imap_body($this->stream, $msgNo);
            $out[] = [
                'id' => $messageId,
                'imap_msgno' => $msgNo,
                'subject' => isset($header->subject) ? imap_utf8($header->subject) : '(No Subject)',
                'from' => ['emailAddress' => ['address' => strtolower($from->mailbox . '@' . $from->host), 'name' => isset($from->personal) ? imap_utf8($from->personal) : '']],
                'receivedDateTime' => isset($header->date) ? date('c', strtotime($header->date)) : date('c'),
                'bodyPreview' => mb_substr(strip_tags($body), 0, 500),
                'body' => ['content' => $body, 'contentType' => 'text'],
                'hasAttachments' => false,
                'importance' => 'normal',
                'isRead' => false,
                'toRecipients' => [], 'ccRecipients' => [],
                'metadata' => ['uid' => imap_uid($this->stream, $msgNo), 'message_id' => $messageId]
            ];
        }
        return $out;
    }
    public function markSeen(int $msgNo): void { if ($this->stream) imap_setflag_full($this->stream, (string)$msgNo, '\\Seen'); }
    public function close(): void { if ($this->stream) { imap_close($this->stream); $this->stream = null; } }
}
