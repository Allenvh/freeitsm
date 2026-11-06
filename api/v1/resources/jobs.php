<?php
/**
 * FreeITSM REST API v1 — background job health (#182).
 *
 *   GET /jobs/health           every job's health, and an overall status
 *   GET /jobs/health?strict=1  the same, but answered 503 whenever any needed job is unhealthy -
 *                              for uptime monitors that look only at the status code
 *
 * For OUTSIDE monitoring: the reliable alarm when FreeITSM's own scheduler has stopped,
 * because it depends on nothing inside FreeITSM running. If FreeITSM or its database is
 * down, this address fails - an error, never a cheerful "healthy" - which is itself the alarm.
 *
 * It NEVER runs a job, and never returns passwords, tokens or connection details: job
 * summaries and source messages are cleaned when they are written (JobRun::clean()).
 *
 * 🔒 Job health is install-wide, so a key that can see only some companies is refused
 * even with the permission: its analyst must be an administrator, or the key and its
 * analyst must reach every company. See jobHealthReachesAllCompanies().
 */

require_once dirname(__DIR__, 3) . '/includes/job_health.php';

/** GET /jobs/health — every job's health; ?strict=1 answers 503 while anything needed is unhealthy. */
function apiJobsHealth(PDO $conn, array $apiKey, array $params, array $body): void {
    $aid = (int)($apiKey['analyst_id'] ?? 0);
    $allCompanies = $apiKey['company_scope'] === null
        || jobHealthReachesAllCompaniesScope($conn, $apiKey['company_scope']);
    if ($aid <= 0 || !$allCompanies || !(analystIsAdmin($conn, $aid) || jobHealthReachesAllCompanies($conn, $aid))) {
        apiError(403, 'forbidden', 'Job health is install-wide: the key and its analyst must reach every company (or the analyst must be an administrator).');
    }

    $summary = jobHealthSummary($conn);
    $jobs = array_map(fn($j) => apiJobHealthRow($j), array_values($summary['jobs']));
    $data = [
        'status'     => $summary['healthy'] ? 'healthy' : 'unhealthy',
        'checked_at' => apiIsoDate($summary['checked_utc']),
        'unhealthy'  => array_map(fn($j) => $j['key'], $summary['unhealthy']),
        'jobs'       => $jobs,
    ];
    $strict = in_array(strtolower((string)($_GET['strict'] ?? '')), ['1', 'true', 'yes'], true);
    apiRespond($data, ($strict && !$summary['healthy']) ? 503 : 200);
}

/** True when a key's company list covers every company. */
function jobHealthReachesAllCompaniesScope(PDO $conn, array $scope): bool {
    try {
        $all = array_map('intval', $conn->query("SELECT id FROM tenants")->fetchAll(PDO::FETCH_COLUMN));
        return !array_diff($all, array_map('intval', $scope));
    } catch (Throwable $e) {
        return false;
    }
}

/**
 * One job as the API returns it - no command lines, no internal ids beyond the job key.
 *
 * @param array $j  a jobHealthOne() row
 */
function apiJobHealthRow(array $j): array {
    $problems = array_map(function ($p) {
        $out = ['code' => $p['code']];
        if (!empty($p['since'])) $out['since'] = apiIsoDate($p['since']);
        if (!empty($p['message'])) $out['message'] = $p['message'];
        if (!empty($p['sources'])) {
            $out['sources'] = array_map(fn($s) => ['key' => $s['key'], 'name' => $s['label'], 'message' => $s['message'],
                                                   'failed_at' => apiIsoDate($s['at'])], $p['sources']);
        }
        if (isset($p['other_runs'])) $out['ran_by_other_trigger'] = (bool)$p['other_runs'];
        return $out;
    }, $j['problems'] ?? []);
    return [
        'key'                       => $j['key'],
        'name'                      => $j['name'],
        'affects'                   => $j['affects'],
        'status'                    => $j['status'],
        'needed'                    => (bool)$j['needed'],
        'expected_interval_minutes' => (int)$j['interval'],
        'grace_minutes'             => (int)$j['grace'],
        'last_scheduled_success_at' => apiIsoDate($j['last_scheduled_success']),
        'last_success_at'           => apiIsoDate($j['last_success']),
        'last_success_trigger'      => $j['last_success_trigger'],
        'last_run'                  => $j['last_run'] ? [
            'trigger'     => $j['last_run']['trigger'],
            'outcome'     => $j['last_run']['outcome'],
            'started_at'  => apiIsoDate($j['last_run']['started']),
            'finished_at' => apiIsoDate($j['last_run']['finished']),
            'summary'     => $j['last_run']['summary'],
        ] : null,
        'problems'                  => $problems,
    ];
}
