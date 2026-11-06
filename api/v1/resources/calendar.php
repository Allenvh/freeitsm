<?php
/**
 * FreeITSM REST API v1 — calendar resource (the standalone team calendar).
 *
 * Mirrors the module's internal endpoints:
 *   - the list uses get_events.php's exact three-branch window-overlap logic
 *     (start inclusive, end exclusive);
 *   - create/update mirror save_event.php (title + start mandatory, end
 *     defaults to start, all_day 0/1) with friendlier validation: category /
 *     contract ids get a 422 instead of a raw FK error, and contract links
 *     honour nothing tenant-y because the calendar is install-wide.
 *
 * ⚠️ Datetime semantics — DIFFERENT from the rest of the API, deliberately:
 * calendar events are stored as NAIVE SERVER-LOCAL datetimes (the UI writes
 * what the browser sends, no timezone conversion; the ICS feed interprets
 * them in the server timezone). To stay pixel-identical with the UI, this
 * resource accepts and returns naive "YYYY-MM-DD HH:MM:SS" values with NO
 * timezone conversion, and REJECTS values carrying Z/offset designators so a
 * consumer can't accidentally mix models.
 *
 * 🛡️ Generated events: the asset-warranty sync owns every row with
 * source = 'asset_warranty' (it wipes and regenerates them wholesale). The
 * API exposes `source` on reads (the internal endpoints don't!) and refuses
 * updates/deletes on generated rows with a 409 — tighter than the UI, which
 * currently lets you edit one only for the change to evaporate on the next
 * sync. Consumers can never set `source` on create.
 *
 * Event WRITES are delegated to CalendarService (includes/services/calendar.php)
 * so the UI and this API share one code path; the read handlers, serializers +
 * the naive-datetime parser (also used by the list filters) stay here.
 */

require_once dirname(__DIR__, 3) . '/includes/service_context.php';
require_once dirname(__DIR__, 3) . '/includes/services/calendar.php';

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

/**
 * Parse a NAIVE local datetime ("YYYY-MM-DD HH:MM[:SS]", T separator OK,
 * date-only OK). 422s on timezone designators — this resource is naive-local
 * by design (see header).
 *
 * @param mixed $value  the caller-supplied datetime (untrusted), server-local with no offset; null or '' gives null
 * @param string $field  the field or query parameter name, quoted in the 422 message
 */
function apiParseNaiveDatetime($value, string $field): ?string {
    if ($value === null || $value === '') {
        return null;
    }
    $v = trim((string)$value);
    if (preg_match('/(Z|[+-]\d{2}:?\d{2})$/i', $v)) {
        apiError(422, 'invalid_field', "'{$field}' must be a naive local datetime (no Z/offset) — the calendar stores server-local times, matching the UI.");
    }
    $v = str_replace('T', ' ', $v);
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
        $v .= ' 00:00:00';
    } elseif (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $v)) {
        $v .= ':00';
    }
    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $v);
    if (!$dt || $dt->format('Y-m-d H:i:s') !== $v) {
        apiError(422, 'invalid_field', "'{$field}' must be 'YYYY-MM-DD HH:MM:SS' (naive local time).");
    }
    return $v;
}

/** The SELECT for calendar events, with the category's name and colour and the creator's name. */
function apiCalendarEventSelect(): string {
    return "SELECT e.*, c.name AS category_name, c.color AS category_color,
                   a.full_name AS created_by_name
            FROM calendar_events e
            LEFT JOIN calendar_categories c ON c.id = e.category_id
            LEFT JOIN analysts a ON a.id = e.created_by";
}

/**
 * Shapes a calendar event row for the API - times stay naive server-local, source says
 * whether it was generated (null = typed in), and created_by is null for generated
 * events.
 *
 * @param array $r  the calendar_events row as apiCalendarEventSelect() loads it, with category and creator names
 */
function apiSerializeCalendarEvent(array $r): array {
    return [
        'id'          => (int)$r['id'],
        'title'       => $r['title'],
        'description' => $r['description'],
        'category'    => $r['category_id'] === null ? null : [
            'id'    => (int)$r['category_id'],
            'name'  => $r['category_name'],
            'color' => $r['category_color'],
        ],
        'start_at'    => $r['start_datetime'],   // naive server-local, by design
        'end_at'      => $r['end_datetime'],
        'all_day'     => (bool)$r['all_day'],
        'location'    => $r['location'],
        'contract_id' => $r['contract_id'] !== null ? (int)$r['contract_id'] : null,
        'source'      => $r['source'],           // null = manual; 'asset_warranty' = generated (read-only)
        'created_by'  => ((int)$r['created_by'] > 0)
            ? ['id' => (int)$r['created_by'], 'name' => $r['created_by_name']]
            : null, // generated events use the 0 sentinel
        'created_at'  => $r['created_at'],
        'updated_at'  => $r['updated_at'],
    ];
}

/**
 * Loads one calendar event row (apiCalendarEventSelect), or answers 404 and stops.
 *
 * @param int $eventId  the calendar_events id
 */
function apiLoadCalendarEvent(PDO $conn, int $eventId): array {
    $stmt = $conn->prepare(apiCalendarEventSelect() . " WHERE e.id = ?");
    $stmt->execute([$eventId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        apiError(404, 'not_found', 'Event not found.');
    }
    return $row;
}

// ---------------------------------------------------------------------------
// GET /calendar/events
// ---------------------------------------------------------------------------
function apiCalendarEventsList(PDO $conn, array $apiKey, array $params, array $body): void {
    $from = isset($_GET['from']) ? apiParseNaiveDatetime($_GET['from'], 'from') : null;
    $to   = isset($_GET['to'])   ? apiParseNaiveDatetime($_GET['to'], 'to')     : null;
    $all        = (($_GET['all'] ?? '') === 'true');
    $contractId = isset($_GET['contract_id']) && $_GET['contract_id'] !== '' ? (int)$_GET['contract_id'] : null;

    // Same rule as get_events.php: a window is required unless you're pulling
    // a contract's events or explicitly asked for everything.
    if (($from === null || $to === null) && !$all && $contractId === null) {
        apiError(400, 'invalid_parameter', "Provide a 'from' + 'to' window, or contract_id, or all=true.");
    }

    $where = ['1=1'];
    $args  = [];

    if ($from !== null && $to !== null) {
        // The UI's exact three-branch overlap: starts in / ends in / spans.
        $where[] = "( (e.start_datetime >= ? AND e.start_datetime < ?)
                   OR (e.end_datetime   >  ? AND e.end_datetime  <= ?)
                   OR (e.start_datetime <  ? AND e.end_datetime  >  ?) )";
        array_push($args, $from, $to, $from, $to, $from, $to);
    }
    if ($contractId !== null) {
        $where[] = 'e.contract_id = ?';
        $args[]  = $contractId;
    }
    if (isset($_GET['category_id']) && $_GET['category_id'] !== '') {
        $where[] = 'e.category_id = ?';
        $args[]  = (int)$_GET['category_id'];
    }
    if (isset($_GET['categories']) && trim($_GET['categories']) !== '') {
        $ids = array_values(array_filter(array_map('intval', explode(',', $_GET['categories'])), fn($i) => $i > 0));
        if ($ids) {
            $where[] = 'e.category_id IN (' . implode(',', array_fill(0, count($ids), '?')) . ')';
            $args = array_merge($args, $ids);
        }
    }
    $source = trim($_GET['source'] ?? '');
    if ($source === 'manual') {
        $where[] = 'e.source IS NULL';
    } elseif ($source !== '') {
        $where[] = 'e.source = ?';
        $args[]  = $source;
    }
    if (isset($_GET['q']) && trim($_GET['q']) !== '') {
        $where[] = '(e.title LIKE ? OR e.location LIKE ?)';
        $like = '%' . trim($_GET['q']) . '%';
        array_push($args, $like, $like);
    }

    [$page, $perPage, $offset] = apiPagination();
    $whereSql = implode(' AND ', $where);

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM calendar_events e WHERE $whereSql");
    $countStmt->execute($args);
    $total = (int)$countStmt->fetchColumn();

    $stmt = $conn->prepare(apiCalendarEventSelect() . " WHERE $whereSql ORDER BY e.start_datetime ASC, e.id ASC LIMIT $perPage OFFSET $offset");
    $stmt->execute($args);
    apiRespond(array_map('apiSerializeCalendarEvent', $stmt->fetchAll(PDO::FETCH_ASSOC)), 200, [
        'page'        => $page,
        'per_page'    => $perPage,
        'total'       => $total,
        'total_pages' => (int)ceil($total / $perPage),
    ]);
}

/** GET /calendar/events/{id} - one calendar event. */
function apiCalendarEventsGet(PDO $conn, array $apiKey, array $params, array $body): void {
    apiRespond(apiSerializeCalendarEvent(apiLoadCalendarEvent($conn, $params[0])));
}

// ---------------------------------------------------------------------------
// POST /calendar/events
// ---------------------------------------------------------------------------
function apiCalendarEventsCreate(PDO $conn, array $apiKey, array $params, array $body): void {
    try {
        $res = CalendarService::saveEvent($conn, ActorContext::fromApiKey($apiKey), $body);
        apiRespond(apiSerializeCalendarEvent(apiLoadCalendarEvent($conn, $res['id'])), 201);
    } catch (ServiceError $e) { apiFailFromService($e); }
}

// ---------------------------------------------------------------------------
// PATCH /calendar/events/{id}
// ---------------------------------------------------------------------------
function apiCalendarEventsUpdate(PDO $conn, array $apiKey, array $params, array $body): void {
    try {
        $res = CalendarService::saveEvent($conn, ActorContext::fromApiKey($apiKey), array_merge($body, ['id' => (int)$params[0]]));
        apiRespond(apiSerializeCalendarEvent(apiLoadCalendarEvent($conn, $res['id'])));
    } catch (ServiceError $e) { apiFailFromService($e); }
}

// ---------------------------------------------------------------------------
// DELETE /calendar/events/{id}
// ---------------------------------------------------------------------------
function apiCalendarEventsDelete(PDO $conn, array $apiKey, array $params, array $body): void {
    try {
        CalendarService::deleteEvent($conn, ActorContext::fromApiKey($apiKey), (int)$params[0]);
        apiRespond(['id' => $params[0], 'deleted' => true]);
    } catch (ServiceError $e) { apiFailFromService($e); }
}

// ---------------------------------------------------------------------------
// GET /calendar-categories (reference)
// ---------------------------------------------------------------------------
function apiCalendarCategoriesList(PDO $conn, array $apiKey, array $params, array $body): void {
    $rows = $conn->query(
        "SELECT c.id, c.name, c.color, c.description, c.is_active,
                (SELECT COUNT(*) FROM calendar_events e WHERE e.category_id = c.id) AS event_count
         FROM calendar_categories c ORDER BY c.name"
    )->fetchAll(PDO::FETCH_ASSOC);
    apiRespond(array_map(function ($c) {
        return [
            'id'          => (int)$c['id'],
            'name'        => $c['name'],
            'color'       => $c['color'],
            'description' => $c['description'],
            'is_active'   => (bool)$c['is_active'],
            'event_count' => (int)$c['event_count'],
        ];
    }, $rows));
}
