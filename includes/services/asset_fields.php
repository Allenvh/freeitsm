<?php
/**
 * AssetFieldsService — user-defined fields on assets.
 *
 * The `assets` table describes a Windows PC, because that is what the agent
 * reports. This is how an install records everything else: printers, webcams,
 * headsets, televisions — with whichever columns that particular customer
 * cares about, declared through the UI rather than by a schema change.
 *
 * WHAT LIVES WHERE
 * ----------------
 * - The TYPE rules (what a number accepts, how a date parses, what a reference
 *   may point at) are in includes/typed_fields.php, shared with the CMDB.
 * - WHICH fields a given asset has is this file's job, and it is the one thing
 *   that could not be shared: the CMDB resolves properties from a class, where
 *   an asset resolves them from the field sets attached to its TYPE plus any
 *   attached to that ONE asset.
 * - Authorisation stays with the caller. Nothing here decides who may see an
 *   asset; it decides what an asset's fields are.
 *
 * 🔑 A field is defined ONCE, install-wide (asset_fields), and reused by every
 * set that wants it. That is what makes "find the thing with serial X" and
 * "every IP address in the estate" answerable at all — fourteen private copies
 * of "Serial Number" could never be queried together.
 *
 * @see docs/design/flexible-asset-fields.md
 */

require_once __DIR__ . '/../service_context.php';
require_once __DIR__ . '/../tenancy.php';
require_once __DIR__ . '/../typed_fields.php';

class AssetFieldsService
{
    /** Field types offered for assets. A subset of TypedFields::TYPES is not allowed — all of them are. */
    const TYPES = TypedFields::TYPES;

    /** Reference kinds a `ref` field may use here. */
    const REF_KINDS = ['user', 'asset', 'cmdb_object'];

    // ====================================================================
    //  Schema descriptor
    // ====================================================================

    /**
     * Where asset field values live, for TypedFields.
     *
     * `self_kind` = 'asset', so the no-self-reference rule fires for an asset
     * pointing at itself and NOT for one whose linked person happens to share
     * its id — which is exactly the bug a bare `$refId === $ownerId` test would
     * have introduced the first time a `user` field was added.
     */
    public static function valueSchema(): array
    {
        return [
            'value_table'  => 'asset_field_values',
            'owner_column' => 'asset_id',
            'def_column'   => 'field_id',
            'columns'      => [
                'text'    => 'value_text',
                'number'  => 'value_number',
                'date'    => 'value_date',
                'boolean' => 'value_boolean',
                'ref'     => 'value_ref_id',
            ],
            'self_kind'    => 'asset',
            'options'      => static function (PDO $conn, int $fieldId): array {
                $stmt = $conn->prepare(
                    "SELECT option_value FROM asset_field_options WHERE field_id = ? ORDER BY display_order, id"
                );
                $stmt->execute([$fieldId]);
                return $stmt->fetchAll(PDO::FETCH_COLUMN);
            },
            'unknown_hint' => 'See the field catalogue in Asset management settings.',
        ];
    }

    /** Has Database Verification created the tables yet? */
    public static function schemaReady(PDO $conn): bool
    {
        static $ready = null;
        if ($ready === null) {
            try {
                $conn->query("SELECT 1 FROM asset_field_values LIMIT 1");
                $ready = true;
            } catch (Exception) {
                $ready = false;
            }
        }
        return $ready;
    }

    // ====================================================================
    //  Resolution — which fields does this asset have?
    // ====================================================================

    /**
     * The sets that apply to an asset: those attached to its TYPE, plus those
     * attached to the asset itself.
     *
     * 🔑 The per-asset half is the pilot case. Ten televisions, three trialled
     * as smart TVs: the other seven do not show empty IP / MAC / Netflix fields,
     * they do not have those fields at all.
     *
     * @param ?int $assetTypeId  the asset's type, whose attached sets are included; null when it has no type
     * @return array Sets in display order, each with `via` = 'type' | 'asset'.
     *               A set attached BOTH ways appears once, marked 'type'.
     */
    public static function setsForAsset(PDO $conn, int $assetId, ?int $assetTypeId): array
    {
        if (!self::schemaReady($conn)) {
            return [];
        }

        $sets = [];

        if ($assetTypeId) {
            $stmt = $conn->prepare(
                "SELECT s.id, s.name, s.description, s.display_order, ats.sort_order
                   FROM asset_type_field_sets ats
                   JOIN asset_field_sets s ON s.id = ats.set_id
                  WHERE ats.asset_type_id = ? AND s.is_deleted = 0
               ORDER BY ats.sort_order, s.display_order, s.name"
            );
            $stmt->execute([$assetTypeId]);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $r['via'] = 'type';
                $sets[(int)$r['id']] = $r;
            }
        }

        $stmt = $conn->prepare(
            "SELECT s.id, s.name, s.description, s.display_order, 0 AS sort_order
               FROM asset_field_set_assets afsa
               JOIN asset_field_sets s ON s.id = afsa.set_id
              WHERE afsa.asset_id = ? AND s.is_deleted = 0
           ORDER BY s.display_order, s.name"
        );
        $stmt->execute([$assetId]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $id = (int)$r['id'];
            if (isset($sets[$id])) {
                continue;   // already applies via the type; do not list it twice
            }
            $r['via'] = 'asset';
            $sets[$id] = $r;
        }

        return array_values($sets);
    }

    /**
     * Canonical TypedFields definitions for an asset, keyed by field_key.
     *
     * A field in two attached sets appears ONCE. Where the two disagree on
     * whether it is required, required wins — the stricter reading is the safe
     * one, and a field that is mandatory for laptops should not become optional
     * because a second set also happens to include it.
     *
     * @param ?int $assetTypeId  the asset's type, whose attached sets are included; null when it has no type
     */
    public static function fieldsForAsset(PDO $conn, int $assetId, ?int $assetTypeId): array
    {
        $sets = self::setsForAsset($conn, $assetId, $assetTypeId);
        if (!$sets) {
            return [];
        }
        return self::fieldsForSets($conn, array_column($sets, 'id'));
    }

    /**
     * Canonical definitions for a list of set ids, keyed by field_key.
     *
     * @param array $setIds  asset_field_sets ids; duplicates and non-integers are tidied
     */
    public static function fieldsForSets(PDO $conn, array $setIds): array
    {
        $setIds = array_values(array_unique(array_map('intval', $setIds)));
        if (!$setIds || !self::schemaReady($conn)) {
            return [];
        }
        $ph = implode(',', array_fill(0, count($setIds), '?'));
        $stmt = $conn->prepare(
            "SELECT f.id, f.field_key, f.label, f.field_type, f.config, f.help_text,
                    f.is_unique, f.show_in_list, f.is_searchable,
                    sf.set_id, sf.sort_order, sf.is_required, sf.default_value,
                    s.name AS set_name, s.display_order AS set_order
               FROM asset_field_set_fields sf
               JOIN asset_fields f      ON f.id = sf.field_id
               JOIN asset_field_sets s  ON s.id = sf.set_id
              WHERE sf.set_id IN ({$ph}) AND f.is_deleted = 0
           ORDER BY s.display_order, s.name, sf.sort_order, f.label"
        );
        $stmt->execute($setIds);

        $defs = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $key = $r['field_key'];
            if (isset($defs[$key])) {
                // Second sighting: keep the first placement, but never relax
                // required — the stricter of the two wins.
                $defs[$key]['required'] = $defs[$key]['required'] || ((int)$r['is_required'] === 1);
                continue;
            }
            $defs[$key] = self::toDef($r);
        }
        return $defs;
    }

    /**
     * One catalogue row (joined to its set membership) -> a canonical definition.
     *
     * @param array $r  one asset_fields row joined to its asset_field_set_fields and asset_field_sets row
     */
    private static function toDef(array $r): array
    {
        $config = [];
        if (!empty($r['config'])) {
            $decoded = json_decode($r['config'], true);
            if (is_array($decoded)) {
                $config = $decoded;
            }
        }
        $isRef = ($r['field_type'] === 'ref');
        return [
            'id'         => (int)$r['id'],
            'key'        => $r['field_key'],
            'label'      => $r['label'],
            'type'       => $r['field_type'],
            'required'   => ((int)($r['is_required'] ?? 0) === 1),
            'config'     => $config,
            'ref_kind'   => $isRef ? ($config['ref_kind'] ?? null) : null,
            'ref_target' => ($isRef && isset($config['ref_target']) && $config['ref_target'] !== '')
                            ? (int)$config['ref_target'] : null,
            // Presentation, ignored by the engine but needed by every screen.
            'help_text'    => $r['help_text'] ?? null,
            'set_id'       => isset($r['set_id']) ? (int)$r['set_id'] : null,
            'set_name'     => $r['set_name'] ?? null,
            'sort_order'   => (int)($r['sort_order'] ?? 0),
            'default_value'=> $r['default_value'] ?? null,
            'show_in_list' => ((int)($r['show_in_list'] ?? 0) === 1),
            'is_unique'    => ((int)($r['is_unique'] ?? 0) === 1),
        ];
    }

    // ====================================================================
    //  Values
    // ====================================================================

    /**
     * Values for ONE asset, as [field_key => value]. Convenience over the
     * batched reader — for a LIST, call readForAssets() once instead.
     *
     * @param array $defs  field definitions keyed by field_key, as from fieldsForAsset()
     */
    public static function valuesForAsset(PDO $conn, int $assetId, array $defs): array
    {
        $all = TypedFields::readValues($conn, self::valueSchema(), $defs, [$assetId]);
        return $all[$assetId] ?? [];
    }

    /**
     * Values for many assets in one query: [assetId => [field_key => value]].
     *
     * ⚠️ $defs must cover every field any of these assets could hold — pass the
     * whole visible catalogue, not one asset's fields, or values belonging to a
     * field outside the list are silently dropped.
     *
     * @param array $assetIds  the assets whose values to read
     * @param array $defs  field definitions keyed by field_key covering every field any of them may hold
     */
    public static function readForAssets(PDO $conn, array $assetIds, array $defs): array
    {
        if (!self::schemaReady($conn)) {
            return [];
        }
        return TypedFields::readValues($conn, self::valueSchema(), $defs, $assetIds);
    }

    /**
     * Validate + store values for one asset.
     *
     * Only fields that actually apply to this asset may be written — a caller
     * cannot smuggle in a field from a set the asset does not have. Unknown or
     * inapplicable keys are a 422, never a silent drop, because a mapping
     * mistake in an import must be visible.
     *
     * History is written per changed field so a custom field is as auditable as
     * a built-in one; an estate where core edits are logged and custom ones
     * vanish is worse than no audit at all.
     *
     * @param array $values  [field_key => value] from the request; keys that do not apply to the asset are refused
     */
    public static function saveValues(PDO $conn, ActorContext $ctx, int $assetId, array $values): array
    {
        if (!self::schemaReady($conn)) {
            throw new ServiceError('conflict', 'schema_not_ready',
                'Custom asset fields need Database Verification to run first.');
        }

        $asset = self::loadAsset($conn, $assetId);
        $defs  = self::fieldsForAsset($conn, $assetId, $asset['asset_type_id'] ? (int)$asset['asset_type_id'] : null);

        if (!$defs) {
            throw new ServiceError('validation', 'invalid_field',
                'This asset has no custom fields. Attach a field set to its type, or to the asset itself.');
        }

        $before = self::valuesForAsset($conn, $assetId, $defs);

        self::assertUnique($conn, $assetId, $defs, $values);
        TypedFields::checkRequired($defs, $values, false);

        $owns = !$conn->inTransaction();
        if ($owns) {
            $conn->beginTransaction();
        }
        try {
            TypedFields::writeValues($conn, self::valueSchema(), $assetId, $defs, $values);
            $after = self::valuesForAsset($conn, $assetId, $defs);
            self::logChanges($conn, $ctx, $assetId, $defs, $before, $after);
            if ($owns) {
                $conn->commit();
            }
        } catch (Throwable $e) {
            if ($owns && $conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }

        return ['id' => $assetId, 'values' => $after];
    }

    /**
     * Enforce `is_unique` across the estate.
     *
     * ⚠️ Scoped to the asset's OWN company. Two customers may each legitimately
     * hold a printer with asset tag PR0001 — the same reasoning that keeps
     * hostname uniqueness per company in application code rather than in an
     * index.
     *
     * @param array $defs  the asset's field definitions keyed by field_key
     * @param array $values  [field_key => value] about to be saved; blank values are never checked
     */
    private static function assertUnique(PDO $conn, int $assetId, array $defs, array $values): void
    {
        $tenantId = self::tenantOfAsset($conn, $assetId);

        foreach ($values as $key => $raw) {
            if (!isset($defs[$key]) || empty($defs[$key]['is_unique'])) {
                continue;
            }
            if ($raw === null || $raw === '') {
                continue;   // absent never collides with absent
            }
            $def   = $defs[$key];
            $class = TypedFields::storageClass($def['type']);
            $col   = self::valueSchema()['columns'][$class] ?? null;
            if ($col === null) {
                continue;
            }

            $sql = "SELECT a.id FROM asset_field_values v
                      JOIN assets a ON a.id = v.asset_id
                     WHERE v.field_id = ? AND v.`{$col}` = ? AND v.asset_id <> ?";
            $params = [(int)$def['id'], $raw, $assetId];
            if ($tenantId === null) {
                $sql .= " AND a.tenant_id IS NULL";
            } else {
                $sql .= " AND a.tenant_id = ?";
                $params[] = $tenantId;
            }
            $sql .= " LIMIT 1";

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            if ($stmt->fetchColumn() !== false) {
                throw new ServiceError('conflict', 'duplicate_value',
                    "Another asset already has '{$def['label']}' set to that value.");
            }
        }
    }

    /**
     * One asset_history row per changed field.
     *
     * field_name is stored as `field:<field_key>` — the column is VARCHAR(100)
     * and the key is the stable identifier, so history stays readable after a
     * label is renamed.
     *
     * @param array $defs  the asset's field definitions keyed by field_key; only these keys are compared
     * @param array $before  [field_key => value] before the save
     * @param array $after  [field_key => value] after the save
     */
    private static function logChanges(
        PDO $conn, ActorContext $ctx, int $assetId, array $defs, array $before, array $after): void
    {
        if ($ctx->actorId <= 0) {
            return;   // an automated path with no analyst behind it
        }
        $ins = $conn->prepare(
            "INSERT INTO asset_history (asset_id, analyst_id, field_name, old_value, new_value)
             VALUES (?, ?, ?, ?, ?)"
        );
        foreach (array_keys($defs) as $key) {
            $old = $before[$key] ?? null;
            $new = $after[$key]  ?? null;
            if ($old === $new) {
                continue;
            }
            $ins->execute([
                $assetId, $ctx->actorId, 'field:' . $key,
                self::historyText($old), self::historyText($new),
            ]);
        }
    }

    /**
     * A value as it should read in the history table. NULL stays NULL — "not set".
     *
     * @param mixed $v  a stored field value; bools become '1'/'0', text is cut to 500 characters
     */
    private static function historyText($v): ?string
    {
        if ($v === null) {
            return null;
        }
        if (is_bool($v)) {
            return $v ? '1' : '0';
        }
        return mb_substr((string)$v, 0, 500);
    }

    // ====================================================================
    //  Per-asset set attachment (the pilot case)
    // ====================================================================

    /**
     * Attach a set to ONE asset. Idempotent.
     *
     * @param int $setId  the asset_field_sets row to attach
     */
    public static function attachSetToAsset(PDO $conn, ActorContext $ctx, int $assetId, int $setId): void
    {
        self::loadAsset($conn, $assetId);
        self::loadSet($conn, $setId);
        $stmt = $conn->prepare(
            "INSERT INTO asset_field_set_assets (asset_id, set_id, created_by_analyst_id)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE set_id = VALUES(set_id)"
        );
        $stmt->execute([$assetId, $setId, $ctx->actorId ?: null]);
    }

    /**
     * Detach a set from one asset.
     *
     * ⚠️ The VALUES ARE KEPT. Un-ticking "Smart TV pilot" hides the fields; it
     * does not throw away the IP address someone recorded. Re-attach and it is
     * all still there. Deleting on detach would make an accidental click
     * unrecoverable, and the rows are harmless — readValues() only returns
     * values for fields that currently apply.
     *
     * @param int $setId  the asset_field_sets row to detach
     */
    public static function detachSetFromAsset(PDO $conn, int $assetId, int $setId): void
    {
        $stmt = $conn->prepare("DELETE FROM asset_field_set_assets WHERE asset_id = ? AND set_id = ?");
        $stmt->execute([$assetId, $setId]);
    }

    // ====================================================================
    //  Catalogue administration
    // ====================================================================

    /**
     * Create or update a field in the catalogue.
     *
     * ⚠️ `field_key` is IMMUTABLE once the row exists. Import mappings and saved
     * reports point at it, so renaming the label from "Size" to "Screen size"
     * must not break a nightly import. The label is freely renameable; the key
     * is not.
     *
     * ⚠️ `field_type` is immutable once VALUES exist, for the same reason
     * FormsService fixed it: changing it strands every answer under a column the
     * new type never reads. Presentational variants are modes inside `config`
     * (multiline, decimals, date_mode), which stay editable forever.
     *
     * @param array $in  the request fields: id (0 = new), label, field_type, field_key, config, help_text, is_unique,
     *     is_searchable, show_in_list, options
     */
    public static function saveField(PDO $conn, ActorContext $ctx, array $in): array
    {
        $id    = (int)($in['id'] ?? 0);
        $label = trim((string)($in['label'] ?? ''));
        if ($label === '') {
            throw new ServiceError('validation', 'missing_field', "'label' is required.");
        }
        if (mb_strlen($label) > 150) {
            throw new ServiceError('validation', 'invalid_field', "'label' must be at most 150 characters.");
        }

        $config = is_array($in['config'] ?? null) ? $in['config'] : [];

        if ($id > 0) {
            $existing = self::loadField($conn, $id);
            $type     = $existing['field_type'];

            // The type may still be corrected while nothing has answered it.
            $wanted = trim((string)($in['field_type'] ?? $type));
            if ($wanted !== $type) {
                if (self::fieldHasValues($conn, $id)) {
                    throw new ServiceError('conflict', 'type_locked',
                        "'{$existing['label']}' already has values recorded, so its type can no longer be changed. "
                        . 'Retire it and add a new field if the type is wrong.');
                }
                $type = self::assertType($wanted, $config);
            }

            $stmt = $conn->prepare(
                "UPDATE asset_fields
                    SET label = ?, field_type = ?, config = ?, help_text = ?,
                        is_unique = ?, is_searchable = ?, show_in_list = ?
                  WHERE id = ?"
            );
            $stmt->execute([
                $label, $type, $config ? json_encode($config) : null,
                self::nullIfBlank($in['help_text'] ?? null),
                !empty($in['is_unique']) ? 1 : 0,
                !empty($in['is_searchable']) ? 1 : 0,
                !empty($in['show_in_list']) ? 1 : 0,
                $id,
            ]);
            self::saveOptions($conn, $id, $type, $in['options'] ?? null);
            return ['id' => $id, 'created' => false];
        }

        $type = self::assertType(trim((string)($in['field_type'] ?? 'text')), $config);
        $key  = self::makeKey($conn, $in['field_key'] ?? $label);

        $tenantId = self::configTenantForCreate($conn, $ctx);
        self::assertKeyFree($conn, $key, $tenantId);

        $stmt = $conn->prepare(
            "INSERT INTO asset_fields
                (field_key, label, field_type, config, help_text, is_unique, is_searchable, show_in_list, tenant_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $key, $label, $type, $config ? json_encode($config) : null,
            self::nullIfBlank($in['help_text'] ?? null),
            !empty($in['is_unique']) ? 1 : 0,
            !empty($in['is_searchable']) ? 1 : 0,
            !empty($in['show_in_list']) ? 1 : 0,
            $tenantId,
        ]);
        $newId = (int)$conn->lastInsertId();
        self::saveOptions($conn, $newId, $type, $in['options'] ?? null);
        return ['id' => $newId, 'created' => true, 'field_key' => $key];
    }

    /**
     * Retire a field. SOFT delete, always.
     *
     * 🔑 The row stays because asset_field_values points at it. A hard delete
     * would take every answer ever recorded with it, and the foreign key is
     * deliberately RESTRICT so that mistake is impossible even from the
     * database side.
     *
     * @param int $id  the asset_fields row to retire
     */
    public static function deleteField(PDO $conn, int $id): void
    {
        self::loadField($conn, $id);
        $conn->prepare("UPDATE asset_fields SET is_deleted = 1 WHERE id = ?")->execute([$id]);
        $conn->prepare("DELETE FROM asset_field_set_fields WHERE field_id = ?")->execute([$id]);
    }

    /**
     * Create or update a field set.
     *
     * @param array $in  the request fields: id (0 = new), name, description, display_order
     */
    public static function saveSet(PDO $conn, ActorContext $ctx, array $in): array
    {
        $id   = (int)($in['id'] ?? 0);
        $name = trim((string)($in['name'] ?? ''));
        if ($name === '') {
            throw new ServiceError('validation', 'missing_field', "'name' is required.");
        }
        $desc  = self::nullIfBlank($in['description'] ?? null);
        $order = (int)($in['display_order'] ?? 0);

        if ($id > 0) {
            self::loadSet($conn, $id);
            $conn->prepare("UPDATE asset_field_sets SET name = ?, description = ?, display_order = ? WHERE id = ?")
                 ->execute([$name, $desc, $order, $id]);
            return ['id' => $id, 'created' => false];
        }

        $conn->prepare("INSERT INTO asset_field_sets (name, description, display_order, tenant_id) VALUES (?, ?, ?, ?)")
             ->execute([$name, $desc, $order, self::configTenantForCreate($conn, $ctx)]);
        return ['id' => (int)$conn->lastInsertId(), 'created' => true];
    }

    /**
     * Retire a set. Soft delete, and its attachments go with it.
     *
     * The VALUES survive — the same reasoning as detachSetFromAsset(). Deleting
     * a set is a statement about which fields to show, never about the answers
     * already given.
     *
     * @param int $id  the asset_field_sets row to retire
     */
    public static function deleteSet(PDO $conn, int $id): void
    {
        self::loadSet($conn, $id);
        $conn->prepare("UPDATE asset_field_sets SET is_deleted = 1 WHERE id = ?")->execute([$id]);
        $conn->prepare("DELETE FROM asset_type_field_sets WHERE set_id = ?")->execute([$id]);
        $conn->prepare("DELETE FROM asset_field_set_assets WHERE set_id = ?")->execute([$id]);
    }

    /**
     * Replace the field list of a set.
     *
     * Whole-list replace rather than add/remove calls, so one drag-reorder is
     * one request and the order can never end up half-applied.
     *
     * @param int $setId  the asset_field_sets row whose field list is replaced
     * @param array $rows  the new list in order, each {field_id, sort_order?, is_required?, default_value?}
     */
    public static function setSetFields(PDO $conn, int $setId, array $rows): void
    {
        self::loadSet($conn, $setId);

        $owns = !$conn->inTransaction();
        if ($owns) {
            $conn->beginTransaction();
        }
        try {
            $conn->prepare("DELETE FROM asset_field_set_fields WHERE set_id = ?")->execute([$setId]);
            $ins = $conn->prepare(
                "INSERT INTO asset_field_set_fields (set_id, field_id, sort_order, is_required, default_value)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $seen = [];
            foreach (array_values($rows) as $i => $row) {
                $fieldId = (int)($row['field_id'] ?? 0);
                if ($fieldId <= 0 || isset($seen[$fieldId])) {
                    continue;   // a set holds a field once
                }
                self::loadField($conn, $fieldId);
                $seen[$fieldId] = true;
                $ins->execute([
                    $setId, $fieldId, (int)($row['sort_order'] ?? $i),
                    !empty($row['is_required']) ? 1 : 0,
                    self::nullIfBlank($row['default_value'] ?? null),
                ]);
            }
            if ($owns) {
                $conn->commit();
            }
        } catch (Throwable $e) {
            if ($owns && $conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Replace which sets are attached to an asset type.
     *
     * @param int $assetTypeId  the asset type whose attached sets are replaced
     * @param array $setIds  the asset_field_sets ids to attach, in display order
     */
    public static function setTypeSets(PDO $conn, int $assetTypeId, array $setIds): void
    {
        $owns = !$conn->inTransaction();
        if ($owns) {
            $conn->beginTransaction();
        }
        try {
            $conn->prepare("DELETE FROM asset_type_field_sets WHERE asset_type_id = ?")->execute([$assetTypeId]);
            $ins = $conn->prepare(
                "INSERT INTO asset_type_field_sets (asset_type_id, set_id, sort_order) VALUES (?, ?, ?)"
            );
            $seen = [];
            foreach (array_values($setIds) as $i => $sid) {
                $sid = (int)$sid;
                if ($sid <= 0 || isset($seen[$sid])) {
                    continue;
                }
                self::loadSet($conn, $sid);
                $seen[$sid] = true;
                $ins->execute([$assetTypeId, $sid, $i]);
            }
            if ($owns) {
                $conn->commit();
            }
        } catch (Throwable $e) {
            if ($owns && $conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    // ---- admin helpers ---------------------------------------------------

    /**
     * Validates a custom field type; a link field must also name a valid ref_kind.
     *
     * @param string $type  the field type asked for; must be one of self::TYPES
     * @param array $config  the field's config; a 'ref' field must carry a valid ref_kind
     */
    private static function assertType(string $type, array $config): string
    {
        if (!in_array($type, self::TYPES, true)) {
            throw new ServiceError('validation', 'invalid_field',
                "Unknown field type '{$type}'. One of: " . implode(', ', self::TYPES) . '.');
        }
        if ($type === 'ref') {
            $kind = $config['ref_kind'] ?? '';
            if (!in_array($kind, self::REF_KINDS, true)) {
                throw new ServiceError('validation', 'invalid_field',
                    'A link field needs config.ref_kind, one of: ' . implode(', ', self::REF_KINDS) . '.');
            }
        }
        return $type;
    }

    /**
     * A stable machine key from a label: lowercase, underscores, no leading digit.
     *
     * @param string $seed  the field_key asked for, or the label when none was given
     */
    private static function makeKey(PDO $conn, string $seed): string
    {
        $key = strtolower(trim($seed));
        $key = preg_replace('/[^a-z0-9]+/', '_', $key);
        $key = trim((string)$key, '_');
        if ($key === '' || ctype_digit($key[0])) {
            $key = 'f_' . $key;
        }
        return mb_substr($key, 0, 100);
    }

    /**
     * ⚠️ Application-level uniqueness, because the UNIQUE index cannot do it:
     * MySQL treats NULLs as distinct, so two GLOBAL fields could both claim
     * `serial_number` while the index looked like it was guarding them. Exactly
     * the trap documented on assets.asset_tag.
     *
     * @param string $key  the field_key the new field would take
     */
    private static function assertKeyFree(PDO $conn, string $key, ?int $tenantId): void
    {
        if ($tenantId === null) {
            $stmt = $conn->prepare("SELECT id FROM asset_fields WHERE field_key = ? AND tenant_id IS NULL LIMIT 1");
            $stmt->execute([$key]);
        } else {
            $stmt = $conn->prepare(
                "SELECT id FROM asset_fields WHERE field_key = ? AND (tenant_id IS NULL OR tenant_id = ?) LIMIT 1"
            );
            $stmt->execute([$key, $tenantId]);
        }
        if ($stmt->fetchColumn() !== false) {
            throw new ServiceError('conflict', 'duplicate_key',
                "A field called '{$key}' already exists. Give this one a different name.");
        }
    }

    /** Which company a new catalogue row belongs to. NULL = a global default. */
    private static function configTenantForCreate(PDO $conn, ActorContext $ctx): ?int
    {
        if (!isMultiTenant($conn)) {
            return null;
        }
        $active = getActiveTenantId($conn, $ctx->actorId);
        if ($active === null || $active === getDefaultTenantId($conn)) {
            return null;   // working in the Default company creates a shared default
        }
        return (int)$active;
    }

    /**
     * Replaces a dropdown field's option list, skipping blanks and duplicates; a field that
     * is not a dropdown loses its options.
     *
     * Leaves the list alone when no options were sent.
     *
     * @param int $fieldId  the asset_fields row whose options are replaced
     * @param string $type  the field's type; anything but 'dropdown' clears its options
     * @param mixed $options  the options from the request, each a string or {option_value, colour}; null leaves them alone
     */
    private static function saveOptions(PDO $conn, int $fieldId, string $type, $options): void
    {
        if ($options === null || !is_array($options)) {
            return;   // not supplied = leave alone
        }
        if ($type !== 'dropdown') {
            // A field that stopped being a dropdown must not keep a stale option
            // list that nothing enforces any more.
            $conn->prepare("DELETE FROM asset_field_options WHERE field_id = ?")->execute([$fieldId]);
            return;
        }
        $conn->prepare("DELETE FROM asset_field_options WHERE field_id = ?")->execute([$fieldId]);
        $ins = $conn->prepare(
            "INSERT INTO asset_field_options (field_id, option_value, colour, display_order) VALUES (?, ?, ?, ?)"
        );
        $seen = [];
        foreach (array_values($options) as $i => $opt) {
            $value  = is_array($opt) ? trim((string)($opt['option_value'] ?? '')) : trim((string)$opt);
            $colour = is_array($opt) ? self::nullIfBlank($opt['colour'] ?? null) : null;
            if ($value === '' || isset($seen[$value])) {
                continue;
            }
            $seen[$value] = true;
            $ins->execute([$fieldId, $value, $colour, $i]);
        }
    }

    /**
     * True when any asset has a value stored for the field.
     *
     * @param int $fieldId  the asset_fields row to check
     */
    private static function fieldHasValues(PDO $conn, int $fieldId): bool
    {
        $stmt = $conn->prepare("SELECT 1 FROM asset_field_values WHERE field_id = ? LIMIT 1");
        $stmt->execute([$fieldId]);
        return $stmt->fetchColumn() !== false;
    }

    /**
     * Loads one custom field that has not been deleted; throws not found otherwise.
     *
     * @param int $id  the asset_fields row to load
     */
    private static function loadField(PDO $conn, int $id): array
    {
        $stmt = $conn->prepare("SELECT * FROM asset_fields WHERE id = ? AND is_deleted = 0");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new ServiceError('not_found', 'not_found', 'Field not found.');
        }
        return $row;
    }

    /**
     * Trimmed text, or null when blank.
     *
     * @param mixed $v  the value from the request
     */
    private static function nullIfBlank($v): ?string
    {
        $v = ($v === null) ? '' : trim((string)$v);
        return $v === '' ? null : $v;
    }

    // ====================================================================
    //  Lookups
    // ====================================================================

    /** Every field in the catalogue visible to this company (global + own). */
    public static function catalogue(PDO $conn, int $analystId): array
    {
        if (!self::schemaReady($conn)) {
            return [];
        }
        $activeId = getActiveTenantId($conn, $analystId) ?? getDefaultTenantId($conn);
        $rows = getTenantConfigRows(
            $conn, 'asset_fields', 'asset_field', (int)$activeId,
            'id, field_key, label, field_type, config, help_text, is_unique, is_searchable, show_in_list, tenant_id',
            'is_deleted = 0', 'label'
        );
        foreach ($rows as &$r) {
            $r['scope'] = ($r['tenant_id'] === null) ? 'global' : 'company';
        }
        return $rows;
    }

    /** Every field set visible to this company (global + own). */
    public static function sets(PDO $conn, int $analystId): array
    {
        if (!self::schemaReady($conn)) {
            return [];
        }
        $activeId = getActiveTenantId($conn, $analystId) ?? getDefaultTenantId($conn);
        $rows = getTenantConfigRows(
            $conn, 'asset_field_sets', 'asset_field_set', (int)$activeId,
            'id, name, description, display_order, tenant_id',
            'is_deleted = 0', 'display_order, name'
        );
        foreach ($rows as &$r) {
            $r['scope'] = ($r['tenant_id'] === null) ? 'global' : 'company';
        }
        return $rows;
    }

    /** Loads an asset's id, type and company; throws not found when it does not exist. */
    private static function loadAsset(PDO $conn, int $assetId): array
    {
        $stmt = $conn->prepare("SELECT id, asset_type_id, tenant_id FROM assets WHERE id = ?");
        $stmt->execute([$assetId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new ServiceError('not_found', 'not_found', 'Asset not found.');
        }
        return $row;
    }

    /**
     * Loads one field set that has not been deleted; throws not found otherwise.
     *
     * @param int $setId  the asset_field_sets row to load
     */
    private static function loadSet(PDO $conn, int $setId): array
    {
        $stmt = $conn->prepare("SELECT id, name FROM asset_field_sets WHERE id = ? AND is_deleted = 0");
        $stmt->execute([$setId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new ServiceError('not_found', 'not_found', 'Field set not found.');
        }
        return $row;
    }

    /** The company id of an asset, or null. */
    private static function tenantOfAsset(PDO $conn, int $assetId): ?int
    {
        $stmt = $conn->prepare("SELECT tenant_id FROM assets WHERE id = ?");
        $stmt->execute([$assetId]);
        $v = $stmt->fetchColumn();
        return ($v === false || $v === null) ? null : (int)$v;
    }
}
