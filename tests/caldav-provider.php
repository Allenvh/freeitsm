<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * CalDAV calendar sync — the provider against a real server (#133).
 *
 * Needs the Baikal fixture, seeded:
 *   docker compose -f docker/carddav-test/docker-compose.yml up -d
 *   bash docker/carddav-test/seed.sh
 *
 * Touches no database. Everything it writes is a FreeITSM-named event in the
 * throwaway `itsm` and `tech2` calendars, and it deletes what it made.
 *
 * The cases that matter, and why:
 *   - an edit keeps what the analyst added (a reminder) - the rule this
 *     provider exists to keep;
 *   - a change token that has gone stale gives a BASELINE, never "everything
 *     was deleted";
 *   - a calendar address on another host is refused before any request - the
 *     per-analyst address must not become a way to reach anywhere;
 *   - the analyst's own appointments are never reported, only ours.
 *
 * Run:  php tests/caldav-provider.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/calendar_sync/calendar_sync.php';
require_once __DIR__ . '/../includes/calendar_sync/CalDavCalendarProvider.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail !== '' ? "  <- {$detail}" : '') . "\n"; }
}

$base = getenv('CALDAV_TEST_BASE') ?: 'http://localhost:8092/dav.php/';
$cal  = $base . 'calendars/itsm/work/';
$connection = ['id' => 0, 'provider' => 'caldav', 'credentials' => ['server_url' => $base, 'auth' => 'auto']];
$tz = date_default_timezone_get();

$p  = calendarSyncProviderFor($connection)->withAccount(['username' => 'itsm', 'password' => 'itsm']);
$p2 = calendarSyncProviderFor($connection)->withAccount(['username' => 'tech2', 'password' => 'tech2']);
/** @var CalDavCalendarProvider $p */
/** @var CalDavCalendarProvider $p2 */

echo "\nCalDAV provider against {$base}\n" . str_repeat('=', 64) . "\n";

// ── Pure: reading times back ────────────────────────────────────────────────
echo "\nReading event times:\n";
$t = CalDavCalendarProvider::eventTimes("BEGIN:VCALENDAR\r\nBEGIN:VEVENT\r\nDTSTART;TZID=America/New_York:20260920T090000\r\nDTEND;TZID=America/New_York:20260920T100000\r\nEND:VEVENT\r\nEND:VCALENDAR", 'Europe/London');
ok('a named zone converts to FreeITSM\'s wall clock', $t && $t['start'] === '2026-09-20 14:00:00' && $t['end'] === '2026-09-20 15:00:00', json_encode($t));
$t = CalDavCalendarProvider::eventTimes("BEGIN:VEVENT\nDTSTART:20260920T090000Z\nDURATION:PT90M\nEND:VEVENT", 'Europe/London');
ok('UTC plus a DURATION gives the end', $t && $t['start'] === '2026-09-20 10:00:00' && $t['end'] === '2026-09-20 11:30:00', json_encode($t));
$t = CalDavCalendarProvider::eventTimes("BEGIN:VEVENT\nDTSTART;VALUE=DATE:20260920\nDTEND;VALUE=DATE:20260921\nEND:VEVENT", 'Europe/London');
ok('a one-day all-day event is 00:00 to 23:59:59 of that day (DTEND is exclusive)', $t && $t['all_day'] && $t['start'] === '2026-09-20 00:00:00' && $t['end'] === '2026-09-20 23:59:59', json_encode($t));
$t = CalDavCalendarProvider::eventTimes("BEGIN:VEVENT\nDTSTART:20260920T090000\nDTEND:20260920T093000\nBEGIN:VALARM\nTRIGGER:-PT15M\nDTSTART:20000101T000000Z\nEND:VALARM\nEND:VEVENT", 'Europe/London');
ok('a floating time is read as local, and an alarm\'s own DTSTART is ignored', $t && $t['start'] === '2026-09-20 09:00:00' && $t['end'] === '2026-09-20 09:30:00', json_encode($t));
$t = CalDavCalendarProvider::eventTimes("BEGIN:VEVENT\nDTSTART;TZID=GMT Standard Time:20260920T090000\nDTEND;TZID=GMT Standard Time:20260920T100000\nEND:VEVENT", 'Europe/London');
ok('a zone name PHP does not know is read as local rather than dropped', $t && $t['start'] === '2026-09-20 09:00:00', json_encode($t));
ok('no VEVENT means no times', CalDavCalendarProvider::eventTimes("BEGIN:VCALENDAR\nEND:VCALENDAR", $tz) === null);

// ── Pure: the origin guard ──────────────────────────────────────────────────
echo "\nOnly the configured server:\n";
ok('same host and port is the same origin', CalDavCalendarProvider::sameOrigin('http://localhost:8092/a/', 'http://localhost:8092/dav.php/'));
ok('a different port is not', !CalDavCalendarProvider::sameOrigin('http://localhost:8093/a/', 'http://localhost:8092/'));
ok('http and https are not', !CalDavCalendarProvider::sameOrigin('https://localhost:8092/', 'http://localhost:8092/'));
ok('https default port equals an explicit :443', CalDavCalendarProvider::sameOrigin('https://x.test/', 'https://x.test:443/y/'));
$threw = '';
try { $p->createEvent('http://169.254.169.254/latest/', ['subject' => 'x', 'start' => '2026-09-20 09:00:00', 'end' => '2026-09-20 10:00:00']); }
catch (Exception $e) { $threw = $e->getMessage(); }
ok('a calendar on another host is refused before any request', stripos($threw, 'not on the calendar server') !== false, $threw);
$threw = '';
try { $p->deleteEvent($cal, '../../../etc/passwd'); } catch (Exception $e) { $threw = $e->getMessage(); }
ok('an event id FreeITSM did not mint is refused', stripos($threw, 'Not a FreeITSM calendar event') !== false, $threw);
$threw = '';
try { calendarSyncProviderFor($connection)->createEvent($cal, ['subject' => 'x', 'start' => '2026-09-20 09:00:00', 'end' => '2026-09-20 10:00:00']); }
catch (Exception $e) { $threw = $e->getMessage(); }
ok('with no sign-in the provider refuses and says why', stripos($threw, 'No calendar sign-in') !== false, $threw);

// ── Live: the server ────────────────────────────────────────────────────────
echo "\nThe server:\n";
try { $p->verifyConnection(); ok('the address is a calendar server', true); }
catch (Exception $e) { ok('the address is a calendar server', false, $e->getMessage()); echo "\n  Is the Baikal fixture running and seeded?\n"; exit(1); }
$bad = calendarSyncProviderFor(['provider' => 'caldav', 'credentials' => ['server_url' => 'http://localhost:8092/definitely-not-dav/']]);
$threw = '';
try { $bad->verifyConnection(); } catch (Exception $e) { $threw = $e->getMessage(); }
ok('a web page that is not DAV is reported, not accepted', $threw !== '', 'no error');

$found = $p->discoverCalendars();
$names = array_column($found['calendars'], 'name');
ok('discovery finds the Work calendar', $found['ok'] && in_array('Work', $names, true), json_encode($found));
ok('a tasks-only calendar is left out', !in_array('To-do', $names, true), implode(', ', $names));
ok('discovery reports the auth scheme the server offered', $found['auth'] !== '', $found['auth']);
$wrong = calendarSyncProviderFor($connection)->withAccount(['username' => 'itsm', 'password' => 'nope'])->discoverCalendars();
ok('a wrong password is a failure with a reason', !$wrong['ok'] && $wrong['error'] !== '', json_encode($wrong));
ok('the Work calendar is writable', $p->checkCalendar($cal)['ok']);
ok('the To-do calendar is refused as holding no events', !$p->checkCalendar($base . 'calendars/itsm/todo/')['ok']);
ok('another user\'s calendar is not reachable with my sign-in', !$p->checkCalendar($base . 'calendars/tech2/work/')['ok']);

// ── Live: write, poll, edit, delete ─────────────────────────────────────────
echo "\nThe round trip:\n";
$baseline = $p->pollChanges($cal, null);
ok('no token gives a baseline with a token', $baseline['baseline'] && $baseline['token'] !== null, json_encode($baseline));

$event = ['subject' => 'TICKET-0001 - Test', 'body' => "Requester: A\nStatus: Open", 'url' => 'https://itsm.test/tickets/?ticket_id=1',
          'start' => '2026-09-21 14:00:00', 'end' => '2026-09-21 15:30:00', 'all_day' => false, 'timezone' => $tz];
$id = $p->createEvent($cal, $event);
ok('create returns a FreeITSM id', (bool)preg_match(CalDavCalendarProvider::ID_PATTERN, $id), $id);

// Somebody's own appointment in the same calendar - must never be reported.
$mine = 'personal-' . bin2hex(random_bytes(4));
cardDavRequest(['url' => $cal, 'username' => 'itsm', 'password' => 'itsm', 'auth' => 'auto'], 'PUT', $cal . $mine . '.ics',
    "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:test\r\nBEGIN:VEVENT\r\nUID:$mine\r\nDTSTAMP:20260916T000000Z\r\nDTSTART:20260922T090000Z\r\nDTEND:20260922T100000Z\r\nSUMMARY:Dentist\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n",
    ['Content-Type: text/calendar']);

$r1 = $p->pollChanges($cal, $baseline['token']);
$ids = array_column($r1['changed'], 'remote_event_id');
ok('our new event is reported as changed', !$r1['baseline'] && in_array($id, $ids, true), json_encode($r1));
ok('the analyst\'s own appointment is not', count($ids) === 1, json_encode($ids));
$c = $r1['changed'][0] ?? [];
ok('its times come back exactly as written', ($c['start'] ?? '') === '2026-09-21 14:00:00' && ($c['end'] ?? '') === '2026-09-21 15:30:00', json_encode($c));

// The analyst adds a reminder in their own client.
$url = $cal . $id . '.ics';
$acct = ['url' => $cal, 'username' => 'itsm', 'password' => 'itsm', 'auth' => 'auto'];
$got = cardDavRequest($acct, 'GET', $url);
$withAlarm = str_replace("END:VEVENT", "BEGIN:VALARM\r\nACTION:DISPLAY\r\nDESCRIPTION:Leave now\r\nTRIGGER:-PT15M\r\nEND:VALARM\r\nX-ANALYST-NOTE:bring the charger\r\nEND:VEVENT", $got['body']);
cardDavRequest($acct, 'PUT', $url, $withAlarm, ['Content-Type: text/calendar', 'If-Match: ' . ($got['headers']['etag'] ?? '*')]);

$p->updateEvent($cal, $id, ['subject' => 'TICKET-0001 - Renamed', 'start' => '2026-09-21 16:00:00', 'end' => '2026-09-21 17:00:00'] + $event);
$after = cardDavRequest($acct, 'GET', $url)['body'];
ok('the update moved the event', str_contains($after, 'Renamed') && CalDavCalendarProvider::eventTimes($after, $tz)['start'] === '2026-09-21 16:00:00', $after);
ok('the reminder the analyst added survived', str_contains($after, 'TRIGGER:-PT15M') && str_contains($after, 'DESCRIPTION:Leave now'), $after);
ok('their own property survived', str_contains($after, 'X-ANALYST-NOTE:bring the charger'));
ok('there is exactly one DTSTART at event level and the old summary is gone',
   substr_count($after, 'SUMMARY:') === 1 && !str_contains($after, 'TICKET-0001 - Test'), $after);

$r2 = $p->pollChanges($cal, $r1['token']);
ok('the edit is reported with its new time', ($r2['changed'][0]['start'] ?? '') === '2026-09-21 16:00:00', json_encode($r2));

$p->updateEvent($cal, $id, ['start' => '2026-09-23 00:00:00', 'end' => '2026-09-23 23:59:59', 'all_day' => true] + $event);
$r3 = $p->pollChanges($cal, $r2['token']);
ok('an all-day event round-trips as one day', ($r3['changed'][0]['all_day'] ?? false) === true
   && ($r3['changed'][0]['start'] ?? '') === '2026-09-23 00:00:00' && ($r3['changed'][0]['end'] ?? '') === '2026-09-23 23:59:59', json_encode($r3));

// Deleted in their calendar -> reported as removed, and update says "missing".
cardDavRequest($acct, 'DELETE', $url);
$r4 = $p->pollChanges($cal, $r3['token']);
ok('a deletion is reported as removed', in_array($id, $r4['removed'], true), json_encode($r4));
$missing = false;
try { $p->updateEvent($cal, $id, $event); } catch (CalendarEventMissing $e) { $missing = true; } catch (Exception $e) {}
ok('updating a deleted event says it is missing, so the caller recreates it', $missing);
$gone = true;
try { $p->deleteEvent($cal, $id); } catch (Exception $e) { $gone = false; }
ok('deleting an already-deleted event is a success', $gone);

// A token the server no longer knows -> baseline, never mass deletion.
$stale = $p->pollChanges($cal, 'sync:garbage-token');
ok('a token the server refuses gives a baseline, not deletions', $stale['baseline'] && !$stale['removed'], json_encode($stale));
// 🔴 sabre/dav does NOT refuse these - it answers "no changes" for ever. This
// is what FreeITSM holds after the calendar server is restored from a backup.
$future = $p->pollChanges($cal, 'sync:http://sabre.io/ns/sync/999999');
ok('a token from the future (server restored from backup) gives a baseline', $future['baseline'] && !$future['removed'], json_encode($future));
$forged = $p->pollChanges($cal, 'sync:http://sabre.io/ns/sync/not-a-token');
ok('a malformed token in the server\'s own format gives a baseline', $forged['baseline'], json_encode($forged));
$quiet = $p->pollChanges($cal, $future['token']);
ok('positive control: a current token with nothing new is NOT a baseline', !$quiet['baseline'] && !$quiet['changed'] && !$quiet['removed'], json_encode($quiet));
$junk = $p->pollChanges($cal, 'https://graph.microsoft.com/v1.0/users/x/calendarView/delta?$deltatoken=abc');
ok('a Microsoft delta link left from before a switch gives a baseline', $junk['baseline'], json_encode($junk));

// The fallback for servers without sync-collection.
$e1 = 'etags:' . json_encode([]);
$id2 = $p->createEvent($cal, $event);
$r5 = $p->pollChanges($cal, $e1);
ok('the ETag fallback reports a new event', !$r5['baseline'] && in_array($id2, array_column($r5['changed'], 'remote_event_id'), true), json_encode($r5));
$p->deleteEvent($cal, $id2);
$r6 = $p->pollChanges($cal, $r5['token']);
ok('the ETag fallback reports a deletion', in_array($id2, $r6['removed'], true), json_encode($r6));

// Two accounts: each writes only into their own.
$id3 = $p2->createEvent($base . 'calendars/tech2/work/', $event);
ok('a second analyst writes into their own calendar', (bool)$id3);
$threw = false;
try { $p->deleteEvent($base . 'calendars/tech2/work/', $id3); } catch (Exception $e) { $threw = true; }
ok('the first analyst cannot delete it', $threw);
$p2->deleteEvent($base . 'calendars/tech2/work/', $id3);

cardDavRequest($acct, 'DELETE', $cal . $mine . '.ics');

echo "\n" . str_repeat('=', 64) . "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
