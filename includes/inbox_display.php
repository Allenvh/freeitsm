<?php
/**
 * What each ticket row in the inbox middle pane shows, and how.
 *
 * ── Why this is a setting rather than a decision ─────────────────────────────
 *
 * GitHub discussion #61 asked for two things: the assigned agent on the row, and
 * a coloured priority indicator. The request was to REPLACE the description
 * preview with "Assigned to: …", which is the one thing not done here — that line
 * is the only content signal in the row, and it is how you tell "printer jammed
 * again" from something that needs reading now.
 *
 * Everything else is offered rather than chosen, because row density is genuinely
 * personal: a manager scanning a queue for breaches wants different chips from
 * somebody working one department all day. Hence per-analyst, over an install
 * default an administrator sets for everyone who has not chosen.
 *
 * ── The one real design constraint ───────────────────────────────────────────
 *
 * ⚠️ THE ROW ALREADY HAS A COLOURED DOT, AND IT ALREADY MEANS SOMETHING.
 * `.email-sla-pill` carries an SLA dot in red / amber / green. Priority in a
 * traffic-light palette is the obvious second dot and would be actively harmful:
 * red would mean "about to breach" in one place and "Critical" in another, in the
 * same row, and those call for different actions.
 *
 * That is why STRIPE and BLOCK exist as styles at all, and why the shipped
 * default puts priority on the left edge rather than in the footer. Anyone who
 * prefers a dot can still choose one — they just have to choose it.
 */

/**
 * The registry: every field that can appear on a row, and the styles it accepts.
 *
 * ⚠️ THIS IS A WHITELIST, NOT DOCUMENTATION. Style values reach the browser as
 * CSS class-name fragments, so an unvalidated value is markup injection. Nothing
 * renders unless it appears here — see inboxDisplayNormalise().
 *
 * Adding a fourth field (department, ticket type, company) is a change to this
 * array plus a column in the list query. It was kept to three because that is
 * what was asked for; the shape is deliberately open.
 */
function inboxDisplayRegistry(): array
{
    return [
        // Colour-bearing fields. `colour_source` names the table column the
        // palette comes from, so the renderer never invents one.
        'priority' => [
            'colour_source' => 'ticket_priorities.colour',
            'styles'        => ['off', 'stripe', 'pill', 'block', 'dot'],
            'default'       => 'stripe',
        ],
        'status' => [
            'colour_source' => 'ticket_statuses.colour',
            'styles'        => ['off', 'stripe', 'pill', 'block', 'dot'],
            'default'       => 'off',
        ],
        // Text field: no colour, so no stripe/block/dot — a coloured edge that
        // encodes a person conveys nothing without a legend nobody will learn.
        'agent' => [
            'colour_source' => null,
            'styles'        => ['off', 'name', 'initials'],
            'default'       => 'initials',
        ],
        // WHICH person the name beside the ticket number refers to (GH #143,
        // reported by mbsouth). Unlike the three above this adds nothing to the
        // row — the name was always there; this decides who it is.
        //
        // 🔴 WHY THE DEFAULT CHANGED. The row used to show the sender of the
        // LATEST message, which is whoever spoke last — so the moment anybody
        // replied, the row started naming your own mailbox. On the development
        // install that was 52 of 118 rows reading "ITSM Support" or
        // "freeitsm.com inbox": a column of identical text where a person's name
        // should be, which is information-free exactly when the queue is busy.
        // 'requester' is therefore the default, and it is what the row shows for
        // anyone who has not chosen — including analysts whose saved config
        // predates this field, because inboxDisplayNormalise() fills a missing
        // field from its default.
        //
        // 'last_sender' keeps the old behaviour for desks that read the queue as
        // a mailbox and want to know who spoke last.
        'row_name' => [
            'colour_source' => null,
            'styles'        => ['requester', 'last_sender', 'off'],
            'default'       => 'requester',
        ],
    ];
}

/** The shipped default, used when neither the install nor the analyst has chosen. */
function inboxDisplayDefaults(): array
{
    $out = [];
    foreach (inboxDisplayRegistry() as $field => $spec) {
        $out[$field] = $spec['default'];
    }
    return $out;
}

/**
 * Coerce anything into a valid config.
 *
 * Unknown fields are dropped, unknown styles fall back to that field's default,
 * and a missing field takes its default — so a config saved by a future version
 * with more fields still renders correctly on an older one, and vice versa.
 *
 * @param mixed $raw  a saved config - a JSON string or an array of field => style; anything else gives the defaults
 */
function inboxDisplayNormalise($raw): array
{
    $registry = inboxDisplayRegistry();
    $out      = [];

    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        $raw     = is_array($decoded) ? $decoded : [];
    }
    if (!is_array($raw)) {
        $raw = [];
    }

    foreach ($registry as $field => $spec) {
        $value = isset($raw[$field]) && is_string($raw[$field]) ? $raw[$field] : null;
        $out[$field] = ($value !== null && in_array($value, $spec['styles'], true))
            ? $value
            : $spec['default'];
    }

    return $out;
}

/** The install-wide default an administrator has set, or the shipped one. */
function inboxDisplayInstallDefault(PDO $conn): array
{
    try {
        $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'inbox_row_display'");
        $stmt->execute();
        $row = $stmt->fetchColumn();
        if ($row !== false && $row !== null && $row !== '') {
            return inboxDisplayNormalise($row);
        }
    } catch (Exception) {
        // Setting table unavailable — the shipped default is still a valid answer.
    }
    return inboxDisplayDefaults();
}

/**
 * What THIS analyst should see: their own choice, else the install default.
 *
 * Deliberately never throws and never returns an invalid shape. A display
 * preference must not be able to break the inbox — the worst outcome available
 * here is showing somebody the wrong chips.
 */
function inboxDisplayForAnalyst(PDO $conn, int $analystId): array
{
    $installDefault = inboxDisplayInstallDefault($conn);

    if ($analystId <= 0) {
        return $installDefault;
    }

    try {
        $stmt = $conn->prepare(
            "SELECT preference_value FROM user_preferences
             WHERE analyst_id = ? AND preference_key = 'inbox_row_display'"
        );
        $stmt->execute([$analystId]);
        $row = $stmt->fetchColumn();
        if ($row !== false && $row !== null && $row !== '') {
            // ⚠️ Merge over the INSTALL default, not over the shipped one. An
            // analyst who saved a config before a field existed should pick up
            // the administrator's choice for that field, not the factory one.
            $mine = json_decode((string)$row, true);
            if (is_array($mine)) {
                return inboxDisplayNormalise(array_merge($installDefault, array_filter(
                    $mine,
                    fn($v) => is_string($v)
                )));
            }
        }
    } catch (Exception) {
        // No preferences table, or an un-migrated install.
    }

    return $installDefault;
}

/** Has this analyst chosen for themselves, or are they following the install default? */
function inboxDisplayIsPersonal(PDO $conn, int $analystId): bool
{
    if ($analystId <= 0) {
        return false;
    }
    try {
        $stmt = $conn->prepare(
            "SELECT COUNT(*) FROM user_preferences
             WHERE analyst_id = ? AND preference_key = 'inbox_row_display'"
        );
        $stmt->execute([$analystId]);
        return (int)$stmt->fetchColumn() > 0;
    } catch (Exception) {
        return false;
    }
}
