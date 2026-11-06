<?php
/**
 * API: the install's calendar-sync connections and feed policy (GH #75, #133).
 *
 *   GET                  -> every connection (WITHOUT secrets) + feed policy +
 *                           the Microsoft mailboxes whose credentials can be
 *                           borrowed + every analyst and the connection they use
 *   POST action=save     -> create (id=0) or update one connection.
 *                           provider=microsoft|caldav. Changing an existing
 *                           connection's provider, or its CalDAV server, answers
 *                           needs_confirm first - it takes back the events of the
 *                           analysts using that connection - resend with
 *                           confirm_switch=1.
 *        policy_only=1   -> the install-wide settings only (feed mode, accept
 *                           deletes, notification URL)
 *   POST action=test     -> one connection (id). Microsoft: mint a token and prove
 *                           the permission was granted. CalDAV: check the address
 *                           is a DAV server, and with probe_user/probe_pass list
 *                           that person's calendars (the sign-in is not stored).
 *   POST action=delete   -> one connection (id); needs_confirm first when anybody
 *                           uses it, then takes their events back
 *   POST action=set_address -> an analyst's Microsoft mailbox
 *
 * 🔑 ANY NUMBER OF CONNECTIONS. Most staff on Microsoft 365 and a few on
 * Nextcloud; an MSP with several tenants; one person on iCloud. Each analyst
 * picks one under Preferences. So every write here is scoped to the connection
 * it names, and a reset only ever touches the analysts who use it.
 *
 * ⚠️ SECRETS ARE NEVER RETURNED. The GET reports has_credentials as a boolean and
 * that is all, exactly as integrations.php does — a read that hands secrets back
 * to a browser needs the same care as one that writes them, and there is no
 * reason for the screen to know them.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';   // System administrators only
require_once '../../includes/functions.php';
require_once '../../includes/encryption.php';
require_once '../../includes/calendar_sync/calendar_sync.php';
require_once '../../includes/calendar_sync/pull.php';   // the accept-deletes setting
require_once '../../includes/calendar_sync/CalDavCalendarProvider.php';

header('Content-Type: application/json');

$action = ($_SERVER['REQUEST_METHOD'] === 'POST') ? ($_POST['action'] ?? '') : '';

/**
 * How many events and analysts depend on one connection.
 *
 * @param int $connectionId  the calendar_connections id
 * @return array{mapped:int, pushing:int}
 */
function csUsage(PDO $conn, int $connectionId): array
{
    $m = $conn->prepare("SELECT COUNT(*) FROM calendar_sync_events WHERE connection_id = ?");
    $m->execute([$connectionId]);
    $p = $conn->prepare("SELECT COUNT(*) FROM calendar_enrolments WHERE connection_id = ? AND mode = 'push'");
    $p->execute([$connectionId]);
    return ['mapped' => (int)$m->fetchColumn(), 'pushing' => (int)$p->fetchColumn()];
}

/**
 * 🔴 TAKE BACK EVERYTHING ONE CONNECTION WROTE, then reset its analysts.
 *
 * Called while the connection is still as it was, because only the old settings
 * can reach the old events: a provider switch, a CalDAV server moving, or the
 * connection being deleted all leave them somewhere the new state cannot reach.
 * Only this connection's analysts are touched; everybody on another connection
 * carries on. Nobody's feed link is affected.
 *
 * @param array $existing  the calendar_connections row as it stands before the change (id and provider are read)
 */
function csTakeBack(PDO $conn, array $existing): void
{
    require_once __DIR__ . '/../../includes/calendar_sync/push.php';
    $id = (int)$existing['id'];

    $rows = $conn->prepare("SELECT * FROM calendar_sync_events WHERE connection_id = ?");
    $rows->execute([$id]);
    foreach ($rows->fetchAll(PDO::FETCH_ASSOC) as $row) {
        calendarSyncRemoveRow($conn, $row);
    }

    // Microsoft subscriptions lapse by themselves within three days, but one we
    // can still remove is better removed.
    if ((string)$existing['provider'] === 'microsoft') {
        $old = calendarSyncLoadConnection($conn, $id);
        $subs = $conn->prepare("SELECT subscription_id FROM calendar_enrolments WHERE connection_id = ? AND subscription_id IS NOT NULL");
        $subs->execute([$id]);
        foreach ($subs->fetchAll(PDO::FETCH_COLUMN) as $sid) {
            try { if ($old) calendarSyncProviderFor($old)->deleteSubscription((string)$sid); } catch (Exception $e) {}
        }
    }

    // The address, sign-in and change token all belonged to that connection.
    $conn->prepare(
        "UPDATE calendar_enrolments
            SET mode = IF(mode = 'push', 'off', mode),
                calendar_address = NULL, credentials = NULL, connection_id = NULL,
                delta_token = NULL, delta_synced_datetime = NULL,
                subscription_id = NULL, subscription_expires = NULL, subscription_secret = NULL,
                last_error = NULL, updated_datetime = UTC_TIMESTAMP()
          WHERE connection_id = ?"
    )->execute([$id]);
}

/**
 * 🔴 NAME THE CONNECTION ON ROWS THAT ONLY IMPLY IT.
 *
 * An enrolment or event made while the install had a single connection may not
 * name it, and works only because "the only connection" is the fallback. The
 * moment a second connection is added that fallback stops answering and those
 * analysts would silently stop syncing - so, while there is still exactly one,
 * write it onto them. Run before anything that adds, resets or removes a
 * connection.
 */
function csAdoptOrphans(PDO $conn): void
{
    $ids = calendarSyncConnectionIds($conn);
    if (count($ids) !== 1) return;
    $conn->prepare("UPDATE calendar_enrolments SET connection_id = ? WHERE connection_id IS NULL")->execute([$ids[0]]);
    $conn->prepare("UPDATE calendar_sync_events SET connection_id = ? WHERE connection_id IS NULL")->execute([$ids[0]]);
}

try {
    $conn = connectToDatabase();

    if (!calendarSyncSchemaReady($conn)) {
        echo json_encode([
            'success' => false,
            'needs_db_verify' => true,
            'error' => 'Calendar sync needs a database update — run System → Database Verification.',
        ]);
        exit;
    }

    // ── Read ────────────────────────────────────────────────────────────────
    if ($action === '') {
        // Only Microsoft mailboxes can lend an app registration, and only ones
        // that actually have Azure credentials on them — offering an IMAP mailbox
        // as a source would be offering something that cannot work.
        $mailboxes = [];
        foreach ($conn->query("SELECT id, name, provider, azure_client_id FROM target_mailboxes WHERE provider = 'microsoft' AND is_active = 1")->fetchAll(PDO::FETCH_ASSOC) as $mb) {
            if (!empty($mb['azure_client_id'])) {
                $mailboxes[] = ['id' => (int)$mb['id'], 'name' => $mb['name']];
            }
        }

        $connections = [];
        foreach ($conn->query("SELECT * FROM calendar_connections ORDER BY id")->fetchAll(PDO::FETCH_ASSOC) as $row) {
            // CalDAV keeps no secret on the connection - only where the server is
            // and how to sign in to it - so those two are safe to hand back.
            $c = $row['provider'] === 'caldav' ? calendarSyncDecodeCredentials($row['credentials'] ?? null) : [];
            $connections[] = [
                'id'         => (int)$row['id'],
                'name'       => $row['name'],
                'provider'   => $row['provider'],
                'mailbox_id' => $row['mailbox_id'] !== null ? (int)$row['mailbox_id'] : null,
                'is_active'  => (int)$row['is_active'] === 1,
                // A boolean and nothing more. See the header.
                'has_credentials'     => $row['provider'] !== 'caldav' && !empty($row['credentials']),
                'caldav_server_url'   => (string)($c['server_url'] ?? ''),
                'caldav_auth'         => (string)($c['auth'] ?? 'auto'),
                'last_error'          => $row['last_error'],
                'last_error_datetime' => $row['last_error_datetime'],
            ] + csUsage($conn, (int)$row['id']);
        }

        echo json_encode([
            'success'     => true,
            'connections' => $connections,
            'mailboxes'   => $mailboxes,
            'feed_mode'      => scheduleFeedMode($conn),
            'accept_deletes' => calendarAcceptDeletes($conn),
            'notify_url'     => calendarNotifyUrl($conn),
            // A SUGGESTION, never applied on its own. FreeITSM cannot know what
            // URL the outside world reaches it on — HTTP_HOST is whatever the
            // last request used, and behind a proxy or a tunnel it is routinely
            // wrong. Offering it saves typing; an admin still confirms it.
            'notify_default' => (!empty($_SERVER['HTTP_HOST'])
                ? 'https://' . $_SERVER['HTTP_HOST']
                  . (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . '/api/calendar/graph_notify.php'
                : ''),
            'subscriptions'  => (int)$conn->query(
                "SELECT COUNT(*) FROM calendar_enrolments WHERE subscription_id IS NOT NULL")->fetchColumn(),
            'enrolled'   => (int)$conn->query("SELECT COUNT(*) FROM calendar_enrolments WHERE mode <> 'off'")->fetchColumn(),

            // 🔑 HOW LONG SINCE ANYTHING WAS CHECKED — the one number that reveals
            // a scheduled job which has quietly stopped. Every other failure on
            // this screen announces itself; a cron that is no longer running looks
            // EXACTLY like a calendar in which nothing has changed, and it can sit
            // like that for weeks. NULL means it has genuinely never run.
            //
            // Measured in SQL rather than by differencing against the browser's
            // clock: delta_synced_datetime is written with UTC_TIMESTAMP(), so comparing it
            // to UTC_TIMESTAMP() keeps both sides on one clock and sidesteps the timezone
            // question altogether.
            'last_poll_minutes' => (function () use ($conn) {
                $v = $conn->query(
                    "SELECT TIMESTAMPDIFF(MINUTE, MAX(delta_synced_datetime), UTC_TIMESTAMP())
                       FROM calendar_enrolments
                      WHERE mode <> 'off' AND delta_synced_datetime IS NOT NULL"
                )->fetchColumn();
                return ($v === null || $v === false) ? null : (int)$v;
            })(),

            // Every active analyst, with the connection they use and where their
            // work would go. LEFT JOIN, because an analyst who has never chosen
            // has no enrolment row and must still be listed — otherwise the one
            // person an admin most needs to fix is the one who is invisible.
            'analysts'   => $conn->query(
                "SELECT a.id, a.full_name, a.email, e.calendar_address, e.mode, e.task_mode, e.last_error,
                        e.connection_id, c.name AS connection_name, c.provider AS connection_provider,
                        e.subscription_id, (e.credentials IS NOT NULL) AS has_account,
                        TIMESTAMPDIFF(HOUR, UTC_TIMESTAMP(), e.subscription_expires) AS sub_hours,
                        TIMESTAMPDIFF(MINUTE, e.delta_synced_datetime, UTC_TIMESTAMP()) AS checked_minutes
                   FROM analysts a
                   LEFT JOIN calendar_enrolments e ON e.analyst_id = a.id
                   LEFT JOIN calendar_connections c ON c.id = e.connection_id
                  WHERE a.is_active = 1
                  ORDER BY a.full_name"
            )->fetchAll(PDO::FETCH_ASSOC),
        ]);
        exit;
    }

    // ── Write ───────────────────────────────────────────────────────────────
    if ($action === 'save') {
        $feedMode = (string)($_POST['feed_mode'] ?? scheduleFeedMode($conn));
        if (!in_array($feedMode, [FEED_MODE_OFF, FEED_MODE_REF, FEED_MODE_FULL], true)) {
            $feedMode = FEED_MODE_FULL;
        }

        // The install-wide settings. Separate from any connection: an install
        // with none at all still publishes subscribe links and must be able to
        // govern them.
        if (($_POST['policy_only'] ?? '') === '1') {
            $set = $conn->prepare(
                "INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
            );
            $set->execute([SCHEDULE_FEED_SETTING, $feedMode]);
            if (array_key_exists('notify_url', $_POST)) {
                $set->execute([CALENDAR_NOTIFY_URL, trim((string)$_POST['notify_url'])]);
            }
            // Whether deleting one of these events in a calendar unschedules the
            // ticket. OFF by default: whether a personal tidy-up may reach shared
            // data is an organisation's call, and the safe answer changes nothing.
            if (array_key_exists('accept_deletes', $_POST)) {
                $set->execute([CALENDAR_ACCEPT_DELETES, $_POST['accept_deletes'] === '1' ? '1' : '0']);
            }
            echo json_encode(['success' => true, 'feed_mode' => $feedMode]);
            exit;
        }

        csAdoptOrphans($conn);
        $id       = (int)($_POST['id'] ?? 0);
        $existing = null;
        if ($id > 0) {
            $st = $conn->prepare("SELECT * FROM calendar_connections WHERE id = ?");
            $st->execute([$id]);
            $existing = $st->fetch(PDO::FETCH_ASSOC) ?: null;
            if (!$existing) { echo json_encode(['success' => false, 'error' => 'That connection no longer exists.']); exit; }
        }

        $provider = (string)($_POST['provider'] ?? 'microsoft');
        if (!in_array($provider, CALENDAR_PROVIDERS, true)) {
            echo json_encode(['success' => false, 'error' => 'Unknown calendar provider.']);
            exit;
        }
        $name = trim((string)($_POST['name'] ?? ''));
        if ($name === '') $name = $provider === 'caldav' ? 'CalDAV' : 'Microsoft 365';
        $name = mb_substr($name, 0, 100);

        $source    = ($_POST['source'] ?? 'mailbox') === 'own' ? 'own' : 'mailbox';
        $mailboxId = ($_POST['mailbox_id'] ?? '') !== '' ? (int)$_POST['mailbox_id'] : null;

        $credentials = null;
        $newOrigin   = '';
        if ($provider === 'caldav') {
            $serverUrl = trim((string)($_POST['server_url'] ?? ''));
            $auth      = (string)($_POST['caldav_auth'] ?? 'auto');
            if (!in_array($auth, ['auto', 'digest', 'basic'], true)) $auth = 'auto';
            if (!preg_match('#^https?://[^/\s]+#i', $serverUrl)) {
                echo json_encode(['success' => false, 'error' => 'Enter the address of the calendar server, starting with http:// or https://.']);
                exit;
            }
            // No passwords here. Each analyst signs in with their own, under
            // Preferences - a CalDAV server has no way for one account to write
            // into everybody's calendar.
            $newOrigin   = rtrim($serverUrl, '/') . '/';
            $credentials = calendarSyncEncodeCredentials(['server_url' => $newOrigin, 'auth' => $auth]);
            $mailboxId   = null;
        } elseif ($source === 'own') {
            $tenant = trim((string)($_POST['tenant_id'] ?? ''));
            $client = trim((string)($_POST['client_id'] ?? ''));
            $secret = (string)($_POST['client_secret'] ?? '');
            // A blank or masked secret on an edit means "unchanged", so an admin
            // can rename the connection without retyping a secret they cannot see.
            if (isMaskedNoChangeValue($secret) && $existing && $existing['provider'] === 'microsoft' && !empty($existing['credentials'])) {
                $old = calendarSyncDecodeCredentials($existing['credentials']);
                $secret = $old['client_secret'] ?? '';
            }
            if ($tenant === '' || $client === '' || $secret === '') {
                echo json_encode(['success' => false, 'error' => 'Tenant ID, client ID and client secret are all required.']);
                exit;
            }
            $credentials = calendarSyncEncodeCredentials([
                'tenant_id' => $tenant, 'client_id' => $client, 'client_secret' => $secret,
            ]);
            $mailboxId = null;               // own credentials win; don't leave a stale borrow
        } else {
            if (!$mailboxId) {
                echo json_encode(['success' => false, 'error' => 'Choose the mailbox to borrow credentials from.']);
                exit;
            }
            $credentials = null;             // borrowing: nothing of its own stored
        }

        // 🔴 CHANGING WHERE THIS CONNECTION'S EVENTS LIVE. A different provider,
        // or a CalDAV server on a different origin, leaves every event this
        // connection wrote where the new settings cannot reach it. Take them back
        // first, while the old settings still can - after asking, because it
        // removes appointments from real calendars.
        $resetting = false;
        if ($existing) {
            if ((string)$existing['provider'] !== $provider) {
                $resetting = true;
            } elseif ($provider === 'caldav') {
                $oldServer = (string)(calendarSyncDecodeCredentials($existing['credentials'] ?? null)['server_url'] ?? '');
                $resetting = $oldServer === '' || !CalDavCalendarProvider::sameOrigin($oldServer, $newOrigin);
            }
        }
        if ($resetting) {
            $usage = csUsage($conn, $id);
            if (($usage['mapped'] || $usage['pushing']) && ($_POST['confirm_switch'] ?? '') !== '1') {
                echo json_encode(['success' => false, 'needs_confirm' => true] + $usage);
                exit;
            }
            csTakeBack($conn, $existing);
        }

        if ($existing) {
            $conn->prepare(
                "UPDATE calendar_connections
                    SET name = ?, provider = ?, mailbox_id = ?, credentials = ?, is_active = 1,
                        last_error = NULL, last_error_datetime = NULL,
                        token_data = NULL, updated_datetime = UTC_TIMESTAMP()
                  WHERE id = ?"
            )->execute([$name, $provider, $mailboxId, $credentials, $id]);
        } else {
            $conn->prepare(
                "INSERT INTO calendar_connections (name, provider, mailbox_id, credentials, created_by)
                 VALUES (?, ?, ?, ?, ?)"
            )->execute([$name, $provider, $mailboxId, $credentials, (int)$_SESSION['analyst_id']]);
            $id = (int)$conn->lastInsertId();
        }
        // token_data is cleared on purpose: a cached token minted with the OLD
        // credentials would keep working for up to an hour and make a broken
        // change look fine until long after the admin walked away.
        echo json_encode(['success' => true, 'id' => $id, 'reset' => $resetting]);
        exit;
    }

    if ($action === 'test') {
        $connection = calendarSyncLoadConnection($conn, (int)($_POST['id'] ?? 0));
        if (!$connection) { echo json_encode(['success' => false, 'error' => 'Save the connection first.']); exit; }
        $cid = (int)$connection['id'];

        $fail = function (string $msg, array $extra = []) use ($conn, $cid) {
            $msg = substr($msg, 0, 500);
            $conn->prepare("UPDATE calendar_connections SET last_error = ?, last_error_datetime = UTC_TIMESTAMP() WHERE id = ?")
                 ->execute([$msg, $cid]);
            echo json_encode(['success' => false, 'error' => $msg] + $extra);
        };
        $clear = function () use ($conn, $cid) {
            $conn->prepare("UPDATE calendar_connections SET last_error = NULL, last_error_datetime = NULL WHERE id = ?")
                 ->execute([$cid]);
        };

        // CalDAV: two separate questions. Is that address a calendar server at
        // all (asked without signing in - the connection holds nobody's
        // password), and, when the admin types a sign-in to try, which calendars
        // would that person be offered. The sign-in is used for this request
        // only and never stored.
        if ($connection['provider'] === 'caldav') {
            try {
                $provider = calendarSyncProviderFor($connection);
                $provider->verifyConnection();
                $result = ['success' => true, 'caldav' => true];
                $user = trim((string)($_POST['probe_user'] ?? ''));
                if ($user !== '') {
                    /** @var CalDavCalendarProvider $provider */
                    $found = $provider->withAccount(['username' => $user, 'password' => (string)($_POST['probe_pass'] ?? '')])
                                      ->discoverCalendars();
                    $result['probe']      = $user;
                    $result['probe_ok']   = $found['ok'];
                    $result['probe_auth'] = $found['auth'];
                    $result['calendars']  = array_column($found['calendars'], 'name');
                    if (!$found['ok']) $result['probe_error'] = $found['error'];
                }
                $clear();
                echo json_encode($result);
            } catch (Exception $e) {
                $fail($e->getMessage());
            }
            exit;
        }

        $creds = $connection['credentials'] ?? [];
        if (empty($creds['tenant_id']) || empty($creds['client_id']) || empty($creds['client_secret'])) {
            echo json_encode(['success' => false, 'error' =>
                'No usable credentials. If you chose to borrow them, check that mailbox still has its Azure details.']);
            exit;
        }

        try {
            $provider = calendarSyncProviderFor($connection);
            $provider->conn = $conn;

            // Two separate questions, reported separately, because they fail for
            // completely different reasons and need different fixes.
            $probe = trim((string)($_POST['probe'] ?? ''));
            $provider->verifyConnection();               // throws if credentials/consent are wrong

            $result = ['success' => true, 'token' => true, 'borrowed' => $connection['borrowed_from_mailbox'] ?? null];
            if ($probe !== '') {
                $result['probe']    = $probe;
                $result['probe_ok'] = $provider->verifyTarget($probe);
            }
            $clear();
            echo json_encode($result);
        } catch (Exception $e) {
            $fail($e->getMessage(), ['token' => false]);
        }
        exit;
    }

    /**
     * Point one analyst's Microsoft calendar sync at a particular mailbox.
     *
     * 🔴 ADMIN ONLY, AND DELIBERATELY NOT SOMETHING AN ANALYST CAN DO. The app
     * permission behind this can write to any mailbox in the tenant, so an
     * analyst able to set their own address could quietly fill a colleague's —
     * or the chief executive's — calendar with their tickets. An analyst
     * controls whether sync is on; an administrator controls where it goes.
     *
     * With CalDAV the calendar belongs to the analyst's own account and is
     * chosen by them, signed in as themselves - an administrator does not have
     * the password that would reach it - so this refuses for an analyst whose
     * connection is CalDAV.
     */
    if ($action === 'set_address') {
        $analystId = (int)($_POST['analyst_id'] ?? 0);
        $address   = trim((string)($_POST['calendar_address'] ?? ''));
        if (!$analystId) { echo json_encode(['success' => false, 'error' => 'Unknown analyst.']); exit; }

        $enrolment = calendarSyncEnrolment($conn, $analystId);
        $theirs    = calendarSyncConnectionFor($conn, $enrolment);
        if ($theirs && $theirs['provider'] === 'caldav') {
            echo json_encode(['success' => false, 'error' => 'This analyst uses a CalDAV server, so they choose their own calendar under Preferences.']);
            exit;
        }
        // The Microsoft connection to check the address against: theirs, or,
        // before they have chosen one, the first Microsoft connection there is.
        $microsoft = $theirs;
        if (!$microsoft) {
            foreach (calendarSyncConnectionIds($conn) as $cid) {
                if (calendarSyncConnectionProvider($conn, $cid) === 'microsoft') {
                    $microsoft = calendarSyncLoadConnection($conn, $cid);
                    break;
                }
            }
        }
        if (!$microsoft) {
            echo json_encode(['success' => false, 'error' => 'Add a Microsoft 365 connection first.']);
            exit;
        }

        if ($address !== '' && !filter_var($address, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'error' => 'That is not a valid email address.']);
            exit;
        }

        // Blank means "go back to inheriting analysts.email" rather than "sync to
        // nowhere" — an admin clearing the box is undoing an override, not
        // switching the analyst off.
        $conn->prepare(
            "INSERT INTO calendar_enrolments (analyst_id, calendar_address, mode)
             VALUES (?, ?, 'off')
             ON DUPLICATE KEY UPDATE calendar_address = VALUES(calendar_address), updated_datetime = UTC_TIMESTAMP()"
        )->execute([$analystId, ($address === '' ? null : $address)]);

        // Verify it while we are here, if we can, so the admin does not have to
        // press a second button to find out whether what they typed exists.
        $verified = null;
        if ($address !== '') {
            try {
                $provider = calendarSyncProviderFor($microsoft);
                $provider->conn = $conn;
                $verified = $provider->verifyTarget($address);
            } catch (Exception $e) {
                $verified = null;   // unknown, not false — a broken connection
            }                       // says nothing about whether the address is real
        }
        echo json_encode(['success' => true, 'verified' => $verified]);
        exit;
    }

    if ($action === 'delete') {
        csAdoptOrphans($conn);
        $st = $conn->prepare("SELECT * FROM calendar_connections WHERE id = ?");
        $st->execute([(int)($_POST['id'] ?? 0)]);
        $existing = $st->fetch(PDO::FETCH_ASSOC);
        if (!$existing) { echo json_encode(['success' => false, 'error' => 'That connection no longer exists.']); exit; }

        // 🔴 Its events are taken back first. Deleting a connection used to
        // leave everything it had written sitting in people's calendars with
        // nothing able to update or remove it.
        $usage = csUsage($conn, (int)$existing['id']);
        if (($usage['mapped'] || $usage['pushing']) && ($_POST['confirm'] ?? '') !== '1') {
            echo json_encode(['success' => false, 'needs_confirm' => true] + $usage);
            exit;
        }
        csTakeBack($conn, $existing);
        $conn->prepare("DELETE FROM calendar_connections WHERE id = ?")->execute([(int)$existing['id']]);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
