<?php
/**
 * Microsoft Teams channel alerts: service desk events as cards in Teams channels.
 *
 * A front page over Workflows, not a second engine. Each Teams channel has a
 * "Workflows" webhook address (in Teams: the channel → … → Workflows → "Post to
 * a channel when a webhook request is received"); every alert ticked for it on
 * System → Integrations → Microsoft Teams IS an ordinary workflow - a trigger,
 * conditions and a send_webhook action in the 'teams_workflows' card format - so
 * it runs through the same engine, retries and delivery log as any other, and
 * shows on the Workflows page. msteams_channel_workflows records which workflow
 * belongs to which channel and alert, so saving the page updates exactly its own
 * workflows and never one somebody wrote by hand.
 *
 * Contributed by Carl Steynvaard (Open Resilience), October 2026, and rebuilt
 * here: alerts by priority ORDER rather than the names "P1"/"P2", the team read
 * from what the events really carry, and workflows tracked by id rather than by
 * a marker in their description that an edit would lose.
 *
 * TRAP: filter a ticket's TEAM on what each event carries. ticket.created sends
 *   ticket.assigned_team_id (ticketDispatchCreated); ticket.team_assigned sends
 *   the team as a top-level team_id. A condition on the other one never matches,
 *   and says nothing - the alert is simply never sent.
 *
 * Not the Teams BOT (Tickets → Settings → Messaging, 3.1.0): that is a chat
 * channel people talk to. This only posts.
 */

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../encryption.php';
require_once __DIR__ . '/../webhook_delivery.php';
require_once __DIR__ . '/../public_url.php';
require_once __DIR__ . '/../../workflow/includes/engine.php';

/** The card format every alert uses (WorkflowEngine::BUILTIN_WEBHOOK_FORMATS). */
const MSTEAMS_FORMAT = 'teams_workflows';

/**
 * The alerts a channel can tick, in page order. 'needs': 'priority' (the
 * channel's "at or above" priority), 'teams' (its teams), or null.
 *
 * The messages are workflow text, stored in the workflow - English, like any
 * workflow an administrator writes, and editable in Workflows afterwards (until
 * this page is saved again).
 */
const MSTEAMS_ALERTS = [
    'priority_new' => [
        'label' => 'New ticket at or above the priority', 'trigger' => 'ticket.created', 'needs' => 'priority',
        'message' => "🚨 **New {{ticket.priority_name}} ticket {{ticket.number}}**\n\n{{ticket.subject}}\n\n**Requester:** {{ticket.requester_email}}",
    ],
    'priority_raised' => [
        'label' => 'Raised to or above the priority', 'trigger' => 'ticket.priority_changed', 'needs' => 'priority',
        'message' => "🚨 **{{ticket.number}} raised to {{ticket.priority_name}}**\n\n{{ticket.subject}}\n\n**Requester:** {{ticket.requester_email}}",
    ],
    'team_new' => [
        'label' => 'New ticket for one of the teams', 'trigger' => 'ticket.created', 'needs' => 'teams',
        'message' => "🎫 **New ticket {{ticket.number}} for {{ticket.assigned_team_name}}**\n\n{{ticket.subject}}\n\n**Priority:** {{ticket.priority_name}} · **Requester:** {{ticket.requester_email}}",
    ],
    'team_handover' => [
        'label' => 'Ticket handed to one of the teams', 'trigger' => 'ticket.team_assigned', 'needs' => 'teams',
        'message' => "📥 **{{ticket.number}} is now with {{team_name}}**\n\n{{ticket.subject}}\n\n**Priority:** {{ticket.priority_name}}",
    ],
    'every_new' => [
        'label' => 'Every new ticket', 'trigger' => 'ticket.created', 'needs' => null,
        'message' => "🆕 **New ticket {{ticket.number}}**\n\n{{ticket.subject}}\n\n**Priority:** {{ticket.priority_name}} · **Requester:** {{ticket.requester_email}}",
    ],
    'sla_warning' => [
        'label' => 'SLA warning', 'trigger' => 'sla.warning', 'needs' => null,
        'message' => "⏳ **SLA warning on {{ticket.number}}** - {{sla.percent}}% of the time used\n\n{{ticket.subject}}\n\n**Priority:** {{ticket.priority_name}}",
    ],
    'sla_breached' => [
        'label' => 'SLA breached', 'trigger' => 'sla.breached', 'needs' => null,
        'message' => "⏰ **SLA breached on {{ticket.number}}**\n\n{{ticket.subject}}\n\n**Priority:** {{ticket.priority_name}}",
    ],
    'status_incident' => [
        'label' => 'Status page incident', 'trigger' => 'service_status.incident_created', 'needs' => null,
        'message' => "📢 **Status page incident:** {{incident.title}}",
    ],
    'approval_waiting' => [
        'label' => 'Catalogue request waiting for approval', 'trigger' => 'catalogue_request.submitted', 'needs' => null,
        'message' => "📝 **Approval needed:** {{form.name}}\n\nRequested by {{submission.email}}.",
    ],
];

/** Are the tables there? (Database Verification creates them.) */
function msteamsReady(PDO $conn): bool
{
    try {
        $conn->query("SELECT 1 FROM msteams_channels LIMIT 1");
        $conn->query("SELECT 1 FROM msteams_channel_workflows LIMIT 1");
        return true;
    } catch (Exception) {
        return false;
    }
}

/** Configured = at least one channel with an address. Read by the Integrations index card. */
function msteamsChannelCount(PDO $conn): int
{
    try {
        return (int)$conn->query("SELECT COUNT(*) FROM msteams_channels WHERE webhook_url IS NOT NULL AND webhook_url <> ''")->fetchColumn();
    } catch (Exception) {
        return 0;
    }
}

/** A JSON list column, as a list of strings or ints; anything else is empty. */
function msteamsJsonList(?string $raw, bool $ints = false): array
{
    $v = json_decode((string)$raw, true);
    if (!is_array($v)) return [];
    return array_values(array_unique($ints ? array_map('intval', $v) : array_map('strval', $v)));
}

/**
 * Every channel, for the page. The address is NEVER returned - only whether
 * there is one: it is a credential (anyone holding it can post to the channel).
 */
function msteamsChannels(PDO $conn): array
{
    $rows = $conn->query("SELECT c.id, c.name, c.webhook_url, c.alerts, c.priority_threshold_id, c.team_ids,
                                 (SELECT COUNT(*) FROM msteams_channel_workflows w
                                    JOIN workflows f ON f.id = w.workflow_id AND f.is_active = 1
                                   WHERE w.channel_id = c.id) AS workflows
                            FROM msteams_channels c ORDER BY c.display_order, c.id")->fetchAll(PDO::FETCH_ASSOC);
    return array_map(fn($r) => [
        'id'                    => (int)$r['id'],
        'name'                  => (string)$r['name'],
        'has_url'               => (string)$r['webhook_url'] !== '',
        'alerts'                => array_values(array_intersect(msteamsJsonList($r['alerts']), array_keys(MSTEAMS_ALERTS))),
        'priority_threshold_id' => $r['priority_threshold_id'] !== null ? (int)$r['priority_threshold_id'] : null,
        'team_ids'              => msteamsJsonList($r['team_ids'], true),
        'workflows'             => (int)$r['workflows'],
    ], $rows);
}

/**
 * The active priorities at or above this one - a higher display_order is more
 * urgent, the same reading as Watchtower and the Zabbix map. No priority NAME is
 * assumed: the contributed version looked for "P1" and "P2".
 *
 * @return list<string>  ids, as the workflow condition stores them
 */
function msteamsPrioritiesAtOrAbove(PDO $conn, int $priorityId): array
{
    $s = $conn->prepare("SELECT p.id FROM ticket_priorities p
                          JOIN ticket_priorities t ON t.id = ?
                         WHERE p.is_active = 1 AND (p.display_order > t.display_order OR (p.display_order = t.display_order AND p.id = t.id))
                         ORDER BY p.display_order, p.id");
    $s->execute([$priorityId]);
    return array_map('strval', $s->fetchAll(PDO::FETCH_COLUMN));
}

/**
 * The workflow an alert becomes for a channel: [trigger, conditions], or null
 * when the channel's settings cannot make it (no priority chosen, no teams).
 */
function msteamsAlertRule(PDO $conn, string $key, ?int $threshold, array $teamIds): ?array
{
    $a = MSTEAMS_ALERTS[$key];
    $conds = [];
    if ($a['needs'] === 'priority') {
        if (!$threshold) return null;
        $ids = msteamsPrioritiesAtOrAbove($conn, $threshold);
        if (!$ids) return null;
        if ($key === 'priority_new') {
            $conds[] = ['field' => 'ticket.priority_id', 'op' => 'in', 'value' => $ids];
        } else {
            // Raised INTO the band, not moved about within it: Urgent → High is
            // not news to a channel already told about the Urgent one.
            $conds[] = ['field' => 'new_priority_id', 'op' => 'in', 'value' => $ids];
            $conds[] = ['field' => 'old_priority_id', 'op' => 'not_in', 'value' => $ids];
        }
    }
    if ($a['needs'] === 'teams') {
        if (!$teamIds) return null;
        $field = $key === 'team_new' ? 'ticket.assigned_team_id' : 'team_id';   // see the TRAP above
        $conds[] = ['field' => $field, 'op' => 'in', 'value' => array_map('strval', $teamIds)];
    }
    return [$a['trigger'], $conds];
}

/**
 * Makes the channel's workflows match its settings: one per ticked alert the
 * settings can make, updated in place when it already exists; any other
 * workflow of this channel is deleted (its run history stays - executions and
 * deliveries keep their rows with workflow_id NULL). No address = no workflows.
 *
 * @return int  how many alerts are now on
 */
function msteamsSyncWorkflows(PDO $conn, int $channelId, int $analystId): int
{
    $s = $conn->prepare("SELECT * FROM msteams_channels WHERE id = ?");
    $s->execute([$channelId]);
    $ch = $s->fetch(PDO::FETCH_ASSOC);
    if (!$ch) return 0;
    $url = (string)$ch['webhook_url'] !== '' ? (string)decryptValue((string)$ch['webhook_url']) : '';
    $threshold = $ch['priority_threshold_id'] !== null ? (int)$ch['priority_threshold_id'] : null;
    $teamIds = msteamsJsonList($ch['team_ids'], true);

    $want = [];
    if ($url !== '') {
        foreach (msteamsJsonList($ch['alerts']) as $key) {
            if (!isset(MSTEAMS_ALERTS[$key])) continue;
            $rule = msteamsAlertRule($conn, $key, $threshold, $teamIds);
            if ($rule) $want[$key] = $rule;
        }
    }

    $have = $conn->prepare("SELECT w.alert_key, w.workflow_id, (f.id IS NOT NULL) AS still_there
                              FROM msteams_channel_workflows w LEFT JOIN workflows f ON f.id = w.workflow_id
                             WHERE w.channel_id = ?");
    $have->execute([$channelId]);
    $have = $have->fetchAll(PDO::FETCH_ASSOC);
    $mine = [];
    foreach ($have as $h) $mine[$h['alert_key']] = (int)$h['still_there'] ? (int)$h['workflow_id'] : 0;

    foreach ($mine as $key => $wid) {
        if (isset($want[$key])) continue;
        if ($wid) $conn->prepare("DELETE FROM workflows WHERE id = ?")->execute([$wid]);
        $conn->prepare("DELETE FROM msteams_channel_workflows WHERE channel_id = ? AND alert_key = ?")->execute([$channelId, $key]);
    }

    foreach ($want as $key => [$trigger, $conds]) {
        $a = MSTEAMS_ALERTS[$key];
        $name = mb_substr("Teams: {$a['label']} → {$ch['name']}", 0, 255);
        $desc = "Posts a card to the Microsoft Teams channel \"{$ch['name']}\". Made by System → Integrations → Microsoft Teams: "
              . "change it there - changes made here are replaced the next time that channel is saved.";
        $actions = json_encode(webhookEncryptActions([['type' => 'send_webhook', 'args' => [
            'preset' => MSTEAMS_FORMAT, 'url' => $url, 'message' => $a['message'], 'secret' => '',
        ]]]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $condJson = json_encode($conds, JSON_UNESCAPED_SLASHES);
        $wid = $mine[$key] ?? 0;
        if ($wid) {
            $conn->prepare("UPDATE workflows SET name = ?, description = ?, trigger_event = ?, conditions = ?, actions = ?, is_active = 1 WHERE id = ?")
                 ->execute([$name, $desc, $trigger, $condJson, $actions, $wid]);
        } else {
            $conn->prepare("INSERT INTO workflows (name, description, trigger_event, conditions, actions, is_active, created_by, created_datetime, updated_datetime)
                            VALUES (?, ?, ?, ?, ?, 1, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())")
                 ->execute([$name, $desc, $trigger, $condJson, $actions, $analystId ?: null]);
            $newId = (int)$conn->lastInsertId();
            $conn->prepare("REPLACE INTO msteams_channel_workflows (channel_id, alert_key, workflow_id) VALUES (?, ?, ?)")
                 ->execute([$channelId, $key, $newId]);
        }
    }
    return count($want);
}

/**
 * Saves one channel (new when id is 0) and brings its workflows into line.
 *
 * @param array $in  id, name, webhook_url ('' = keep the saved one), clear_url, alerts [keys],
 *                   priority_threshold_id (0 = none), team_ids [ids]
 * @return array{id:int, alerts_on:int}
 * @throws InvalidArgumentException  with a sentence to show
 */
function msteamsSaveChannel(PDO $conn, int $analystId, array $in): array
{
    $id = (int)($in['id'] ?? 0);
    $name = trim((string)($in['name'] ?? ''));
    if ($name === '') throw new InvalidArgumentException('Give the channel a name.');
    $url = trim((string)($in['webhook_url'] ?? ''));
    if ($url !== '' && !preg_match('#^https://\S+$#i', $url)) throw new InvalidArgumentException('The webhook address must start with https://');
    if (strlen($url) > 1000) throw new InvalidArgumentException('That webhook address is too long.');
    $alerts = array_values(array_intersect(array_map('strval', (array)($in['alerts'] ?? [])), array_keys(MSTEAMS_ALERTS)));
    $threshold = (int)($in['priority_threshold_id'] ?? 0);
    if ($threshold > 0) {
        $p = $conn->prepare("SELECT 1 FROM ticket_priorities WHERE id = ?");
        $p->execute([$threshold]);
        if (!$p->fetchColumn()) throw new InvalidArgumentException('That priority no longer exists.');
    }
    $teamIds = array_values(array_unique(array_filter(array_map('intval', (array)($in['team_ids'] ?? [])))));
    if ($teamIds) {
        $known = $conn->query("SELECT id FROM teams WHERE id IN (" . implode(',', $teamIds) . ")")->fetchAll(PDO::FETCH_COLUMN);
        $teamIds = array_values(array_intersect($teamIds, array_map('intval', $known)));
    }
    foreach ($alerts as $k) {
        if (MSTEAMS_ALERTS[$k]['needs'] === 'priority' && $threshold <= 0) throw new InvalidArgumentException('Choose the priority for "' . MSTEAMS_ALERTS[$k]['label'] . '".');
        if (MSTEAMS_ALERTS[$k]['needs'] === 'teams' && !$teamIds) throw new InvalidArgumentException('Tick at least one team for "' . MSTEAMS_ALERTS[$k]['label'] . '".');
    }

    $conn->beginTransaction();
    try {
        $vals = [mb_substr($name, 0, 100), json_encode($alerts), $threshold > 0 ? $threshold : null, json_encode($teamIds)];
        if ($id > 0) {
            $s = $conn->prepare("SELECT webhook_url FROM msteams_channels WHERE id = ? FOR UPDATE");
            $s->execute([$id]);
            $old = $s->fetchColumn();
            if ($old === false) throw new InvalidArgumentException('That channel no longer exists.');
            $stored = !empty($in['clear_url']) ? null : ($url !== '' ? encryptValue($url) : $old);
            $conn->prepare("UPDATE msteams_channels SET name = ?, alerts = ?, priority_threshold_id = ?, team_ids = ?, webhook_url = ? WHERE id = ?")
                 ->execute(array_merge($vals, [$stored, $id]));
        } else {
            $order = (int)$conn->query("SELECT COALESCE(MAX(display_order), 0) + 10 FROM msteams_channels")->fetchColumn();
            $conn->prepare("INSERT INTO msteams_channels (name, alerts, priority_threshold_id, team_ids, webhook_url, display_order) VALUES (?, ?, ?, ?, ?, ?)")
                 ->execute(array_merge($vals, [$url !== '' ? encryptValue($url) : null, $order]));
            $id = (int)$conn->lastInsertId();
        }
        $on = msteamsSyncWorkflows($conn, $id, $analystId);
        $conn->commit();
    } catch (Throwable $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        throw $e;
    }
    return ['id' => $id, 'alerts_on' => $on];
}

/** Removes a channel and the workflows it made. */
function msteamsDeleteChannel(PDO $conn, int $id): void
{
    $conn->beginTransaction();
    try {
        $s = $conn->prepare("SELECT workflow_id FROM msteams_channel_workflows WHERE channel_id = ?");
        $s->execute([$id]);
        foreach ($s->fetchAll(PDO::FETCH_COLUMN) as $wid) $conn->prepare("DELETE FROM workflows WHERE id = ?")->execute([(int)$wid]);
        $conn->prepare("DELETE FROM msteams_channel_workflows WHERE channel_id = ?")->execute([$id]);
        $conn->prepare("DELETE FROM msteams_channels WHERE id = ?")->execute([$id]);
        $conn->commit();
    } catch (Throwable $e) {
        $conn->rollBack();
        throw $e;
    }
}

/**
 * Posts a test card to a channel through the SAME request builder and transport
 * a real alert uses (WorkflowEngine::buildWebhookRequest, webhookHttpSend), so
 * "the test arrived" means the alerts will.
 *
 * @return array{ok:bool, status:int, detail:string}
 */
function msteamsSendTest(PDO $conn, int $channelId, string $sentBy): array
{
    $s = $conn->prepare("SELECT name, webhook_url FROM msteams_channels WHERE id = ?");
    $s->execute([$channelId]);
    $ch = $s->fetch(PDO::FETCH_ASSOC);
    if (!$ch) throw new InvalidArgumentException('That channel no longer exists.');
    if ((string)$ch['webhook_url'] === '') throw new InvalidArgumentException('Save a webhook address for this channel first.');
    $payload = WorkflowEngine::enrichWithLinks($conn, ['event' => 'msteams.test']);
    $req = WorkflowEngine::buildWebhookRequest([
        'preset'  => MSTEAMS_FORMAT,
        'url'     => (string)decryptValue((string)$ch['webhook_url']),
        'message' => "✅ **FreeITSM is connected to this channel**\n\nAlerts ticked for \"{$ch['name']}\" on System → Integrations → Microsoft Teams will appear here. Sent by {$sentBy}.",
    ], $payload);
    $r = webhookHttpSend($req['url'], $req['headers'], $req['body']);
    $ok = $r['status'] >= 200 && $r['status'] < 300;
    $detail = $r['status'] ? 'HTTP ' . $r['status'] . ($ok ? '' : ': ' . mb_substr(trim(strip_tags((string)$r['body'])), 0, 200)) : (string)$r['error'];
    return ['ok' => $ok, 'status' => (int)$r['status'], 'detail' => $detail];
}
