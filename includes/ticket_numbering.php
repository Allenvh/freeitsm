<?php
/**
 * Ticket numbering — the ONE place a ticket number is made or recognised.
 *
 * ⚠️ WHY THIS FILE EXISTS. `generateTicketNumber()` was written THREE times —
 * in includes/services/tickets.php, api/self-service/create_ticket.php and
 * api/tickets/check_mailbox_email.php — and the format was hardcoded a fourth,
 * fifth, sixth and seventh time as the regex `[A-Z]{3}-\d{3}-\d{5}` across four
 * more files. A setting that reached only one of them would have given portal
 * tickets one format and emailed tickets another.
 *
 * 🔴 THE LANDMINE THIS FILE DEFUSES. Every notification FreeITSM has ever sent
 * carries `[SDREF:CKQ-418-73926]` in its subject, and those emails live in
 * customers' inboxes forever. The inbound parser used to look for that exact
 * SHAPE. Change the format and every reply to every historical email silently
 * becomes a new ticket — no error, no warning, across the whole estate at once.
 * Zammad's own documentation carries the same warning about its numbering.
 *
 * 🔑 The fix is a change of principle: **the tag is a delimiter, the DATABASE is
 * the authority.** Capture whatever sits between `SDREF:` and `]`, then look it
 * up. The parser must never encode a format, because there is no longer only
 * one — an install can change format, and every number it ever issued has to go
 * on working.
 *
 * @see docs/design/ticket-numbering.md
 */

require_once __DIR__ . '/../config.php';

class TicketNumbering
{
    /** Every setting, with the default that reproduces today's behaviour. */
    const DEFAULTS = [
        // random = today's CKQ-418-73926. Kept as the default so an upgrade
        // changes nothing until somebody chooses otherwise.
        'ticket_number_style'      => 'random',      // random | sequential
        'ticket_number_format'     => 'TICKET-{######}',
        'ticket_number_start'      => '1',
        // What each counter counts. per_type gives INC-… and REQ-… their own
        // sequences, which is what ServiceNow does and what the request asked for.
        'ticket_number_scope'      => 'global',      // global | per_type | per_company
        'ticket_number_reset'      => 'never',       // never | yearly | monthly
        // Renumbering existing tickets is a MIGRATION tool, not housekeeping.
        'ticket_number_renumber'   => 'never',       // never | once
    ];

    /**
     * The tokens a format may contain.
     *
     * 🔑 `{###…}` is a MINIMUM WIDTH, never a limit. Ticket 1,000,001 under
     * `{######}` simply prints seven digits. A format that broke at its width
     * would need a migration on the day an install got busy, which is exactly
     * the sort of cliff nobody remembers is coming.
     */
    const TOKEN_HELP = [
        '{#}'     => 'the number, padded to as many digits as you write hashes',
        '{YYYY}'  => 'four-digit year',
        '{YY}'    => 'two-digit year',
        '{MM}'    => 'two-digit month',
        '{DD}'    => 'two-digit day',
        '{TYPE}'  => "the ticket type's short code, if it has one",
        '{COMPANY}' => "the company's ticket code, set under System → Companies",
    ];

    // ====================================================================
    //  Settings
    // ====================================================================

    /** @var array|null cached settings; a class property so forget() can clear it */
    private static $cache = null;

    /**
     * The saved numbering settings over the built-in defaults, read once and
     * cached for the request. Without a settings table the defaults are returned.
     *
     * @reads system_settings - the numbering keys named in DEFAULTS
     */
    public static function settings(PDO $conn): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        $cache = self::DEFAULTS;
        try {
            $keys = array_keys(self::DEFAULTS);
            $ph   = implode(',', array_fill(0, count($keys), '?'));
            $stmt = $conn->prepare("SELECT setting_key, setting_value FROM system_settings WHERE setting_key IN ({$ph})");
            $stmt->execute($keys);
            foreach ($stmt->fetchAll(PDO::FETCH_KEY_PAIR) as $k => $v) {
                if ($v !== null && $v !== '') {
                    $cache[$k] = $v;
                }
            }
        } catch (Exception) {
            // No settings table yet — the defaults reproduce today's behaviour.
        }
        self::$cache = $cache;
        return self::$cache;
    }

    /** Forget the cached settings — after a save, and between tests. */
    public static function forget(): void
    {
        self::$cache    = null;
        self::$override = null;
    }

    /** @var array|null test/preview override, bypassing system_settings */
    private static $override = null;

    /**
     * Run with a specific configuration — used by the live preview and tests.
     *
     * @param ?array $settings  numbering settings keyed as in DEFAULTS, laid over the defaults; null goes back to the saved settings
     */
    public static function withSettings(?array $settings): void
    {
        self::$override = $settings;
    }

    /** The settings to number with: a withSettings() override over the defaults when one is set, otherwise settings(). */
    private static function config(PDO $conn): array
    {
        return self::$override !== null
            ? array_merge(self::DEFAULTS, self::$override)
            : self::settings($conn);
    }

    // ====================================================================
    //  Making a number
    // ====================================================================

    /**
     * The next ticket number.
     *
     * @param ?int $ticketTypeId used by the per_type scope and {TYPE}
     * @param ?int $tenantId     used by the per_company scope
     */
    public static function next(PDO $conn, ?int $ticketTypeId = null, ?int $tenantId = null): string
    {
        $cfg = self::config($conn);
        // NULL means the default company, not "no company" — see resolveTenant().
        $tenantId = self::resolveTenant($conn, $tenantId);

        if (($cfg['ticket_number_style'] ?? 'random') === 'random') {
            return self::randomNumber($conn);
        }

        // Uniqueness is proven against the TABLE, never assumed from the counter:
        // two requests can read the same counter, and a renumbered install has
        // gaps. So a claimed number that turns out to be taken is normal.
        //
        // 🔴 WHAT IS NOT NORMAL, AND USED TO BE FATAL: a counter that has fallen
        // BEHIND the estate. Restore a database backup, or renumber 106 tickets
        // while the counter says 50, and every claim collides — one at a time,
        // ten times, and then mail collection stops with "could not generate a
        // unique ticket number". A service desk must not stop accepting email
        // because a counter is out of step, so instead of crawling forward one
        // number per attempt we JUMP, doubling the stride each time. Fifty-seven
        // taken numbers are cleared in six attempts rather than never.
        //
        // Only ever forward: the wind uses GREATEST, so a jump can never hand a
        // lower number to a request that overtakes us.
        $step = 1;
        for ($attempt = 0; $attempt < 40; $attempt++) {
            $seq    = self::claimNext($conn, $cfg, $ticketTypeId, $tenantId);
            $number = self::render($cfg['ticket_number_format'], $seq, $conn, $ticketTypeId, null, $tenantId);
            if (!self::inUse($conn, $number)) {
                return $number;
            }
            self::windCounterTo($conn, self::counterKey($cfg, $ticketTypeId, $tenantId), $seq + $step);
            $step = min($step * 2, 65536);
        }

        throw new Exception(
            'Could not find an unused ticket number. The numbering counter looks far behind the '
            . 'tickets that already exist — check Tickets → Settings → Ticket numbering.'
        );
    }

    /**
     * Move a counter forward to at least $value. Never backwards.
     *
     * Shared by the collision jump above and by applyRenumber(), so there is one
     * statement in the codebase that decides what "wind a counter on" means.
     *
     * @param string $key  the counter (ticket_number_counters.counter_key), from counterKey()
     * @param int $value  the lowest next_value the counter may hold afterwards
     */
    private static function windCounterTo(PDO $conn, string $key, int $value): void
    {
        $conn->prepare(
            "INSERT INTO ticket_number_counters (counter_key, next_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE next_value = GREATEST(next_value, VALUES(next_value))"
        )->execute([$key, $value]);
    }

    /** Today's format: three letters, three digits, five digits. */
    private static function randomNumber(PDO $conn): string
    {
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $letters = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
            $number  = $letters . '-' . rand(100, 999) . '-'
                     . str_pad((string)rand(0, 99999), 5, '0', STR_PAD_LEFT);
            if (!self::inUse($conn, $number)) {
                return $number;
            }
        }
        throw new Exception('Could not generate a unique ticket number after 10 attempts.');
    }

    /**
     * Substitute the tokens.
     *
     * 🔑 `{####}` pads to at least four digits and NEVER truncates — the width
     * is a floor, so an install that outgrows it just gets longer numbers.
     *
     * @param string $format  the number format, e.g. 'INC-{YYYY}-{######}'
     * @param int $seq  the sequence number for the {#...} token
     * @param ?int $ticketTypeId  the ticket type, for {TYPE}; null renders it empty
     * @param ?DateTimeImmutable $at  the date for {YYYY}, {YY}, {MM} and {DD}; null means now (UTC)
     */
    public static function render(string $format, int $seq, ?PDO $conn = null, ?int $ticketTypeId = null, ?DateTimeImmutable $at = null, ?int $tenantId = null): string
    {
        $out = $format;

        // The number token, however many hashes it has.
        $out = preg_replace_callback('/\{(#+)\}/', static function ($m) use ($seq) {
            return str_pad((string)$seq, strlen($m[1]), '0', STR_PAD_LEFT);
        }, $out);

        // $at lets a RENUMBER stamp a ticket with its OWN year rather than this
        // one. A 2024 ticket relabelled INC-2026-00003 would be actively wrong.
        $now = $at ?: new DateTimeImmutable("now", new DateTimeZone("UTC"));
        $out = str_replace(
            ['{YYYY}', '{YY}', '{MM}', '{DD}'],
            [$now->format('Y'), $now->format('y'), $now->format('m'), $now->format('d')],
            $out
        );

        if (str_contains($out, "{TYPE}")) {
            $out = str_replace("{TYPE}", self::typeCode($conn, $ticketTypeId), $out);
        }
        if (str_contains($out, "{COMPANY}")) {
            $out = str_replace("{COMPANY}", self::companyCode($conn, $tenantId), $out);
        }
        return $out;
    }

    /**
     * A short code for a ticket type, for {TYPE}.
     *
     * Derived from the name rather than stored: an install that never uses
     * {TYPE} should not have to fill in a column, and one that does gets
     * something sensible without configuring anything. "Incident" -> INC.
     *
     * @param ?int $ticketTypeId  the ticket type (ticket_types.id); null or 0 gives ''
     */
    private static function typeCode(?PDO $conn, ?int $ticketTypeId): string
    {
        if (!$conn || !$ticketTypeId) {
            return '';
        }
        try {
            $stmt = $conn->prepare("SELECT name FROM ticket_types WHERE id = ?");
            $stmt->execute([$ticketTypeId]);
            $name = (string)$stmt->fetchColumn();
        } catch (Exception) {
            return '';
        }
        if ($name === '') {
            return '';
        }
        $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', $name));
        return substr($clean, 0, 3);
    }


    /**
     * A ticket's company, with NULL read as what it actually means.
     *
     * 🔴 A NULL `tenant_id` DOES NOT MEAN "no company". Throughout FreeITSM it
     * means the DEFAULT company — the silent one a single-company install never
     * sees. Ed's own install has 16 tickets stored that way alongside 84 stored
     * with the default company's real id, because the two have been written by
     * different code paths over the years.
     *
     * Left unresolved, those 16 would draw from a counter of their own AND
     * render {COMPANY} as nothing, so per-company numbering would produce
     * "-00001" for them while their neighbours got "DEF-00001". Resolving here,
     * once, at the two places a number is decided, is what keeps the two halves
     * of the same company on the same sequence.
     */
    public static function resolveTenant(?PDO $conn, ?int $tenantId): ?int
    {
        if ($tenantId !== null || !$conn) {
            return $tenantId;
        }
        static $defaultId = false;          // false = not looked up yet
        if ($defaultId === false) {
            try {
                $id = $conn->query("SELECT id FROM tenants WHERE is_default = 1 LIMIT 1")->fetchColumn();
                $defaultId = ($id === false) ? null : (int)$id;
            } catch (Exception) {
                $defaultId = null;          // no tenancy tables — single company, nothing to resolve
            }
        }
        return $defaultId;
    }
    /**
     * A short code for a company, for {COMPANY}.
     *
     * 🔑 THE CODE IS SET, NOT GUESSED. `tenants.ticket_code` is what an
     * administrator typed and can see; only when it is empty does FreeITSM
     * derive one, so an install that never thinks about this still gets
     * something sensible.
     *
     * ⚠️ A DERIVED CODE CAN COLLIDE. "Acme Ltd" and "Acme Group" both derive
     * ACM, and under per-company counting that means two companies producing
     * the same ticket numbers. Deriving is a convenience, never a guarantee —
     * codeClashes() is what proves an install is safe, and the numbering screen
     * refuses per-company counting until it is.
     */
    private static function companyCode(?PDO $conn, ?int $tenantId): string
    {
        if (!$conn || !$tenantId) {
            return '';
        }
        try {
            $stmt = $conn->prepare("SELECT name, slug, ticket_code FROM tenants WHERE id = ?");
            $stmt->execute([$tenantId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception) {
            // An install that has not run db_verify since this column arrived.
            // Fall back rather than break every ticket number on the install.
            try {
                $stmt = $conn->prepare("SELECT name, slug FROM tenants WHERE id = ?");
                $stmt->execute([$tenantId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            } catch (Exception) {
                return '';
            }
        }
        return self::codeFor($row);
    }

    /**
     * The effective code for a company row — what {COMPANY} would render.
     *
     * Separate from companyCode() so the settings screen can show somebody the
     * code they are about to get before anything is saved, using the very same
     * rule that will be applied for real.
     *
     * @param array $tenant  a tenants row: ticket_code, slug and name are read, in that order of preference
     */
    public static function codeFor(array $tenant): string
    {
        $explicit = self::cleanCode((string)($tenant['ticket_code'] ?? ''));
        if ($explicit !== '') {
            return $explicit;
        }
        // A slug is close enough to a chosen name to beat anything derived.
        $slug = self::cleanCode((string)($tenant['slug'] ?? ''));
        if ($slug !== '') {
            return $slug;
        }
        $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', (string)($tenant['name'] ?? '')));
        return substr($clean, 0, 3);
    }

    /**
     * Letters and digits, upper case, at most 12 — the shape a code may take.
     *
     * @param string $raw  the code as typed
     */
    public static function cleanCode(string $raw): string
    {
        return substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $raw)), 0, 12);
    }

    /**
     * Companies that would produce the SAME ticket numbers as each other.
     *
     * 🔴 Two companies sharing a code is not a cosmetic problem under
     * per-company counting: each counts from 1, both render the same string,
     * and `tickets.ticket_number` is unique across the whole install — so the
     * second company's tickets would burn numbers climbing over the first
     * company's, and the sequence people were promised would be a fiction.
     *
     * @return array<string, string[]> code => company names sharing it
     */
    public static function codeClashes(PDO $conn): array
    {
        try {
            $rows = $conn->query("SELECT id, name, slug, ticket_code FROM tenants WHERE is_active = 1")
                         ->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception) {
            return [];
        }
        $byCode = [];
        foreach ($rows as $r) {
            $code = self::codeFor($r);
            // A company with no usable code at all (a name of digits, say) is
            // its own kind of problem and is reported under an empty key.
            $byCode[$code][] = (string)$r['name'];
        }
        $clashes = [];
        foreach ($byCode as $code => $names) {
            if (count($names) > 1 || $code === '') {
                $clashes[$code] = $names;
            }
        }
        return $clashes;
    }

    /**
     * Take the next number from the right counter, atomically.
     *
     * ⚠️ ON DUPLICATE KEY UPDATE with LAST_INSERT_ID() is the trick that makes
     * this safe: the read and the increment are one statement, so two tickets
     * created in the same instant cannot take the same number. A SELECT then an
     * UPDATE would race, and the collision would only appear under load.
     *
     * @param array $cfg  the numbering settings, as from config()
     * @param ?int $ticketTypeId  the ticket type, for the per-type counter
     */
    private static function claimNext(PDO $conn, array $cfg, ?int $ticketTypeId, ?int $tenantId): int
    {
        $key   = self::counterKey($cfg, $ticketTypeId, $tenantId);
        $start = max(1, (int)($cfg['ticket_number_start'] ?? 1));

        $stmt = $conn->prepare(
            "INSERT INTO ticket_number_counters (counter_key, next_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE next_value = LAST_INSERT_ID(next_value + 1)"
        );
        $stmt->execute([$key, $start]);

        // ⚠️ rowCount() FIRST, not lastInsertId(). MySQL returns 1 for a fresh
        // INSERT and 2 when ON DUPLICATE KEY actually updated a row. On the
        // insert path lastInsertId() is meaningless here — the table has no
        // AUTO_INCREMENT, so it would be 0 and every first ticket of a counter
        // would be numbered zero.
        if ($stmt->rowCount() === 1) {
            return $start;          // first number this counter has ever issued
        }
        return (int)$conn->lastInsertId();   // LAST_INSERT_ID(next_value + 1)
    }

    /**
     * Which counter a ticket draws from.
     *
     * The reset period is part of the KEY rather than a stored date: a yearly
     * reset is simply a different counter each year, so nothing has to notice
     * midnight on the 31st of December or run a job to zero anything.
     *
     * @param array $cfg  the numbering settings (ticket_number_scope, ticket_number_reset)
     * @param ?int $ticketTypeId  the ticket type, for the per-type scope
     * @param ?DateTimeImmutable $at  the ticket's date, for a yearly or monthly reset; null means now (UTC)
     */
    public static function counterKey(array $cfg, ?int $ticketTypeId, ?int $tenantId, ?DateTimeImmutable $at = null): string
    {
        $parts = ["t"];
        if (($cfg['ticket_number_scope'] ?? 'global') === 'per_type') {
            $parts[] = 'ty' . (int)$ticketTypeId;
        } elseif (($cfg['ticket_number_scope'] ?? 'global') === 'per_company') {
            $parts[] = 'co' . (int)$tenantId;
        }
        // $at again: a renumber must put a 2024 ticket on the 2024 counter.
        $now = $at ?: new DateTimeImmutable("now", new DateTimeZone("UTC"));
        if (($cfg["ticket_number_reset"] ?? "never") === "yearly") {
            $parts[] = $now->format('Y');
        } elseif (($cfg['ticket_number_reset'] ?? 'never') === 'monthly') {
            $parts[] = $now->format('Ym');
        }
        return implode(':', $parts);
    }

    // ====================================================================
    //  Recognising a number
    // ====================================================================

    /**
     * Is this number already taken — by a live ticket OR by history?
     *
     * 🔑 History counts. A number that once belonged to a renumbered ticket must
     * never be handed to a different one, or a reply to an old email would land
     * on somebody else's ticket. That is worse than not matching at all.
     *
     * @param string $number  a ticket number as it would be stored
     */
    public static function inUse(PDO $conn, string $number): bool
    {
        $stmt = $conn->prepare("SELECT 1 FROM tickets WHERE ticket_number = ? LIMIT 1");
        $stmt->execute([$number]);
        if ($stmt->fetchColumn() !== false) {
            return true;
        }
        try {
            $stmt = $conn->prepare("SELECT 1 FROM ticket_number_history WHERE ticket_number = ? LIMIT 1");
            $stmt->execute([$number]);
            return $stmt->fetchColumn() !== false;
        } catch (Exception) {
            return false;   // table not created yet
        }
    }

    /**
     * The ticket id for a number — current OR any it has ever had.
     *
     * This is what makes renumbering safe. It is the same principle merges
     * already use (`resolveMergedTicket`): an old identifier keeps working
     * forever, because the emails quoting it do.
     *
     * @param string $number  a ticket number, current or one a ticket used to have
     */
    public static function findTicketId(PDO $conn, string $number): ?int
    {
        $stmt = $conn->prepare("SELECT id FROM tickets WHERE ticket_number = ? LIMIT 1");
        $stmt->execute([$number]);
        $id = $stmt->fetchColumn();
        if ($id !== false) {
            return (int)$id;
        }
        try {
            $stmt = $conn->prepare("SELECT ticket_id FROM ticket_number_history WHERE ticket_number = ? LIMIT 1");
            $stmt->execute([$number]);
            $id = $stmt->fetchColumn();
            return $id === false ? null : (int)$id;
        } catch (Exception) {
            return null;
        }
    }

    /**
     * The pattern that finds a reference in an email subject or body.
     *
     * ⚠️ DELIBERATELY FORMAT-AGNOSTIC. It captures whatever sits between
     * `SDREF:` and the closing bracket, and the DATABASE decides whether that is
     * a real ticket. Encoding a shape here is what would break every historical
     * email the day an install changed format — see this file's header.
     *
     * The character class is still bounded (no spaces, no brackets) so a
     * malformed subject cannot swallow the rest of the line.
     */
    const REF_PATTERN      = '/\[SDREF:\s*([A-Za-z0-9._\/-]{3,60})\s*\]/i';
    const REF_LINE_PATTERN = '/\[\*{3}\s*SDREF:\s*([A-Za-z0-9._\/-]{3,60})\s*REPLY ABOVE THIS LINE\s*\*{3}\]/i';

    // ====================================================================
    //  Preview
    // ====================================================================

    /**
     * What the next few numbers would look like under a given configuration,
     * without writing anything. Drives the live preview in settings.
     *
     * @param array $cfg  numbering settings to preview, laid over DEFAULTS - may be unsaved
     * @param int $count  how many numbers to show
     */
    public static function preview(array $cfg, int $count = 3): array
    {
        $cfg   = array_merge(self::DEFAULTS, $cfg);
        $start = max(1, (int)($cfg['ticket_number_start'] ?? 1));
        $out   = [];

        if (($cfg['ticket_number_style'] ?? 'random') === 'random') {
            for ($i = 0; $i < $count; $i++) {
                $out[] = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90)) . '-'
                       . rand(100, 999) . '-' . str_pad((string)rand(0, 99999), 5, '0', STR_PAD_LEFT);
            }
            return $out;
        }
        // ⚠️ A preview has no ticket, so {TYPE} and {COMPANY} would render as
        // nothing and an administrator would be shown "-00001" and conclude the
        // token is broken. Stand-ins make the SHAPE readable, which is the only
        // thing a preview is for.
        $format = str_replace(["{TYPE}", "{COMPANY}"], ["INC", "ACME"], $cfg["ticket_number_format"]);
        for ($i = 0; $i < $count; $i++) {
            $out[] = self::render($format, $start + $i);
        }
        return $out;
    }

    /**
     * Sanity-check a format before it is saved.
     *
     * @param string $format  the number format to check
     * @param string $scope  the counting scope it will be used with: 'global', 'per_type' or 'per_company'
     * @return string[] problems, empty when the format is usable
     */
    public static function validateFormat(string $format, string $scope = "global"): array
    {
        $problems = [];
        if (trim($format) === '') {
            $problems[] = 'The format cannot be empty.';
            return $problems;
        }
        if (!preg_match('/\{#+\}/', $format)) {
            // Without a number token every ticket gets the same reference.
            $problems[] = 'The format needs a number — add {######} where the digits should go.';
        }
        if (preg_match('/[\[\]]/', $format)) {
            // Square brackets would break the [SDREF:…] tag in email subjects.
            $problems[] = 'Square brackets cannot be used in a ticket number.';
        }
        if (preg_match('/\s/', $format)) {
            $problems[] = 'Spaces cannot be used in a ticket number.';
        }
        // 🔑 A SCOPE THAT NOTHING IN THE FORMAT DISTINGUISHES IS A COLLISION.
        // Counting separately per ticket type gives type 1 and type 2 their own
        // runs of numbers — and then renders both as TICKET-000001 unless the
        // format says which is which. Live creation survives it (it proves
        // uniqueness against the table and simply burns numbers), but the
        // per-type counting the administrator asked for silently does nothing.
        if ($scope === "per_type" && !str_contains($format, "{TYPE}")) {
            $problems[] = "Counting separately for each ticket type needs {TYPE} in the format, or every type produces the same numbers.";
        }
        if ($scope === "per_company" && !str_contains($format, "{COMPANY}")) {
            $problems[] = "Counting separately for each company needs {COMPANY} in the format, or every company produces the same numbers.";
        }
        if (mb_strlen(self::render($format, 999999)) > 50) {
            // The column is VARCHAR(50).
            $problems[] = 'That format is too long — a ticket number can be at most 50 characters.';
        }
        return $problems;
    }

    // ====================================================================
    //  Renumbering an existing estate
    //
    //  🔴 THE RISKIEST THING IN THIS FILE. It rewrites the reference on every
    //  ticket, and those references are quoted in emails, change records,
    //  knowledge articles and customers' own spreadsheets.
    //
    //  🔑 IT IS ONLY SAFE BECAUSE OF ticket_number_history. Every old number is
    //  kept and keeps resolving to its ticket for ever, so a reply to a
    //  two-year-old email still lands in the right place. That is the same
    //  principle ticket merges already rely on. Without it, renumbering would
    //  silently turn every historical reply into a new ticket.
    //
    //  It lives here rather than in the endpoint so it can be TESTED. A tool
    //  this destructive proving itself only by being run on real data is not
    //  a proof at all.
    // ====================================================================

    /**
     * Work out what a renumber would do, and refuse it if the answer is unsafe.
     * Writes nothing.
     *
     * @return array{total:int, changing:int, skipped:int, planned:array, seqs:array, next_after:?string}
     * @throws Exception if the plan would produce a number twice, or reuse a
     *                   number another ticket has retired.
     *
     * @param array $cfg  the numbering settings to renumber to, laid over DEFAULTS; must be sequential
     * @param ?array $rows the tickets to plan over. Defaults to ALL of them,
     *        which is what the tool does; tests pass a set of their own so a
     *        run cannot rewrite the real estate.
     */
    public static function planRenumber(PDO $conn, array $cfg, ?array $rows = null): array
    {
        $cfg = array_merge(self::DEFAULTS, $cfg);

        if (($cfg['ticket_number_style'] ?? 'random') !== 'sequential') {
            throw new Exception('Renumbering only makes sense with a sequential format. Choose one, save, then come back.');
        }
        $problems = self::validateFormat($cfg['ticket_number_format'], $cfg['ticket_number_scope'] ?? 'global');
        if ($problems) {
            throw new Exception(implode(' ', $problems));
        }

        // Oldest first, so the numbers run in the order the tickets happened.
        // ⚠️ ORDER BY the creation date, then id — id alone is nearly the same
        // and not quite, once merges and imports have moved things around.
        $rows = $rows ?? $conn->query(
            "SELECT id, ticket_number, ticket_type_id, tenant_id, created_datetime
               FROM tickets
           ORDER BY created_datetime, id"
        )->fetchAll(PDO::FETCH_ASSOC);

        $planned = [];
        $skipped = 0;
        $start   = max(1, (int)($cfg['ticket_number_start'] ?? 1));

        // 🔑 ONE SEQUENCE PER COUNTER KEY, not one for the whole run. Under
        // "count separately for each ticket type" a single shared sequence would
        // renumber everything from one run of numbers and then leave every
        // type's counter still sitting at 1 — so the next new incident would be
        // handed a number a renumbered ticket already has. The keys here are
        // exactly the ones live ticket creation uses, which is what makes them
        // line up.
        $seqs = [];

        foreach ($rows as $r) {
            $typeId   = $r['ticket_type_id'] !== null ? (int)$r['ticket_type_id'] : null;
            // NULL means the default company — resolved here so the two ways a
            // default-company ticket has been stored land on ONE sequence.
            $tenantId = self::resolveTenant($conn, $r["tenant_id"] !== null ? (int)$r["tenant_id"] : null);

            // ⚠️ The ticket's OWN date, not today's, for both the counter it
            // draws from and the year in its number. A 2024 ticket must not come
            // back as INC-2026-000001.
            $at  = new DateTimeImmutable($r['created_datetime'] ?: 'now', new DateTimeZone('UTC'));
            $key = self::counterKey($cfg, $typeId, $tenantId, $at);
            if (!isset($seqs[$key])) {
                $seqs[$key] = $start;
            }

            $newNumber = self::render($cfg['ticket_number_format'], $seqs[$key], $conn, $typeId, $at, $tenantId);
            $seqs[$key]++;

            // Already in the target scheme? Leave it entirely alone — renumbering
            // a ticket to the number it already has would still write a history
            // row, and history is what old email replies are matched against.
            if ($r['ticket_number'] === $newNumber) {
                $skipped++;
                continue;
            }
            $planned[] = ['id' => (int)$r['id'], 'from' => $r['ticket_number'], 'to' => $newNumber];
        }

        self::assertPlanSafe($conn, $planned);

        // What the next brand-new ticket would get — the one number an
        // administrator wants to see afterwards.
        //
        // ⚠️ Only meaningful when everything shares ONE sequence. Counting per
        // type or per company means the answer depends on which type or company
        // the next ticket happens to be, so there is no single number and we say
        // nothing rather than picking one and being wrong.
        $nextAfter = (($cfg['ticket_number_scope'] ?? 'global') === 'global')
            ? self::render($cfg['ticket_number_format'], $seqs[self::counterKey($cfg, null, null)] ?? $start)
            : null;

        return [
            'total'      => count($rows),
            'changing'   => count($planned),
            'skipped'    => $skipped,
            'planned'    => $planned,
            'seqs'       => $seqs,
            'next_after' => $nextAfter,
        ];
    }

    /**
     * The guard that does not depend on me having thought of the cause.
     *
     * 🔴 A renumber that writes two tickets the same reference is the worst
     * outcome this tool has: replies would land on whichever row matched first,
     * for ever. validateFormat() already refuses the causes that are knowable
     * from the settings alone — but a {TYPE} that renders empty because that
     * type was deleted years ago is not one of them.
     *
     * So the plan is checked for what it IS, not for how it was built.
     *
     * @param array $planned  the 'planned' list from planRenumber(): [{id, from, to}, ...]
     */
    private static function assertPlanSafe(PDO $conn, array $planned): void
    {
        $byNumber = [];
        foreach ($planned as $p) {
            if (isset($byNumber[$p['to']])) {
                throw new Exception(
                    'That format would give more than one ticket the number ' . $p['to']
                    . '. Nothing has been changed. Add {TYPE} or {COMPANY} to the format, '
                    . 'or count everything in one sequence.'
                );
            }
            $byNumber[$p['to']] = $p['id'];
        }
        if (!$byNumber) {
            return;
        }

        // A planned number that a DIFFERENT ticket has retired is equally fatal:
        // ticket_number_history is what makes old references keep working, so
        // handing one to somebody else would silently redirect an old thread.
        $numbers = array_keys($byNumber);
        for ($i = 0; $i < count($numbers); $i += 500) {
            $slice = array_slice($numbers, $i, 500);
            $in    = implode(',', array_fill(0, count($slice), '?'));
            $stmt  = $conn->prepare(
                "SELECT ticket_number, ticket_id FROM ticket_number_history WHERE ticket_number IN ($in)"
            );
            $stmt->execute($slice);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $h) {
                // Its own old number coming back round is fine — that is just a
                // renumber being undone. Somebody else's is not.
                if ((int)$h['ticket_id'] !== (int)$byNumber[$h['ticket_number']]) {
                    throw new Exception(
                        'The number ' . $h['ticket_number'] . ' was used by another ticket in the past '
                        . 'and still has to keep working. Nothing has been changed. '
                        . 'Try a different format, or start counting from a higher number.'
                    );
                }
            }
        }
    }

    /**
     * Carry out a plan from planRenumber().
     *
     * ⚠️ ONE TRANSACTION for the whole run. A half-renumbered estate would have
     * two schemes in it and a counter that matched neither.
     *
     * @param array $plan  the result of planRenumber() - its 'planned' and 'seqs' are used
     */
    public static function applyRenumber(PDO $conn, array $plan): void
    {
        $conn->beginTransaction();
        try {
            $hist = $conn->prepare(
                "INSERT INTO ticket_number_history (ticket_id, ticket_number, reason) VALUES (?, ?, 'renumber')"
            );
            $upd = $conn->prepare("UPDATE tickets SET ticket_number = ? WHERE id = ?");

            foreach ($plan['planned'] as $p) {
                // History FIRST. If anything fails after this the transaction
                // rolls back, but the ORDER matters for the unique key: the old
                // number is recorded before it stops being the ticket's own.
                $hist->execute([$p['id'], $p['from']]);
                $upd->execute([$p['to'], $p['id']]);
            }

            // 🔑 Wind EVERY counter the run touched past what it issued, in the
            // SAME transaction. Miss one and the next new ticket on that counter
            // collides with a renumbered one, which is worse than never having
            // renumbered at all.
            foreach ($plan["seqs"] as $key => $seq) {
                self::windCounterTo($conn, $key, $seq);
            }

            $conn->commit();
        } catch (Throwable $e) {
            $conn->rollBack();
            throw $e;
        }
    }
}
