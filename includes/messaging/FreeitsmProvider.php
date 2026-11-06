<?php
/**
 * FreeitsmProvider — the "provider" for the self-hosted website chat channel.
 *
 * Unlike Twilio/Meta there is no third party to call: a web chat conversation lives
 * entirely inside FreeITSM. Inbound arrives through the public widget endpoints
 * (api/webchat/*), not the shared provider webhook, and "sending" a reply is simply
 * persisting the outbound message — the visitor's browser polls poll.php and picks it
 * up. So sendMessage does no network I/O; it just hands back a synthetic message id
 * for the emails.exchange_message_id column (kept unique for dedupe/threading parity
 * with the other channels).
 *
 * The class exists so the shared reply path (api/messaging/send_message.php, which
 * calls messagingProvider($channel)->sendMessage()) works for webchat with no special
 * casing — the one channel that has no external send still satisfies the contract.
 */

require_once __DIR__ . '/MessagingProvider.php';

class FreeitsmProvider extends MessagingProvider
{
    /**
     * Webchat has its own public endpoints, so the shared webhook never routes here.
     *
     * @param string $rawBody  the raw request body (ignored)
     * @param array $headers  the request headers (ignored)
     * @param array $params  the parsed POST parameters (ignored)
     * @param string $url  the public URL that was hit (ignored)
     */
    public function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
    {
        return false;
    }

    /**
     * Not used — inbound is handled by api/webchat/send.php, not parseInbound.
     *
     * @param string $rawBody  the raw request body (ignored)
     * @param array $params  the parsed POST parameters (ignored)
     */
    public function parseInbound(string $rawBody, array $params): array
    {
        return [];
    }

    /**
     * "Send" an outbound reply. There is nothing to transmit — the message row is
     * stored by the caller and delivered when the visitor next polls — so we only
     * mint a stable id. Prefixed 'wc_out_' so it's recognisable in the emails table.
     *
     * @param string $to  the web chat visitor's address (ignored - the caller stores the message)
     * @param string $body  the reply text (ignored - the caller stores the message)
     */
    public function sendMessage(string $to, string $body): string
    {
        return 'wc_out_' . bin2hex(random_bytes(12));
    }

    /** Nothing to reach; the channel is healthy as long as it exists and is active. */
    public function testConnection(): string
    {
        return 'Web chat channel is self-hosted — no external provider to test.';
    }
}
