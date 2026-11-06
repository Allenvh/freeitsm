<?php
/**
 * MetaCloudProvider — WhatsApp via Meta's WhatsApp Cloud API (direct, no BSP).
 *
 * Cheapest long-term path, but requires Meta business verification before it can
 * send/receive — which is why TwilioProvider is the recommended first build. The
 * class exists now so "abstract both providers" is real from day one; the Twilio
 * path is the tested one for Phase 1.
 *
 * Credentials JSON (messaging_channels.credentials, encrypted at rest):
 *   { "phone_number_id": "1234567890", "access_token": "EAAxxxx", "app_secret": "xxxx" }
 * messaging_channels.verify_token holds the hub.verify_token used for the GET
 * subscription handshake.
 *
 * Inbound: Meta POSTs JSON (entry[].changes[].value.messages[]). Authenticity is
 * the X-Hub-Signature-256 header: 'sha256=' . HMAC-SHA256(app_secret, rawBody).
 */

require_once __DIR__ . '/MessagingProvider.php';

class MetaCloudProvider extends MessagingProvider
{
    // Default Graph API version. Meta deprecates a version roughly two years after
    // release, so this is bumped periodically. An install can override it per channel
    // without a code change by adding "graph_version" to the credentials JSON.
    private const GRAPH_VERSION = 'v21.0';

    /**
     * Answers Meta's webhook subscription check: returns the challenge when the verify
     * token matches, else null.
     *
     * @param array $get  the request's query parameters - hub.mode, hub.verify_token, hub.challenge (PHP may turn the dots into _)
     */
    public function verifyChallenge(array $get): ?string
    {
        $mode      = $get['hub_mode']         ?? ($get['hub.mode'] ?? '');
        $token     = $get['hub_verify_token'] ?? ($get['hub.verify_token'] ?? '');
        $challenge = $get['hub_challenge']    ?? ($get['hub.challenge'] ?? '');
        $expected  = $this->channel['verify_token'] ?? '';
        if ($mode === 'subscribe' && $expected !== '' && hash_equals($expected, (string) $token)) {
            return (string) $challenge;
        }
        return null;
    }

    /**
     * Checks the X-Hub-Signature-256 HMAC of the raw body against the app secret.
     *
     * @param string $rawBody  the raw request body, exactly as received - the HMAC is over these bytes
     * @param array $headers  the request headers, lower-cased keys (x-hub-signature-256)
     * @param array $params  the parsed POST parameters (not used)
     * @param string $url  the public URL that was hit (not used)
     */
    public function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
    {
        $sig = $headers['x-hub-signature-256'] ?? '';
        $secret = $this->channel['credentials']['app_secret'] ?? '';
        if ($sig === '' || $secret === '' || !str_starts_with($sig, 'sha256=')) {
            return false;
        }
        $expected = 'sha256=' . hash_hmac('sha256', $rawBody, $secret);
        return hash_equals($expected, $sig);
    }

    /**
     * Turns a WhatsApp Cloud API webhook into inbound messages - text, or media to download
     * later with the caption as the body.
     *
     * @param string $rawBody  the raw JSON webhook body from Meta
     * @param array $params  the parsed POST parameters (not used)
     */
    public function parseInbound(string $rawBody, array $params): array
    {
        $payload = json_decode($rawBody, true);
        if (!is_array($payload)) {
            return [];
        }
        $out = [];
        foreach (($payload['entry'] ?? []) as $entry) {
            foreach (($entry['changes'] ?? []) as $change) {
                $value = $change['value'] ?? [];
                // Map waid → profile name from the contacts block.
                $names = [];
                foreach (($value['contacts'] ?? []) as $contact) {
                    $names[$contact['wa_id'] ?? ''] = $contact['profile']['name'] ?? '';
                }
                foreach (($value['messages'] ?? []) as $msg) {
                    $type = $msg['type'] ?? '';
                    $entry = [
                        'from'            => $this->ensurePlus($msg['from'] ?? ''),
                        'to'              => $this->ensurePlus($value['metadata']['display_phone_number'] ?? ''),
                        'body'            => '',
                        'profile_name'    => $names[$msg['from'] ?? ''] ?? '',
                        'provider_msg_id' => $msg['id'] ?? '',
                        'media'           => [],
                        'timestamp'       => isset($msg['timestamp']) ? (int) $msg['timestamp'] : null,
                    ];
                    if ($type === 'text') {
                        $entry['body'] = trim($msg['text']['body'] ?? '');
                    } elseif (in_array($type, ['image', 'document', 'audio', 'video', 'voice', 'sticker'], true)) {
                        // Media arrives as an id to fetch later (see downloadMedia); caption (if any) is the body.
                        $m = $msg[$type] ?? [];
                        $entry['body'] = trim($m['caption'] ?? '');
                        $entry['media'][] = [
                            'id'           => $m['id'] ?? '',
                            'content_type' => $m['mime_type'] ?? 'application/octet-stream',
                            'filename'     => $m['filename'] ?? '',
                        ];
                    } else {
                        // location / contacts / interactive etc. — not handled yet.
                        continue;
                    }
                    $out[] = $entry;
                }
            }
        }
        return $out;
    }

    /**
     * Sends a WhatsApp text message through the Graph API and returns Meta's message id.
     *
     * @param string $to  the recipient's WhatsApp number, with or without the leading +
     * @param string $body  the message text
     */
    public function sendMessage(string $to, string $body): string
    {
        $phoneId = $this->channel['credentials']['phone_number_id'] ?? '';
        $token   = $this->channel['credentials']['access_token'] ?? '';
        if ($phoneId === '' || $token === '') {
            throw new Exception('Meta channel is missing its phone number id or access token.');
        }

        $version = $this->channel['credentials']['graph_version'] ?? self::GRAPH_VERSION;
        $url = 'https://graph.facebook.com/' . $version . "/$phoneId/messages";
        $payload = json_encode([
            'messaging_product' => 'whatsapp',
            'to'                => ltrim($this->ensurePlus($to), '+'),
            'type'              => 'text',
            'text'              => ['body' => $body],
        ]);

        [$code, $resp] = $this->httpRequest($url, [
            'method'  => 'POST',
            'headers' => ['Content-Type: application/json', "Authorization: Bearer $token"],
            'body'    => $payload,
        ]);

        $json = json_decode($resp, true);
        if ($code < 200 || $code >= 300) {
            $msg = $json['error']['message'] ?? ('HTTP ' . $code);
            throw new Exception('Meta rejected the message: ' . $msg);
        }
        return $json['messages'][0]['id'] ?? '';
    }

    /**
     * Two Graph API calls: upload the bytes to get a media id, then send a
     * message referencing it. $filePath is used directly (multipart upload);
     * $publicUrl is Twilio's requirement, not Meta's, and is ignored here.
     *
     * @param string $to  the recipient's WhatsApp number, with or without the leading +
     * @param string $filePath  the file on this server's disk, already validated
     * @param string $mimeType  the file's MIME type - also decides image, video, audio or document
     * @param string $caption  text sent with the file; '' for none, and never sent with audio
     * @param string $publicUrl  not used - Meta takes an upload
     * @param string $filename  the name the recipient sees on a document; '' uses the stored name
     */
    public function sendMedia(string $to, string $filePath, string $mimeType, string $caption = '', string $publicUrl = '', string $filename = ''): string
    {
        $phoneId = $this->channel['credentials']['phone_number_id'] ?? '';
        $token   = $this->channel['credentials']['access_token'] ?? '';
        if ($phoneId === '' || $token === '') {
            throw new Exception('Meta channel is missing its phone number id or access token.');
        }
        if (!is_file($filePath)) {
            throw new Exception('Attachment file not found.');
        }

        $version = $this->channel['credentials']['graph_version'] ?? self::GRAPH_VERSION;
        $category = messagingMediaCategory($mimeType); // 'image'|'video'|'audio'|'document'

        // Step 1: upload the bytes to get a media id (multipart, shared helper).
        $displayName = $this->outboundFilename($filePath, $filename);
        [$code, $resp] = $this->httpMultipart(
            'https://graph.facebook.com/' . $version . "/$phoneId/media",
            [
                'messaging_product' => 'whatsapp',
                'type'              => $mimeType,
                'file'              => new CURLFile($filePath, $mimeType, $displayName),
            ],
            ["Authorization: Bearer $token"]
        );

        $json = json_decode($resp, true);
        if ($code < 200 || $code >= 300 || empty($json['id'])) {
            throw new Exception('Meta rejected the attachment upload: ' . ($json['error']['message'] ?? ('HTTP ' . $code)));
        }
        $mediaId = $json['id'];

        // Step 2: send a message referencing that media id.
        $mediaObj = ['id' => $mediaId];
        // Audio messages have no caption field in the Cloud API — Meta
        // rejects the request outright if one is sent, so it's left off.
        if ($caption !== '' && $category !== 'audio') {
            $mediaObj['caption'] = $caption;
        }
        // A document shows its file name in the chat; without this WhatsApp
        // shows "Untitled".
        if ($category === 'document') {
            $mediaObj['filename'] = $displayName;
        }
        $payload = json_encode([
            'messaging_product' => 'whatsapp',
            'to'                => ltrim($this->ensurePlus($to), '+'),
            'type'              => $category,
            $category           => $mediaObj,
        ]);

        [$code2, $resp2] = $this->httpRequest('https://graph.facebook.com/' . $version . "/$phoneId/messages", [
            'method'  => 'POST',
            'headers' => ['Content-Type: application/json', "Authorization: Bearer $token"],
            'body'    => $payload,
        ]);
        $json2 = json_decode($resp2, true);
        if ($code2 < 200 || $code2 >= 300) {
            throw new Exception('Meta rejected the attachment message: ' . ($json2['error']['message'] ?? ('HTTP ' . $code2)));
        }
        return $json2['messages'][0]['id'] ?? '';
    }

    /**
     * Sends an approved WhatsApp template, with $vars as the body parameters, and returns
     * Meta's message id.
     *
     * Needed to message someone outside the 24-hour service window.
     *
     * @param string $to  the recipient's WhatsApp number, with or without the leading +
     * @param array $template  a messaging_templates row (provider_ref = the Meta template name, language)
     * @param array $vars  ordered values for the template's {{1}}, {{2}}, ...
     */
    public function sendTemplate(string $to, array $template, array $vars): string
    {
        $phoneId = $this->channel['credentials']['phone_number_id'] ?? '';
        $token   = $this->channel['credentials']['access_token'] ?? '';
        $name    = $template['provider_ref'] ?? '';
        $lang    = $template['language'] ?? 'en';
        if ($phoneId === '' || $token === '') {
            throw new Exception('Meta channel is missing its phone number id or access token.');
        }
        if ($name === '') {
            throw new Exception('This template has no Meta template name set.');
        }

        $templatePayload = [
            'name'     => $name,
            'language' => ['code' => $lang],
        ];
        if (!empty($vars)) {
            $templatePayload['components'] = [[
                'type'       => 'body',
                'parameters' => array_map(function ($v) {
                    return ['type' => 'text', 'text' => (string) $v];
                }, array_values($vars)),
            ]];
        }

        $version = $this->channel['credentials']['graph_version'] ?? self::GRAPH_VERSION;
        $url = 'https://graph.facebook.com/' . $version . "/$phoneId/messages";
        $payload = json_encode([
            'messaging_product' => 'whatsapp',
            'to'                => ltrim($this->ensurePlus($to), '+'),
            'type'              => 'template',
            'template'          => $templatePayload,
        ]);

        [$code, $resp] = $this->httpRequest($url, [
            'method'  => 'POST',
            'headers' => ['Content-Type: application/json', "Authorization: Bearer $token"],
            'body'    => $payload,
        ]);
        $json = json_decode($resp, true);
        if ($code < 200 || $code >= 300) {
            throw new Exception('Meta rejected the template: ' . ($json['error']['message'] ?? ('HTTP ' . $code)));
        }
        return $json['messages'][0]['id'] ?? '';
    }

    /**
     * Downloads an inbound media item: resolves its id to a URL, then fetches the bytes
     * with the access token.
     *
     * @param array $item  one entry from parseInbound()'s 'media' list (id, content_type, filename)
     */
    public function downloadMedia(array $item): array
    {
        $token = $this->channel['credentials']['access_token'] ?? '';
        $id    = $item['id'] ?? '';
        if ($token === '') {
            throw new Exception('Meta channel is missing its access token.');
        }
        if ($id === '') {
            throw new Exception('Media item has no id.');
        }
        $version = $this->channel['credentials']['graph_version'] ?? self::GRAPH_VERSION;

        // Step 1: resolve the media id to a (short-lived, auth-gated) download URL.
        [$c1, $r1] = $this->httpRequest("https://graph.facebook.com/$version/$id", [
            'method'  => 'GET',
            'headers' => ["Authorization: Bearer $token"],
        ]);
        $meta = json_decode($r1, true);
        if ($c1 < 200 || $c1 >= 300 || empty($meta['url'])) {
            throw new Exception('Meta media lookup failed: ' . ($meta['error']['message'] ?? ('HTTP ' . $c1)));
        }

        // Step 2: download the bytes (also requires the bearer token).
        [$c2, $body] = $this->httpRequest($meta['url'], [
            'method'  => 'GET',
            'headers' => ["Authorization: Bearer $token"],
            'follow'  => true,
        ]);
        if ($c2 < 200 || $c2 >= 300 || $body === '') {
            throw new Exception('Meta media download failed (HTTP ' . $c2 . ').');
        }

        $mime = $item['content_type'] ?: ($meta['mime_type'] ?? 'application/octet-stream');
        $filename = ($item['filename'] ?? '') !== '' ? $item['filename'] : ('media.' . messagingExtForMime($mime));
        return ['data' => $body, 'content_type' => $mime, 'filename' => $filename];
    }

    /**
     * Checks the phone number id and access token by reading the number from the Graph API,
     * sending nothing.
     */
    public function testConnection(): string
    {
        $phoneId = $this->channel['credentials']['phone_number_id'] ?? '';
        $token   = $this->channel['credentials']['access_token'] ?? '';
        if ($phoneId === '' || $token === '') {
            throw new Exception('Missing Phone number ID or Access token.');
        }

        // Read-only: fetch the phone number resource. Validates the token + id.
        $version = $this->channel['credentials']['graph_version'] ?? self::GRAPH_VERSION;
        $url = 'https://graph.facebook.com/' . $version . "/$phoneId?fields=display_phone_number,verified_name";
        [$code, $resp] = $this->httpRequest($url, [
            'method'  => 'GET',
            'headers' => ["Authorization: Bearer $token"],
        ]);
        $json = json_decode($resp, true);

        if ($code < 200 || $code >= 300) {
            throw new Exception($json['error']['message'] ?? ('Meta returned HTTP ' . $code));
        }

        $name   = $json['verified_name'] ?? '';
        $number = $json['display_phone_number'] ?? $phoneId;
        return 'Connected to Meta WhatsApp number ' . ($name !== '' ? "\"$name\" ($number)." : "$number.");
    }

    /**
     * Puts a single leading + on a phone number; blank stays blank.
     *
     * @param string $number  a phone number, with or without a leading +
     */
    private function ensurePlus(string $number): string
    {
        $number = ltrim(trim($number), '+');
        return $number === '' ? '' : '+' . $number;
    }
}
