<?php
/**
 * AssetTypeLayout - which built-in sections and tabs an asset TYPE shows (GH #200, part A).
 *
 * A monitor does not need CPU, memory and BIOS on its Key info, or a Devices tab. Custom
 * fields were already chosen per type (asset_type_field_sets); this is the other half: the
 * built-in sections and the tab strip.
 *
 * 🔑 PRESENTATION ONLY. A layout decides what one asset's page SHOWS - never what is stored,
 * never what is required, never who may write. The agent, Intune and REST imports write
 * exactly what they always wrote, and the API returns every field. That one rule is what
 * keeps this from growing into a second field system beside custom fields.
 *
 * "Hidden by this profile" is a display state, not a fact about the asset: a team may hide
 * Software on its computers and still collect it. The page therefore says when an asset HAS
 * data in a hidden section and offers to show it (asset_layout_hidden_notice).
 *
 * Storage: asset_type_layout, one row per (type, kind, key). NO ROW MEANS SHOWN, in the
 * default position - so existing installs need no migration and a tab added in a later
 * release appears on every type until somebody hides it.
 *
 * @wiki Asset-Type-Profiles-Design
 */

final class AssetTypeLayout
{
    /**
     * The Key info sections that can be switched off, in the order they print. Identity (name,
     * asset tag, service tag, type, status, location, company) is not here: it is how an asset is
     * found, scanned and matched, so it is never hidden.
     */
    public const SECTIONS = ['make_model', 'hardware', 'os', 'reporting', 'storage', 'cmdb'];   // cmdb: GH #201's link card

    /** The tabs after Key info (which is always first and always shown), in their default order. */
    public const TABS = ['financial', 'devices', 'software', 'connected', 'tickets', 'contracts', 'projects', 'documents'];

    /** How an asset says it has details its type hides. 'notice' = a line on Key info; 'link' = a quiet link only. */
    public const NOTICE_SETTING = 'asset_layout_hidden_notice';
    public const NOTICE_MODES = ['notice', 'link'];

    /**
     * The "Start from" presets. Only a shortcut for ticking boxes: the result is ordinary ticks,
     * stored like any other layout, and nothing here is enforced anywhere.
     */
    public const PRESETS = [
        'everything' => ['hide_sections' => [], 'hide_tabs' => []],
        'peripheral' => ['hide_sections' => ['hardware', 'os', 'reporting', 'storage', 'cmdb'], 'hide_tabs' => ['devices', 'software']],
        'network'    => ['hide_sections' => ['hardware', 'storage'], 'hide_tabs' => ['devices', 'software']],
        'mobile'     => ['hide_sections' => ['hardware', 'cmdb'], 'hide_tabs' => ['devices']],
    ];

    /** Everything shown, in the default order - what a type with no rows gets. */
    public static function defaults(): array
    {
        return [
            'sections' => array_fill_keys(self::SECTIONS, true),
            'tabs'     => array_map(fn($k) => ['key' => $k, 'shown' => true], self::TABS),
        ];
    }

    /**
     * The layouts of several types at once.
     *
     * @param int[] $typeIds
     * @return array<int, array{sections: array<string,bool>, tabs: array<int, array{key:string, shown:bool}>}>
     *         keyed by type id; every id asked for is present
     */
    public static function forTypes(PDO $conn, array $typeIds): array
    {
        $typeIds = array_values(array_unique(array_filter(array_map('intval', $typeIds))));
        $rowsByType = [];
        if ($typeIds) {
            try {
                $in = implode(',', array_fill(0, count($typeIds), '?'));
                $st = $conn->prepare("SELECT asset_type_id, item_kind, item_key, is_shown, sort_order
                                        FROM asset_type_layout WHERE asset_type_id IN ($in)");
                $st->execute($typeIds);
                foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) $rowsByType[(int)$r['asset_type_id']][] = $r;
            } catch (PDOException) {
                // No table yet (DB Verify not run since the upgrade): every type shows everything,
                // which is exactly what the page did before this feature.
            }
        }
        $out = [];
        foreach ($typeIds as $id) $out[$id] = self::fromRows($rowsByType[$id] ?? []);
        return $out;
    }

    /**
     * Rows to a layout. Unknown keys (a tab removed in a later release) are dropped; keys with no
     * row are shown, sections in their fixed order and tabs after the stored ones in default order.
     */
    private static function fromRows(array $rows): array
    {
        $layout = self::defaults();
        $tabRows = [];
        foreach ($rows as $r) {
            $key = (string)$r['item_key'];
            if ($r['item_kind'] === 'section' && in_array($key, self::SECTIONS, true)) {
                $layout['sections'][$key] = (bool)$r['is_shown'];
            } elseif ($r['item_kind'] === 'tab' && in_array($key, self::TABS, true)) {
                $tabRows[$key] = ['shown' => (bool)$r['is_shown'], 'sort' => (int)$r['sort_order']];
            }
        }
        if ($tabRows) {
            $default = array_flip(self::TABS);
            $keys = self::TABS;
            // Stored tabs by their saved position; tabs never saved keep their default place after them.
            usort($keys, function ($a, $b) use ($tabRows, $default) {
                $sa = isset($tabRows[$a]) ? [0, $tabRows[$a]['sort']] : [1, $default[$a]];
                $sb = isset($tabRows[$b]) ? [0, $tabRows[$b]['sort']] : [1, $default[$b]];
                return $sa <=> $sb;
            });
            $layout['tabs'] = array_map(fn($k) => ['key' => $k, 'shown' => $tabRows[$k]['shown'] ?? true], $keys);
        }
        return $layout;
    }

    /**
     * A layout from a request, validated: unknown keys are ignored, every known key is present.
     * Anything not a valid layout comes back as everything shown, never as an error that could
     * leave a type half-saved.
     *
     * @param mixed $in  ['sections' => {key: bool}, 'tabs' => [{key, shown}] in order]
     */
    public static function normalise($in): array
    {
        $layout = self::defaults();
        if (!is_array($in)) return $layout;
        foreach (self::SECTIONS as $k) {
            if (isset($in['sections']) && is_array($in['sections']) && array_key_exists($k, $in['sections'])) {
                $layout['sections'][$k] = (bool)$in['sections'][$k];
            }
        }
        if (isset($in['tabs']) && is_array($in['tabs'])) {
            $seen = [];
            $tabs = [];
            foreach ($in['tabs'] as $t) {
                $k = is_array($t) ? (string)($t['key'] ?? '') : '';
                if (!in_array($k, self::TABS, true) || isset($seen[$k])) continue;
                $seen[$k] = true;
                $tabs[] = ['key' => $k, 'shown' => !empty($t['shown'])];
            }
            foreach (self::TABS as $k) if (!isset($seen[$k])) $tabs[] = ['key' => $k, 'shown' => true];
            $layout['tabs'] = $tabs;
        }
        return $layout;
    }

    /**
     * Stores a type's layout. A layout equal to the default stores NO rows, so "nothing configured"
     * stays distinguishable and a later release's new tab still appears on the type.
     */
    public static function save(PDO $conn, int $typeId, array $layout): void
    {
        $layout = self::normalise($layout);
        $conn->prepare("DELETE FROM asset_type_layout WHERE asset_type_id = ?")->execute([$typeId]);
        if ($layout == self::defaults()) return;
        $ins = $conn->prepare("INSERT INTO asset_type_layout (asset_type_id, item_kind, item_key, is_shown, sort_order) VALUES (?, ?, ?, ?, ?)");
        foreach (self::SECTIONS as $i => $k) {
            $ins->execute([$typeId, 'section', $k, $layout['sections'][$k] ? 1 : 0, $i]);
        }
        foreach ($layout['tabs'] as $i => $t) {
            $ins->execute([$typeId, 'tab', $t['key'], $t['shown'] ? 1 : 0, $i]);
        }
    }

    /** The install's choice for hidden details: 'notice' (default) or 'link'. */
    public static function noticeMode(PDO $conn): string
    {
        try {
            $st = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
            $st->execute([self::NOTICE_SETTING]);
            $v = (string)$st->fetchColumn();
        } catch (PDOException) {
            $v = '';
        }
        return in_array($v, self::NOTICE_MODES, true) ? $v : 'notice';
    }
}
