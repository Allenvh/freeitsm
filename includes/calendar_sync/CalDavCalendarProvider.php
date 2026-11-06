<?php
/**
 * CalDavCalendarProvider — scheduled work into an analyst's calendar on any
 * standard CalDAV server: Baikal, Nextcloud, Radicale, SOGo, Fastmail, iCloud.
 *
 * Asked for in https://github.com/edmozley/freeitsm/issues/133 by an operator
 * running his own sabre/dav server. The Microsoft route (GH #75) already did
 * this for Exchange; this is the same feature without the vendor.
 *
 * ──────────────────────────────────────────────────────────────────────────
 * 🔑 IT SIGNS IN AS THE ANALYST. Microsoft lets one administrator-approved app
 * write into every mailbox, so that provider is built from the connection
 * alone. A CalDAV server has no equivalent - each calendar belongs to its
 * owner's account - so every call here runs with the credentials the analyst
 * saved under Preferences, handed over through withAccount(). The connection
 * row holds only the server address and the auth scheme.
 *
 * That changes who may choose the calendar, too. With Microsoft an analyst
 * could never set their own address, because the app could write to anybody's.
 * Here the analyst's own sign-in is the limit, so choosing their own calendar
 * is safe - with one guard: the calendar must be on the configured server
 * (sameOrigin()), so a saved address cannot turn FreeITSM into a tool for
 * sending requests to anywhere else on the network.
 *
 * 🔴 NEVER REGENERATE AN EVENT. An analyst may add a reminder, a note or a
 * colour to one of these in their own client. updateEvent() therefore edits
 * the lines FreeITSM owns in the calendar data the server sent back and leaves
 * every other byte alone - the same rule the CardDAV write-back follows.
 *
 * 🔴 AUTH IS NEGOTIATED. A stock Baikal wants Digest and answers Basic with a
 * 401 that looks exactly like a wrong password. The transport is the CardDAV
 * one (cardDavRequest), which negotiates for exactly that reason.
 * ──────────────────────────────────────────────────────────────────────────
 *
 * Event identity: FreeITSM names every event it creates `freeitsm-<hex>`, used
 * both as the iCalendar UID and as the resource name (`freeitsm-<hex>.ics`).
 * That is the remote_event_id. It is also how the poll tells our events from
 * the analyst's own appointments without fetching any of theirs.
 */

require_once __DIR__ . '/CalendarSyncProvider.php';
require_once __DIR__ . '/../carddav.php';
require_once __DIR__ . '/../ics.php';

class CalDavCalendarProvider extends CalendarSyncProvider
{
    const ID_PATTERN = '/^freeitsm-[a-f0-9]{16,64}$/';

    /** Hrefs fetched per calendar-multiget when reading changes back. */
    const MULTIGET_BATCH = 50;

    /**
     * Properties FreeITSM writes and therefore replaces on an update. Anything
     * else on the event - alarms, attendees the analyst added, categories,
     * colour, their own notes in X- properties - is theirs and is kept.
     */
    const OWNED = ['DTSTART', 'DTEND', 'DURATION', 'SUMMARY', 'DESCRIPTION', 'URL', 'DTSTAMP', 'LAST-MODIFIED'];

    /**
     * CalDAV supports all-day events, checking a target calendar, and per-analyst accounts.
     *
     * @param string $capability  a CalendarSyncProvider::CAP_* key - true for all_day, verify_target and per_analyst
     */
    public function supports(string $capability): bool
    {
        return in_array($capability, [
            self::CAP_ALL_DAY,
            self::CAP_VERIFY_TARGET,
            self::CAP_PER_ANALYST,
        ], true);
    }

    // ------------------------------------------------------------ plumbing

    /** The configured server, always ending in a slash. */
    public function serverUrl(): string
    {
        $u = trim((string)($this->connection['credentials']['server_url'] ?? ''));
        return $u === '' ? '' : rtrim($u, '/') . '/';
    }

    /** Returns the configured HTTP auth mode - 'auto', 'digest' or 'basic' (anything else reads as 'auto'). */
    private function authMode(): string
    {
        $a = (string)($this->connection['credentials']['auth'] ?? 'auto');
        return in_array($a, ['auto', 'digest', 'basic'], true) ? $a : 'auto';
    }

    /**
     * Request settings for cardDavRequest(), signed in as the current analyst.
     *
     * @param string $url  the absolute URL the request goes to, copied into the settings
     * @param bool $interactive short timeouts - true for anything that runs
     *                          inside somebody's Save, false for the cron
     */
    private function cfg(string $url, bool $interactive = true): array
    {
        if (($this->account['username'] ?? '') === '') {
            throw new Exception('No calendar sign-in has been saved for this analyst. They can add one under Preferences, My work calendar.');
        }
        return [
            'url'         => $url,
            'username'    => (string)$this->account['username'],
            'password'    => (string)($this->account['password'] ?? ''),
            'auth'        => $this->authMode(),
            'interactive' => $interactive,
        ];
    }

    /**
     * Same scheme, host and port.
     *
     * 🔴 The guard that keeps a per-analyst calendar address from becoming a way
     * to make FreeITSM send authenticated requests to any host an analyst names
     * - the metadata service, an internal admin page. The address must sit on
     * the server an administrator configured.
     *
     * @param string $a  the first absolute URL (untrusted - may be an analyst's saved address)
     * @param string $b  the second absolute URL - a missing port is read as 443 for https, 80 otherwise
     */
    public static function sameOrigin(string $a, string $b): bool
    {
        $pa = parse_url($a);
        $pb = parse_url($b);
        if (!$pa || !$pb || empty($pa['host']) || empty($pb['host'])) return false;
        $sa = strtolower($pa['scheme'] ?? '');
        $sb = strtolower($pb['scheme'] ?? '');
        $port = fn($p, $s) => (int)($p['port'] ?? ($s === 'https' ? 443 : 80));
        return $sa === $sb
            && in_array($sa, ['http', 'https'], true)
            && strtolower($pa['host']) === strtolower($pb['host'])
            && $port($pa, $sa) === $port($pb, $sb);
    }

    /**
     * A calendar address, checked and normalised to end in a slash.
     *
     * @param string $address  the calendar address as saved under Preferences (untrusted); must be on the configured server
     */
    public function calendarUrl(string $address): string
    {
        $url = rtrim(trim($address), '/') . '/';
        if (!preg_match('#^https?://#i', $url)) {
            throw new Exception('The calendar address is not a web address. Choose the calendar again under Preferences.');
        }
        if ($this->serverUrl() === '' || !self::sameOrigin($url, $this->serverUrl())) {
            throw new Exception('That calendar is not on the calendar server this system is set up for.');
        }
        return $url;
    }

    /**
     * The URL of one of our events. Refuses any id FreeITSM did not mint.
     *
     * @param string $calendarAddress  the analyst's calendar address, checked by calendarUrl()
     * @param string $remoteEventId  the event's 'freeitsm-<hex>' id; any other shape throws
     */
    private function eventUrl(string $calendarAddress, string $remoteEventId): string
    {
        if (!preg_match(self::ID_PATTERN, $remoteEventId)) {
            throw new Exception('Not a FreeITSM calendar event: ' . substr($remoteEventId, 0, 60));
        }
        return $this->calendarUrl($calendarAddress) . $remoteEventId . '.ics';
    }

    /**
     * Parse a DAV XML body, or null. External entities are never loaded.
     *
     * @param string $xml  a response body from the DAV server; blank or unparseable gives null
     */
    private static function xpath(string $xml): ?DOMXPath
    {
        if (trim($xml) === '') return null;
        $prev = libxml_use_internal_errors(true);
        $doc  = new DOMDocument();
        $ok   = $doc->loadXML($xml, LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        if (!$ok) return null;
        $xp = new DOMXPath($doc);
        $xp->registerNamespace('d', 'DAV:');
        $xp->registerNamespace('c', 'urn:ietf:params:xml:ns:caldav');
        return $xp;
    }

    /**
     * Returns the trimmed text of the first node an XPath query matches (optionally inside $ctx), or ''.
     *
     * @param DOMXPath $xp  the parsed document from xpath()
     * @param string $query  an XPath query using the d: (DAV) and c: (CalDAV) prefixes
     * @param ?DOMNode $ctx  the node a relative query runs inside (one d:response); null searches the whole document
     */
    private static function text(DOMXPath $xp, string $query, ?DOMNode $ctx = null): string
    {
        $n = $ctx ? $xp->query($query, $ctx) : $xp->query($query);
        return ($n && $n->length) ? trim($n->item(0)->textContent) : '';
    }

    // ---------------------------------------------------------- the event

    /**
     * One whole VCALENDAR holding our event. No METHOD: RFC 4791 forbids it in a stored object.
     *
     * @param string $uid  the 'freeitsm-<hex>' id, written as the iCalendar UID
     * @param array $event  the canonical event shape (see CalendarSyncProvider)
     */
    private function buildCalendar(string $uid, array $event): string
    {
        $lines = array_merge(
            ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//FreeITSM//Calendar sync//EN', 'CALSCALE:GREGORIAN'],
            $this->eventLines($uid, $event),
            ['END:VCALENDAR']
        );
        return implode("\r\n", $lines) . "\r\n";
    }

    /**
     * BEGIN:VEVENT … END:VEVENT for the canonical event (see CalendarSyncProvider).
     *
     * @param string $uid  the 'freeitsm-<hex>' id, written as the iCalendar UID
     * @param array $event  the canonical event shape (see CalendarSyncProvider); 'timezone' defaults to PHP's zone
     */
    private function eventLines(string $uid, array $event): array
    {
        $lines = icsEvent([
            'uid'         => $uid,
            'summary'     => (string)($event['subject'] ?? ''),
            'description' => (string)($event['body'] ?? ''),
            'url'         => (string)($event['url'] ?? ''),
            'start'       => (string)$event['start'],
            'end'         => (string)($event['end'] ?? $event['start']),
            'all_day'     => !empty($event['all_day']),
        ], (string)(($event['timezone'] ?? '') ?: date_default_timezone_get()));
        if (!$lines) {
            throw new Exception('The scheduled time could not be read, so no calendar event was written.');
        }
        return $lines;
    }

    /**
     * RFC 5545 unfolding: a line starting with a space or tab continues the one before.
     *
     * @param string $ics  raw iCalendar text, CRLF or LF line endings
     */
    public static function unfold(string $ics): array
    {
        $ics = preg_replace("/\r\n[ \t]|\n[ \t]/", '', $ics);
        return preg_split("/\r\n|\n|\r/", rtrim($ics, "\r\n"));
    }

    /**
     * The property name of a content line, upper-cased: "DTSTART;TZID=…:x" -> "DTSTART".
     *
     * @param string $line  one unfolded content line
     */
    private static function propName(string $line): string
    {
        return strtoupper((string)preg_split('/[;:]/', $line, 2)[0]);
    }

    /**
     * Replace the lines FreeITSM owns in an event and keep everything else.
     *
     * Only the FIRST VEVENT is touched, and only its own properties - never
     * those of a VALARM inside it, which carry DESCRIPTION and SUMMARY of their
     * own. Returns null when there is no VEVENT to edit.
     *
     * @param string $ics  the event's calendar data as the server sent it back
     * @param array $newEventLines  fresh VEVENT lines from eventLines(); their BEGIN, END and UID lines are ignored
     */
    public static function rewriteEvent(string $ics, array $newEventLines): ?string
    {
        // The new values: everything between BEGIN:VEVENT and END:VEVENT except UID.
        $fresh = [];
        foreach ($newEventLines as $l) {
            $name = self::propName(self::unfold($l)[0]);
            if (in_array($name, ['BEGIN', 'END', 'UID'], true)) continue;
            $fresh[] = $l;
        }

        $out = [];
        $state = 'before';       // before -> inside -> after
        $nested = 0;
        foreach (self::unfold($ics) as $line) {
            if ($line === '') continue;
            $name  = self::propName($line);
            $value = strtoupper(trim((string)(explode(':', $line, 2)[1] ?? '')));

            if ($state === 'before' && $name === 'BEGIN' && $value === 'VEVENT') {
                $state = 'inside';
                $out[] = $line;
                continue;
            }
            if ($state === 'inside') {
                if ($name === 'BEGIN') { $nested++; $out[] = $line; continue; }
                if ($name === 'END' && $nested > 0) { $nested--; $out[] = $line; continue; }
                if ($name === 'END' && $value === 'VEVENT') {
                    foreach ($fresh as $f) $out[] = $f;
                    $out[] = $line;
                    $state = 'after';
                    continue;
                }
                if ($nested === 0 && in_array($name, self::OWNED, true)) continue;   // replaced above
            }
            $out[] = icsFold($line);
        }
        return $state === 'after' ? implode("\r\n", $out) . "\r\n" : null;
    }

    // ------------------------------------------------------------ outbound

    /**
     * PUTs a new event under a random 'freeitsm-...' name with If-None-Match, so nothing existing is overwritten; retries once on a name clash.
     *
     * @param string $calendarAddress  the analyst's calendar address, checked by calendarUrl()
     * @param array $event  the canonical event shape (see CalendarSyncProvider)
     * @return string  the event's uid, kept as the remote id
     */
    public function createEvent(string $calendarAddress, array $event): string
    {
        $calendar = $this->calendarUrl($calendarAddress);
        for ($attempt = 0; $attempt < 2; $attempt++) {
            $uid = 'freeitsm-' . bin2hex(random_bytes(16));
            $res = cardDavRequest($this->cfg($calendar . $uid . '.ics'), 'PUT', $calendar . $uid . '.ics',
                $this->buildCalendar($uid, $event), [
                    'Content-Type: text/calendar; charset=utf-8',
                    // Never overwrite something already there. A random id makes a
                    // collision all but impossible; this makes it harmless too.
                    'If-None-Match: *',
                ]);
            if ($res['ok']) return $uid;
            if ($res['status'] !== 412) {
                throw new Exception('Could not create the calendar event: ' . $this->explain($res));
            }
        }
        throw new Exception('Could not create the calendar event: the server kept reporting that the name was taken.');
    }

    /**
     * Reads the event, replaces only the properties FreeITSM owns and PUTs it back with If-Match, rereading once if it changed meanwhile.
     *
     * Throws CalendarEventMissing when the event was deleted from the calendar, so the caller can create a fresh one.
     *
     * @param string $calendarAddress  the analyst's calendar address, checked by calendarUrl()
     * @param string $remoteEventId  the 'freeitsm-<hex>' id createEvent() returned
     * @param array $event  the canonical event shape (see CalendarSyncProvider)
     */
    public function updateEvent(string $calendarAddress, string $remoteEventId, array $event): void
    {
        $url = $this->eventUrl($calendarAddress, $remoteEventId);

        // Twice at most: a 412 means somebody changed the event between our read
        // and our write, so read it again and apply to what is there now.
        for ($attempt = 0; $attempt < 2; $attempt++) {
            $got = cardDavRequest($this->cfg($url), 'GET', $url);
            if (in_array($got['status'], [404, 410], true)) {
                // Deleted from their own calendar - ordinary. The caller puts a
                // fresh one back.
                throw new CalendarEventMissing('The calendar event no longer exists.');
            }
            if (!$got['ok']) {
                throw new Exception('Could not read the calendar event before updating it: ' . $this->explain($got));
            }

            $body = self::rewriteEvent($got['body'], $this->eventLines($remoteEventId, $event));
            if ($body === null) {
                // Something that is not an event now lives at our address. Replace
                // it with ours rather than guess at what it was meant to be.
                $body = $this->buildCalendar($remoteEventId, $event);
            }

            $headers = ['Content-Type: text/calendar; charset=utf-8'];
            $etag = $got['headers']['etag'] ?? '';
            if ($etag !== '') $headers[] = 'If-Match: ' . $etag;

            $put = cardDavRequest($this->cfg($url), 'PUT', $url, $body, $headers);
            if ($put['ok']) return;
            if (in_array($put['status'], [404, 410], true)) {
                throw new CalendarEventMissing('The calendar event no longer exists.');
            }
            if ($put['status'] !== 412) {
                throw new Exception('Could not update the calendar event: ' . $this->explain($put));
            }
        }
        throw new Exception('Could not update the calendar event: it kept changing on the server while FreeITSM was writing it.');
    }

    /**
     * Deletes the event; one already gone counts as success.
     *
     * @param string $calendarAddress  the analyst's calendar address, checked by calendarUrl()
     * @param string $remoteEventId  the 'freeitsm-<hex>' id createEvent() returned
     */
    public function deleteEvent(string $calendarAddress, string $remoteEventId): void
    {
        $url = $this->eventUrl($calendarAddress, $remoteEventId);
        $res = cardDavRequest($this->cfg($url), 'DELETE', $url);
        // Already gone is success: the desired end state is "not in their calendar".
        if ($res['ok'] || in_array($res['status'], [404, 410], true)) return;
        throw new Exception('Could not remove the calendar event: ' . $this->explain($res));
    }

    // ------------------------------------------------------------- inbound

    /**
     * What changed in this calendar since last time.
     *
     * 🔑 sync-collection (RFC 6578), the standard way to ask a DAV server for
     * changes since a token. It lists hrefs and ETags only, so the events
     * FreeITSM did not create are never fetched, and ours are then read in
     * batches with a calendar-multiget.
     *
     * Servers without sync-collection get the same answer a longer way: a list
     * of our events' ETags, remembered in the token and compared next time.
     *
     * The token is prefixed with the method that produced it (`sync:` or
     * `etags:`), so one is never read as the other, and anything else - a
     * Microsoft delta link left over from before the provider was switched -
     * is simply re-baselined.
     *
     * @param string $calendarAddress  the analyst's calendar address, checked by calendarUrl()
     * @param ?string $token  the token the last poll returned ('sync:...' or 'etags:{json}'); null or '' takes a baseline
     */
    public function pollChanges(string $calendarAddress, ?string $token): array
    {
        return $this->pollChangesOnce($calendarAddress, $token, false);
    }

    /**
     * The calendar's own sync-token right now, or '' when it does not say.
     *
     * @param string $calendar  the calendar URL, already checked by calendarUrl()
     */
    private function currentSyncToken(string $calendar): string
    {
        $res = cardDavRequest($this->cfg($calendar, false), 'PROPFIND', $calendar,
            '<?xml version="1.0" encoding="utf-8"?><d:propfind xmlns:d="DAV:"><d:prop><d:sync-token/></d:prop></d:propfind>',
            ['Depth: 0', 'Content-Type: application/xml; charset=utf-8']);
        $xp = $res['ok'] ? self::xpath($res['body']) : null;
        return $xp ? self::text($xp, '//d:sync-token') : '';
    }

    /**
     * One pass of pollChanges() - sync-collection when the server supports it,
     * otherwise a comparison of our events' ETags - returning token, baseline,
     * changed and removed.
     *
     * @param string $calendarAddress  the analyst's calendar address, checked by calendarUrl()
     * @param ?string $token  the token the last poll returned ('sync:...' or 'etags:{json}'); null or '' takes a baseline
     * @param bool $retried true on the second look after a suspicious "no changes"
     */
    private function pollChangesOnce(string $calendarAddress, ?string $token, bool $retried): array
    {
        $calendar = $this->calendarUrl($calendarAddress);
        $out = ['token' => null, 'baseline' => $token === null || $token === '', 'changed' => [], 'removed' => []];

        $syncToken = null;
        if (!$out['baseline']) {
            if (str_starts_with($token, 'sync:'))       $syncToken = substr($token, 5);
            elseif (!str_starts_with($token, 'etags:'))  return $this->pollChanges($calendarAddress, null);
        }

        // ── sync-collection ────────────────────────────────────────────────
        if ($out['baseline'] || $syncToken !== null) {
            $res = cardDavRequest($this->cfg($calendar, false), 'REPORT', $calendar,
                '<?xml version="1.0" encoding="utf-8"?>'
                . '<d:sync-collection xmlns:d="DAV:">'
                . '<d:sync-token>' . htmlspecialchars((string)$syncToken, ENT_XML1) . '</d:sync-token>'
                . '<d:sync-level>1</d:sync-level>'
                . '<d:prop><d:getetag/></d:prop>'
                . '</d:sync-collection>',
                ['Depth: 1', 'Content-Type: application/xml; charset=utf-8']);

            // A token the server no longer recognises. Start again WITHOUT one:
            // the result is a baseline, so the caller applies nothing. This is
            // the case that must never read as "every event was deleted".
            if ($syncToken !== null && (in_array($res['status'], [403, 409, 412], true)
                    || str_contains($res['body'], 'valid-sync-token'))) {
                return $this->pollChanges($calendarAddress, null);
            }

            $xp = $res['ok'] ? self::xpath($res['body']) : null;
            $newToken = $xp ? self::text($xp, '/d:multistatus/d:sync-token') : '';

            if ($xp && $newToken !== '') {
                $out['token'] = 'sync:' . $newToken;
                if ($out['baseline']) return $out;

                // 🔴 A TOKEN FROM THE FUTURE IS NOT REFUSED. sabre/dav (Baikal,
                // Nextcloud) answers a token it never issued - which is what we
                // hold after the calendar server is restored from a backup - with
                // "no changes" and the same token back, for ever. Inbound sync
                // would then stop without a single error. So when nothing changed
                // and our own token came back, check it against where the calendar
                // actually is; if they differ, ask once more (a change may have
                // landed in between), and if the answer is still "nothing", start
                // again from a baseline.
                if ($xp->query('/d:multistatus/d:response')->length === 0 && $newToken === $syncToken) {
                    $current = $this->currentSyncToken($calendar);
                    if ($current !== '' && $current !== $syncToken) {
                        if (!$retried) {
                            return $this->pollChangesOnce($calendarAddress, $token, true);
                        }
                        return $this->pollChanges($calendarAddress, null);
                    }
                }

                $changedHrefs = [];
                foreach ($xp->query('/d:multistatus/d:response') as $resp) {
                    $href = self::text($xp, './d:href', $resp);
                    $id   = self::idFromHref($href);
                    if ($id === null) continue;                       // not ours
                    $status = self::text($xp, './d:status', $resp);
                    if (str_contains($status, ' 404') || str_contains($status, ' 410')) {
                        $out['removed'][] = $id;
                    } else {
                        $changedHrefs[$id] = $href;
                    }
                }
                $out['changed'] = $this->fetchChanges($calendar, $changedHrefs);
                return $out;
            }

            // Not supported (or not understood). A server that answered with a
            // hard error on the calendar itself is a real failure, not a reason to
            // fall back.
            if (in_array($res['status'], [0, 401, 404], true)) {
                throw new Exception('Could not read changes from the calendar: ' . $this->explain($res));
            }
        }

        // ── fallback: compare our events' ETags ────────────────────────────
        $res = cardDavRequest($this->cfg($calendar, false), 'PROPFIND', $calendar,
            '<?xml version="1.0" encoding="utf-8"?><d:propfind xmlns:d="DAV:"><d:prop><d:getetag/></d:prop></d:propfind>',
            ['Depth: 1', 'Content-Type: application/xml; charset=utf-8']);
        $xp = $res['ok'] ? self::xpath($res['body']) : null;
        if (!$xp) {
            throw new Exception('Could not read changes from the calendar: ' . $this->explain($res));
        }
        $now = [];
        $hrefs = [];
        foreach ($xp->query('/d:multistatus/d:response') as $resp) {
            $href = self::text($xp, './d:href', $resp);
            $id   = self::idFromHref($href);
            if ($id === null) continue;
            $now[$id]   = self::text($xp, './/d:getetag', $resp);
            $hrefs[$id] = $href;
        }
        $out['token'] = 'etags:' . json_encode($now);
        if ($out['baseline'] || !str_starts_with((string)$token, 'etags:')) {
            $out['baseline'] = true;
            return $out;
        }

        $was = json_decode(substr($token, 6), true);
        if (!is_array($was)) { $out['baseline'] = true; return $out; }
        $changed = [];
        foreach ($now as $id => $etag) {
            if (!isset($was[$id]) || $was[$id] !== $etag) $changed[$id] = $hrefs[$id];
        }
        foreach ($was as $id => $etag) {
            if (!isset($now[$id])) $out['removed'][] = (string)$id;
        }
        $out['changed'] = $this->fetchChanges($calendar, $changed);
        return $out;
    }

    /**
     * The event id in an href, or null when the href is not one of ours.
     *
     * @param string $href  a d:href from a multistatus response, absolute or server-relative
     */
    private static function idFromHref(string $href): ?string
    {
        $name = rawurldecode(basename(rtrim($href, '/')));
        if (substr($name, -4) !== '.ics') return null;
        $id = substr($name, 0, -4);
        return preg_match(self::ID_PATTERN, $id) ? $id : null;
    }

    /**
     * Read the new times of changed events, in batches.
     *
     * @param string $calendar  the calendar URL, already checked by calendarUrl()
     * @param array $hrefs id => href as the server reported it
     * @return array the 'changed' shape pollChanges() returns
     */
    private function fetchChanges(string $calendar, array $hrefs): array
    {
        $out = [];
        $tz  = date_default_timezone_get();
        foreach (array_chunk($hrefs, self::MULTIGET_BATCH, true) as $chunk) {
            $body = '<?xml version="1.0" encoding="utf-8"?>'
                  . '<c:calendar-multiget xmlns:d="DAV:" xmlns:c="urn:ietf:params:xml:ns:caldav">'
                  . '<d:prop><d:getetag/><c:calendar-data/></d:prop>';
            foreach ($chunk as $href) $body .= '<d:href>' . htmlspecialchars($href, ENT_XML1) . '</d:href>';
            $body .= '</c:calendar-multiget>';

            $res = cardDavRequest($this->cfg($calendar, false), 'REPORT', $calendar, $body,
                ['Depth: 1', 'Content-Type: application/xml; charset=utf-8']);
            $xp = $res['ok'] ? self::xpath($res['body']) : null;
            if (!$xp) {
                throw new Exception('Could not read the changed calendar events: ' . $this->explain($res));
            }
            foreach ($xp->query('/d:multistatus/d:response') as $resp) {
                $id = self::idFromHref(self::text($xp, './d:href', $resp));
                if ($id === null) continue;
                $data = self::text($xp, './/c:calendar-data', $resp);
                if ($data === '') continue;             // removed between the two calls
                $times = self::eventTimes($data, $tz);
                if ($times === null) continue;
                $out[] = ['remote_event_id' => $id] + $times;
            }
        }
        return $out;
    }

    /**
     * The start, end and all-day flag of the first VEVENT, as FreeITSM stores
     * them: naive wall clock in $tz, all-day as 00:00 to 23:59:59 of the last day.
     *
     * @param string $ics  one event's calendar data as the server sent it
     * @param string $tz  the PHP zone name the times are converted into (FreeITSM's own zone)
     * @return array|null ['start'=>…, 'end'=>…, 'all_day'=>bool]
     */
    public static function eventTimes(string $ics, string $tz): ?array
    {
        $props = [];
        $state = 'before';
        $nested = 0;
        foreach (self::unfold($ics) as $line) {
            $name  = self::propName($line);
            $value = strtoupper(trim((string)(explode(':', $line, 2)[1] ?? '')));
            if ($state === 'before') {
                if ($name === 'BEGIN' && $value === 'VEVENT') $state = 'inside';
                continue;
            }
            if ($name === 'BEGIN') { $nested++; continue; }
            if ($name === 'END') {
                if ($nested > 0) { $nested--; continue; }
                break;                                   // END:VEVENT
            }
            if ($nested === 0 && in_array($name, ['DTSTART', 'DTEND', 'DURATION'], true) && !isset($props[$name])) {
                $props[$name] = $line;
            }
        }
        if (empty($props['DTSTART'])) return null;

        $start = self::parseDate($props['DTSTART'], $tz);
        if ($start === null) return null;
        $allDay = $start['date_only'];

        $end = !empty($props['DTEND']) ? self::parseDate($props['DTEND'], $tz) : null;
        if ($end === null && !empty($props['DURATION'])) {
            try {
                $spec = trim((string)explode(':', $props['DURATION'], 2)[1]);
                $neg  = str_starts_with($spec, '-');
                $iv   = new DateInterval(ltrim($spec, '+-'));
                $dt   = clone $start['dt'];
                $neg ? $dt->sub($iv) : $dt->add($iv);
                $end = ['dt' => $dt, 'date_only' => $allDay];
            } catch (Exception) {
                $end = null;
            }
        }

        if ($allDay) {
            // DTEND is EXCLUSIVE for a date: the day after the last day.
            $last = $end ? (clone $end['dt'])->modify('-1 day') : clone $start['dt'];
            if ($last < $start['dt']) $last = clone $start['dt'];
            return [
                'start'   => $start['dt']->format('Y-m-d') . ' 00:00:00',
                'end'     => $last->format('Y-m-d') . ' 23:59:59',
                'all_day' => true,
            ];
        }
        return [
            'start'   => $start['dt']->format('Y-m-d H:i:s'),
            'end'     => ($end ? $end['dt'] : $start['dt'])->format('Y-m-d H:i:s'),
            'all_day' => false,
        ];
    }

    /**
     * One DTSTART/DTEND line, converted to $tz.
     *
     * Three shapes are legal: UTC (a trailing Z), a named zone (TZID=…), and
     * "floating" (neither), which means local time wherever you are - read here
     * as FreeITSM's own zone. A date on its own (VALUE=DATE, or eight digits) is
     * an all-day value and is not converted, because a date has no zone.
     *
     * ⚠️ A zone name PHP does not know - Outlook writes Windows names such as
     * "GMT Standard Time" - is read as FreeITSM's zone rather than rejected. An
     * event that lands an hour out is visible and fixable; a change that is
     * silently dropped is neither.
     *
     * @param string $line  one unfolded DTSTART or DTEND line, parameters included
     * @param string $tz  the PHP zone name to convert into; also how floating times and unknown TZIDs are read
     */
    private static function parseDate(string $line, string $tz): ?array
    {
        [$head, $value] = array_pad(explode(':', $line, 2), 2, '');
        $value = trim($value);
        $params = [];
        foreach (array_slice(explode(';', $head), 1) as $p) {
            [$k, $v] = array_pad(explode('=', $p, 2), 2, '');
            $params[strtoupper($k)] = trim($v, '"');
        }
        $local = new DateTimeZone($tz);

        if (strtoupper($params['VALUE'] ?? '') === 'DATE' || preg_match('/^\d{8}$/', $value)) {
            $dt = DateTime::createFromFormat('!Ymd', substr($value, 0, 8), $local);
            return $dt ? ['dt' => $dt, 'date_only' => true] : null;
        }

        if (!preg_match('/^(\d{8}T\d{6})(Z?)$/i', $value, $m)) return null;
        $zone = $local;
        if ($m[2] !== '') {
            $zone = new DateTimeZone('UTC');
        } elseif (!empty($params['TZID'])) {
            try {
                $zone = new DateTimeZone(ltrim($params['TZID'], '/'));
            } catch (Exception) {
                $zone = $local;
            }
        }
        $dt = DateTime::createFromFormat('Ymd\THis', strtoupper($m[1]), $zone);
        if (!$dt) return null;
        $dt->setTimezone($local);
        return ['dt' => $dt, 'date_only' => false];
    }

    // ----------------------------------------------------------- discovery

    /**
     * Is the configured address a DAV server at all?
     *
     * Asked without signing in, because the connection holds nobody's password.
     * A DAV server answers an anonymous PROPFIND with 401 (it wants a sign-in)
     * or 207 (it lets anyone look); anything else is not a calendar server.
     */
    public function verifyConnection(): void
    {
        $url = $this->serverUrl();
        if ($url === '') throw new Exception('No calendar server address has been saved.');
        $res = cardDavRequest(['url' => $url, 'username' => '', 'password' => '', 'auth' => $this->authMode(), 'interactive' => true],
            'PROPFIND', $url,
            '<?xml version="1.0" encoding="utf-8"?><d:propfind xmlns:d="DAV:"><d:prop><d:resourcetype/></d:prop></d:propfind>',
            ['Depth: 0', 'Content-Type: application/xml; charset=utf-8']);
        if ($res['status'] === 401) return;
        if ($res['status'] === 207 && self::xpath($res['body'])) return;
        throw new Exception($res['status'] === 0 ? $this->explain($res)
            : 'The server answered, but not like a calendar server (HTTP ' . $res['status'] . '). Check the address - for Baikal it ends in /dav.php/.');
    }

    /**
     * True when the signed-in analyst can write events into this calendar (see checkCalendar()).
     *
     * @param string $calendarAddress  the calendar address to test, as the analyst chose it
     */
    public function verifyTarget(string $calendarAddress): bool
    {
        try {
            return $this->checkCalendar($calendarAddress)['ok'];
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Can the signed-in analyst write events into this calendar?
     *
     * @param string $calendarAddress  the calendar address to test, as the analyst chose it
     * @return array ['ok'=>bool, 'name'=>string, 'error'=>string]
     */
    public function checkCalendar(string $calendarAddress): array
    {
        $url = $this->calendarUrl($calendarAddress);
        $res = cardDavRequest($this->cfg($url), 'PROPFIND', $url, self::calendarPropfind(),
            ['Depth: 0', 'Content-Type: application/xml; charset=utf-8']);
        if (!$res['ok']) return ['ok' => false, 'name' => '', 'error' => $this->explain($res)];
        $xp = self::xpath($res['body']);
        $resp = $xp ? $xp->query('/d:multistatus/d:response') : null;
        if (!$resp || !$resp->length) {
            return ['ok' => false, 'name' => '', 'error' => 'The server answered, but not with a calendar.'];
        }
        $cal = self::describeCalendar($xp, $resp->item(0), $url);
        if (!$cal['is_calendar']) return ['ok' => false, 'name' => '', 'error' => 'That address is not a calendar.'];
        if (!$cal['events'])      return ['ok' => false, 'name' => $cal['name'], 'error' => 'That calendar only holds tasks, not events.'];
        if (!$cal['writable'])    return ['ok' => false, 'name' => $cal['name'], 'error' => 'That calendar is read-only for this sign-in.'];
        return ['ok' => true, 'name' => $cal['name'], 'error' => ''];
    }

    /**
     * The calendars this sign-in can write events into.
     *
     * The standard route: the server address tells us who we are
     * (current-user-principal), that tells us where their calendars live
     * (calendar-home-set), and a Depth: 1 listing of that shows them. A server
     * that skips a step is tolerated: with no principal the address itself is
     * treated as the calendar home.
     *
     * @return array ['ok'=>bool, 'calendars'=>[['url','name']], 'auth'=>string, 'error'=>string]
     */
    public function discoverCalendars(): array
    {
        $server = $this->serverUrl();
        $out = ['ok' => false, 'calendars' => [], 'auth' => '', 'error' => ''];
        if ($server === '') { $out['error'] = 'No calendar server address has been saved.'; return $out; }

        $home = null;
        $res = cardDavRequest($this->cfg($server), 'PROPFIND', $server,
            '<?xml version="1.0" encoding="utf-8"?><d:propfind xmlns:d="DAV:" xmlns:c="urn:ietf:params:xml:ns:caldav">'
            . '<d:prop><d:current-user-principal/><c:calendar-home-set/></d:prop></d:propfind>',
            ['Depth: 0', 'Content-Type: application/xml; charset=utf-8']);
        $out['auth'] = $res['auth'];
        if (!$res['ok']) { $out['error'] = $this->explain($res); return $out; }

        $xp = self::xpath($res['body']);
        if ($xp) {
            $home = self::text($xp, '//c:calendar-home-set/d:href') ?: null;
            $principal = self::text($xp, '//d:current-user-principal/d:href');
            if ($home === null && $principal !== '') {
                $pUrl = cardDavAbsoluteUrl($server, $principal);
                if (self::sameOrigin($pUrl, $server)) {
                    $p = cardDavRequest($this->cfg($pUrl), 'PROPFIND', $pUrl,
                        '<?xml version="1.0" encoding="utf-8"?><d:propfind xmlns:d="DAV:" xmlns:c="urn:ietf:params:xml:ns:caldav">'
                        . '<d:prop><c:calendar-home-set/></d:prop></d:propfind>',
                        ['Depth: 0', 'Content-Type: application/xml; charset=utf-8']);
                    $px = $p['ok'] ? self::xpath($p['body']) : null;
                    if ($px) $home = self::text($px, '//c:calendar-home-set/d:href') ?: null;
                }
            }
        }
        $homeUrl = $home !== null ? cardDavAbsoluteUrl($server, $home) : $server;
        if (!self::sameOrigin($homeUrl, $server)) {
            $out['error'] = 'The server said your calendars live on a different server, which this system is not set up for.';
            return $out;
        }
        $homeUrl = rtrim($homeUrl, '/') . '/';

        $list = cardDavRequest($this->cfg($homeUrl), 'PROPFIND', $homeUrl, self::calendarPropfind(),
            ['Depth: 1', 'Content-Type: application/xml; charset=utf-8']);
        if (!$list['ok']) { $out['error'] = $this->explain($list); return $out; }
        $lx = self::xpath($list['body']);
        if (!$lx) { $out['error'] = 'The server answered, but not with a list FreeITSM could read.'; return $out; }

        foreach ($lx->query('/d:multistatus/d:response') as $resp) {
            $href = self::text($lx, './d:href', $resp);
            $url  = rtrim(cardDavAbsoluteUrl($homeUrl, $href), '/') . '/';
            $cal  = self::describeCalendar($lx, $resp, $url);
            if (!$cal['is_calendar'] || !$cal['events'] || !$cal['writable']) continue;
            if (!self::sameOrigin($url, $server)) continue;
            $out['calendars'][] = ['url' => $url, 'name' => $cal['name']];
        }
        $out['ok'] = true;
        return $out;
    }

    /** The PROPFIND body asking a collection for its name, type, supported components and the analyst's privileges. */
    private static function calendarPropfind(): string
    {
        return '<?xml version="1.0" encoding="utf-8"?>'
             . '<d:propfind xmlns:d="DAV:" xmlns:c="urn:ietf:params:xml:ns:caldav">'
             . '<d:prop><d:displayname/><d:resourcetype/><c:supported-calendar-component-set/>'
             . '<d:current-user-privilege-set/></d:prop></d:propfind>';
    }

    /**
     * What one PROPFIND response says about a collection.
     *
     * Missing properties are read generously: a server that does not report
     * its component set is assumed to hold events, and one that does not report
     * privileges is assumed writable - the write itself will say otherwise.
     *
     * @param DOMXPath $xp  the parsed multistatus document
     * @param DOMNode $resp  one d:response element in it
     * @param string $url  the collection's absolute URL - its last path segment is the name when no displayname is given
     */
    private static function describeCalendar(DOMXPath $xp, DOMNode $resp, string $url): array
    {
        $isCal = $xp->query('.//d:resourcetype/c:calendar', $resp);
        $comps = $xp->query('.//c:supported-calendar-component-set/c:comp', $resp);
        $events = true;
        if ($comps && $comps->length) {
            $events = false;
            foreach ($comps as $c) {
                if (strtoupper($c->getAttribute('name')) === 'VEVENT') { $events = true; break; }
            }
        }
        $privs = $xp->query('.//d:current-user-privilege-set/d:privilege/*', $resp);
        $writable = true;
        if ($privs && $privs->length) {
            $writable = false;
            foreach ($privs as $p) {
                if (in_array($p->localName, ['all', 'write', 'write-content', 'bind'], true)) { $writable = true; break; }
            }
        }
        $name = self::text($xp, './/d:displayname', $resp);
        return [
            'is_calendar' => $isCal && $isCal->length > 0,
            'events'      => $events,
            'writable'    => $writable,
            'name'        => $name !== '' ? $name : rawurldecode(basename(rtrim($url, '/'))),
        ];
    }

    /**
     * One readable sentence for a failed request, without calling it a contacts problem.
     *
     * @param array $res  the result of cardDavRequest() - 'status' and 'error' are read
     */
    private function explain(array $res): string
    {
        $status = (int)($res['status'] ?? 0);
        if ($status === 403) return 'The server accepted the sign-in but would not allow this. The account may not have permission to change that calendar.';
        if ($status === 404) return 'Nothing was found at that address. The calendar may have been deleted or renamed - choose it again under Preferences.';
        if ($status === 405) return 'The server would not accept that request at this address, so it may not be a calendar.';
        $msg = (string)($res['error'] ?? '');
        if ($msg === '') $msg = 'HTTP ' . $status;
        // The shared explanations talk about address books; this is a calendar.
        return str_replace(['address book', 'CardDAV'], ['calendar', 'CalDAV'], $msg);
    }
}
