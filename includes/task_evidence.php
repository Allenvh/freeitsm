<?php
/**
 * Repeating task evidence (#181 Parts E and F) - the series report, worked out from the record.
 *
 * Reads what includes/services/task_history.php wrote (task_events, rule versions, removed
 * records, step history) and #182's run history (job_runs), and answers an auditor's
 * question: for this repeating task, which occurrences SHOULD have happened in a period,
 * and what happened to each? Nothing here writes a task - viewing or exporting the report
 * never creates an occurrence (#181 acceptance criterion 2). The only write is the line
 * recordExport() adds to the series' history when evidence is exported.
 *
 * The rules, and where they came from:
 *
 *  - OUTCOMES are judged twice (Dr4inder, 19 Oct): against the ORIGINAL due date (the
 *    date the repeat made the occurrence for - scheduled_date) and against the due date
 *    IN FORCE when it was completed (recorded on the completed event). On time = on or
 *    before both; On time after rescheduling = after the original, on or before the moved
 *    date; Late = after the date in force. A due-date edit AFTER completion is flagged
 *    and never changes the outcome. Dates compare in the INSTALL's time zone, because a
 *    due date is a calendar day.
 *  - MISSING, fixed schedule: an expected date (from the rule versions) with no
 *    occurrence and no removed record. Only from the first recorded rule version
 *    onwards - before that the rule was not recorded, and the report says so rather
 *    than guessing. A date whose occurrence is due to be made today is "not created yet".
 *  - MISSING, after completion: never before the previous occurrence is completed - but
 *    once a completion or skip recorded that a successor was expected (a 'successor'
 *    event, written BEFORE the successor is created) and none exists, it is missing.
 *  - REMOVED occurrences come from their frozen copy (task_removed_records), so their
 *    checklist results, exceptions and history are still in the report.
 *  - EXPLANATIONS say only what the run history establishes (Dr4inder on #182): "no
 *    recorded run", never "did not run"; "not available" before recording began or past
 *    retention.
 *
 * 🔒 Company scope: an occurrence the viewer's companies cannot see is left out (and
 * counted, so the total does not silently shrink). Linked tickets and changes are named
 * by number only.
 */

require_once __DIR__ . '/services/task_recurrence.php';
require_once __DIR__ . '/services/task_history.php';
require_once __DIR__ . '/tenancy.php';
require_once __DIR__ . '/job_registry.php';

final class TaskEvidence
{
    /** The install's time zone, in which completion times are turned into calendar days. */
    private static function tz(): DateTimeZone
    {
        return new DateTimeZone(date_default_timezone_get());
    }

    /** A stored UTC datetime as a calendar day in the install's time zone, or null. */
    public static function localDate(?string $utc): ?string
    {
        if (!$utc) return null;
        try {
            return (new DateTimeImmutable($utc, new DateTimeZone('UTC')))->setTimezone(self::tz())->format('Y-m-d');
        } catch (Throwable) {
            return null;
        }
    }

    /** A stored UTC datetime in the install's time zone, Y-m-d H:i. */
    public static function localDateTime(?string $utc): ?string
    {
        if (!$utc) return null;
        try {
            return (new DateTimeImmutable($utc, new DateTimeZone('UTC')))->setTimezone(self::tz())->format('Y-m-d H:i');
        } catch (Throwable) {
            return $utc;
        }
    }

    /**
     * A bare calendar day (a due or scheduled date) in the viewer's date format - NOT converted between
     * zones, because "the 1st" is the 1st wherever the reader is.
     *
     * @param ?string $ymd  YYYY-MM-DD, or null
     */
    public static function day(?string $ymd): string
    {
        if (!$ymd) return '';
        try {
            $dt = new DateTime(substr($ymd, 0, 10) . ' 12:00:00');
            return class_exists('DateFmt') ? DateFmt::render($dt, DateFmt::DATE_TEMPLATES[DateFmt::dateKey()]) : $dt->format('Y-m-d');
        } catch (Throwable) {
            return (string)$ymd;
        }
    }

    /**
     * A stored UTC instant shown in the INSTALL's time zone with the viewer's formats - the report states
     * one zone for everything, so a printed page means the same to every reader.
     *
     * @param ?string $utc  a UTC datetime as stored
     */
    public static function when(?string $utc): string
    {
        if (!$utc) return '';
        try {
            $dt = new DateTime($utc, new DateTimeZone('UTC'));
            $dt->setTimezone(self::tz());
            return class_exists('DateFmt')
                ? DateFmt::render($dt, DateFmt::DATE_TEMPLATES[DateFmt::dateKey()]) . ' ' . DateFmt::render($dt, DateFmt::TIME_TEMPLATES[DateFmt::timeKey()])
                : $dt->format('Y-m-d H:i');
        } catch (Throwable) {
            return (string)$utc;
        }
    }

    /** May the analyst see a row in this company? NULL is the Default company. */
    private static function tenantVisible(PDO $conn, int $analystId, $tenantId): bool
    {
        if (!isMultiTenant($conn)) return true;
        $t = $tenantId === null ? getDefaultTenantId($conn) : (int)$tenantId;
        return analystCanAccessTenant($conn, $analystId, $t);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  The series list (Tasks -> Evidence)
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Every repeating series the analyst can see - live, stopped, finished, or with every task gone - newest
     * activity first. A series whose tasks have all been deleted is still listed from its rule versions, so its
     * evidence stays reachable (Dr4inder on #181).
     *
     * @return array<int,array{id:int,title:string,mode:?string,state:string,occurrences:int,removed:int,last_due:?string,rule:?array}>
     */
    public static function seriesList(PDO $conn, int $analystId): array
    {
        $ids = [];
        try {
            foreach ($conn->query("SELECT id FROM task_recurrences")->fetchAll(PDO::FETCH_COLUMN) as $id) $ids[(int)$id] = true;
            foreach ($conn->query("SELECT DISTINCT recurrence_id FROM task_recurrence_versions")->fetchAll(PDO::FETCH_COLUMN) as $id) $ids[(int)$id] = true;
        } catch (Throwable) { /* before Database Verification */ }

        $out = [];
        foreach (array_keys($ids) as $rid) {
            $s = self::seriesHeader($conn, $rid);
            if (!$s || !self::tenantVisible($conn, $analystId, $s['tenant_id'])) continue;
            $out[] = $s;
        }
        usort($out, fn($a, $b) => strcmp((string)$b['last_activity'], (string)$a['last_activity']));
        return $out;
    }

    /**
     * What is known about a series even when its rule row or its tasks are gone.
     *
     * @param int $rid  the series (task_recurrences.id)
     */
    public static function seriesHeader(PDO $conn, int $rid): ?array
    {
        $one = function (string $sql, array $a) use ($conn) {
            try { $s = $conn->prepare($sql); $s->execute($a); return $s->fetch(PDO::FETCH_ASSOC) ?: null; } catch (Throwable) { return null; }
        };
        $rule   = $one("SELECT * FROM task_recurrences WHERE id = ?", [$rid]);
        $latest = $one("SELECT id, title, tenant_id, due_date, updated_datetime FROM tasks
                         WHERE recurrence_id = ? AND parent_task_id IS NULL ORDER BY id DESC LIMIT 1", [$rid]);
        $ver    = $one("SELECT * FROM task_recurrence_versions WHERE recurrence_id = ? ORDER BY version_no DESC LIMIT 1", [$rid]);
        if (!$rule && !$latest && !$ver) return null;
        $count  = $one("SELECT COUNT(*) AS n, MAX(due_date) AS last_due FROM tasks WHERE recurrence_id = ? AND parent_task_id IS NULL", [$rid]);
        $removed = $one("SELECT COUNT(*) AS n FROM task_removed_records WHERE recurrence_id = ?", [$rid]);
        $verRule = $ver ? (json_decode($ver['rule_json'], true) ?: []) : [];

        $state = !$rule ? 'removed' : ((int)$rule['is_active'] ? 'active' : (($ver['change_kind'] ?? '') === 'finished' ? 'finished' : 'stopped'));
        if ($rule && !$latest) $state = 'no_tasks';
        return [
            'id'            => $rid,
            'title'         => $latest['title'] ?? ($ver['title'] ?? ('#' . $rid)),
            'tenant_id'     => $latest ? $latest['tenant_id'] : ($ver['tenant_id'] ?? null),
            'mode'          => $rule['mode'] ?? ($verRule['mode'] ?? null),
            'rule'          => $rule ?: ($verRule ?: null),
            'state'         => $state,
            'occurrences'   => (int)($count['n'] ?? 0),
            'removed'       => (int)($removed['n'] ?? 0),
            'last_due'      => $count['last_due'] ?? null,
            'last_activity' => $latest['updated_datetime'] ?? ($ver['created_datetime'] ?? null),
            'latest_task_id'=> $latest ? (int)$latest['id'] : null,
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    //  The report
    // ─────────────────────────────────────────────────────────────────────

    /**
     * The evidence report for one series over a period of SCHEDULED dates.
     *
     * @param int $rid  the series
     * @param string $from  first scheduled date (YYYY-MM-DD) to include
     * @param string $to  last scheduled date to include
     * @param int $analystId  the viewer - occurrences in companies they cannot see are left out
     * @return array{series:array, period:array, versions:array, rows:array, summary:array, hidden:int, notes:array, exports:array}
     */
    public static function report(PDO $conn, int $rid, string $from, string $to, int $analystId): array
    {
        $series = self::seriesHeader($conn, $rid);
        if (!$series) throw new InvalidArgumentException('No such repeating task.');
        $today = date('Y-m-d');
        $q = function (string $sql, array $a) use ($conn): array {
            try { $s = $conn->prepare($sql); $s->execute($a); return $s->fetchAll(PDO::FETCH_ASSOC) ?: []; } catch (Throwable) { return []; }
        };

        $versions = $q("SELECT * FROM task_recurrence_versions WHERE recurrence_id = ? ORDER BY version_no", [$rid]);
        foreach ($versions as &$v) { $v['rule'] = json_decode($v['rule_json'], true) ?: []; unset($v['rule_json']); }
        unset($v);

        // Every occurrence: the live ones, and the removed ones from their frozen copies.
        $live = $q("SELECT t.*, ts.name AS status_name, ts.is_closed AS status_is_closed,
                           a.full_name AS assignee_name, tm.name AS team_name, cb.full_name AS completed_by_name
                      FROM tasks t
                      LEFT JOIN task_statuses ts ON ts.id = t.status_id
                      LEFT JOIN analysts a ON a.id = t.assigned_analyst_id
                      LEFT JOIN teams tm ON tm.id = t.assigned_team_id
                      LEFT JOIN analysts cb ON cb.id = t.completed_by_id
                     WHERE t.recurrence_id = ? AND t.parent_task_id IS NULL ORDER BY t.id", [$rid]);
        $removed = $q("SELECT * FROM task_removed_records WHERE recurrence_id = ? ORDER BY id", [$rid]);
        $events = [];
        foreach ($q("SELECT * FROM task_events WHERE recurrence_id = ? ORDER BY id", [$rid]) as $e) {
            $e['detail'] = $e['detail_json'] ? (json_decode($e['detail_json'], true) ?: []) : [];
            $events[(int)$e['task_id']][] = $e;
        }

        $occ = [];
        $hidden = 0;
        // GH #190: an occurrence's form answers follow the ONE answers rule
        // (TaskForms::mayReadAnswers) - on screen, in print and in the CSV alike.
        require_once __DIR__ . '/services/task_forms.php';
        require_once __DIR__ . '/services/task_procedures.php';
        require_once __DIR__ . '/services/task_follow_ups.php';
        $mayReadAnswers = TaskForms::mayReadAnswers($conn, $analystId);
        foreach ($live as $t) {
            if (!self::tenantVisible($conn, $analystId, $t['tenant_id'])) { $hidden++; continue; }
            $o = self::occurrenceFromLive($conn, $t, $events[(int)$t['id']] ?? []);
            $o['forms'] = self::formsOf(TaskForms::snapshot($conn, (int)$t['id']), $mayReadAnswers);
            self::addFormExceptions($o, $mayReadAnswers);
            // GH #190 phase 2: the procedures it followed, and the frozen text each points at.
            $o['procedures'] = TaskProcedures::snapshot($conn, (int)$t['id']);
            // GH #190 phase 3: follow-ups, named by number only where the viewer cannot see them.
            $o['follow_ups'] = TaskFollowUps::forTask($conn, (int)$t['id'], $analystId);
            $occ[] = $o;
        }
        foreach ($removed as $r) {
            if (!self::tenantVisible($conn, $analystId, $r['tenant_id'])) { $hidden++; continue; }
            $o = self::occurrenceFromRemoved($r);
            $snap = json_decode((string)$r['snapshot_json'], true) ?: [];
            $o['forms'] = self::formsOf($snap['forms'] ?? [], $mayReadAnswers);
            self::addFormExceptions($o, $mayReadAnswers);
            $o['procedures'] = $snap['procedures'] ?? [];
            $o['follow_ups'] = $snap['follow_ups'] ?? [];
            $occ[] = $o;
        }

        $mode = $series['mode'] ?? 'completion';
        $rows = [];
        $notes = [];
        $firstVersion = $versions[0] ?? null;
        $recordedFrom = $firstVersion ? self::localDate($firstVersion['created_datetime']) : null;

        // Index the occurrences by the date they were made FOR.
        $byDate = [];
        foreach ($occ as $o) {
            if ($o['scheduled'] !== null) $byDate[$o['scheduled']][] = $o;
        }

        if ($mode === 'schedule') {
            // Expected dates, walked from the series' first occurrence with the rule in force at each date.
            $expected = self::expectedDates($conn, $series, $versions, $occ, $to, $today);
            foreach ($expected['dates'] as $d => $info) {
                if ($d < $from || $d > $to) continue;
                if (!empty($byDate[$d])) continue;                       // it exists (listed below)
                if ($recordedFrom !== null && $firstVersion['change_kind'] === 'baseline' && $d < $recordedFrom) continue;
                if ($info['begins'] >= $today) {
                    if ($info['begins'] === $today) $rows[] = self::missingRow($d, $info['begins'], 'not_created_yet');
                    continue;
                }
                $rows[] = self::missingRow($d, $info['begins'], 'missing')
                        + ['explanation' => self::explainGap($conn, $info['begins'], null)];
            }
            foreach ($expected['stopped'] as $span) {
                if ($span['to'] < $from || $span['from'] > $to) continue;
                $notes[] = ['kind' => 'stopped', 'from' => $span['from'], 'to' => $span['to'], 'by' => $span['by']];
            }
            if ($recordedFrom !== null && $firstVersion['change_kind'] === 'baseline' && $from < $recordedFrom) {
                $notes[] = ['kind' => 'rule_not_recorded', 'before' => $recordedFrom];
            }
        } else {
            // After completion: a successor that a completion or skip made due, and that never appeared.
            $successorOf = [];
            foreach ($occ as $o) if ($o['previous_id']) $successorOf[$o['previous_id']] = true;
            foreach ($occ as $o) {
                $exp = $o['successor_expected'];
                if (!$exp || isset($successorOf[$o['task_id']])) continue;
                $d = $exp['due'];
                if ($d === null || $d < $from || $d > $to) continue;
                $rows[] = self::missingRow($d, $d, 'missing') + [
                    'missing_successor_of' => $o['task_id'],
                    'explanation' => ['code' => 'successor_not_created', 'expected_at' => $exp['at']],
                ];
            }
            $notes[] = ['kind' => 'completion_mode'];
        }

        foreach ($occ as $o) {
            $d = $o['scheduled'];
            if ($d === null || $d < $from || $d > $to) continue;
            if ($o['flags']['created_late'] ?? false) {
                $o['explanation'] = self::explainGap($conn, $o['begins'] ?? $d, $o['created_utc'], $o['created_run_id']);
            }
            $rows[] = $o;
        }

        // In date order; for an after-completion repeat, the gap between one completion and the next.
        usort($rows, fn($a, $b) => strcmp($a['scheduled'] . sprintf('%09d', $a['task_id'] ?? 0), $b['scheduled'] . sprintf('%09d', $b['task_id'] ?? 0)));
        if ($mode !== 'schedule') {
            $prev = null;
            foreach ($rows as &$r) {
                if (empty($r['completed_local'])) continue;
                if ($prev) $r['gap_days'] = (int)(new DateTimeImmutable($prev))->diff(new DateTimeImmutable($r['completed_local']))->days;
                $prev = $r['completed_local'];
            }
            unset($r);
        }

        $summary = ['expected' => count($rows), 'on_time' => 0, 'on_time_rescheduled' => 0, 'late' => 0, 'overdue' => 0,
                    'open' => 0, 'skipped' => 0, 'missing' => 0, 'not_created_yet' => 0, 'removed' => 0,
                    'exceptions' => 0, 'created_late' => 0, 'rescheduled' => 0, 'not_recorded' => 0];
        foreach ($rows as $r) {
            if (isset($summary[$r['outcome']])) $summary[$r['outcome']]++;
            $summary['exceptions']   += count($r['exceptions'] ?? []);
            $summary['created_late'] += !empty($r['flags']['created_late']) ? 1 : 0;
            $summary['rescheduled']  += !empty($r['flags']['rescheduled']) ? 1 : 0;
            $summary['not_recorded'] += !empty($r['flags']['not_recorded']) ? 1 : 0;
        }

        $exports = $q("SELECT created_datetime, actor_name, to_value, detail_json FROM task_events
                        WHERE recurrence_id = ? AND event_type = 'evidence_exported' ORDER BY id DESC LIMIT 50", [$rid]);

        // GH #190 phase 2: each frozen procedure text ONCE, for the "Procedures used" section.
        // Its words follow Knowledge access: without it the report names the procedure and version only.
        $procIds = [];
        foreach ($rows as $row) foreach ($row['procedures'] ?? [] as $p) if (!empty($p['snapshot_id'])) $procIds[] = (int)$p['snapshot_id'];
        $procedureTexts = TaskProcedures::mayReadKnowledge($conn, $analystId) ? TaskProcedures::snapshots($conn, $procIds) : [];

        return [
            'procedure_texts'        => $procedureTexts,
            'procedure_texts_hidden' => $procIds && !$procedureTexts,
            'series'   => $series,
            'period'   => ['from' => $from, 'to' => $to, 'today' => $today, 'tz' => date_default_timezone_get(),
                           'produced_utc' => gmdate('Y-m-d H:i:s'), 'recording_since' => $recordedFrom],
            'versions' => $versions,
            'rows'     => $rows,
            'summary'  => $summary,
            'hidden'   => $hidden,
            'notes'    => $notes,
            'exports'  => $exports,
        ];
    }

    /** A row for an occurrence that does not exist: missing, or not created yet. */
    private static function missingRow(string $scheduled, string $begins, string $outcome): array
    {
        return ['kind' => 'expected', 'task_id' => null, 'scheduled' => $scheduled, 'begins' => $begins,
                'outcome' => $outcome, 'flags' => [], 'exceptions' => [], 'checklists' => [], 'events' => []];
    }

    /**
     * One live occurrence, judged.
     *
     * @param array $t  the task row with its status, assignee and completer names
     * @param array $events  its task_events, oldest first
     */
    private static function occurrenceFromLive(PDO $conn, array $t, array $events): array
    {
        $lists = [];
        try {
            $s = $conn->prepare("SELECT * FROM task_checklists WHERE task_id = ? ORDER BY sort_order, id");
            $s->execute([(int)$t['id']]);
            $lists = $s->fetchAll(PDO::FETCH_ASSOC);
            $i = $conn->prepare("SELECT * FROM task_checklist_items WHERE task_checklist_id = ? ORDER BY sort_order, id");
            foreach ($lists as &$l) { $i->execute([(int)$l['id']]); $l['items'] = $i->fetchAll(PDO::FETCH_ASSOC); }
            unset($l);
        } catch (Throwable) { /* none */ }
        $steps = [];
        try {
            $s = $conn->prepare("SELECT * FROM task_checklist_item_events WHERE task_id = ? ORDER BY id");
            $s->execute([(int)$t['id']]);
            $steps = $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable) { /* none */ }
        $audit = [];
        try {
            $s = $conn->prepare("SELECT * FROM task_audit WHERE task_id = ? ORDER BY id");
            $s->execute([(int)$t['id']]);
            $audit = $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable) { /* none */ }
        return self::judge($t, $lists, $steps, $events, $audit, null);
    }

    /**
     * An occurrence's forms for the report (GH #190): each requirement, the submission that met
     * it, any earlier ones it replaced, and - only where the viewer may read them - the answers.
     * Built from TaskForms::snapshot() for a live occurrence and from the frozen copy for a removed
     * one, so both read the same.
     *
     * @param array $snap  ['requirements' => [...], 'submissions' => [...]]
     * @param bool $mayRead  TaskForms::mayReadAnswers() for the viewer
     */
    private static function formsOf(array $snap, bool $mayRead): array
    {
        $subs = [];
        foreach ($snap['submissions'] ?? [] as $s) $subs[(int)$s['id']] = $s;
        $out = [];
        foreach ($snap['requirements'] ?? [] as $req) {
            $sid = $req['submission_id'] !== null ? (int)$req['submission_id'] : null;
            $s = $sid && isset($subs[$sid]) ? $subs[$sid] : null;
            // The submissions this one replaced: follow each superseded_by_id chain to it.
            $earlier = [];
            foreach ($subs as $x) {
                $at = $x; $hops = 0;
                while ($sid && $at['superseded_by_id'] !== null && $hops++ < 100) {
                    if ((int)$at['superseded_by_id'] === $sid) { $earlier[] = $x; break; }
                    $at = $subs[(int)$at['superseded_by_id']] ?? ['superseded_by_id' => null];
                }
            }
            $out[] = [
                'title'         => (string)$req['title'],
                'required'      => (int)$req['is_required'] === 1,
                'mode'          => (string)$req['requirement_mode'],
                'submission_id' => $sid,
                'form_id'       => $s ? (int)$s['form_id'] : (int)$req['form_id'],
                'by'            => $s['submitted_by'] ?? null,
                'at_utc'        => $s['submitted_date'] ?? null,
                'earlier'       => count($earlier),
                'answers'       => $s && $mayRead ? ($s['answers'] ?? []) : null,
                // Counted for everybody; WHICH answer, only for those who may read answers.
                'exception_answers' => $s ? array_values(array_filter($s['answers'] ?? [], fn($a) => !empty($a['exception']))) : [],
                'answers_hidden'=> $s !== null && !$mayRead,
            ];
        }
        return $out;
    }

    /**
     * GH #190 phase 4: answers marked "counts as an exception" join the occurrence's exceptions,
     * beside failed checklist steps, so the summary counts them. Without access to the answers
     * the exception is still listed - that something was flagged - but not the answer itself.
     */
    private static function addFormExceptions(array &$o, bool $mayRead): void
    {
        foreach ($o['forms'] ?? [] as $f) {
            foreach ($f['exception_answers'] ?? [] as $a) {
                $o['exceptions'][] = ['kind' => 'form_answer', 'form' => $f['title'],
                                      'question' => $mayRead ? $a['label'] : null, 'answer' => $mayRead ? $a['value'] : null];
            }
        }
        foreach ($o['forms'] ?? [] as $i => $f) unset($o['forms'][$i]['exception_answers']);
    }

    /** One removed occurrence, from its frozen copy - judged exactly like a live one, then marked Removed. */
    private static function occurrenceFromRemoved(array $r): array
    {
        $snap = json_decode((string)$r['snapshot_json'], true) ?: [];
        $t = $snap['task'] ?? ['id' => (int)$r['task_id'], 'title' => $r['title'], 'due_date' => $r['due_date'], 'scheduled_date' => $r['scheduled_date']];
        $events = array_map(function ($e) {
            $e['detail'] = !empty($e['detail_json']) ? (json_decode($e['detail_json'], true) ?: []) : [];
            return $e;
        }, $snap['events'] ?? []);
        $o = self::judge($t, $snap['checklists'] ?? [], $snap['step_events'] ?? [], $events, $snap['audit'] ?? [], $r);
        $o['removed'] = ['by' => $r['removed_by_name'], 'at_utc' => $r['removed_datetime'], 'outcome_before' => $o['outcome']];
        $o['outcome'] = 'removed';
        $o['comments'] = $snap['comments'] ?? [];
        $o['documents'] = $snap['documents'] ?? [];
        return $o;
    }

    /**
     * The heart of it: one occurrence's outcome and flags.
     *
     * @param array $t  the task (live row, or the frozen copy's)
     * @param array $lists  its checklists, each with 'items'
     * @param array $steps  its checklist step history
     * @param array $events  its task_events, oldest first, each with 'detail'
     * @param array $audit  its task_audit rows
     * @param ?array $removedRec  the task_removed_records row when it was deleted
     */
    private static function judge(array $t, array $lists, array $steps, array $events, array $audit, ?array $removedRec): array
    {
        $today = date('Y-m-d');
        $flags = [];
        $scheduled = !empty($t['scheduled_date']) ? substr($t['scheduled_date'], 0, 10) : null;
        if ($scheduled === null && !empty($t['due_date'])) {
            // Made before #181 recorded the date it was made for: its due date is the best there is.
            $scheduled = substr($t['due_date'], 0, 10);
            $flags['scheduled_not_recorded'] = true;
        }
        $currentDue = !empty($t['due_date']) ? substr($t['due_date'], 0, 10) : null;
        /* GH #190 phase 4: the due date it was CREATED with - its scheduled day plus the
           repeat's due offset. Saturday's check due on Monday and done on Monday is on time,
           not "on time after rescheduling". Without an offset this is the scheduled day. */
        $originalDue = !empty($t['original_due_date']) ? substr($t['original_due_date'], 0, 10) : $scheduled;

        // The LATEST closing of it - completed or skipped - and anything since.
        $closeEv = null; $closeIdx = -1;
        foreach ($events as $i => $e) {
            if (in_array($e['event_type'], ['completed', 'skipped'], true)) { $closeEv = $e; $closeIdx = $i; }
            if ($e['event_type'] === 'reopened' && $i > $closeIdx) { $closeEv = null; $closeIdx = $i; }
        }
        $created = null;
        foreach ($events as $e) if ($e['event_type'] === 'created') { $created = $e; break; }
        $succ = null;
        foreach ($events as $i => $e) {
            if ($e['event_type'] === 'successor' && $i > $closeIdx - 1) $succ = $e;
            if ($e['event_type'] === 'reopened') $succ = null;
        }

        $closed = !empty($t['status_is_closed']) || !empty($t['completed_datetime']);
        $skipped = !empty($t['skipped_datetime']) || ($closeEv && $closeEv['event_type'] === 'skipped');
        $completedLocal = self::localDate($t['completed_datetime'] ?? null);
        $outcome = 'open';
        $dueAtCompletion = null;

        if ($closed && $skipped) {
            $outcome = 'skipped';
        } elseif ($closed && $completedLocal) {
            if ($closeEv && $closeEv['event_type'] === 'completed') {
                $dueAtCompletion = $closeEv['to_value'] !== null ? substr($closeEv['to_value'], 0, 10) : null;
            } else {
                // Completed before #181 recorded the due date in force - the current one is all there is.
                $dueAtCompletion = $currentDue;
                $flags['due_history_not_recorded'] = true;
            }
            $original = $originalDue ?? $dueAtCompletion;
            if ($dueAtCompletion !== null && $completedLocal > $dueAtCompletion) $outcome = 'late';
            elseif ($original !== null && $completedLocal > $original)          $outcome = 'on_time_rescheduled';
            elseif ($dueAtCompletion === null && $original === null)              $outcome = 'not_recorded';
            else                                                                  $outcome = 'on_time';
            if ($dueAtCompletion !== null && $originalDue !== null && $dueAtCompletion !== $originalDue) $flags['rescheduled'] = true;
            // A due date moved AFTER completion is flagged, and never re-judges the work.
            foreach ($events as $i => $e) {
                if ($i > $closeIdx && $e['event_type'] === 'due_date_changed') { $flags['due_changed_after_completion'] = true; break; }
            }
        } elseif (!$closed) {
            $outcome = ($currentDue !== null && $currentDue < $today) ? 'overdue' : 'open';
            if ($currentDue !== null && $originalDue !== null && $currentDue !== $originalDue) $flags['rescheduled'] = true;
        }

        // Created late: the scheduler made it after the day work on it should have begun.
        $via = $t['created_via'] ?? ($created['detail']['via'] ?? null);
        if ($via === 'repeat_catchup') $flags['created_late'] = true;
        if (!$created && !$removedRec) $flags['history_not_recorded'] = true;
        if (empty($t['completed_by_id']) && $closed && !$skipped && !($closeEv && $closeEv['analyst_id'])) {
            if (!$closeEv) $flags['not_recorded'] = true;
        }

        // Checklist results and exceptions. An exception is a failed step, or a completion with
        // mandatory steps outstanding (task_audit, written by ChecklistsService).
        $exceptions = [];
        $checklists = [];
        foreach ($lists as $l) {
            $items = [];
            foreach ($l['items'] ?? [] as $it) {
                $items[] = [
                    'title' => $it['title'], 'mandatory' => (int)($it['is_mandatory'] ?? 0) === 1,
                    'done' => (int)($it['is_completed'] ?? 0) === 1, 'value' => $it['response_value'] ?? null,
                    'result' => $it['result'] ?? null, 'exception_note' => $it['exception_note'] ?? null,
                    'by' => $it['completed_by_name'] ?? null, 'at_utc' => $it['completed_datetime'] ?? null,
                ];
                if (($it['result'] ?? null) === 'fail') {
                    $exceptions[] = ['kind' => 'failed_step', 'checklist' => $l['title'], 'step' => $it['title'], 'note' => $it['exception_note'] ?? null];
                }
            }
            $checklists[] = ['title' => $l['title'], 'template_id' => $l['template_id'] ?? null,
                             'template_version' => $l['template_version'] ?? null, 'items' => $items];
        }
        foreach ($audit as $a) {
            if (($a['field_name'] ?? '') === 'Completed with checklist steps outstanding') {
                $exceptions[] = ['kind' => 'completed_outstanding', 'note' => $a['new_value']];
            }
        }
        $after = array_filter($steps, fn($s) => !empty($s['after_completion']));
        foreach ($events as $e) {
            if (in_array($e['event_type'], ['checklist_attached', 'checklist_removed'], true) && !empty($e['detail']['after_completion'])) $after[] = $e;
        }
        if ($after) $flags['changed_after_completion'] = true;

        // When work on it could begin, for "created late" and the run-history window.
        $begins = $scheduled;
        if (!empty($t['start_date']) && !empty($t['due_date']) && $scheduled) {
            $begins = TaskRecurrence::startForDue($t, $scheduled) ?: $scheduled;
        }
        if (!$flags || !isset($flags['created_late'])) {
            $createdLocal = self::localDate($t['created_datetime'] ?? null);
            if ($via && str_starts_with($via, 'repeat') && $via !== 'repeat_completion' && $createdLocal && $begins && $createdLocal > $begins) {
                $flags['created_late'] = true;
            }
        }

        return [
            'kind'               => 'occurrence',
            'task_id'            => (int)$t['id'],
            'title'              => $t['title'] ?? null,
            'scheduled'          => $scheduled,
            'original_due'       => $originalDue,
            'period_start'       => !empty($t['period_start']) ? substr($t['period_start'], 0, 10) : null,
            'period_end'         => !empty($t['period_end']) ? substr($t['period_end'], 0, 10) : null,
            'begins'             => $begins,
            'due_at_completion'  => $dueAtCompletion,
            'current_due'        => $currentDue,
            'status'             => $t['status_name'] ?? null,
            'outcome'            => $outcome,
            'flags'              => $flags,
            'created_utc'        => $t['created_datetime'] ?? null,
            'created_via'        => $via,
            'created_run_id'     => $created['job_run_id'] ?? null,
            'completed_utc'      => $t['completed_datetime'] ?? null,
            'completed_local'    => $completedLocal,
            'completed_by'       => $t['completed_by_name'] ?? ($closeEv['actor_name'] ?? null),
            'skip_reason'        => $skipped && $closeEv && $closeEv['event_type'] === 'skipped' ? $closeEv['to_value'] : null,
            'assignee'           => $t['assignee_name'] ?? null,
            'team'               => $t['team_name'] ?? null,
            'previous_id'        => !empty($t['previous_occurrence_id']) ? (int)$t['previous_occurrence_id'] : null,
            'successor_expected' => ($succ && !empty($succ['detail']['expected']))
                                        ? ['due' => $succ['to_value'] ? substr($succ['to_value'], 0, 10) : null, 'at' => $succ['created_datetime']]
                                        : null,
            'ticket_id'          => !empty($t['ticket_id']) ? (int)$t['ticket_id'] : null,
            'change_id'          => !empty($t['change_id']) ? (int)$t['change_id'] : null,
            'checklists'         => $checklists,
            'exceptions'         => $exceptions,
            'events'             => $events,
            'steps'              => $steps,
        ];
    }

    /**
     * The dates a fixed-schedule series was expected to land on up to $to, with the rule in force at each,
     * and the spans it was stopped. Walks from the series' FIRST occurrence; stops at an end date or count.
     *
     * @param array $series  seriesHeader()
     * @param array $versions  its rule versions, oldest first
     * @param array $occ  its occurrences (for the first date and the start-to-due gap)
     * @return array{dates:array<string,array{begins:string}>, stopped:array}
     */
    private static function expectedDates(PDO $conn, array $series, array $versions, array $occ, string $to, string $today): array
    {
        $first = null; $gapSrc = null;
        foreach ($occ as $o) {
            if ($o['scheduled'] !== null && ($first === null || $o['scheduled'] < $first)) $first = $o['scheduled'];
        }
        $latest = null;
        if (!empty($series['latest_task_id'])) {
            $s = $conn->prepare("SELECT start_date, due_date FROM tasks WHERE id = ?");
            $s->execute([(int)$series['latest_task_id']]);
            $latest = $s->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        $out = ['dates' => [], 'stopped' => []];
        if ($first === null) return $out;

        // Stopped spans, from the versions: a stop or finish until the next resume.
        $stopFrom = null; $stopBy = null;
        foreach ($versions as $v) {
            $at = self::localDate($v['created_datetime']);
            if (in_array($v['change_kind'], ['stopped', 'finished'], true) && $stopFrom === null) { $stopFrom = $at; $stopBy = $v['actor_name'] ?: $v['change_kind']; }
            elseif (in_array($v['change_kind'], ['resumed', 'changed', 'created'], true) && $stopFrom !== null) {
                $out['stopped'][] = ['from' => $stopFrom, 'to' => $at, 'by' => $stopBy];
                $stopFrom = null;
            }
        }
        if ($stopFrom !== null) $out['stopped'][] = ['from' => $stopFrom, 'to' => '9999-12-31', 'by' => $stopBy];

        $ruleAt = function (string $d) use ($versions, $series) {
            $r = null;
            foreach ($versions as $v) { if (self::localDate($v['created_datetime']) <= $d) $r = $v['rule']; }
            return $r ?: ($versions[0]['rule'] ?? ($series['rule'] ?? null));
        };
        $inStop = function (string $d) use ($out) {
            foreach ($out['stopped'] as $s) if ($d >= $s['from'] && $d < $s['to']) return true;
            return false;
        };

        $d = $first; $n = 1; $guard = 0;
        $limit = min($to, date('Y-m-d', strtotime($today . ' +400 days')));
        while ($d !== null && $d <= $limit && $guard++ < 2000) {
            $rule = $ruleAt($d);
            if (!$rule) break;
            $probe = $rule; $probe['occurrences_created'] = $n - 1;
            if (TaskRecurrence::isExhausted($probe, $d) && $n > 1) break;
            $begins = $latest ? (TaskRecurrence::startForDue($latest, $d) ?: $d) : $d;
            if (!$inStop($begins)) $out['dates'][$d] = ['begins' => $begins];
            $next = TaskRecurrence::nextDate($rule, $d);
            if ($next === null || $next <= $d) break;
            $d = $next; $n++;
        }
        return $out;
    }

    /**
     * What the run history of the repeating-tasks job says about a window - only what the records establish.
     *
     * @param string $fromDate  the day work on the occurrence should have begun (install date)
     * @param ?string $createdUtc  when it was created (for created late), or null when it never was
     * @param ?int $runId  the run that created it, if recorded
     * @return array{code:string, from:string, to:string, run_at?:string, message?:string}
     */
    public static function explainGap(PDO $conn, string $fromDate, ?string $createdUtc, ?int $runId = null): array
    {
        $toDate = $createdUtc ? (self::localDate($createdUtc) ?? date('Y-m-d')) : date('Y-m-d');
        $since = jobSetting($conn, 'job_runs_recording_since');
        $sinceDate = $since ? self::localDate($since) : null;
        $keepFrom = date('Y-m-d', strtotime('-' . jobHealthRetentionMonths($conn) . ' months'));
        $base = ['from' => $fromDate, 'to' => $toDate];
        if ($sinceDate === null || $fromDate < $sinceDate) return ['code' => 'history_not_available', 'reason' => 'before_recording'] + $base;
        if ($fromDate < $keepFrom) return ['code' => 'history_not_available', 'reason' => 'beyond_retention'] + $base;

        $fromUtc = (new DateTimeImmutable($fromDate . ' 00:00:00', self::tz()))->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');
        $toUtc = $createdUtc ?: gmdate('Y-m-d H:i:s');
        try {
            $s = $conn->prepare("SELECT id, outcome, trigger_kind, started_datetime, summary FROM job_runs
                                  WHERE job_key = 'task_recurrence' AND outcome IN ('ok','partial','failed')
                                    AND started_datetime >= ? AND started_datetime <= ? ORDER BY id");
            $s->execute([$fromUtc, $toUtc]);
            $runs = $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable) {
            return ['code' => 'history_not_available', 'reason' => 'unreadable'] + $base;
        }
        if ($runId) {
            $r = $conn->prepare("SELECT started_datetime, trigger_kind FROM job_runs WHERE id = ?");
            $r->execute([$runId]);
            $row = $r->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $prior = array_filter($runs, fn($x) => (int)$x['id'] < $runId);
                return ['code' => $prior ? 'created_by_catchup_after_runs' : 'created_by_catchup',
                        'run_at' => $row['started_datetime'], 'trigger' => $row['trigger_kind']] + $base;
            }
        }
        if (!$runs) return ['code' => 'no_recorded_run'] + $base;
        $failed = array_values(array_filter($runs, fn($x) => $x['outcome'] === 'failed'));
        $good = array_values(array_filter($runs, fn($x) => $x['outcome'] !== 'failed'));
        if ($failed && !$good) return ['code' => 'job_failed', 'run_at' => $failed[0]['started_datetime'], 'message' => $failed[0]['summary']] + $base;
        if ($createdUtc) return ['code' => 'created_late_runs_seen', 'run_at' => $good[0]['started_datetime'] ?? $runs[0]['started_datetime']] + $base;
        return ['code' => 'ran_but_not_created', 'run_at' => ($good[0] ?? $runs[0])['started_datetime']] + $base;
    }

    /**
     * Records an export on the series' history (#181 Part F): who, when, which period, which file.
     * Kept as a task_events row with task id 0 - it belongs to the series, not to one occurrence.
     *
     * @param string $what  print, occurrences, checklist_results or evidence
     */
    public static function recordExport(PDO $conn, ActorContext $ctx, int $rid, string $what, string $from, string $to): void
    {
        TaskHistory::record($conn, 0, 'evidence_exported', $ctx, null, $what, ['from' => $from, 'to' => $to], $rid);
    }

    /** One sentence for a rule, in the viewer's language - the server-side twin of the task window's describeRecurrence(). */
    public static function describeRule(?array $r): string
    {
        if (!$r) return t('tasks.recur.none');
        $T = fn($k, $p = []) => t('tasks.recur.' . $k, $p);
        $n = max(1, (int)($r['interval_n'] ?? 1));
        $days = ['', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        switch ($r['freq'] ?? '') {
            case 'daily':  $what = $n === 1 ? $T('every_day') : $T('every_n_days', ['n' => $n]); break;
            case 'weekly':
                $wd = array_filter(explode(',', (string)($r['weekdays'] ?? '')));
                $names = implode(', ', array_map(fn($d) => t('common.calendar.weekdays_short.' . ($days[(int)$d] ?? '')), $wd));
                $what = ($n === 1 ? $T('every_week') : $T('every_n_weeks', ['n' => $n])) . ($names ? ' - ' . $names : '');
                break;
            case 'monthly':
            case 'yearly':
                $what = ($r['freq'] === 'monthly')
                    ? ($n === 1 ? $T('every_month') : $T('every_n_months', ['n' => $n]))
                    : ($n === 1 ? $T('every_year') : $T('every_n_years', ['n' => $n]));
                $on = '';
                if (($r['month_mode'] ?? '') === 'nth') {
                    $ord = (int)$r['nth'] === -1 ? $T('ord_last') : $T('ord_' . (int)$r['nth']);
                    $on = $T('on_nth_weekday', ['ord' => $ord, 'weekday' => t('common.calendar.weekdays.' . ($days[(int)$r['nth_weekday']] ?? ''))]);
                } elseif ((int)($r['day_of_month'] ?? 0) === -1) {
                    $on = $T('on_last_day');
                } elseif (!empty($r['day_of_month'])) {
                    $on = $T('on_day', ['day' => $r['day_of_month']]);
                }
                $what .= $on ? ' - ' . $on : '';
                break;
            default: $what = '';
        }
        if (($r['ends_mode'] ?? '') === 'on_date' && !empty($r['ends_on'])) $what .= ' · ' . $T('until', ['date' => $r['ends_on']]);
        elseif (($r['ends_mode'] ?? '') === 'after_count' && !empty($r['max_occurrences'])) $what .= ' · ' . $T('for_n', ['n' => $r['max_occurrences']]);
        return $what . ' · ' . $T(($r['mode'] ?? '') === 'schedule' ? 'mode_schedule_short' : 'mode_completion_short');
    }
}
