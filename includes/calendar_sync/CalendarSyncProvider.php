<?php
/**
 * CalendarSyncProvider — the provider-agnostic contract for putting an analyst's
 * scheduled work into the calendar they actually live in (GH discussion #75).
 *
 * Everything above this line — which tickets sync, whose, what happens when one
 * is reassigned or unscheduled, the remote-id map, the per-analyst enrolment and
 * the settings UI — talks to this interface and never knows which calendar is
 * live. That is the same split as includes/integrations/IssueTrackerProvider.php
 * and includes/messaging/MessagingProvider.php, and it is deliberately copied
 * rather than reinvented.
 *
 * 🔑 THE REASON THIS IS AN INTERFACE AND NOT JUST GRAPH CODE. What varies
 * between providers is genuinely small: create, update and delete an event in a
 * named person's calendar. Everything difficult here — deciding whose calendar,
 * removing the old copy when a ticket moves from A to B, not orphaning events
 * when somebody opts out — is provider-independent. So keeping the door open for
 * Google (or CalDAV, or nothing at all) costs almost nothing, while a
 * Microsoft-shaped hole would have to be dug out again later.
 *
 * ⚠️ Not every install can use ANY of these. A shop with no supported provider
 * falls back to a subscribe (.ics) link, which needs no provider code at all —
 * so "we have not implemented your calendar" must never mean "you get nothing".
 *
 * Concrete providers are constructed with a DECRYPTED calendar_connections row
 * (credentials already a PHP array), exactly as IssueTrackerProvider is.
 *
 * ── The canonical event shape (what push() is given) ────────────────────────
 *
 *   [
 *     'subject'   => 'TICKET-000105 — Rebuild laptop',   // required
 *     'body'      => 'Requester: …\nStatus: …',          // plain text, may be ''
 *     'start'     => '2026-09-01 14:00:00',   // NAIVE wall clock — see below
 *     'end'       => '2026-09-01 16:00:00',
 *     'all_day'   => false,
 *     'timezone'  => 'Europe/London',         // the zone the naive values mean
 *     'url'       => 'https://…/tickets/?ticket_id=635',  // link back
 *   ]
 *
 * ⚠️ START AND END ARE NAIVE WALL-CLOCK, not UTC. Scheduling values in FreeITSM
 * are stored without a zone on purpose — "2pm" means 2pm to every analyst (see
 * assets/js/schedule.js). A provider MUST combine them with 'timezone' rather
 * than assuming UTC, or every event lands an hour out for half the year. This is
 * the single most likely way to get this integration subtly wrong.
 */

abstract class CalendarSyncProvider
{
    /** Capability keys for supports(). */
    const CAP_ALL_DAY       = 'all_day';        // real all-day events, not 00:00-23:59
    const CAP_BODY_HTML     = 'body_html';      // rich body rather than plain text
    const CAP_VERIFY_TARGET = 'verify_target';  // can answer "does this mailbox exist?"
    const CAP_NOTIFY        = 'notify';         // change notifications (subscriptions)
    const CAP_PER_ANALYST   = 'per_analyst';    // signs in AS each analyst - see $account

    /** @var array decrypted calendar_connections row */
    protected $connection;

    /**
     * The analyst this provider is acting for, when the provider signs in as
     * them rather than as an application: their decrypted
     * calendar_enrolments.credentials. Empty for Microsoft, whose app-only
     * token reaches every mailbox by itself.
     *
     * 🔑 WHY THIS EXISTS. Microsoft lets one administrator-approved app write
     * into everyone's calendar, so a provider could be built from the
     * connection alone. A CalDAV server has no such thing: each calendar
     * belongs to its owner's account. So the caller hands over the enrolment of
     * whoever the event belongs to, and a provider that needs it refuses to act
     * without it rather than guessing.
     *
     * @var array
     */
    protected $account = [];

    /**
     * Act as this analyst. Returns $this so it chains off the factory.
     *
     * @param array $account  the analyst's decrypted calendar_enrolments.credentials; [] for a provider that needs none
     */
    public function withAccount(array $account): self
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Optional database handle, for a provider that caches something across
     * requests — Microsoft caches its hour-long app-only token.
     *
     * Declared HERE rather than on the concrete provider so assigning it is
     * valid against the abstract type. Set only on the concrete class, it was a
     * dynamic property from any caller holding a CalendarSyncProvider, which
     * PHP 8.2 deprecates and static analysis flags.
     *
     * @var PDO|null
     */
    public $conn = null;

    /**
     * Keeps the decrypted calendar_connections row (credentials already an array) the provider works from.
     *
     * @param array $connection  the decrypted calendar_connections row, credentials already a PHP array
     */
    public function __construct(array $connection)
    {
        $this->connection = $connection;
    }

    /** Returns the connection's provider key (for example 'microsoft' or 'caldav'), or ''. */
    public function getProvider(): string
    {
        return $this->connection['provider'] ?? '';
    }

    /**
     * Does this provider do X?
     *
     * The settings screen needs to know BEFORE it renders a field — discovering
     * it from a thrown exception is the wrong way round. Same reasoning as
     * IssueTrackerProvider::supports().
     *
     * @param string $capability  a CAP_* key, e.g. CalendarSyncProvider::CAP_ALL_DAY
     */
    public function supports(string $capability): bool
    {
        return false;
    }

    // ------------------------------------------------------------- outbound

    /**
     * Create an event in $calendarAddress's calendar. Returns the provider's id
     * for it, which the caller stores in calendar_sync_events.
     *
     * 🔴 THE RETURNED ID IS LOAD-BEARING. Without it there is no way to update
     * or remove the event later, and a reassigned ticket leaves a copy in the
     * old analyst's calendar with nothing pointing at it. A provider that cannot
     * return a durable id cannot be supported.
     *
     * @param string $calendarAddress  whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV
     * @param array $event  the canonical event shape described at the top of this file
     */
    abstract public function createEvent(string $calendarAddress, array $event): string;

    /**
     * Update an event in place.
     *
     * Implementations SHOULD treat "the event is gone" as a recoverable state
     * and throw CalendarEventMissing, so the caller can create a fresh one
     * rather than the analyst silently losing the entry — somebody deleting it
     * from their own calendar is a normal thing to do, not an error.
     *
     * @param string $calendarAddress  whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV
     * @param string $remoteEventId  the provider's id for the event, as createEvent() returned it
     * @param array $event  the canonical event shape described at the top of this file
     */
    abstract public function updateEvent(string $calendarAddress, string $remoteEventId, array $event): void;

    /**
     * Remove an event.
     *
     * An event that is ALREADY gone is a success, not a failure: the desired
     * end state is "not in their calendar", and that is satisfied. Treating it
     * as an error would leave the map row behind and retry forever.
     *
     * @param string $calendarAddress  whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV
     * @param string $remoteEventId  the provider's id for the event, as createEvent() returned it
     */
    abstract public function deleteEvent(string $calendarAddress, string $remoteEventId): void;

    /**
     * Prove the connection works at all: credentials valid, permission granted.
     *
     * Separate from verifyTarget() ON PURPOSE. This answers "can FreeITSM talk to
     * the provider", that one answers "does this person have a calendar" — they
     * fail for entirely different reasons and need entirely different fixes, so
     * a settings screen must be able to report them separately rather than
     * collapsing both into "it didn't work".
     *
     * Throws with a human-readable message on failure; returns nothing on success.
     */
    abstract public function verifyConnection(): void;

    // -------------------------------------------------------------- inbound

    /**
     * What has changed in this calendar since last time (GH #75, bi-directional).
     *
     * @param  string      $calendarAddress whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV
     * @param  string|null $token opaque provider state from the previous call;
     *                            null means "no history — take a baseline"
     * @return array [
     *   'token'    => string|null,   // store this for next time
     *   'baseline' => bool,          // TRUE when there was no usable history and
     *                                // this call established one. The caller MUST
     *                                // apply NOTHING on a baseline.
     *   'changed'  => [ ['remote_event_id'=>…, 'start'=>'Y-m-d H:i:s',
     *                    'end'=>…, 'all_day'=>bool], … ],
     *   'removed'  => [ 'remote_event_id', … ],
     * ]
     *
     * 🔴 'baseline' IS THE SAFETY RAIL AND IT IS NOT OPTIONAL. A provider that
     * has lost its place — an expired token, a moved mailbox, a revoked
     * permission — answers with everything, or with nothing. Code that reads
     * "absent" as "deleted" would then unschedule an entire service desk on the
     * strength of a token expiring. A caller must treat baseline as "I know
     * nothing yet", never as "it is all gone".
     *
     * Times come back as NAIVE wall clock in the caller's zone, matching the way
     * FreeITSM stores them.
     */
    public function pollChanges(string $calendarAddress, ?string $token): array
    {
        return ['token' => null, 'baseline' => true, 'changed' => [], 'removed' => []];
    }

    // --------------------------------------------------- change notifications

    /**
     * Ask the provider to tell us when this calendar changes.
     *
     * ⚠️ AN ACCELERATOR, NEVER THE ONLY MECHANISM. A notification says only that
     * something changed, not what — the caller still runs pollChanges(). And
     * notifications go missing: the provider drops them, the endpoint is down for
     * a deploy, a subscription lapses unnoticed. A silent gap then looks exactly
     * like "nothing changed", so the poll must remain as a backstop.
     *
     * @param  string $calendarAddress whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV
     * @param  string $notifyUrl publicly reachable HTTPS endpoint
     * @param  string $secret    echoed back in every notification; the only thing
     *                           telling a real callback from anyone on the
     *                           internet POSTing to a deliberately public URL
     * @return array ['id' => string, 'expires' => 'Y-m-d H:i:s']
     */
    public function createSubscription(string $calendarAddress, string $notifyUrl, string $secret): array
    {
        throw new Exception('This calendar provider does not support change notifications.');
    }

    /**
     * Push an existing subscription's expiry out. Same return as create.
     *
     * @param string $subscriptionId  the provider's subscription id, as createSubscription() returned it
     */
    public function renewSubscription(string $subscriptionId): array
    {
        throw new Exception('This calendar provider does not support change notifications.');
    }

    /**
     * Stop notifications. An ALREADY-GONE subscription is a success: the desired
     * end state is "not subscribed", and that is satisfied.
     *
     * @param string $subscriptionId  the provider's subscription id, as createSubscription() returned it
     */
    public function deleteSubscription(string $subscriptionId): void
    {
        throw new Exception('This calendar provider does not support change notifications.');
    }

    // ------------------------------------------------------------- discovery

    /**
     * Does this address have a calendar we can write to?
     *
     * Drives the honest error on the analyst's own settings — an analyst whose
     * FreeITSM email is not their mailbox UPN must be told "we could not find a
     * mailbox for you", not left with a switch that appears on and never works.
     *
     * Providers that cannot answer should report CAP_VERIFY_TARGET as false;
     * the default assumes the address is fine rather than blocking enrolment on
     * a check the provider cannot perform.
     *
     * @param string $calendarAddress  the address to check - a mailbox address for Microsoft, a calendar URL for CalDAV
     */
    public function verifyTarget(string $calendarAddress): bool
    {
        return true;
    }
}

/**
 * The event we were told to update is no longer there.
 *
 * Its own class because the caller's response is specific and correct: create a
 * replacement and re-point the map row. Someone deleting a FreeITSM event from
 * their own calendar is ordinary behaviour.
 */
class CalendarEventMissing extends Exception {}

/**
 * The subscription we tried to renew has already lapsed.
 *
 * Its own class because the response is specific: create a fresh one, rather
 * than retrying a renewal against something that cannot come back.
 */
class CalendarSubscriptionMissing extends Exception {}
