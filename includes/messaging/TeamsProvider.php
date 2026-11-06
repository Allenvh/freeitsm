<?php
/**
 * TeamsProvider — Microsoft Teams one-to-one chats with the FreeITSM bot, via
 * the Bot Framework. Contributed by turbay-a in PR #166; see the Teams and
 * Mattermost developer guide for what changed at merge.
 *
 * Credentials JSON (messaging_channels.credentials, encrypted at rest):
 *   { "app_id": "<Azure Bot App ID>", "app_secret": "...", "tenant_id": "<AAD tenant>" }
 * The tenant is required for a single-tenant app registration: token requests
 * to the wrong tenant fail with AADSTS700016.
 *
 * ── INBOUND ──────────────────────────────────────────────────────────────────
 * Bot Framework POSTs an Activity with `Authorization: Bearer <JWT>`.
 * verifyWebhook() checks, in this order:
 *   1. the RS256 signature, against Bot Framework's published keys - with the
 *      vendored firebase/php-jwt, the same library SSO uses (includes/oidc.php),
 *      not a second hand-written RSA/DER decoder;
 *   2. issuer, audience (this channel's App ID) and expiry (5 min leeway);
 *   3. the token's `serviceurl` claim equals the activity's serviceUrl.
 *      TRAP: never trust a serviceUrl the token does not vouch for. Replies are
 *      POSTed to it WITH THE BOT'S ACCESS TOKEN, so an activity that could name
 *      its own serviceUrl could collect that token. Bot Framework requires
 *      this check; the branch first skipped it;
 *   4. the serviceUrl is a Microsoft Bot Framework host (belt and braces for 3);
 *   5. the activity comes from THIS channel's Microsoft 365 tenant, so a person
 *      in another organisation cannot open tickets by finding the bot.
 *
 * ── THE REPLY ADDRESS ────────────────────────────────────────────────────────
 * The serviceUrl is PER CONVERSATION (Teams routes by the user's region), so it
 * travels with the conversation, not the channel: parseInbound() sets
 *   to = "<serviceUrl>|<conversationId>"
 * ingest stores that as the row's to_recipients and messagingReplyAddress()
 * hands it back to sendMessage().
 *
 * TRAP: never store the serviceUrl on the channel. The branch first kept the
 *   latest one in messaging_channels.channel_ref, which answers one user through
 *   another user's region.
 *
 * Outbound: POST {serviceUrl}v3/conversations/{conversationId}/activities with
 * an Azure AD client-credentials token for the Bot Framework scope.
 *
 * Limits: one-to-one chats only (group chats and channels are ignored, as
 * Telegram groups are); images only for analyst attachments, because Teams
 * needs a public https URL for the file (media.php); no read receipts.
 */

require_once __DIR__ . '/MessagingProvider.php';
require_once __DIR__ . '/../vendor/firebase-jwt/src/JWT.php';
require_once __DIR__ . '/../vendor/firebase-jwt/src/JWK.php';
require_once __DIR__ . '/../vendor/firebase-jwt/src/Key.php';
require_once __DIR__ . '/../vendor/firebase-jwt/src/JWTExceptionWithPayloadInterface.php';
require_once __DIR__ . '/../vendor/firebase-jwt/src/BeforeValidException.php';
require_once __DIR__ . '/../vendor/firebase-jwt/src/ExpiredException.php';
require_once __DIR__ . '/../vendor/firebase-jwt/src/SignatureInvalidException.php';

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

class TeamsProvider extends MessagingProvider
{
    private const BOT_OPENID = 'https://login.botframework.com/v1/.well-known/openidconfiguration';
    private const BOT_ISSUER = 'https://api.botframework.com';
    private const DEFAULT_SERVICE_URL = 'https://smba.trafficmanager.net/teams/';
    private const JWKS_CACHE_SECONDS = 86400;

    /**
     * Hosts Bot Framework answers on (public cloud and the US government clouds),
     * and where Teams serves the images a customer sends. A serviceUrl or an
     * attachment URL outside these never receives the bot's token.
     */
    private const SERVICE_HOSTS = ['smba.trafficmanager.net', '.botframework.com', '.teams.microsoft.com',
                                   '.botframework.us', '.botframework.azure.us', '.teams.microsoft.us'];
    private const MEDIA_HOSTS   = ['smba.trafficmanager.net', '.botframework.com', '.teams.microsoft.com',
                                   '.asm.skype.com', '.botframework.us', '.teams.microsoft.us'];

    /** Token cache for this request only — never written to disk. */
    private static $tokenCache = [];

    /** Test hook: a key set to verify against instead of Bot Framework's (tests/messaging-teams-mattermost.php). */
    public static $testKeys = null;

    /**
     * Checks an inbound Bot Framework activity: the bearer JWT's signature, issuer and
     * audience (the app id), and that its service URL is an allowed Microsoft host matching
     * the token.
     *
     * @param string $rawBody  the raw JSON activity, read for serviceUrl and the sender's tenant
     * @param array $headers  the request headers, lower-cased keys - 'authorization' carries the Bot Framework JWT
     * @param array $params  the parsed POST parameters (not used)
     * @param string $url  the public URL that was hit (not used)
     */
    public function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
    {
        $appId = (string)($this->channel['credentials']['app_id'] ?? '');
        $auth  = (string)($headers['authorization'] ?? '');
        if ($appId === '' || stripos($auth, 'Bearer ') !== 0) {
            return false;
        }
        $keys = self::$testKeys ?? $this->botFrameworkKeys();
        if (!$keys) {
            return false;
        }
        $leeway = JWT::$leeway;
        JWT::$leeway = 300;
        try {
            $claims = (array)JWT::decode(trim(substr($auth, 7)), JWK::parseKeySet(['keys' => $keys], 'RS256'));
        } catch (Throwable) {
            return false;   // bad signature, unknown key, expired, not yet valid
        } finally {
            JWT::$leeway = $leeway;
        }
        if (($claims['iss'] ?? '') !== self::BOT_ISSUER || ($claims['aud'] ?? '') !== $appId) {
            return false;
        }

        $activity   = json_decode($rawBody, true);
        $serviceUrl = is_array($activity) ? (string)($activity['serviceUrl'] ?? '') : '';
        if ($serviceUrl === '' || !self::hostAllowed($serviceUrl, self::SERVICE_HOSTS)) {
            return false;
        }
        if (rtrim((string)($claims['serviceurl'] ?? ''), '/') !== rtrim($serviceUrl, '/')) {
            return false;
        }
        $tenant = strtolower((string)($this->channel['credentials']['tenant_id'] ?? ''));
        $from   = strtolower((string)($activity['conversation']['tenantId'] ?? ($activity['channelData']['tenant']['id'] ?? '')));
        return $tenant !== '' && $from === $tenant;
    }

    /**
     * An https URL whose host is one of $hosts (exact, or a suffix starting with a dot).
     *
     * @param string $url  the URL to check; only https passes
     * @param array $hosts  allowed hosts - an exact name, or a suffix starting with a dot
     */
    private static function hostAllowed(string $url, array $hosts): bool
    {
        $p = parse_url($url);
        if (($p['scheme'] ?? '') !== 'https' || empty($p['host'])) {
            return false;
        }
        $host = strtolower($p['host']);
        foreach ($hosts as $h) {
            if ($h[0] === '.' ? substr($host, -strlen($h)) === $h : $host === $h) {
                return true;
            }
        }
        return false;
    }

    /**
     * "<serviceUrl>|<conversationId>" -> [serviceUrl, conversationId]. A bare id uses the default region.
     *
     * @param string $to  the reply address "<serviceUrl>|<conversationId>", or a bare conversation id
     */
    private static function splitAddress(string $to): array
    {
        $bar = strrpos($to, '|');
        if ($bar === false) {
            return [self::DEFAULT_SERVICE_URL, $to];
        }
        return [substr($to, 0, $bar), substr($to, $bar + 1)];
    }

    /**
     * Turns a Teams activity into inbound messages - a CSAT rating press or a message in a
     * one-to-one chat.
     *
     * Group chats and channels are ignored, since one conversation there is shared by many
     * people.
     *
     * @param string $rawBody  the raw JSON Bot Framework activity
     * @param array $params  the parsed POST parameters (not used)
     */
    public function parseInbound(string $rawBody, array $params): array
    {
        $a = json_decode($rawBody, true);
        if (!is_array($a) || ($a['type'] ?? '') !== 'message') {
            return [];
        }
        // One-to-one chats only. A group chat or channel has one conversation shared
        // by many people, so it would merge them into one requester.
        $convType = (string)($a['conversation']['conversationType'] ?? 'personal');
        if ($convType !== 'personal') {
            return [];
        }
        $convId     = (string)($a['conversation']['id'] ?? '');
        $activityId = (string)($a['id'] ?? '');
        $serviceUrl = (string)($a['serviceUrl'] ?? '');
        if ($convId === '' || $activityId === '' || !self::hostAllowed($serviceUrl, self::SERVICE_HOSTS)) {
            return [];
        }

        // A press on a CSAT rating button (see sendRatingRequest()). Only ours are read.
        $csatValue = $a['value']['csat'] ?? null;
        if (is_array($csatValue) && isset($csatValue['r'], $csatValue['v'])) {
            $r = (int)$csatValue['r'];
            $v = (int)$csatValue['v'];
            if ($r > 0 && $v >= 1 && $v <= 5) {
                return [[
                    'from'            => $convId,
                    'to'              => $serviceUrl . '|' . $convId,
                    'body'            => '',
                    'profile_name'    => trim((string)($a['from']['name'] ?? '')),
                    'provider_msg_id' => 'teams:' . $activityId,
                    'media'           => [],
                    'timestamp'       => null,
                    'language_code'   => '',
                    'csat'            => ['response_id' => $r, 'rating' => $v, 'callback_id' => ''],
                ]];
            }
        }

        // Adaptive card button presses arrive with empty text and their payload in value.
        $text = (string)($a['text'] ?? '');
        if (is_array($a['value'] ?? null) && isset($a['value']['text'])) {
            $text = (string)$a['value']['text'];
        }
        $text = trim(html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        $media = [];
        foreach (($a['attachments'] ?? []) as $att) {
            $type = (string)($att['contentType'] ?? '');
            $url  = (string)($att['contentUrl'] ?? '');
            if ($url !== '' && str_starts_with($type, 'image/')) {
                $media[] = ['url' => $url, 'content_type' => $type, 'filename' => (string)($att['name'] ?? '')];
            }
        }

        return [[
            'from'            => $convId,
            'to'              => $serviceUrl . '|' . $convId,   // the reply address - see the file header
            'sender_id'       => (string)($a['from']['id'] ?? ''),   // for lookupUser(): who, not which chat
            'body'            => $text,
            'profile_name'    => trim((string)($a['from']['name'] ?? '')),
            'provider_msg_id' => 'teams:' . $activityId,
            'media'           => $media,
            'timestamp'       => isset($a['timestamp']) ? (int)strtotime((string)$a['timestamp']) : null,
            'language_code'   => '',
        ]];
    }

    /**
     * The rating question as an Adaptive Card with five buttons, 1 to 5. A press
     * comes back as a message whose value carries csat.r (response id) and csat.v (rating).
     *
     * @param string $to  the reply address "<serviceUrl>|<conversationId>"
     * @param string $text  the rating question
     * @param int $responseId  the ticket_csat_responses id each button carries back
     */
    public function sendRatingRequest(string $to, string $text, int $responseId): string
    {
        $actions = [];
        for ($n = 1; $n <= 5; $n++) {
            $actions[] = [
                'type'  => 'Action.Submit',
                'title' => (string)$n,
                'data'  => ['csat' => ['r' => $responseId, 'v' => $n]],
            ];
        }
        return $this->postActivity($to, [
            'type'        => 'message',
            'attachments' => [[
                'contentType' => 'application/vnd.microsoft.card.adaptive',
                'content'     => [
                    '$schema' => 'http://adaptivecards.io/schemas/adaptive-card.json',
                    'type'    => 'AdaptiveCard',
                    'version' => '1.4',
                    'body'    => [['type' => 'TextBlock', 'text' => $text, 'wrap' => true]],
                    'actions' => $actions,
                ],
            ]],
        ]);
    }

    /**
     * Sends a text reply into the Teams conversation and returns the activity id.
     *
     * @param string $to  the reply address "<serviceUrl>|<conversationId>"; '' throws
     * @param string $body  the reply text
     */
    public function sendMessage(string $to, string $body): string
    {
        if ($to === '') {
            throw new Exception('No Teams conversation to send to.');
        }
        return $this->postActivity($to, ['type' => 'message', 'text' => $body]);
    }

    /**
     * Teams shows an image sent as an attachment with contentUrl. That URL must be
     * a public https address — messagingOutboundMediaUrl() provides one, which is
     * why images are the only kind this sends. Other files are refused with a
     * plain message, not sent as a broken attachment.
     *
     * @param string $to  the reply address "<serviceUrl>|<conversationId>"
     * @param string $filePath  the file on this server's disk; only its name is used
     * @param string $mimeType  the file's MIME type - anything but image/* is refused
     * @param string $caption  text sent with the image; '' for none
     * @param string $publicUrl  a public https URL to the image - required, Teams fetches it from there
     * @param string $filename  the name the recipient sees; '' uses the stored name
     */
    public function sendMedia(string $to, string $filePath, string $mimeType, string $caption = '', string $publicUrl = '', string $filename = ''): string
    {
        if (!str_starts_with($mimeType, 'image/')) {
            throw new Exception('Teams can only receive images sent from FreeITSM for now.');
        }
        if ($publicUrl === '' || !str_starts_with($publicUrl, 'https://')) {
            throw new Exception('Teams needs a public https address for the image, and this install does not have one configured.');
        }
        return $this->postActivity($to, [
            'type'        => 'message',
            'text'        => $caption,
            'attachments' => [[
                'contentType' => $mimeType,
                'contentUrl'  => $publicUrl,
                'name'        => $this->outboundFilename($filePath, $filename),
            ]],
        ]);
    }

    /**
     * Inbound images: fetch with the bot's token — Teams does not serve them anonymously.
     *
     * @param array $item  one entry from parseInbound()'s 'media' list (url, content_type, filename)
     */
    public function downloadMedia(array $item): array
    {
        $url = (string)($item['url'] ?? '');
        if ($url === '') {
            throw new Exception('Media item has no URL.');
        }
        // The bot's token goes with this request, so only to a Microsoft host,
        // and never on to wherever a redirect points (the SSO review's rule).
        if (!self::hostAllowed($url, self::MEDIA_HOSTS)) {
            throw new Exception('Teams image is not on a Microsoft host; not fetched.');
        }
        [$code, $body] = $this->httpRequest($url, [
            'method'  => 'GET',
            'headers' => ['Authorization: Bearer ' . $this->accessToken()],
        ]);
        if ($code < 200 || $code >= 300 || $body === '') {
            throw new Exception('Teams media download failed (HTTP ' . $code . ').');
        }
        $mime = (string)($item['content_type'] ?: 'application/octet-stream');
        $name = (string)($item['filename'] ?? '') !== '' ? $item['filename'] : ('image.' . messagingExtForMime($mime));
        return ['data' => $body, 'content_type' => $mime, 'filename' => $name];
    }

    /** Checks the bot credentials by getting an access token, and returns the app id. */
    public function testConnection(): string
    {
        $this->accessToken(); // throws with a readable message if the credentials are wrong
        return 'Connected to Microsoft Teams bot app ' . $this->appId() . '.';
    }

    // ── internals ──────────────────────────────────────────────────────────────

    /** The channel's Microsoft app (bot) id. */
    private function appId(): string
    {
        return (string)($this->channel['credentials']['app_id'] ?? '');
    }


    /** Azure AD client-credentials token for the Bot Framework scope. */
    private function accessToken(): string
    {
        $appId  = $this->appId();
        $secret = (string)($this->channel['credentials']['app_secret'] ?? '');
        $tenant = (string)($this->channel['credentials']['tenant_id'] ?? '');
        if ($appId === '' || $secret === '' || $tenant === '') {
            throw new Exception('Teams channel is missing its App ID, App secret or Tenant ID.');
        }
        $key = $appId . '|' . $tenant;
        if (isset(self::$tokenCache[$key])) {
            return self::$tokenCache[$key];
        }
        [$code, $resp] = $this->httpRequest('https://login.microsoftonline.com/' . rawurlencode($tenant) . '/oauth2/v2.0/token', [
            'method'  => 'POST',
            'headers' => ['Content-Type: application/x-www-form-urlencoded'],
            'body'    => http_build_query([
                'grant_type'    => 'client_credentials',
                'client_id'     => $appId,
                'client_secret' => $secret,
                'scope'         => 'https://api.botframework.com/.default',
            ]),
        ]);
        $json = json_decode($resp, true);
        if ($code < 200 || $code >= 300 || empty($json['access_token'])) {
            throw new Exception('Microsoft rejected the Teams credentials: ' . ($json['error_description'] ?? ('HTTP ' . $code)));
        }
        return self::$tokenCache[$key] = (string)$json['access_token'];
    }

    /**
     * Who a Teams user is: ['name' => …, 'email' => …], either may be ''.
     * Bot Framework's "get conversation member" - the bot's own token, no
     * Microsoft Graph permission. Used to file their tickets under the person
     * FreeITSM already knows (messagingResolveRequesterByEmail()). Never throws.
     *
     * The email comes from the organisation's own Microsoft 365 directory, and
     * verifyWebhook() only lets in people from the channel's own tenant, so it
     * is an address the organisation issued. `email` first; the sign-in name
     * (userPrincipalName) only when it is shaped like an address, which in most
     * tenants it is the same thing.
     *
     * @param string $to  the reply address "<serviceUrl>|<conversationId>" of the chat the person is in
     * @param string $memberId  the sender's Teams id (the message's sender_id)
     */
    public function lookupUser(string $to, string $memberId): array
    {
        try {
            [$serviceUrl, $conversationId] = self::splitAddress($to);
            if ($memberId === '' || $conversationId === '' || !self::hostAllowed($serviceUrl, self::SERVICE_HOSTS)) {
                return ['name' => '', 'email' => ''];
            }
            $url = rtrim($serviceUrl, '/') . '/v3/conversations/' . rawurlencode($conversationId)
                 . '/members/' . rawurlencode($memberId);
            [$code, $resp] = $this->httpRequest($url, [
                'method'  => 'GET',
                'headers' => ['Authorization: Bearer ' . $this->accessToken()],
            ]);
            $m = json_decode((string)$resp, true);
            if ($code < 200 || $code >= 300 || !is_array($m)) {
                throw new Exception('HTTP ' . $code);
            }
            $email = trim((string)($m['email'] ?? ''));
            if ($email === '' && filter_var((string)($m['userPrincipalName'] ?? ''), FILTER_VALIDATE_EMAIL)) {
                $email = (string)$m['userPrincipalName'];
            }
            return ['name' => trim((string)($m['name'] ?? '')), 'email' => $email];
        } catch (Throwable $e) {
            error_log('Teams member lookup failed for ' . $memberId . ': ' . $e->getMessage());
            return ['name' => '', 'email' => ''];
        }
    }

    /**
     * Posts an activity to a conversation through its Bot Framework service URL and returns
     * the new activity id.
     *
     * Throws when the address is incomplete, the host is not allowed, or Teams rejects the
     * message.
     *
     * @param string $to  the reply address "<serviceUrl>|<conversationId>"
     * @param array $activity  the Bot Framework activity to post, e.g. ['type' => 'message', 'text' => ...]
     */
    private function postActivity(string $to, array $activity): string
    {
        [$serviceUrl, $conversationId] = self::splitAddress($to);
        if ($conversationId === '' || !self::hostAllowed($serviceUrl, self::SERVICE_HOSTS)) {
            throw new Exception('No Teams conversation to send to.');
        }
        $url = rtrim($serviceUrl, '/') . '/v3/conversations/' . rawurlencode($conversationId) . '/activities';
        [$code, $resp] = $this->httpRequest($url, [
            'method'  => 'POST',
            'headers' => ['Content-Type: application/json', 'Authorization: Bearer ' . $this->accessToken()],
            'body'    => json_encode($activity),
        ]);
        $json = json_decode($resp, true);
        if ($code < 200 || $code >= 300) {
            throw new Exception('Teams rejected the message: ' . ($json['error']['message'] ?? ('HTTP ' . $code)));
        }
        return (string)($json['id'] ?? '');
    }

    /**
     * Bot Framework's published signing keys, cached for a day. The key set is
     * public and rotates rarely, so a cache file holds nothing secret.
     */
    private function botFrameworkKeys(): array
    {
        $cache = sys_get_temp_dir() . '/freeitsm_botfw_jwks.json';
        if (is_file($cache) && (time() - filemtime($cache)) < self::JWKS_CACHE_SECONDS) {
            $cached = json_decode((string)file_get_contents($cache), true);
            if (is_array($cached)) {
                return $cached;
            }
        }
        [$c1, $r1] = $this->httpRequest(self::BOT_OPENID, ['method' => 'GET']);
        $cfg = json_decode((string)$r1, true);
        $jwks = (string)($cfg['jwks_uri'] ?? '');
        // Only follow the key location Microsoft publishes, never one an attacker names.
        if ($c1 !== 200 || !str_starts_with($jwks, 'https://login.botframework.com/')) {
            return [];
        }
        [$c2, $r2] = $this->httpRequest($jwks, ['method' => 'GET']);
        $set = json_decode($r2, true);
        $keys = ($c2 === 200 && is_array($set['keys'] ?? null)) ? $set['keys'] : [];
        if ($keys) {
            @file_put_contents($cache, json_encode($keys));
        }
        return $keys;
    }

}
