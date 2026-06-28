<?php
/**
 * Shared Microsoft Graph helpers for target mailboxes.
 *
 * Used by api/tickets/check_mailbox_email.php, send_email.php and
 * verify_mailbox_folder.php to support the two authentication modes:
 *
 *   - delegated : OAuth sign-in; the token is a user's, so calls go to /me.
 *   - app_only  : client-credentials; the app authenticates itself and reads the
 *                 specific /users/<target_mailbox>.
 *
 * Delegated token refresh stays in each caller (legacy, unchanged). This file only
 * adds the app-only token + the per-request Graph base path.
 *
 * Requires config.php (for SSL_VERIFY_PEER) to be loaded already. $mailbox must be
 * the DECRYPTED row (azure_* fields in clear text).
 */

if (!function_exists('mailboxAppOnlyToken')) {

    /**
     * App-only access token via the client-credentials flow (no user, no interactive
     * sign-in). Cached in target_mailboxes.token_data until it expires (app-only tokens
     * carry no refresh token — we just re-fetch).
     */
    function mailboxAppOnlyToken($conn, $mailbox) {
        $cached = json_decode(preg_replace('/[\x00-\x1F\x7F]/', '', (string) ($mailbox['token_data'] ?? '')), true);
        if (is_array($cached) && !empty($cached['app_only']) && !empty($cached['access_token'])
            && isset($cached['expires_at']) && $cached['expires_at'] > time() + 300) {
            return $cached['access_token'];
        }

        $tokenUrl = 'https://login.microsoftonline.com/' . $mailbox['azure_tenant_id'] . '/oauth2/v2.0/token';
        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id'     => $mailbox['azure_client_id'],
            'client_secret' => $mailbox['azure_client_secret'],
            'grant_type'    => 'client_credentials',
            'scope'         => 'https://graph.microsoft.com/.default',
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, SSL_VERIFY_PEER);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, SSL_VERIFY_PEER ? 2 : 0);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) { $err = curl_error($ch); curl_close($ch); throw new Exception('cURL error: ' . $err); }
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('client-credentials token request failed (HTTP ' . $httpCode . '): ' . $response);
        }
        $data = json_decode($response, true);
        if (empty($data['access_token'])) {
            throw new Exception('No access_token in client-credentials response: ' . $response);
        }

        $cacheJson = json_encode([
            'app_only'     => true,
            'access_token' => $data['access_token'],
            'expires_at'   => time() + ($data['expires_in'] ?? 3600),
            'created_at'   => time(),
        ]);
        $conn->prepare("UPDATE target_mailboxes SET token_data = ? WHERE id = ?")
             ->execute([$cacheJson, $mailbox['id']]);

        return $data['access_token'];
    }

    /**
     * Pull the signed-in user's address straight out of a Graph access token. The
     * token is a JWT whose payload carries the user's UPN / email — so we can read
     * it offline, with NO Graph permission. This matters because the usual mailbox
     * scopes (Mail.*) do NOT include User.Read, so a /me call 403s and can't tell us
     * who signed in. Returns a lowercased email, or '' if the token has no usable claim.
     */
    function mailboxIdentityFromToken($jwt) {
        $parts = explode('.', (string) $jwt);
        if (count($parts) < 2) return '';
        $payload = strtr($parts[1], '-_', '+/');
        $pad = strlen($payload) % 4;
        if ($pad) $payload .= str_repeat('=', 4 - $pad);
        $json = base64_decode($payload, true);
        if ($json === false) return '';
        $claims = json_decode($json, true);
        if (!is_array($claims)) return '';
        // upn / preferred_username are the email-like identity claims; unique_name/email are fallbacks.
        $email = $claims['upn'] ?? $claims['preferred_username'] ?? $claims['email'] ?? $claims['unique_name'] ?? '';
        return ($email && strpos($email, '@') !== false) ? strtolower(trim($email)) : '';
    }

    /**
     * Who does a (delegated) access token belong to? Returns the lowercased email,
     * or '' if it can't be determined. Used to back-fill authenticated_as for
     * mailboxes that signed in before we started recording it.
     *
     * Reads the token's own JWT claims first (offline, no permission needed); only
     * falls back to Graph /me if the token isn't a readable JWT (needs User.Read).
     */
    function mailboxDelegatedIdentity($accessToken) {
        $fromToken = mailboxIdentityFromToken($accessToken);
        if ($fromToken !== '') return $fromToken;

        $ch = curl_init('https://graph.microsoft.com/v1.0/me?$select=mail,userPrincipalName');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, SSL_VERIFY_PEER);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, SSL_VERIFY_PEER ? 2 : 0);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode !== 200) return '';
        $data = json_decode($response, true);
        $email = $data['mail'] ?? $data['userPrincipalName'] ?? '';
        return $email ? strtolower(trim($email)) : '';
    }

    /**
     * Per-request Graph base path: '/me' (delegated) or '/users/<addr>' (app-only).
     * Set once after the mailbox is loaded; the Graph helpers read it back.
     */
    function mailboxGraphBase($set = null) {
        static $base = '/me';
        if ($set !== null) $base = $set;
        return $base;
    }

    /**
     * Resolve the base path for a mailbox from its auth_mode (Microsoft only).
     * Google mailboxes always behave as delegated.
     */
    function mailboxResolveGraphBase($mailbox) {
        $isAppOnly = (($mailbox['provider'] ?? 'microsoft') === 'microsoft')
                  && (($mailbox['auth_mode'] ?? 'delegated') === 'app_only');
        return mailboxGraphBase($isAppOnly
            ? '/users/' . rawurlencode(trim((string) $mailbox['target_mailbox']))
            : '/me');
    }
}
