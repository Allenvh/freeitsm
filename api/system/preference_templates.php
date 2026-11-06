<?php
/**
 * Preference templates (discussion #124) - System -> Preference templates.
 *
 *   GET  ?action=list       every template, plus the sections a template can hold
 *                           and the active analysts it can be captured from or given to
 *   POST {action:'save', id?, name, description, for_new_analysts, sections[], capture_from?}
 *        capture_from = an analyst id: the ticked sections are copied from their
 *        preferences now. Omitted (editing only): the template keeps what it has,
 *        narrowed to the ticked sections.
 *   POST {action:'delete', id}
 *   POST {action:'apply', id, analyst_ids[]}   overwrites those people's own choices
 *                                              for the settings in the template
 *
 * System administrators only. The logic lives in includes/preference_templates.php.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';   // System admins only
require_once '../../includes/functions.php';
require_once '../../includes/preference_templates.php';
require_once '../../includes/i18n.php';
I18n::initFromSession();   // section headings and messages in the admin's language

header('Content-Type: application/json');

/**
 * Stop with a JSON failure.
 *
 * @param string $message  what went wrong, shown to the administrator
 */
function prefTplFail(string $message): void
{
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

/**
 * One template by id, shaped, or stop with "not found".
 *
 * @param PDO $conn  the database
 * @param int $id  the template id
 */
function prefTplFind(PDO $conn, int $id): array
{
    $st = $conn->prepare("SELECT * FROM preference_templates WHERE id = ?");
    $st->execute([$id]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    if (!$row) prefTplFail(t('system.pref_templates.not_found'));
    return prefTemplateShape($row);
}

try {
    $conn   = connectToDatabase();
    $method = $_SERVER['REQUEST_METHOD'];
    $in     = $method === 'POST' ? (json_decode(file_get_contents('php://input'), true) ?: []) : [];
    $action = $method === 'POST' ? (string)($in['action'] ?? '') : (string)($_GET['action'] ?? 'list');
    $me     = (int)($_SESSION['analyst_id'] ?? 0);

    if ($action === 'list') {
        $templates = array_map('prefTemplateShape',
            $conn->query("SELECT * FROM preference_templates ORDER BY name")->fetchAll(PDO::FETCH_ASSOC));
        $sections = [];
        foreach (PREF_TEMPLATE_SECTIONS as $id => [$heading]) $sections[] = ['id' => $id, 'label' => t($heading)];
        $analysts = $conn->query("SELECT id, COALESCE(NULLIF(full_name, ''), username) AS name FROM analysts
                                   WHERE is_active = 1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($analysts as &$a) $a['id'] = (int)$a['id'];
        echo json_encode(['success' => true, 'templates' => $templates, 'sections' => $sections,
                          'analysts' => $analysts, 'me' => $me]);
        exit;
    }

    if ($method !== 'POST') prefTplFail('POST required');

    if ($action === 'save') {
        $id   = (int)($in['id'] ?? 0);
        $name = trim((string)($in['name'] ?? ''));
        if ($name === '') prefTplFail(t('system.pref_templates.name_required'));
        $name = mb_substr($name, 0, 100);
        $desc = mb_substr(trim((string)($in['description'] ?? '')), 0, 255);
        $forNew = !empty($in['for_new_analysts']) ? 1 : 0;
        $sections = array_values(array_intersect(array_keys(PREF_TEMPLATE_SECTIONS), (array)($in['sections'] ?? [])));
        if (!$sections) prefTplFail(t('system.pref_templates.sections_required'));

        $from = (int)($in['capture_from'] ?? 0);
        if ($from > 0) {
            $chk = $conn->prepare("SELECT 1 FROM analysts WHERE id = ? AND is_active = 1");
            $chk->execute([$from]);
            if (!$chk->fetchColumn()) prefTplFail(t('system.pref_templates.source_missing'));
            $settings = prefTemplateCapture($conn, $from, $sections);
        } elseif ($id > 0) {
            // Keep what it has, narrowed to the sections still ticked.
            $keep = [];
            foreach ($sections as $s) $keep = array_merge($keep, PREF_TEMPLATE_SECTIONS[$s][1]);
            $settings = array_intersect_key(prefTplFind($conn, $id)['settings'], array_flip($keep));
        } else {
            prefTplFail(t('system.pref_templates.source_missing'));
        }
        if (!$settings) prefTplFail(t('system.pref_templates.sections_required'));

        $conn->beginTransaction();
        // Only one template is given to new analysts.
        if ($forNew) $conn->prepare("UPDATE preference_templates SET for_new_analysts = 0 WHERE id <> ?")->execute([$id]);
        if ($id > 0) {
            prefTplFind($conn, $id);
            $conn->prepare("UPDATE preference_templates SET name = ?, description = ?, settings = ?, for_new_analysts = ?,
                                   updated_datetime = UTC_TIMESTAMP() WHERE id = ?")
                 ->execute([$name, $desc, json_encode($settings), $forNew, $id]);
        } else {
            $conn->prepare("INSERT INTO preference_templates (name, description, settings, for_new_analysts, created_datetime, updated_datetime)
                            VALUES (?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())")
                 ->execute([$name, $desc, json_encode($settings), $forNew]);
            $id = (int)$conn->lastInsertId();
        }
        $conn->commit();
        echo json_encode(['success' => true, 'template' => prefTplFind($conn, $id)]);
        exit;
    }

    if ($action === 'delete') {
        $tpl = prefTplFind($conn, (int)($in['id'] ?? 0));
        // Deleting a template changes nobody: what it gave people is theirs now.
        $conn->prepare("DELETE FROM preference_templates WHERE id = ?")->execute([$tpl['id']]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'apply') {
        $tpl = prefTplFind($conn, (int)($in['id'] ?? 0));
        $ids = array_map('intval', (array)($in['analyst_ids'] ?? []));
        if ($ids) {
            // Active analysts only - the same people the picker offers.
            $inList = implode(',', array_fill(0, count($ids), '?'));
            $st = $conn->prepare("SELECT id FROM analysts WHERE is_active = 1 AND id IN ($inList)");
            $st->execute($ids);
            $ids = array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));
        }
        if (!$ids) prefTplFail(t('system.pref_templates.people_required'));
        $n = prefTemplateApply($conn, $tpl, $ids);
        try {
            $conn->prepare("INSERT INTO system_logs (log_type, analyst_id, details, created_datetime) VALUES (?, ?, ?, UTC_TIMESTAMP())")
                 ->execute(['preference_template_applied', $me ?: null,
                            json_encode(['template_id' => $tpl['id'], 'template' => $tpl['name'],
                                         'sections' => $tpl['sections'], 'analyst_ids' => $ids])]);
        } catch (Throwable $e) {
            error_log('[preference_templates] log: ' . $e->getMessage());
        }
        echo json_encode(['success' => true, 'applied' => $n]);
        exit;
    }

    prefTplFail('Unknown action');
} catch (Throwable $e) {
    if (isset($conn) && $conn->inTransaction()) $conn->rollBack();
    error_log('[preference_templates] ' . $e->getMessage());
    prefTplFail(t('system.pref_templates.failed'));
}
