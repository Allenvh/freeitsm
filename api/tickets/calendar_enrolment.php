<?php
/**
 * API: an analyst's OWN calendar choice (GH #75, CalDAV #133).
 *
 *   GET            -> my mode, my connection, where my work would go, and what is on offer
 *   POST mode=off|push|feed [connection_id]
 *
 * CalDAV only - the analyst signs in as themselves, so they also choose where:
 *   POST action=caldav_discover  connection_id, username, password -> the calendars that sign-in can write to
 *   POST action=caldav_save      connection_id, username, password, calendar_url[, turn_on=1]
 *   POST action=caldav_forget                            -> take our events back, forget the sign-in
 *
 * 🔑 ANY NUMBER OF CONNECTIONS. An install can have Microsoft 365 and one or
 * more CalDAV servers side by side, and each analyst picks the one their work
 * goes to. connection_id may be left out while there is only one, or to mean
 * "the one I already use".
 *
 * 🔴 WITH MICROSOFT THIS ENDPOINT CANNOT SET AN ADDRESS. Only mode. The
 * application permission behind that push can write to any mailbox in the
 * tenant, so letting an analyst name their own target would let them fill a
 * colleague's calendar with their tickets. Where it goes is an administrator's
 * decision (System → Calendar sync); whether it happens at all is the analyst's.
 *
 * 🔑 WITH CALDAV IT CAN, AND MUST. Each calendar belongs to its owner's account
 * and FreeITSM signs in with the analyst's own password, so the only calendars
 * it can reach are ones they could already write to - and nobody else has the
 * password to choose for them. The calendar must still be on the server the
 * administrator configured (CalDavCalendarProvider::calendarUrl).
 *
 * 🔑 ONE MODE, NOT TWO SWITCHES. With a push and a subscribed feed both live you
 * see every scheduled ticket twice — once as a real event, once from the
 * subscription. A single value makes that unrepresentable.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/calendar_sync/calendar_sync.php';
require_once '../../includes/timezone.php';   // naive_now(), for the work-window backfill (GH #126)

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');

$analystId = (int)$_SESSION['analyst_id'];

/**
 * Put what is already scheduled into a calendar that has just been switched on
 * (or just changed), rather than only tickets touched from now on — an empty
 * calendar after opting in reads as "it didn't work".
 */
function enrolBackfillTickets(PDO $conn, int $analystId): void
{
    require_once __DIR__ . '/../../includes/calendar_sync/push.php';
    $st = $conn->prepare(
        "SELECT t.id FROM tickets t
           LEFT JOIN ticket_statuses ts ON ts.id = t.status_id
          WHERE t.owner_id = :analyst AND t.work_start_datetime IS NOT NULL
            AND t.deleted_datetime IS NULL AND COALESCE(ts.is_closed, 0) = 0
            AND t.work_start_datetime >= (:cutoff - INTERVAL 1 WEEK)
          ORDER BY t.work_start_datetime"
    );
    // A wall clock — work_start_datetime is naive (GH #126).
    //
    // 🔴 The same reversed-parameter bug as the subscribe feed (GH #133):
    // #1446 passed the cutoff where the analyst goes, so this backfill
    // selected NOTHING and switching push on left the calendar empty —
    // which is precisely the failure the comment above says it exists to
    // prevent. Named so it cannot happen a third time.
    $st->execute([':analyst' => $analystId, ':cutoff' => naive_now()]);
    // Bounded to the last week onwards on purpose: back-filling months of
    // finished work would fill a calendar with history nobody asked for, and
    // make opting in a very long request.
    foreach ($st->fetchAll(PDO::FETCH_COLUMN) as $tid) {
        calendarSyncReconcileTicket($conn, (int)$tid);
    }
}

/**
 * Tasks, whenever the choice moved at all (#75).
 *
 * 🔑 RECONCILE, DON'T BRANCH. The same sweep handles switching tasks on,
 * switching them off, and narrowing 'both' to one kind — because
 * calendarSyncReconcileTask() works out what SHOULD be there and makes that
 * true.
 */
function enrolReconcileTasks(PDO $conn, int $analystId): void
{
    require_once __DIR__ . '/../../includes/calendar_sync/push.php';
    $st = $conn->prepare(
        "SELECT tk.id
           FROM tasks tk
      LEFT JOIN task_statuses ts ON ts.id = tk.status_id
          WHERE tk.assigned_analyst_id = ?
            AND (tk.work_start_datetime IS NOT NULL OR tk.due_date IS NOT NULL)
            AND COALESCE(ts.is_closed, 0) = 0
         UNION
         SELECT task_id FROM calendar_sync_events WHERE analyst_id = ? AND task_id IS NOT NULL"
    );
    // ⚠️ The UNION is not tidiness. Turning tasks OFF means reconciling
    // tasks that no longer qualify on the first half — a completed one, or
    // one reassigned away — and those are exactly the rows whose events
    // must be taken back. Without it, switching off would leave them.
    $st->execute([$analystId, $analystId]);
    foreach ($st->fetchAll(PDO::FETCH_COLUMN) as $tid) {
        if ($tid) calendarSyncReconcileTask($conn, (int)$tid);
    }
}

/**
 * Move an analyst onto another connection. Returns true when anything moved.
 *
 * 🔴 THE OLD CONNECTION TAKES ITS EVENTS BACK FIRST. Each event is removed
 * through the connection that wrote it (calendarSyncRemoveRow), so this has to
 * happen before the enrolment forgets it. Everything tied to the old
 * connection goes with it: the sign-in, the change token, a Microsoft
 * subscription, and the calendar address whenever either side is CalDAV (a
 * CalDAV address is a web address that means nothing to Microsoft, and the
 * other way round). A push that was on is switched off here; the caller
 * switches it back on once the new connection has been checked.
 *
 * @param array $enrolment  the analyst's calendar_enrolments row, as from calendarSyncEnrolment()
 * @param ?array $current  the calendar_connections row the analyst is on now; null when none
 * @param array $target  the calendar_connections row to move them onto
 */
function enrolSwitchConnection(PDO $conn, int $analystId, array $enrolment, ?array $current, array $target): bool
{
    if ($current && (int)$current['id'] === (int)$target['id']) {
        // Already there - but an enrolment that only implied it now names it,
        // so it survives a second connection being added.
        if (empty($enrolment['connection_id'])) {
            $conn->prepare("UPDATE calendar_enrolments SET connection_id = ? WHERE analyst_id = ?")
                 ->execute([(int)$target['id'], $analystId]);
        }
        return false;
    }
    require_once __DIR__ . '/../../includes/calendar_sync/push.php';
    calendarSyncRemoveAllForAnalyst($conn, $analystId);

    if (!empty($enrolment['subscription_id']) && $current && $current['provider'] === 'microsoft') {
        try {
            $p = calendarSyncProviderFor($current, $enrolment);
            $p->conn = $conn;
            $p->deleteSubscription((string)$enrolment['subscription_id']);
        } catch (Exception $e) {
            // It lapses by itself within three days.
        }
    }

    $clearAddress = ($current && $current['provider'] === 'caldav') || $target['provider'] === 'caldav';
    $conn->prepare(
        "UPDATE calendar_enrolments
            SET connection_id = ?, mode = IF(mode = 'push', 'off', mode),
                calendar_address = IF(? = 1, NULL, calendar_address), credentials = NULL,
                delta_token = NULL, delta_synced_datetime = NULL,
                subscription_id = NULL, subscription_expires = NULL, subscription_secret = NULL,
                last_error = NULL, updated_datetime = UTC_TIMESTAMP()
          WHERE analyst_id = ?"
    )->execute([(int)$target['id'], $clearAddress ? 1 : 0, $analystId]);
    return true;
}

try {
    $conn = connectToDatabase();

    if (!calendarSyncSchemaReady($conn)) {
        // Not an error the analyst can do anything about — report it as "nothing
        // on offer" rather than a failure, and the screen simply shows less.
        echo json_encode([
            'success' => true, 'mode' => CALENDAR_MODE_OFF,
            'push_available' => false, 'feed_available' => false,
            'reason' => 'needs_db',
        ]);
        exit;
    }

    // $connection is the one this analyst uses now: their own, or the
    // install's only one when they have not chosen. $target (below) is the one
    // this request is about: the one they picked on the screen, else the same.
    $enrolment  = calendarSyncEnrolment($conn, $analystId);
    $connection = calendarSyncConnectionFor($conn, $enrolment);
    $isCalDav   = $connection && $connection['provider'] === 'caldav';
    $account    = calendarSyncAccount($enrolment);
    $saved      = calendarSyncDecodeCredentials($enrolment['credentials'] ?? null);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $connections = [];
        foreach (calendarSyncConnectionIds($conn) as $cid) {
            $c = calendarSyncLoadConnection($conn, $cid);
            if (!$c) continue;
            $connections[] = [
                'id'         => $cid,
                'name'       => $c['name'],
                'provider'   => $c['provider'],
                'server_url' => $c['provider'] === 'caldav' ? (string)($c['credentials']['server_url'] ?? '') : '',
            ];
        }
        echo json_encode([
            'success'        => true,
            'mode'           => $enrolment['mode'],
            'task_mode'      => $enrolment['task_mode'] ?? TASK_CAL_OFF,
            'address'        => $enrolment['calendar_address'],
            'push_available' => count($connections) > 0,
            'feed_available' => scheduleFeedAllowed($conn),
            'last_error'     => $enrolment['last_error'] ?? null,
            'connections'    => $connections,
            'connection_id'  => $connection ? (int)$connection['id'] : null,
            'provider'       => $connection ? $connection['provider'] : null,
            // CalDAV: who they sign in as and which calendar - never the password.
            'caldav'         => $isCalDav ? [
                'username'      => $account['username'],
                'has_password'  => $account['password'] !== '',
                'calendar_url'  => $enrolment['calendar_address'],
                'calendar_name' => (string)($saved['calendar_name'] ?? ''),
                'server_url'    => (string)($connection['credentials']['server_url'] ?? ''),
            ] : null,
        ]);
        exit;
    }

    $target = $connection;
    if ((int)($_POST['connection_id'] ?? 0) > 0) {
        $target = calendarSyncLoadConnection($conn, (int)$_POST['connection_id']);
        if (!$target) {
            echo json_encode(['success' => false, 'error' => 'That calendar connection is no longer available.']);
            exit;
        }
    }
    $switching = $target && (!$connection || (int)$connection['id'] !== (int)$target['id']);

    $action = (string)($_POST['action'] ?? '');

    // ── CalDAV: the analyst's own sign-in and calendar ──────────────────────
    if ($action === 'caldav_forget') {
        // Take back what we put there while the sign-in still works, then
        // forget it. Leaving events FreeITSM can no longer update is the
        // worst of both worlds. The connection they chose stays chosen.
        require_once '../../includes/calendar_sync/push.php';
        calendarSyncRemoveAllForAnalyst($conn, $analystId);
        $conn->prepare(
            "UPDATE calendar_enrolments
                SET mode = IF(mode = 'push', 'off', mode), calendar_address = NULL, credentials = NULL,
                    delta_token = NULL, delta_synced_datetime = NULL, last_error = NULL,
                    updated_datetime = UTC_TIMESTAMP()
              WHERE analyst_id = ?"
        )->execute([$analystId]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action !== '') {
        if (!$target) {
            echo json_encode(['success' => false, 'error' => 'Choose which calendar connection to use.']);
            exit;
        }
        if ($target['provider'] !== 'caldav') {
            echo json_encode(['success' => false, 'error' => 'That connection is not a CalDAV server.']);
            exit;
        }
        require_once '../../includes/calendar_sync/push.php';

        // A blank password means "the one I saved", so somebody changing
        // calendars does not have to type it again. Only for the same username
        // on the same connection: a new username, or another server, with the
        // old password is not what anybody means.
        $typedUser = trim((string)($_POST['username'] ?? ''));
        $typedPass = (string)($_POST['password'] ?? '');
        if ($typedPass === '' && !$switching && $typedUser === $account['username']) {
            $typedPass = $account['password'];
        }

        if ($action === 'caldav_discover') {
            if ($typedUser === '') {
                echo json_encode(['success' => false, 'error' => 'Enter your calendar username.']);
                exit;
            }
            $found = calendarSyncProviderFor($target)
                ->withAccount(['username' => $typedUser, 'password' => $typedPass])
                ->discoverCalendars();
            if (!$found['ok']) {
                echo json_encode(['success' => false, 'error' => $found['error'], 'auth' => $found['auth']]);
                exit;
            }
            echo json_encode(['success' => true, 'calendars' => $found['calendars'], 'auth' => $found['auth']]);
            exit;
        }

        if ($action === 'caldav_save') {
            $url = trim((string)($_POST['calendar_url'] ?? ''));
            if ($typedUser === '' || $url === '') {
                echo json_encode(['success' => false, 'error' => 'Sign in and choose a calendar first.']);
                exit;
            }
            $provider = calendarSyncProviderFor($target)
                ->withAccount(['username' => $typedUser, 'password' => $typedPass]);
            try {
                $check = $provider->checkCalendar($url);
                $url   = $provider->calendarUrl($url);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit;
            }
            if (!$check['ok']) {
                echo json_encode(['success' => false, 'error' => $check['error']]);
                exit;
            }
            // calendar_address and calendar_sync_events.remote_calendar are both
            // VARCHAR(255). Refused rather than cut short: a truncated address
            // is a calendar that looks set and can never be reached.
            if (strlen($url) > 255) {
                echo json_encode(['success' => false, 'error' => 'That calendar’s address is too long for FreeITSM to store (over 255 characters).']);
                exit;
            }

            // Only now, with the new calendar proven, does anything move.
            $wasPushing = ($enrolment['mode'] ?? '') === CALENDAR_MODE_PUSH;
            $moved      = enrolSwitchConnection($conn, $analystId, $enrolment, $connection, $target);

            // A different calendar or account on the same server: take our
            // events out of the old one first, signed in as the old account,
            // while we still can. (A move of connection already did.)
            $wasUrl = $moved ? '' : (string)($enrolment['calendar_address'] ?? '');
            $moving = $wasUrl !== '' && ($wasUrl !== $url || $account['username'] !== $typedUser);
            if ($moving) calendarSyncRemoveAllForAnalyst($conn, $analystId);
            $moving = $moving || $moved;

            $turnOn = !empty($_POST['turn_on']);
            $mode   = ($wasPushing || $turnOn) ? CALENDAR_MODE_PUSH : (string)$enrolment['mode'];

            $conn->prepare(
                "INSERT INTO calendar_enrolments (analyst_id, mode, connection_id, calendar_address, credentials)
                 VALUES (?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE mode = VALUES(mode), connection_id = VALUES(connection_id),
                                         calendar_address = VALUES(calendar_address),
                                         credentials = VALUES(credentials),
                                         delta_token = IF(? = 1, NULL, delta_token),
                                         last_error = NULL, updated_datetime = UTC_TIMESTAMP()"
            )->execute([
                $analystId, $mode, (int)$target['id'], $url,
                calendarSyncEncodeCredentials([
                    'username' => $typedUser, 'password' => $typedPass, 'calendar_name' => $check['name'],
                ]),
                ($moving || $wasUrl === '') ? 1 : 0,
            ]);

            // A calendar that is now receiving work, for the first time or
            // afresh, gets what is already scheduled.
            if ($mode === CALENDAR_MODE_PUSH && (!$wasPushing || $moving)) {
                enrolBackfillTickets($conn, $analystId);
                enrolReconcileTasks($conn, $analystId);
            }
            $after = calendarSyncEnrolment($conn, $analystId);
            echo json_encode(['success' => true, 'mode' => $mode, 'calendar_name' => $check['name'],
                              'connection_id' => (int)$target['id'],
                              'last_error' => $after['last_error'] ?? null]);
            exit;
        }

        echo json_encode(['success' => false, 'error' => 'Unknown action.']);
        exit;
    }

    // ── The mode ────────────────────────────────────────────────────────────
    $mode = (string)($_POST['mode'] ?? '');
    if (!calendarModeIsValid($mode)) {
        echo json_encode(['success' => false, 'error' => 'Unknown option.']);
        exit;
    }
    if ($mode === CALENDAR_MODE_FEED && !scheduleFeedAllowed($conn)) {
        echo json_encode(['success' => false, 'error' => 'Subscription links are switched off on this system.']);
        exit;
    }

    if ($mode === CALENDAR_MODE_PUSH) {
        if (!$target) {
            $none = !calendarSyncConnectionIds($conn);
            echo json_encode(['success' => false, 'needs_connection' => !$none, 'error' => $none
                ? 'No calendar connection has been set up on this system yet.'
                : 'Choose which calendar connection to use.']);
            exit;
        }
        $targetCalDav = $target['provider'] === 'caldav';

        // CalDAV: nothing to switch on until they have signed in and chosen a
        // calendar on that server. Said as its own answer so the screen can
        // show the form - and nothing moves until they have.
        if ($targetCalDav && ($switching || $account['username'] === ''
                              || empty($enrolment['calendar_address']))) {
            echo json_encode(['success' => false, 'needs_account' => true,
                'error' => 'Sign in to your calendar and choose one first.']);
            exit;
        }

        // Where it would go. Moving onto Microsoft from CalDAV drops the CalDAV
        // address, so it is their own email address again.
        $address = (string)($enrolment['calendar_address'] ?? '');
        if ($switching && $connection && $connection['provider'] === 'caldav') {
            $aStmt = $conn->prepare("SELECT email FROM analysts WHERE id = ?");
            $aStmt->execute([$analystId]);
            $address = (string)($aStmt->fetchColumn() ?: '');
        }
        if ($address === '') {
            echo json_encode(['success' => false, 'no_address' => true,
                'error' => 'We do not know which mailbox is yours.']);
            exit;
        }

        // 🔑 VERIFIED AT THE MOMENT OF OPTING IN, not on every page load. One
        // call when the analyst actually chooses this is cheap and is exactly
        // when the answer matters; checking on every visit to Preferences would
        // be a call per page view for a question that rarely changes.
        //
        // ⚠️ And it must be checked SOMEWHERE. Ed's own FreeITSM address is
        // admin@localhost, which is not a mailbox at all — switching this on and
        // discovering nothing ever appeared, with no explanation, is the failure
        // this prevents. Checked BEFORE a move, so a bad address does not cost
        // the analyst the calendar they had.
        try {
            $provider = calendarSyncProviderFor($target, $enrolment);
            $provider->conn = $conn;
            if ($targetCalDav) {
                $check = $provider->checkCalendar($address);
                if (!$check['ok']) {
                    echo json_encode(['success' => false, 'needs_account' => true, 'error' => $check['error']]);
                    exit;
                }
            } elseif (!$provider->verifyTarget($address)) {
                echo json_encode(['success' => false, 'bad_address' => true, 'address' => $address,
                    'error' => 'No calendar could be found for ' . $address . '.']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }

        // A move switches any old push off, so what follows sees a push being
        // switched on afresh: the new calendar gets the backfill.
        if (enrolSwitchConnection($conn, $analystId, $enrolment, $connection, $target)) {
            $enrolment = calendarSyncEnrolment($conn, $analystId);
        }
    }

    $wasPushing = ($enrolment['mode'] ?? '') === CALENDAR_MODE_PUSH;

    // What of a TASK they want (#75). Sent alongside the mode because they are
    // chosen on the same screen; absent means "leave it as it was", so an older
    // client that does not know about tasks cannot silently switch them off.
    $wasTaskMode = (string)($enrolment['task_mode'] ?? TASK_CAL_OFF);
    $taskMode    = array_key_exists('task_mode', $_POST) ? (string)$_POST['task_mode'] : $wasTaskMode;
    if (!taskCalendarModeIsValid($taskMode)) {
        echo json_encode(['success' => false, 'error' => 'Unknown task calendar choice']);
        exit;
    }

    // connection_id is only ever SET here, never cleared: switching push off
    // keeps the connection (and a CalDAV sign-in on it) so switching back on
    // is one click.
    $conn->prepare(
        "INSERT INTO calendar_enrolments (analyst_id, mode, task_mode, connection_id)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE mode = VALUES(mode), task_mode = VALUES(task_mode),
                                 connection_id = COALESCE(VALUES(connection_id), connection_id),
                                 last_error = NULL, updated_datetime = UTC_TIMESTAMP()"
    )->execute([$analystId, $mode, $taskMode,
                ($mode === CALENDAR_MODE_PUSH && $target) ? (int)$target['id'] : null]);

    // 🔑 TURNING IT OFF TAKES BACK WHAT WE PUT THERE. Leaving events behind in
    // somebody's calendar that FreeITSM has stopped tracking is the worst of both
    // worlds: they cannot be updated, they cannot be removed by us later, and the
    // person has to delete each one by hand wondering where they came from.
    //
    // A CalDAV sign-in is KEPT when the mode goes off, so switching back on is
    // one click. "Forget my sign-in" is its own action.
    if ($wasPushing && $mode !== CALENDAR_MODE_PUSH) {
        require_once '../../includes/calendar_sync/push.php';
        calendarSyncRemoveAllForAnalyst($conn, $analystId);
    }

    if (!$wasPushing && $mode === CALENDAR_MODE_PUSH) {
        enrolBackfillTickets($conn, $analystId);
    }

    // ⚠️ Also runs when the ticket mode changed, because task events only exist
    // while mode is 'push' — turning tickets off has to take tasks with them.
    if ($taskMode !== $wasTaskMode || $wasPushing !== ($mode === CALENDAR_MODE_PUSH)) {
        enrolReconcileTasks($conn, $analystId);
    }

    $after = calendarSyncEnrolment($conn, $analystId);
    echo json_encode(['success' => true, 'mode' => $mode, 'task_mode' => $taskMode,
                      'address' => $after['calendar_address'],
                      'connection_id' => $after['connection_id'] ? (int)$after['connection_id'] : null]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
