<?php
/**
 * API: the evidence report's CSV files (#181 Part F).
 *
 * Three files that join on stable ids, for a period of scheduled dates:
 *   occurrences        one row per expected occurrence - series id, task id, scheduled date, outcome, flags
 *   checklist_results  one row per checklist step per occurrence, with the task id
 *   evidence           one row per attachment, comment or link, with the task id
 * UTF-8 with a BOM so Excel reads it, as the other exports do; times in UTC AND in the install's
 * time zone. Each export is recorded on the series' history - who, when, which period, which file.
 *
 * Gated exactly like tasks/evidence/report.php: Tasks access, "Run the evidence report" where
 * restricted, the series' company; occurrences in companies the viewer cannot see are left out.
 * Linked tickets and changes are named by number only.
 *
 * @input series, from, to, file  GET
 * @output text/csv attachment
 * @wiki Repeating-Task-Evidence-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/i18n.php';
require_once '../../includes/rbac.php';
require_once '../../includes/spreadsheet.php';
require_once '../../includes/task_evidence.php';
I18n::initFromSession();

if (!isset($_SESSION['analyst_id'])) {
    http_response_code(401);
    exit('Not authenticated');
}
requireModuleAccessJson('tasks');

$conn = connectToDatabase();
$analystId = (int)$_SESSION['analyst_id'];
if (!analystMayAct($conn, $analystId, Cap::TASKS_EVIDENCE_REPORT)) {
    http_response_code(403);
    exit(t('tasks.evidence.no_permission'));
}
$rid = (int)($_GET['series'] ?? 0);
$header = TaskEvidence::seriesHeader($conn, $rid);
if (!$header || (isMultiTenant($conn) && !analystCanAccessTenant($conn, $analystId,
        $header['tenant_id'] === null ? getDefaultTenantId($conn) : (int)$header['tenant_id']))) {
    http_response_code(404);
    exit(t('tasks.evidence.not_found'));
}
$isDate = fn($s) => is_string($s) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $s);
$to   = $isDate($_GET['to'] ?? null) ? $_GET['to'] : date('Y-m-d');
$from = $isDate($_GET['from'] ?? null) ? $_GET['from'] : date('Y-m-d', strtotime($to . ' -12 months +1 day'));
if ($from > $to) [$from, $to] = [$to, $from];
$file = (string)($_GET['file'] ?? 'occurrences');
if (!in_array($file, ['occurrences', 'checklist_results', 'evidence', 'form_answers'], true)) $file = 'occurrences';

$r = TaskEvidence::report($conn, $rid, $from, $to, $analystId);
$local = fn(?string $utc) => TaskEvidence::localDateTime($utc) ?? '';
$rows = [];

if ($file === 'occurrences') {
    $head = ['series_id', 'series_title', 'task_id', 'scheduled_date', 'due_date_at_completion', 'current_due_date', 'outcome',
             'rescheduled', 'due_changed_after_completion', 'created_late', 'changed_after_completion', 'not_recorded',
             'status', 'created_utc', 'created_local', 'created_via', 'completed_utc', 'completed_local', 'completed_by',
             'assigned_to', 'team', 'skip_reason', 'removed_by', 'removed_utc', 'exceptions', 'explanation', 'successor_of_task_id',
             // GH #190 phase 4, at the end so existing joins and column positions hold.
             'original_due_date', 'period_start', 'period_end'];
    foreach ($r['rows'] as $o) {
        $notRec = !empty($o['flags']['not_recorded']) || !empty($o['flags']['scheduled_not_recorded'])
               || !empty($o['flags']['due_history_not_recorded']) || !empty($o['flags']['history_not_recorded']);
        $exc = array_map(fn($x) => $x['kind'] === 'failed_step' ? ($x['checklist'] . ' / ' . $x['step'] . ': ' . ($x['note'] ?? ''))
            : ($x['kind'] === 'form_answer' ? ($x['form'] . ' / ' . ($x['question'] ?? '-') . ': ' . ($x['answer'] ?? t('tasks.forms.no_answer_access'))) : ($x['note'] ?? '')),
            $o['exceptions'] ?? []);
        $rows[] = [$rid, $r['series']['title'], $o['task_id'] ?? '', $o['scheduled'], $o['due_at_completion'] ?? '', $o['current_due'] ?? '',
                   $o['outcome'], !empty($o['flags']['rescheduled']) ? 1 : 0, !empty($o['flags']['due_changed_after_completion']) ? 1 : 0,
                   !empty($o['flags']['created_late']) ? 1 : 0, !empty($o['flags']['changed_after_completion']) ? 1 : 0, $notRec ? 1 : 0,
                   $o['status'] ?? '', $o['created_utc'] ?? '', $local($o['created_utc'] ?? null), $o['created_via'] ?? '',
                   $o['completed_utc'] ?? '', $local($o['completed_utc'] ?? null), $o['completed_by'] ?? '',
                   $o['assignee'] ?? '', $o['team'] ?? '', $o['skip_reason'] ?? '',
                   $o['removed']['by'] ?? '', $o['removed']['at_utc'] ?? '', implode(' | ', $exc),
                   !empty($o['explanation']) ? $o['explanation']['code'] : '', $o['missing_successor_of'] ?? '',
                   $o['original_due'] ?? '', $o['period_start'] ?? '', $o['period_end'] ?? ''];
    }
} elseif ($file === 'checklist_results') {
    $head = ['series_id', 'task_id', 'scheduled_date', 'outcome', 'checklist', 'template_id', 'template_version', 'step', 'mandatory',
             'done', 'value', 'result', 'exception_note', 'by', 'at_utc', 'at_local'];
    foreach ($r['rows'] as $o) {
        foreach ($o['checklists'] ?? [] as $c) {
            foreach ($c['items'] as $it) {
                $rows[] = [$rid, $o['task_id'], $o['scheduled'], $o['outcome'], $c['title'], $c['template_id'] ?? '', $c['template_version'] ?? '',
                           $it['title'], $it['mandatory'] ? 1 : 0, $it['done'] ? 1 : 0, $it['value'] ?? '', $it['result'] ?? '',
                           $it['exception_note'] ?? '', $it['by'] ?? '', $it['at_utc'] ?? '', $local($it['at_utc'] ?? null)];
            }
        }
    }
} elseif ($file === 'form_answers') {
    // GH #190: one row per answer, joining to the other files on task_id. Answers the
    // viewer may not read (TaskForms::mayReadAnswers) say so in the value column rather
    // than leaving the row out, so the row count does not depend on who ran it.
    $head = ['series_id', 'task_id', 'scheduled_date', 'outcome', 'form', 'required', 'requirement', 'submission_id', 'form_version_id',
             'submitted_by', 'submitted_utc', 'submitted_local', 'question', 'answer'];
    $hidden = t('tasks.forms.no_answer_access');
    foreach ($r['rows'] as $o) {
        foreach ($o['forms'] ?? [] as $f) {
            $base = [$rid, $o['task_id'], $o['scheduled'], $o['outcome'], $f['title'], $f['required'] ? 1 : 0, $f['mode'],
                     $f['submission_id'] ?? '', $f['form_id'] ?? '', $f['by'] ?? '', $f['at_utc'] ?? '', $local($f['at_utc'] ?? null)];
            if (!$f['submission_id']) { $rows[] = array_merge($base, ['', '']); continue; }
            if ($f['answers_hidden'])  { $rows[] = array_merge($base, ['', $hidden]); continue; }
            foreach ($f['answers'] ?? [] as $a) $rows[] = array_merge($base, [$a['label'], $a['value']]);
        }
    }
} else {
    // Attachments (name, size, who and when, sha256), comments, and linked tickets / changes by number.
    $head = ['series_id', 'task_id', 'scheduled_date', 'kind', 'reference', 'name', 'size_bytes', 'sha256', 'by', 'at_utc', 'at_local', 'text'];
    $docs = $conn->prepare("SELECT d.id, d.title, d.original_name, d.size_bytes, d.content_hash, d.external_url, l.linked_by_id,
                                   a.full_name AS linked_by, l.created_datetime
                              FROM document_links l JOIN documents d ON d.id = l.document_id
                              LEFT JOIN analysts a ON a.id = l.linked_by_id
                             WHERE l.parent_type = 'task' AND l.parent_id = ?");
    $comments = $conn->prepare("SELECT c.id, c.comment, c.created_datetime, a.full_name FROM task_comments c
                                  LEFT JOIN analysts a ON a.id = c.analyst_id WHERE c.task_id = ? ORDER BY c.id");
    foreach ($r['rows'] as $o) {
        if (!$o['task_id']) continue;
        if (($o['outcome'] ?? '') === 'removed') {
            foreach ($o['documents'] ?? [] as $d) {
                $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'attachment', 'document:' . $d['id'], $d['original_name'] ?: $d['title'],
                           $d['size_bytes'] ?? '', $d['content_hash'] ?? '', '', $d['created_datetime'] ?? '', $local($d['created_datetime'] ?? null), $d['external_url'] ?? ''];
            }
            foreach ($o['comments'] ?? [] as $c) {
                $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'comment', 'comment:' . $c['id'], '', '', '', $c['analyst_name'] ?? '',
                           $c['created_datetime'], $local($c['created_datetime']), $c['comment']];
            }
        } else {
            try {
                $docs->execute([$o['task_id']]);
                foreach ($docs->fetchAll(PDO::FETCH_ASSOC) as $d) {
                    $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'attachment', 'document:' . $d['id'], $d['original_name'] ?: $d['title'],
                               $d['size_bytes'] ?? '', $d['content_hash'] ?? '', $d['linked_by'] ?? '', $d['created_datetime'],
                               $local($d['created_datetime']), $d['external_url'] ?? ''];
                }
            } catch (Throwable $e) { /* no documents tables */ }
            $comments->execute([$o['task_id']]);
            foreach ($comments->fetchAll(PDO::FETCH_ASSOC) as $c) {
                $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'comment', 'comment:' . $c['id'], '', '', '', $c['full_name'] ?? '',
                           $c['created_datetime'], $local($c['created_datetime']), $c['comment']];
            }
        }
        // GH #190 phase 2: each procedure it followed - the frozen text's sha256 as the fingerprint.
        if (!empty($o['procedures'])) {
            $texts = TaskProcedures::snapshots($conn, array_column($o['procedures'], 'snapshot_id'));
            foreach ($o['procedures'] as $p) {
                $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'procedure', 'knowledge_article:' . $p['article_id'], $p['title'], '',
                           isset($texts[(int)$p['snapshot_id']]) ? $texts[(int)$p['snapshot_id']]['content_hash'] : '', '',
                           $p['frozen_datetime'] ?? '', $local($p['frozen_datetime'] ?? null),
                           !empty($p['snapshot_id']) ? 'version ' . ($p['article_version'] ?? '-') : 'not completed yet'];
            }
        }
        // GH #190 phase 3: each follow-up ticket or task, by number, with whether it is still open.
        foreach ($o['follow_ups'] ?? [] as $fu) {
            $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'follow_up_' . $fu['kind'], $fu['kind'] . ':' . $fu['record_id'], (string)($fu['number'] ?? ''),
                       '', '', '', '', '', $fu['open'] === null ? 'deleted' : ($fu['open'] ? 'open' : 'closed')];
        }
        // GH #190: each form submission, as evidence beside the attachments.
        foreach ($o['forms'] ?? [] as $f) {
            if ($f['submission_id']) $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'form_submission', 'form_submission:' . $f['submission_id'],
                                               $f['title'], '', '', $f['by'] ?? '', $f['at_utc'] ?? '', $local($f['at_utc'] ?? null), ''];
        }
        if (!empty($o['ticket_id'])) $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'linked_ticket', 'ticket:' . $o['ticket_id'], '', '', '', '', '', '', ''];
        if (!empty($o['change_id'])) $rows[] = [$rid, $o['task_id'], $o['scheduled'], 'linked_change', 'change:' . $o['change_id'], '', '', '', '', '', '', ''];
    }
}

TaskEvidence::recordExport($conn, ActorContext::fromSession($conn), $rid, $file, $from, $to);

$name = 'evidence-series-' . $rid . '-' . $file . '-' . $from . '-to-' . $to . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $name . '"');
header('X-Content-Type-Options: nosniff');
echo spreadsheetWriteCsv($head, $rows);
