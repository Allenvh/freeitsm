<?php
/**
 * Manufacturer and model names (GH #200 part D).
 *
 * The same vendor arrives spelled several ways - "HP", "HP Inc.", "Hewlett-Packard" -
 * from the agent, Intune and people, and a dashboard chart by manufacturer splits one
 * vendor into three slices. A mapping says "show HP Inc. as HP". It is applied when a
 * value is SHOWN or FILTERED - the table view, the asset list's search, dashboard charts,
 * a hint on the asset page - and never written back: each asset keeps exactly what was
 * reported or typed, so removing a mapping undoes it completely.
 *
 * Install-wide: a vendor's name is not a company's choice. Matching ignores case.
 *
 * @wiki Asset-Review-Developer-Guide
 */
require_once dirname(__DIR__) . '/service_context.php';

final class AssetNameMapService
{
    public const FIELDS = ['manufacturer', 'model'];

    /** Every mapping, by field then name. */
    public static function all(PDO $conn): array
    {
        if (!self::ready($conn)) { return []; }
        return $conn->query('SELECT id, field, from_value, to_value FROM asset_name_mappings ORDER BY field, to_value, from_value')->fetchAll(PDO::FETCH_ASSOC);
    }

    /** field => [lower(from) => to], for mapping rows in PHP. */
    public static function lookup(PDO $conn): array
    {
        static $cache = null;
        if ($cache !== null) { return $cache; }
        $cache = array_fill_keys(self::FIELDS, []);
        foreach (self::all($conn) as $m) { $cache[$m['field']][mb_strtolower($m['from_value'])] = $m['to_value']; }
        return $cache;
    }

    /** The name a value is shown as. */
    public static function show(PDO $conn, string $field, ?string $value): ?string
    {
        if ($value === null || $value === '') { return $value; }
        return self::lookup($conn)[$field][mb_strtolower(trim($value))] ?? $value;
    }

    /**
     * The same in SQL, for grouping and counting in the database (dashboard charts).
     *
     * @param string $column the qualified column, e.g. 'a.manufacturer'
     */
    public static function sqlExpr(PDO $conn, string $field, string $column): string
    {
        if (!in_array($field, self::FIELDS, true) || !self::ready($conn)) { return $column; }
        return "COALESCE((SELECT nm.to_value FROM asset_name_mappings nm WHERE nm.field = '$field' AND nm.from_value = TRIM($column) LIMIT 1), $column)";
    }

    /** Raw values whose mapped name contains $term - so searching "HP" finds "Hewlett-Packard". */
    public static function fromValuesMatching(PDO $conn, string $field, string $term): array
    {
        if (!self::ready($conn) || $term === '') { return []; }
        $st = $conn->prepare('SELECT from_value FROM asset_name_mappings WHERE field = ? AND to_value LIKE ?');
        $st->execute([$field, '%' . $term . '%']);
        return $st->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Add or change a mapping. Mapping a name to itself, or to nothing, is refused. */
    public static function save(PDO $conn, ActorContext $ctx, string $field, string $from, string $to): int
    {
        $from = trim($from); $to = trim($to);
        if (!in_array($field, self::FIELDS, true)) { throw new ServiceError('validation', 'invalid_field', 'Choose manufacturer or model.'); }
        if ($from === '' || $to === '' || mb_strlen($from) > 100 || mb_strlen($to) > 100) {
            throw new ServiceError('validation', 'invalid_field', 'Both names are needed, up to 100 characters each.');
        }
        if (strcasecmp($from, $to) === 0) { throw new ServiceError('validation', 'invalid_field', 'A name cannot be shown as itself.'); }
        $chain = $conn->prepare('SELECT 1 FROM asset_name_mappings WHERE field = ? AND from_value = ?');
        $chain->execute([$field, $to]);
        if ($chain->fetchColumn()) {
            throw new ServiceError('validation', 'invalid_field', "\"$to\" is itself shown as another name. Map to that name instead.");
        }
        $conn->prepare('INSERT INTO asset_name_mappings (field, from_value, to_value, created_by_id, created_at) VALUES (?, ?, ?, ?, UTC_TIMESTAMP())
            ON DUPLICATE KEY UPDATE to_value = VALUES(to_value)')->execute([$field, $from, $to, $ctx->actorId > 0 ? $ctx->actorId : null]);
        // Anything that already pointed at $from now points at $to, so no mapping chains.
        $conn->prepare('UPDATE asset_name_mappings SET to_value = ? WHERE field = ? AND to_value = ?')->execute([$to, $field, $from]);
        $id = $conn->prepare('SELECT id FROM asset_name_mappings WHERE field = ? AND from_value = ?');
        $id->execute([$field, $from]);
        return (int)$id->fetchColumn();
    }

    public static function delete(PDO $conn, int $id): void
    {
        $conn->prepare('DELETE FROM asset_name_mappings WHERE id = ?')->execute([$id]);
    }

    /** The names in use, with how many assets have each, to choose from. */
    public static function namesInUse(PDO $conn, string $field): array
    {
        if (!in_array($field, self::FIELDS, true)) { return []; }
        return $conn->query("SELECT TRIM($field) AS name, COUNT(*) AS n FROM assets WHERE $field IS NOT NULL AND TRIM($field) <> ''
            GROUP BY TRIM($field) ORDER BY n DESC, name LIMIT 300")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ready(PDO $conn): bool
    {
        static $ready = null;
        if ($ready === null) {
            try { $conn->query('SELECT 1 FROM asset_name_mappings LIMIT 0'); $ready = true; }
            catch (Throwable) { $ready = false; }
        }
        return $ready;
    }
}
