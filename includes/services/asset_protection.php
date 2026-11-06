<?php
/**
 * Protected values (GH #200 part D): a name, manufacturer or model a person set that no
 * reporting tool may overwrite.
 *
 * The agent and Intune write these three fields on every report, which is right for a
 * machine nobody has touched and wrong for one somebody corrected - "Hewlett-Packard"
 * typed to match the purchase records turned back into "HP" by the next sync, with
 * nothing on screen to say so. A protected field is skipped by every source; what the
 * source reported instead is kept beside it (reported_value / _source / _at) so the page
 * can show the disagreement, and Release hands the field back to the sources.
 *
 * Only these three: they are the built-in fields a person can edit on Key info AND a
 * source writes. The serial is deliberately not protectable - it is what matching uses,
 * and a serial frozen against what the machine reports would split it into two assets.
 *
 * Who it stops: automatic sources only - the agent's report (manufacturer, model) and
 * AssetsService::updateAssetHostname() (the rename every source goes through). A person -
 * on the page, through the REST API or a spreadsheet import - is making a deliberate
 * change, and still can.
 *
 * @wiki Asset-Review-Developer-Guide
 */
require_once dirname(__DIR__) . '/service_context.php';

final class AssetProtectionService
{
    public const FIELDS = ['hostname', 'manufacturer', 'model'];

    /** The setting: protect automatically when a person edits one of FIELDS on a reporting asset. */
    public const AUTO_SETTING = 'asset_protect_on_edit';

    /** asset's protected fields: field => [protected_by, protected_at, reported_value, reported_source, reported_at]. */
    public static function forAsset(PDO $conn, int $assetId): array
    {
        if (!self::ready($conn)) { return []; }
        $st = $conn->prepare('SELECT p.field_name, p.protected_at, p.reported_value, p.reported_source, p.reported_at, a.full_name AS protected_by
            FROM asset_protected_fields p LEFT JOIN analysts a ON a.id = p.protected_by_id WHERE p.asset_id = ?');
        $st->execute([$assetId]);
        $out = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) { $out[$r['field_name']] = $r; }
        return $out;
    }

    /** Protect (true) or release (false) one field, with a history row. */
    public static function set(PDO $conn, ActorContext $ctx, int $assetId, string $field, bool $protect): void
    {
        if (!in_array($field, self::FIELDS, true)) {
            throw new ServiceError('validation', 'invalid_field', 'Only the name, manufacturer and model can be protected.');
        }
        if (!self::ready($conn)) {
            throw new ServiceError('validation', 'invalid_field', 'Run Database Verification first.');
        }
        $by = $ctx->actorId > 0 ? $ctx->actorId : null;
        if ($protect) {
            $st = $conn->prepare('INSERT IGNORE INTO asset_protected_fields (asset_id, field_name, protected_by_id, protected_at) VALUES (?, ?, ?, UTC_TIMESTAMP())');
            $st->execute([$assetId, $field, $by]);
        } else {
            $st = $conn->prepare('DELETE FROM asset_protected_fields WHERE asset_id = ? AND field_name = ?');
            $st->execute([$assetId, $field]);
        }
        if ($st->rowCount()) {
            $conn->prepare("INSERT INTO asset_history (asset_id, analyst_id, field_name, old_value, new_value, created_datetime)
                VALUES (?, ?, ?, NULL, ?, UTC_TIMESTAMP())")->execute([$assetId, $by, $protect ? 'protected' : 'released', $field]);
        }
    }

    /**
     * A person changed one of the fields: protect it if the setting says so and a source
     * reports this asset, and forget a reported value that now matches.
     *
     * @return bool whether the field is protected now
     */
    public static function afterPersonEdit(PDO $conn, ActorContext $ctx, int $assetId, string $field, ?string $value): bool
    {
        if (!in_array($field, self::FIELDS, true) || !self::ready($conn)) { return false; }
        $protected = isset(self::forAsset($conn, $assetId)[$field]);
        if (!$protected && self::autoProtect($conn)) {
            $st = $conn->prepare('SELECT last_seen IS NOT NULL FROM assets WHERE id = ?');
            $st->execute([$assetId]);
            if ((int)$st->fetchColumn() === 1) { self::set($conn, $ctx, $assetId, $field, true); $protected = true; }
        }
        $conn->prepare('UPDATE asset_protected_fields SET reported_value = NULL, reported_source = NULL, reported_at = NULL
            WHERE asset_id = ? AND field_name = ? AND reported_value <=> ?')->execute([$assetId, $field, $value]);
        return $protected;
    }

    /**
     * What a SOURCE may write: $reported minus the protected fields. For each protected
     * one, what the source said is kept beside it (or cleared when it agrees again).
     *
     * @param array<string,?string> $reported field => value the source reported
     * @param string $source        how to name the source on the page, e.g. 'the agent', 'Intune'
     * @return array<string,?string> the fields the source may write
     */
    public static function filterReported(PDO $conn, int $assetId, array $reported, string $source): array
    {
        if (!self::ready($conn)) { return $reported; }
        $prot = self::forAsset($conn, $assetId);
        if (!$prot) { return $reported; }
        $cur = $conn->prepare('SELECT * FROM assets WHERE id = ?');
        $cur->execute([$assetId]);
        $now = $cur->fetch(PDO::FETCH_ASSOC) ?: [];
        foreach ($reported as $field => $value) {
            if (!isset($prot[$field])) { continue; }
            unset($reported[$field]);
            $differs = $value !== null && (string)$value !== '' && strcasecmp((string)$value, (string)($now[$field] ?? '')) !== 0;
            if ($differs) {
                $conn->prepare('UPDATE asset_protected_fields SET reported_value = ?, reported_source = ?, reported_at = UTC_TIMESTAMP() WHERE asset_id = ? AND field_name = ?')
                    ->execute([mb_substr((string)$value, 0, 255), mb_substr($source, 0, 64), $assetId, $field]);
            } else {
                $conn->prepare('UPDATE asset_protected_fields SET reported_value = NULL, reported_source = NULL, reported_at = NULL WHERE asset_id = ? AND field_name = ?')
                    ->execute([$assetId, $field]);
            }
        }
        return $reported;
    }

    /** On unless someone has switched it off (Assets -> Settings -> Discovery & reconciliation). */
    public static function autoProtect(PDO $conn): bool
    {
        try {
            $st = $conn->prepare('SELECT setting_value FROM system_settings WHERE setting_key = ?');
            $st->execute([self::AUTO_SETTING]);
            return $st->fetchColumn() !== '0';
        } catch (Throwable) {
            return true;
        }
    }

    public static function ready(PDO $conn): bool
    {
        static $ready = null;
        if ($ready === null) {
            try { $conn->query('SELECT 1 FROM asset_protected_fields LIMIT 0'); $ready = true; }
            catch (Throwable) { $ready = false; }
        }
        return $ready;
    }
}
