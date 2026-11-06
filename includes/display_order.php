<?php
/**
 * Display order - one rule for putting a SAVED order on top of a list that keeps
 * growing (GH #187). Used for the personal module order (waffle menu, home page,
 * the ⌘K palette, the Files start menu) and the shared Watchtower card order.
 *
 * THE RULE - displayOrderMerge():
 *   1. the saved keys that still exist, in the saved order
 *   2. then every key that is not in the saved order, in its DEFAULT order
 * So a module or card added in a later release turns up on its own, in its
 * default place relative to the others that are new, without anybody's saved
 * order being touched; one that is removed simply drops out; and a saved order
 * of junk degrades to the default instead of losing anything.
 *
 * An entry the viewer cannot see right now (no access, hidden, switched off)
 * KEEPS its saved position, because ordering happens BEFORE filtering. Order
 * never grants anything: every caller filters exactly as it did before.
 *
 * Same idea as applyColumnConfig() in assets/js/data-table.js, which has kept
 * saved table views working across new columns since 2.x.
 *
 * @wiki Display-Order-Developer-Guide
 */

/**
 * The saved order merged onto the current default order (the rule above).
 *
 * @param array $saved     keys in the saved order (untrusted: may hold unknown keys, duplicates, non-strings)
 * @param array $defaults  every key that exists today, in default order
 * @return array           every default key exactly once
 */
function displayOrderMerge(array $saved, array $defaults): array {
    $known = array_flip(array_map('strval', $defaults));
    $out = [];
    foreach ($saved as $k) {
        if (!is_string($k) || !isset($known[$k]) || isset($out[$k])) continue;
        $out[$k] = true;
    }
    foreach ($defaults as $k) {
        $k = (string)$k;
        if (!isset($out[$k])) $out[$k] = true;
    }
    return array_keys($out);
}

/**
 * A stored order (a JSON array of keys) as a list. Anything else - blank, a
 * hand-edited row, an object - is "no saved order".
 *
 * @param mixed $raw  the stored value
 */
function displayOrderParse($raw): array {
    if (!is_string($raw) || $raw === '') return [];
    $v = json_decode($raw, true);
    if (!is_array($v)) return [];
    return array_values(array_filter($v, 'is_string'));
}

/**
 * The signed-in analyst's saved module order (user_preferences.module_order),
 * as stored - not yet merged. Read once per request: the waffle, the palette,
 * the Files start menu and the home page all ask.
 */
function analystModuleOrderSaved(): array {
    static $memo = null;
    if ($memo !== null) return $memo;
    $memo = [];
    $aid = (int)($_SESSION['analyst_id'] ?? 0);
    if ($aid <= 0 || !function_exists('connectToDatabase')) return $memo;
    try {
        $st = connectToDatabase()->prepare(
            "SELECT preference_value FROM user_preferences WHERE analyst_id = ? AND preference_key = 'module_order'"
        );
        $st->execute([$aid]);
        $memo = displayOrderParse($st->fetchColumn());
    } catch (Throwable) {
        // No table yet, or no database: the default order.
    }
    return $memo;
}

/**
 * The modules this analyst may open, in THEIR order - the one list every module
 * launcher draws from (waffle, home page, ⌘K, Files start menu), so they can
 * never disagree about what is offered or in what order.
 *
 * Filtering is unchanged from what each launcher did on its own:
 *   - System only for administrators (not the per-analyst module list);
 *   - everything else only if it is in the session's allowed modules (null = all);
 *   - a Forms approver without Forms gets its approvals inbox instead (#180).
 *
 * @param array $modules  the $modules list from includes/waffle-menu.php: key => [name, path, icon]
 * @return array          key => module, ordered, filtered; 'approvals' => true on the Forms alias
 */
function analystVisibleModules(array $modules): array {
    $allowed = analystAllowedModulesNow();
    $out = [];
    foreach (displayOrderMerge(analystModuleOrderSaved(), array_keys($modules)) as $key) {
        $module = $modules[$key];
        if ($key === 'system') {
            if (!sessionIsAdmin()) continue;
        } elseif ($allowed !== null && !in_array($key, $allowed)) {
            if ($key === 'forms' && sessionIsFormApproverOnly()) {
                $module['path'] = 'forms/approvals.php';
                $module['name'] = t('common.waffle.form_approvals');
                $module['approvals'] = true;
            } else {
                continue;
            }
        }
        $out[$key] = $module;
    }
    return $out;
}
