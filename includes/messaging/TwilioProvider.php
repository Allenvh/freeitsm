<?php
/**
 * TwilioProvider — WhatsApp via Twilio's Messaging API.
 *
 * This is the recommended first-build provider because Twilio offers a WhatsApp
 * **sandbox**: you can send/receive end-to-end on a dev box (WAMP + ngrok) with
 * no Meta business verification. The production path is identical once your own
 * WhatsApp sender is approved.
 *
 * Credentials JSON (messaging_channels.credentials, encrypted at rest):
 *   { "account_sid": "ACxxxx", "auth_token": "xxxx" }
 * The business WhatsApp number is messaging_channels.phone_number (the "From").
 *
 * Inbound: Twilio POSTs application/x-www-form-urlencoded with From/To/Body/
 * MessageSid/ProfileName/NumMedia/MediaUrl{n}/MediaContentType{n}. Authenticity is
 * the X-Twilio-Signature header: base64(HMAC-SHA1(authToken, url + sorted POST kv)).
 */

require_once __DIR__ . '/MessagingProvider.php';

class TwilioProvider extends MessagingProvider
{
    /**
     * Checks the X-Twilio-Signature header: an HMAC-SHA1 of the URL and the sorted POST
     * fields, keyed with the auth token.
     *
     * @param string $rawBody  the raw request body (not used - Twilio signs the parsed fields)
     * @param array $headers  the request headers, lower-cased keys - x-twilio-signature is checked
     * @param array $params  the parsed POST fields, all part of the signed string
     * @param string $url  the full public URL Twilio called, exactly as configured - it is part of the signed string
     */
    public function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
    {
        $signature = $headers['x-twilio-signature'] ?? '';
        $authToken = $this->channel['credentials']['auth_token'] ?? '';
        if ($signature === '' || $authToken === '') {
            return false;
        }
        // Twilio's scheme: the full URL, then every POST param appended as key+value
        // in alphabetical key order, HMAC-SHA1'd with the auth token, base64-encoded.
        $data = $url;
        ksort($params);
        foreach ($params as $key => $value) {
            $data .= $key . $value;
        }
        $expected = base64_encode(hash_hmac('sha1', $data, $authToken, true));
        return hash_equals($expected, $signature);
    }

    /**
     * Turns a Twilio WhatsApp webhook into an inbound message with its media URLs; status
     * callbacks give nothing.
     *
     * @param string $rawBody  the raw request body (not used)
     * @param array $params  the parsed POST fields (From, To, Body, MessageSid, ProfileName, NumMedia, MediaUrl{n}, ...)
     */
    public function parseInbound(string $rawBody, array $params): array
    {
        // A status callback (delivered/read) has MessageStatus but no Body/From pair
        // we care about — skip anything without a sender + a message id.
        $from = $this->stripPrefix($params['From'] ?? '');
        $msgId = $params['MessageSid'] ?? ($params['SmsSid'] ?? '');
        if ($from === '' || $msgId === '') {
            return [];
        }

        $media = [];
        $numMedia = (int) ($params['NumMedia'] ?? 0);
        for ($i = 0; $i < $numMedia; $i++) {
            if (!empty($params["MediaUrl$i"])) {
                $media[] = [
                    'url'          => $params["MediaUrl$i"],
                    'content_type' => $params["MediaContentType$i"] ?? 'application/octet-stream',
                ];
            }
        }

        return [[
            'from'            => $from,
            'to'              => $this->stripPrefix($params['To'] ?? ''),
            'body'            => trim($params['Body'] ?? ''),
            'profile_name'    => trim($params['ProfileName'] ?? ''),
            'provider_msg_id' => $msgId,
            'media'           => $media,
            'timestamp'       => null,
        ]];
    }

    /**
     * Sends a WhatsApp text message through Twilio and returns the message SID.
     *
     * @param string $to  the recipient's WhatsApp number, with or without the leading +
     * @param string $body  the message text
     */
    public function sendMessage(string $to, string $body): string
    {
        $sid   = $this->channel['credentials']['account_sid'] ?? '';
        $token = $this->channel['credentials']['auth_token'] ?? '';
        $from  = $this->channel['phone_number'] ?? '';
        if ($sid === '' || $token === '' || $from === '') {
            throw new Exception('Twilio channel is missing its Account SID, Auth Token or From number.');
        }

        $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json";
        $post = http_build_query([
            'From' => 'whatsapp:' . $this->ensurePlus($from),
            'To'   => 'whatsapp:' . $this->ensurePlus($to),
            'Body' => $body,
        ]);

        [$code, $resp] = $this->httpRequest($url, [
            'method'  => 'POST',
            'headers' => ['Content-Type: application/x-www-form-urlencoded'],
            'body'    => $post,
            'auth'    => "$sid:$token",
        ]);

        $json = json_decode($resp, true);
        if ($code < 200 || $code >= 300) {
            $msg = $json['message'] ?? ('HTTP ' . $code);
            throw new Exception('Twilio rejected the message: ' . $msg);
        }
        return $json['sid'] ?? '';
    }

    /**
     * Twilio's Messages API takes no upload at all — it fetches the media
     * itself from a URL you give it (MediaUrl), fire-and-forget, sometime
     * after this call returns. $publicUrl (messagingOutboundMediaUrl()) is
     * therefore REQUIRED here, unlike Meta/Telegram which take the bytes
     * directly; $filePath is unused.
     *
     * @param string $to  the recipient's WhatsApp number, with or without the leading +
     * @param string $filePath  not used - Twilio fetches the file from $publicUrl
     * @param string $mimeType  the file's MIME type (not sent - Twilio reads it from the download)
     * @param string $caption  text sent with the file; '' for none
     * @param string $publicUrl  a public URL Twilio can fetch the file from - required
     * @param string $filename  not used - Twilio names the file itself
     */
    public function sendMedia(string $to, string $filePath, string $mimeType, string $caption = '', string $publicUrl = '', string $filename = ''): string
    {
        if ($publicUrl === '') {
            throw new Exception('Twilio needs a public URL to fetch the attachment from, and none was given.');
        }
        $sid   = $this->channel['credentials']['account_sid'] ?? '';
        $token = $this->channel['credentials']['auth_token'] ?? '';
        $from  = $this->channel['phone_number'] ?? '';
        if ($sid === '' || $token === '' || $from === '') {
            throw new Exception('Twilio channel is missing its Account SID, Auth Token or From number.');
        }

        $fields = [
            'From'     => 'whatsapp:' . $this->ensurePlus($from),
            'To'       => 'whatsapp:' . $this->ensurePlus($to),
            'MediaUrl' => $publicUrl,
        ];
        if ($caption !== '') {
            $fields['Body'] = $caption;
        }

        [$code, $resp] = $this->httpRequest(
            "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json",
            [
                'method'  => 'POST',
                'headers' => ['Content-Type: application/x-www-form-urlencoded'],
                'body'    => http_build_query($fields),
                'auth'    => "$sid:$token",
            ]
        );

        $json = json_decode($resp, true);
        if ($code < 200 || $code >= 300) {
            throw new Exception('Twilio rejected the attachment: ' . ($json['message'] ?? ('HTTP ' . $code)));
        }
        return $json['sid'] ?? '';
    }

    /**
     * Sends an approved WhatsApp template by its Content SID, with $vars as numbered
     * content variables, and returns the message SID.
     *
     * @param string $to  the recipient's WhatsApp number, with or without the leading +
     * @param array $template  a messaging_templates row - provider_ref is the Twilio Content SID
     * @param array $vars  ordered values for the template's {{1}}, {{2}}, ...
     */
    public function sendTemplate(string $to, array $template, array $vars): string
    {
        $sid   = $this->channel['credentials']['account_sid'] ?? '';
        $token = $this->channel['credentials']['auth_token'] ?? '';
        $from  = $this->channel['phone_number'] ?? '';
        $contentSid = $template['provider_ref'] ?? '';
        if ($sid === '' || $token === '' || $from === '') {
            throw new Exception('Twilio channel is missing its Account SID, Auth Token or From number.');
        }
        if ($contentSid === '') {
            throw new Exception('This template has no Twilio Content SID set.');
        }

        // Twilio Content API: ContentVariables maps "1","2",… to the placeholder values.
        $contentVars = [];
        foreach (array_values($vars) as $i => $v) {
            $contentVars[(string) ($i + 1)] = $v;
        }

        $fields = [
            'From'       => 'whatsapp:' . $this->ensurePlus($from),
            'To'         => 'whatsapp:' . $this->ensurePlus($to),
            'ContentSid' => $contentSid,
        ];
        if (!empty($contentVars)) {
            $fields['ContentVariables'] = json_encode($contentVars);
        }

        [$code, $resp] = $this->httpRequest(
            "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json",
            [
                'method'  => 'POST',
                'headers' => ['Content-Type: application/x-www-form-urlencoded'],
                'body'    => http_build_query($fields),
                'auth'    => "$sid:$token",
            ]
        );
        $json = json_decode($resp, true);
        if ($code < 200 || $code >= 300) {
            throw new Exception('Twilio rejected the template: ' . ($json['message'] ?? ('HTTP ' . $code)));
        }
        return $json['sid'] ?? '';
    }

    /**
     * Downloads an inbound media item with account authentication, naming the file after its Media SID.
     *
     * @param array $item  one entry from parseInbound()'s 'media' list (url, content_type)
     */
    public function downloadMedia(array $item): array
    {
        $sid   = $this->channel['credentials']['account_sid'] ?? '';
        $token = $this->channel['credentials']['auth_token'] ?? '';
        $url   = $item['url'] ?? '';
        if ($url === '') {
            throw new Exception('Media item has no URL.');
        }

        // Twilio media URLs require account auth and 307-redirect to storage.
        [$code, $body] = $this->httpRequest($url, [
            'method' => 'GET',
            'auth'   => "$sid:$token",
            'follow' => true,
        ]);
        if ($code < 200 || $code >= 300 || $body === '') {
            throw new Exception('Twilio media download failed (HTTP ' . $code . ').');
        }

        $contentType = $item['content_type'] ?? 'application/octet-stream';
        $ext = messagingExtForMime($contentType);
        // Use the Media SID (last URL path segment) as the base name when present.
        $base = basename(parse_url($url, PHP_URL_PATH) ?: '') ?: 'media';
        $base = preg_replace('/\.[A-Za-z0-9]+$/', '', $base);
        return [
            'data'         => $body,
            'content_type' => $contentType,
            'filename'     => $base . '.' . $ext,
        ];
    }

    /**
     * Checks the Account SID and Auth Token by reading the account, sending nothing; notes
     * when no WhatsApp number is set.
     */
    public function testConnection(): string
    {
        $sid   = $this->channel['credentials']['account_sid'] ?? '';
        $token = $this->channel['credentials']['auth_token'] ?? '';
        if ($sid === '' || $token === '') {
            throw new Exception('Missing Account SID or Auth Token.');
        }

        // Read-only: fetch the account resource. Validates the SID + token without
        // sending anything.
        [$code, $resp] = $this->httpRequest(
            "https://api.twilio.com/2010-04-01/Accounts/$sid.json",
            ['method' => 'GET', 'auth' => "$sid:$token"]
        );
        $json = json_decode($resp, true);

        if ($code === 401) {
            throw new Exception('Authentication failed — check the Account SID and Auth Token.');
        }
        if ($code < 200 || $code >= 300) {
            throw new Exception($json['message'] ?? ('Twilio returned HTTP ' . $code));
        }

        $name   = $json['friendly_name'] ?? $sid;
        $status = $json['status'] ?? 'unknown';
        $from   = $this->channel['phone_number'] ?? '';
        $detail = "Connected to Twilio account \"$name\" (status: $status).";
        if ($from === '') {
            $detail .= ' Note: no WhatsApp number is set on this channel.';
        }
        return $detail;
    }

    /**
     * Drop Twilio's "whatsapp:" channel prefix, leaving the bare number.
     *
     * @param string $addr  a Twilio address, e.g. 'whatsapp:+447700900123'
     */
    private function stripPrefix(string $addr): string
    {
        $addr = trim($addr);
        if (stripos($addr, 'whatsapp:') === 0) {
            $addr = substr($addr, strlen('whatsapp:'));
        }
        return trim($addr);
    }

    /**
     * Puts a single leading + on a phone number.
     *
     * @param string $number  a phone number, with or without a leading +
     */
    private function ensurePlus(string $number): string
    {
        $number = ltrim(trim($number), '+');
        return '+' . $number;
    }
}
