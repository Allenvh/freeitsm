<?php
/**
 * The Slack app manifest for THIS install.
 *
 * FreeITSM does not publish a Slack app. There is no "Add to Slack" button and
 * no FreeITSM-hosted OAuth service, deliberately: publishing one would mean this
 * project ran a server that every customer's Slack traffic passed through,
 * forever, and became a data processor for other people's companies. Instead the
 * customer creates an app in their own workspace, and it talks straight to their
 * own FreeITSM.
 *
 * A manifest turns that from a page of instructions into one paste. Slack's
 * "Create an app → From a manifest" flow takes this JSON and sets the name,
 * scopes, event subscriptions and request URL in one go — so nobody has to hunt
 * through the Slack UI ticking permission boxes, which is where this kind of
 * setup normally goes wrong.
 *
 * ⚠️ The request URL is baked in per channel, because the webhook endpoint is
 * per-channel (`webhook.php?channel=<id>`). So the manifest can only be built
 * AFTER the channel row exists — which is why the settings page saves first and
 * offers the manifest second.
 */

require_once __DIR__ . '/messaging.php';

/**
 * The scopes we ask for, and — just as importantly — why.
 *
 * Kept minimal on purpose: a Slack admin reads this list before approving the
 * app, and every scope that is not obviously necessary is a reason to say no.
 * Notably absent: channels:manage (creating channels), users:write, and
 * anything touching files:write. Those belong to features we have not built.
 */
function slackManifestScopes(): array
{
    return [
        'channels:history'  => 'Read messages in public channels the app is invited to — this is how a message becomes a ticket.',
        'groups:history'    => 'The same, for private channels, if you invite it to one.',
        'im:history'        => 'The same, for direct messages to the app.',
        'chat:write'        => 'Post the analyst\'s reply back into the thread.',
        'users:read'        => 'Turn a Slack user id into a person\'s name on the ticket.',
        'users:read.email'  => 'Match that person to an existing FreeITSM user, so their tickets and company line up.',
        'files:read'        => 'Download a screenshot someone shares, and attach it to the ticket.',
    ];
}

/**
 * Build the manifest for one channel row.
 *
 * @param string $webhookUrl the channel's own public webhook URL
 * @param string $appName    what the app will be called in Slack
 */
function slackBuildManifest(string $webhookUrl, string $appName = 'FreeITSM'): array
{
    // Slack caps the display name at 35 characters and rejects the app outright
    // if it is longer — a confusing failure at the very first step.
    $appName = trim($appName) !== '' ? trim($appName) : 'FreeITSM';
    if (function_exists('mb_substr')) {
        $appName = mb_substr($appName, 0, 35);
    } else {
        $appName = substr($appName, 0, 35);
    }

    return [
        'display_information' => [
            'name'             => $appName,
            'description'      => 'Raise and answer service desk tickets from Slack',
            'background_color' => '#2b3137',
        ],
        'features' => [
            'bot_user' => [
                'display_name'   => $appName,
                // Keeps the bot visible in the sidebar so people can see it is
                // there — a service desk you cannot find is not a service desk.
                'always_online'  => true,
            ],
        ],
        'oauth_config' => [
            'scopes' => [
                'bot' => array_keys(slackManifestScopes()),
            ],
        ],
        'settings' => [
            'event_subscriptions' => [
                'request_url' => $webhookUrl,
                'bot_events'  => [
                    'message.channels',
                    'message.groups',
                    'message.im',
                ],
            ],
            'org_deploy_enabled'     => false,
            'socket_mode_enabled'    => false,
            'token_rotation_enabled' => false,
        ],
    ];
}

/**
 * The manifest as the JSON string a person pastes into Slack.
 * Pretty-printed because a human reads it before pasting, and unescaped slashes
 * because an escaped URL (https:\/\/…) looks broken and invites "fixing".
 *
 * @param string $webhookUrl  the channel's own public webhook URL
 * @param string $appName  what the app will be called in Slack (trimmed to 35 characters; empty gives FreeITSM)
 */
function slackManifestJson(string $webhookUrl, string $appName = 'FreeITSM'): string
{
    return json_encode(
        slackBuildManifest($webhookUrl, $appName),
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
}

/**
 * Is this install's public URL usable as a Slack request URL?
 *
 * Slack will not accept http://, and it cannot reach localhost — so an install
 * that has not been given a real public address can never complete setup. Saying
 * so here turns a baffling "Your URL didn't respond with the value of the
 * challenge parameter" in Slack's UI into a sentence that names the problem.
 *
 * @param string $webhookUrl  the public webhook URL that Slack will be given
 * @return string '' when it looks usable, otherwise the reason it will not work
 */
function slackWebhookUrlProblem(string $webhookUrl): string
{
    $host = strtolower((string) parse_url($webhookUrl, PHP_URL_HOST));
    $scheme = strtolower((string) parse_url($webhookUrl, PHP_URL_SCHEME));

    if ($host === '') {
        return 'This install has no public address configured yet.';
    }
    if ($scheme !== 'https') {
        return 'Slack only accepts https:// addresses, and this one is ' . $scheme . '://.';
    }
    if ($host === 'localhost' || $host === '127.0.0.1' || $host === '::1'
        || preg_match('/^(10|127)\./', $host)
        || preg_match('/^192\.168\./', $host)
        || preg_match('/^172\.(1[6-9]|2[0-9]|3[01])\./', $host)
        || substr($host, -6) === '.local') {
        return 'Slack has to reach this address from the internet, and ' . $host . ' is only reachable on your own network.';
    }
    return '';
}
