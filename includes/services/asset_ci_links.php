<?php
/**
 * The asset <-> configuration item link (GH #201) - "these two records are the same
 * piece of equipment". ONE implementation behind the asset page, the CI page and REST.
 *
 * An IDENTITY, not a dependency: the link never appears in the CMDB graph, never spreads
 * impact and never deletes anything. Nothing on it is copied - the CI page reads the
 * asset's details live through it.
 *
 * Storage: asset_ci_links, one row per PERIOD of being linked. A row is closed
 * (unlinked_at), never deleted, so the table is also the history - the CMDB has none of
 * its own. At most one live row per asset and one per CI; checked here, inside a
 * transaction that locks both records (asset first, then CI, everywhere).
 *
 * Who sees what (agreed on #201): seeing the other end needs the same access as opening
 * it. Someone who cannot gets {restricted: true} - no name, no id, no details - in the
 * card, in REST, in the history and in conflict messages. The display setting
 * (CMDB -> Settings -> Asset card) only narrows what an authorised reader sees.
 *
 * Who may change it: write access to Assets AND to the CMDB, and both records' company.
 * Neither module has a Read only level yet, so today that is module access; if either
 * gains one, analystCanWriteModule() refuses read-only analysts here with no change.
 * REST: the narrow asset_ci_links permissions and the key's company scope.
 *
 * Callers pass an ACCESS array alongside the ActorContext, because the two adapters
 * know different things: accessForAnalyst() (module access) or accessForApiKey()
 * (key permissions). Keys: assets_read, cmdb_read, assets_write, cmdb_write.
 *
 * TRAP: asset_ci_links.asset_id is in AssetMergeService::REFERENCES (handled by
 *   mergeLinks()), and original_asset_id is in NOT_REFERENCES - it is the record the
 *   link was MADE on and must never move. CmdbService::deleteObject() calls ciDeleted()
 *   inside its own transaction. Miss either and the history quietly lies.
 *
 * @wiki Asset-CI-Link-Design
 * @wiki Asset-CI-Link-Developer-Guide
 */
require_once dirname(__DIR__) . '/service_context.php';
require_once dirname(__DIR__) . '/tenancy.php';

final class AssetCiLinkService
{
    /** Why a link ended. */
    public const REASONS = ['unlinked', 'replaced', 'ci_deleted', 'asset_merged'];

    /** The asset details the CI's card can show - CMDB -> Settings -> Asset card. All on by default. */
    public const CARD_FIELDS = ['type', 'make_model', 'serial', 'asset_tag', 'status', 'location', 'freshness'];

    /** system_settings key holding the ticked CARD_FIELDS (JSON list). Missing = all of them. */
    public const CARD_SETTING = 'cmdb_asset_card_fields';

    // ======================================================================
    //  Access
    // ======================================================================

    /** What a signed-in analyst may see and change, from their module access. */
    public static function accessForAnalyst(PDO $conn, int $analystId): array
    {
        return [
            'assets_read'  => analystCanAccessModule($conn, $analystId, 'assets'),
            'cmdb_read'    => analystCanAccessModule($conn, $analystId, 'cmdb'),
            'assets_write' => analystCanWriteModule($conn, $analystId, 'assets'),
            'cmdb_write'   => analystCanWriteModule($conn, $analystId, 'cmdb'),
        ];
    }

    /**
     * What a REST key may see and change. Reading either end needs that side's read
     * permission; changing the link needs only the narrow asset_ci_links permission,
     * which is the point of it (an integration can maintain links without editing records).
     *
     * @param bool $forWrite  true when the request changes the link (create/delete granted by the route)
     */
    public static function accessForApiKey(array $apiKey, bool $forWrite = false): array
    {
        $p = $apiKey['permissions'] ?? [];
        $has = static fn(string $r, string $a) => isset($p[$r]) && in_array($a, $p[$r], true);
        return [
            'assets_read'  => $has('assets', 'read'),
            'cmdb_read'    => $has('cmdb_objects', 'read'),
            'assets_write' => $forWrite,
            'cmdb_write'   => $forWrite,
        ];
    }

    /** Has Database Verification created the table yet? */
    public static function ready(PDO $conn): bool
    {
        static $ready = null;
        if ($ready === null) {
            try { $conn->query('SELECT 1 FROM asset_ci_links LIMIT 0'); $ready = true; }
            catch (PDOException) { $ready = false; }
        }
        return $ready;
    }

    // ======================================================================
    //  Reading
    // ======================================================================

    /**
     * The link as seen from the asset: the live CI (or restricted, or null), the history,
     * and whether this reader may change it.
     */
    public static function forAsset(PDO $conn, ActorContext $ctx, array $access, int $assetId): array
    {
        $asset = self::asset($conn, $ctx, $assetId, false);
        $out = ['ready' => self::ready($conn), 'current' => null, 'history' => [],
            'can_change' => self::canChange($access), 'cmdb_access' => (bool)$access['cmdb_read']];
        if (!$out['ready']) { return $out; }
        $rows = self::rows($conn, 'asset_id = ?', [$asset['id']]);
        foreach ($rows as $r) {
            $entry = self::historyEntry($conn, $r, 'asset', $access);
            if ($r['unlinked_at'] === null) {
                $out['current'] = $access['cmdb_read'] ? array_merge(self::ciView($conn, (int)$r['cmdb_object_id']), self::meta($r))
                    : ['restricted' => true];
            }
            $out['history'][] = $entry;
        }
        return $out;
    }

    /**
     * The link as seen from the CI: the live asset with the card's details (or restricted,
     * or null), the history, and whether this reader may change it.
     */
    public static function forCi(PDO $conn, ActorContext $ctx, array $access, int $ciId): array
    {
        $ci = self::ci($conn, $ctx, $ciId, false);
        $out = ['ready' => self::ready($conn), 'current' => null, 'history' => [],
            'can_change' => self::canChange($access), 'assets_access' => (bool)$access['assets_read']];
        if (!$out['ready']) { return $out; }
        foreach (self::rows($conn, 'cmdb_object_id = ?', [$ci['id']]) as $r) {
            if ($r['unlinked_at'] === null) {
                $out['current'] = $access['assets_read'] ? array_merge(self::assetView($conn, $ctx, (int)$r['asset_id'], true), self::meta($r))
                    : ['restricted' => true];
            }
            $out['history'][] = self::historyEntry($conn, $r, 'ci', $access);
        }
        return $out;
    }

    /**
     * The REST block on GET /assets/{id} and GET /cmdb/objects/{id}: {id, name[, class]},
     * {restricted: true}, or null. Additive and read-only.
     *
     * @param string $side  'asset' (block names the CI) or 'ci' (block names the asset)
     */
    public static function restBlock(PDO $conn, array $access, string $side, int $id): ?array
    {
        if (!self::ready($conn)) { return null; }
        $r = self::liveRow($conn, $side === 'asset' ? $id : null, $side === 'ci' ? $id : null, false);
        if (!$r) { return null; }
        if ($side === 'asset') {
            if (!$access['cmdb_read']) { return ['restricted' => true]; }
            $v = self::ciView($conn, (int)$r['cmdb_object_id']);
            return ['id' => $v['id'], 'name' => $v['name'], 'class' => $v['class_name']];
        }
        if (!$access['assets_read']) { return ['restricted' => true]; }
        $st = $conn->prepare('SELECT id, hostname FROM assets WHERE id = ?');
        $st->execute([(int)$r['asset_id']]);
        $a = $st->fetch(PDO::FETCH_ASSOC);
        return $a ? ['id' => (int)$a['id'], 'name' => $a['hostname']] : null;
    }

    /**
     * Before a merge: the live link on each of the two assets, so the merge window can show
     * them and ask which one ends. 'choice_needed' is true only when both are linked (to
     * necessarily different CIs - a CI has one live link).
     */
    public static function mergePreview(PDO $conn, ActorContext $ctx, array $access, int $keepId, int $goneId): array
    {
        self::asset($conn, $ctx, $keepId, false);
        self::asset($conn, $ctx, $goneId, false);
        $out = ['keep' => null, 'gone' => null, 'choice_needed' => false, 'seen' => []];
        if (!self::ready($conn)) { return $out; }
        foreach (['keep' => $keepId, 'gone' => $goneId] as $k => $aid) {
            $r = self::liveRow($conn, $aid, null, false);
            if (!$r) { continue; }
            $out['seen'][] = (int)$r['id'];
            $out[$k] = $access['cmdb_read'] ? ['link_id' => (int)$r['id']] + self::ciView($conn, (int)$r['cmdb_object_id'])
                : ['link_id' => (int)$r['id'], 'restricted' => true];
        }
        sort($out['seen']);
        $out['choice_needed'] = $out['keep'] !== null && $out['gone'] !== null;
        return $out;
    }

    // ======================================================================
    //  Writing
    // ======================================================================

    /**
     * Link an asset and a CI.
     *
     * The same pair again does nothing (unchanged). A conflict - the asset linked to another
     * CI, or the CI linked to another asset - is refused unless $replace, and a replace must
     * name EXACTLY the live links it expects to end ($replaces): if they changed since the
     * person looked, it is refused as link_changed, so a newer link is never displaced silently.
     *
     * @param int[] $replaces  the live link ids the caller saw and means to end
     * @param string $source  'ui', 'api:<key name>' or 'integration:<name>'
     * @return array{link_id:int, unchanged:bool, replaced:int[]}
     */
    public static function link(PDO $conn, ActorContext $ctx, array $access, int $assetId, int $ciId,
        bool $replace = false, array $replaces = [], string $source = 'ui'): array
    {
        self::requireReady($conn);
        self::requireChange($access);
        $own = !$conn->inTransaction();
        if ($own) { $conn->beginTransaction(); }
        try {
            $asset = self::asset($conn, $ctx, $assetId, true);
            $ci = self::ci($conn, $ctx, $ciId, true);
            if (self::tenantOf($conn, $asset['tenant_id']) !== self::tenantOf($conn, $ci['tenant_id'])) {
                throw new ServiceError('validation', 'different_company', 'The asset and the configuration item belong to different companies.');
            }
            $onAsset = self::liveRow($conn, $assetId, null, true);
            if ($onAsset && (int)$onAsset['cmdb_object_id'] === $ciId) {
                if ($own) { $conn->commit(); }
                return ['link_id' => (int)$onAsset['id'], 'unchanged' => true, 'replaced' => []];
            }
            $onCi = self::liveRow($conn, null, $ciId, true);
            $conflicts = array_values(array_filter([$onAsset, $onCi]));
            if ($conflicts && !$replace) {
                if ($onCi) {
                    throw self::conflict('ci_already_linked', $access['assets_read']
                        ? 'This configuration item is already linked to ' . self::assetName($conn, (int)$onCi['asset_id']) . '.'
                        : 'This configuration item is already linked to an asset you cannot open.');
                }
                throw self::conflict('asset_already_linked', $access['cmdb_read']
                    ? 'This asset is already linked to ' . self::ciName($conn, (int)$onAsset['cmdb_object_id']) . '.'
                    : 'This asset is already linked to a configuration item you cannot open.');
            }
            $live = array_map(fn($r) => (int)$r['id'], $conflicts);
            sort($live);
            $expected = array_values(array_unique(array_map('intval', $replaces)));
            sort($expected);
            if ($replace && $live !== $expected) {
                throw self::conflict('link_changed', 'The link changed after you looked at it. Check it again before replacing.');
            }
            foreach ($conflicts as $r) {
                self::close($conn, $ctx, $r, 'replaced', $source);
            }
            $conn->prepare('INSERT INTO asset_ci_links (asset_id, original_asset_id, asset_name, cmdb_object_id, linked_at, linked_by_id, link_source)
                VALUES (?, ?, ?, ?, UTC_TIMESTAMP(), ?, ?)')
                ->execute([$assetId, $assetId, mb_substr((string)$asset['hostname'], 0, 255), $ciId, $ctx->actorId > 0 ? $ctx->actorId : null, mb_substr($source, 0, 80)]);
            $linkId = (int)$conn->lastInsertId();
            self::history($conn, $ctx, $assetId, $onAsset ? 'ci:' . (int)$onAsset['cmdb_object_id'] : null, 'ci:' . $ciId);
            if ($onCi) {
                // The other asset lost its link - its history says so too.
                self::history($conn, $ctx, (int)$onCi['asset_id'], 'ci:' . $ciId, null);
            }
            if ($own) { $conn->commit(); }
            return ['link_id' => $linkId, 'unchanged' => false, 'replaced' => $live];
        } catch (Throwable $e) {
            if ($own && $conn->inTransaction()) { $conn->rollBack(); }
            throw $e;
        }
    }

    /**
     * End the live link of an asset or a CI. $expectedLinkId, when given, must still be the
     * live link (the card the person saw); otherwise link_changed.
     *
     * @return array{link_id:int}
     */
    public static function unlink(PDO $conn, ActorContext $ctx, array $access, ?int $assetId, ?int $ciId,
        ?int $expectedLinkId = null, string $source = 'ui'): array
    {
        self::requireReady($conn);
        self::requireChange($access);
        $own = !$conn->inTransaction();
        if ($own) { $conn->beginTransaction(); }
        try {
            if ($assetId !== null) {
                self::asset($conn, $ctx, $assetId, true);
                $r = self::liveRow($conn, $assetId, null, true);
                if ($r) { self::ci($conn, $ctx, (int)$r['cmdb_object_id'], true); }
            } else {
                self::ci($conn, $ctx, (int)$ciId, false);
                $r = self::liveRow($conn, null, (int)$ciId, false);
                if ($r) {
                    // Lock in the usual order - asset, then CI - and read again under the lock.
                    self::asset($conn, $ctx, (int)$r['asset_id'], true);
                    self::ci($conn, $ctx, (int)$ciId, true);
                    $r = self::liveRow($conn, null, (int)$ciId, true);
                }
            }
            if (!$r) {
                if ($expectedLinkId) { throw self::conflict('link_changed', 'The link changed after you looked at it.'); }
                throw new ServiceError('not_found', 'not_linked', 'There is no link to end.');
            }
            if ($expectedLinkId !== null && (int)$r['id'] !== $expectedLinkId) {
                throw self::conflict('link_changed', 'The link changed after you looked at it.');
            }
            self::close($conn, $ctx, $r, 'unlinked', $source);
            self::history($conn, $ctx, (int)$r['asset_id'], 'ci:' . (int)$r['cmdb_object_id'], null);
            if ($own) { $conn->commit(); }
            return ['link_id' => (int)$r['id']];
        } catch (Throwable $e) {
            if ($own && $conn->inTransaction()) { $conn->rollBack(); }
            throw $e;
        }
    }

    /**
     * CIs are being deleted (CmdbService::deleteObject, INSIDE its transaction): end each
     * one's live link as ci_deleted, keeping its name and class on the row.
     *
     * @param int[] $ciIds  the CI and every descendant being deleted with it
     */
    public static function ciDeleted(PDO $conn, ActorContext $ctx, array $ciIds): int
    {
        if (!$ciIds || !self::ready($conn)) { return 0; }
        $ph = implode(',', array_fill(0, count($ciIds), '?'));
        $st = $conn->prepare("SELECT * FROM asset_ci_links WHERE unlinked_at IS NULL AND cmdb_object_id IN ($ph) FOR UPDATE");
        $st->execute(array_map('intval', $ciIds));
        $n = 0;
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            self::close($conn, $ctx, $r, 'ci_deleted', $ctx->source === 'api' ? 'api' : 'ui');
            self::history($conn, $ctx, (int)$r['asset_id'], 'ci:' . (int)$r['cmdb_object_id'], null);
            $n++;
        }
        return $n;
    }

    /**
     * A merge (AssetMergeService, INSIDE its transaction): the links move to the kept asset.
     *
     * When both assets are linked (to different CIs) the person must have chosen which link
     * survives ($keepLinkId) - the other ends as asset_merged and neither CI is touched. When
     * $seen is given it must equal the live links now, or the merge is refused as
     * ci_link_changed (the window showed something that is no longer true).
     *
     * Every row moves (asset_id), so the kept asset shows the whole history;
     * original_asset_id never moves, so a row still says which record it was made on.
     *
     * @param ?int[] $seen  the live link ids the merge window showed, or null when the caller showed none
     */
    public static function mergeLinks(PDO $conn, ActorContext $ctx, int $keepId, int $goneId, ?int $keepLinkId, ?array $seen): array
    {
        if (!self::ready($conn)) { return [0, 0]; }
        $keep = self::liveRow($conn, $keepId, null, true);
        $gone = self::liveRow($conn, $goneId, null, true);
        $live = array_values(array_map(fn($r) => (int)$r['id'], array_filter([$keep, $gone])));
        sort($live);
        if ($seen !== null) {
            $seen = array_values(array_map('intval', $seen));
            sort($seen);
            if ($seen !== $live) {
                throw self::conflict('ci_link_changed', 'The configuration item links changed after the merge window opened. Open it again.');
            }
        }
        $dropped = 0;
        if ($keep && $gone) {
            if (!in_array($keepLinkId, [(int)$keep['id'], (int)$gone['id']], true)) {
                throw self::conflict('ci_link_choice_required', 'Both assets are linked to a configuration item. Choose which link to keep.');
            }
            $end = $keepLinkId === (int)$keep['id'] ? $gone : $keep;
            self::close($conn, $ctx, $end, 'asset_merged', 'ui');
            self::history($conn, $ctx, $keepId, 'ci:' . (int)$end['cmdb_object_id'], null);
            $dropped = 1;
        }
        $st = $conn->prepare('UPDATE asset_ci_links SET asset_id = ? WHERE asset_id = ?');
        $st->execute([$keepId, $goneId]);
        return [$st->rowCount(), $dropped];
    }

    /**
     * The live links that linking this pair would end - on the asset and on the CI - as ids
     * only. A refused link sends these back so a confirmed Replace names exactly what it
     * showed; an id says nothing about the record behind it.
     *
     * @return int[]
     */
    public static function conflictIds(PDO $conn, int $assetId, int $ciId): array
    {
        if (!self::ready($conn)) { return []; }
        $ids = [];
        foreach ([self::liveRow($conn, $assetId, null, false), self::liveRow($conn, null, $ciId, false)] as $r) {
            if (!$r) { continue; }
            $samePair = (int)$r['asset_id'] === $assetId && (int)$r['cmdb_object_id'] === $ciId;
            if (!$samePair) { $ids[] = (int)$r['id']; }
        }
        $ids = array_values(array_unique($ids));
        sort($ids);
        return $ids;
    }

    /**
     * An asset_history value of a 'cmdb_link' row, as a reader may see it. Rows store
     * 'ci:<id>' so the history never holds a name; this gives the CI's name (live, or as it
     * was when its link ended) to a reader who may open the CMDB, and a generic phrase to
     * one who may not. $named false keeps the machine form for REST ('ci:88').
     */
    public static function historyValue(PDO $conn, ?string $value, bool $cmdbRead, bool $named = true, string $restricted = 'a restricted configuration item'): ?string
    {
        if ($value === null || !preg_match('/^ci:(\d+)$/', $value, $m)) { return $value; }
        if (!$cmdbRead) { return $named ? $restricted : 'restricted'; }
        if (!$named) { return $value; }
        return (string)(self::ciView($conn, (int)$m[1])['name'] ?? ('#' . $m[1]));
    }

    // ======================================================================
    //  Part C: creating the CI from the asset
    // ======================================================================

    /**
     * The active CMDB class an asset type is mapped to (Assets -> Settings -> Asset types),
     * or null. A mapping to a class since deactivated or deleted reads as none.
     */
    public static function mappedClass(PDO $conn, ?int $assetTypeId): ?array
    {
        if (!$assetTypeId) { return null; }
        try {
            $st = $conn->prepare('SELECT c.id, c.name FROM asset_types t JOIN cmdb_classes c ON c.id = t.cmdb_class_id AND c.is_active = 1 WHERE t.id = ?');
            $st->execute([$assetTypeId]);
            $r = $st->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException) {
            return null;   // before Database Verification adds asset_types.cmdb_class_id
        }
        return $r ? ['id' => (int)$r['id'], 'name' => $r['name']] : null;
    }

    /**
     * What Create linked CI needs before it can show its form: the type's class (null when
     * the type is not mapped), the properties that class requires, and the possible
     * matches - CIs of that class in the asset's company, not already linked, with the
     * asset's name ignoring case. A match is a suggestion, never proof, and never linked
     * automatically.
     */
    public static function createPreview(PDO $conn, ActorContext $ctx, array $access, int $assetId): array
    {
        require_once __DIR__ . '/cmdb.php';
        $asset = self::assetForCreate($conn, $ctx, $assetId, false);
        $class = self::mappedClass($conn, $asset['asset_type_id']);
        $out = ['class' => $class, 'name' => $asset['hostname'], 'required' => [], 'matches' => [],
            'linked' => self::ready($conn) && self::liveRow($conn, $assetId, null, false) !== null];
        if (!$class) { return $out; }
        $out['required'] = CmdbService::requiredProperties($conn, $class['id']);
        $out['matches'] = self::possibleMatches($conn, $asset, $class['id']);
        return $out;
    }

    /**
     * Create linked CI: the CI and its link in ONE transaction - a linked CI, or nothing.
     *
     * Retry-safe: an asset already linked by the time this lands creates nothing and
     * returns the CI it is linked to (created false). Possible matches are refused as
     * possible_matches unless $ignoreMatches - the person saw them and chose to create.
     * The CI's workflow event fires only after the commit.
     *
     * @param array $in  name (defaults to the asset's), properties {key: value} for the class's required ones
     * @return array{cmdb_object_id:int, link_id:int, created:bool}
     */
    public static function createLinkedCi(PDO $conn, ActorContext $ctx, array $access, int $assetId, array $in,
        bool $ignoreMatches = false, string $source = 'ui'): array
    {
        require_once __DIR__ . '/cmdb.php';
        self::requireReady($conn);
        self::requireChange($access);
        $own = !$conn->inTransaction();
        if ($own) { $conn->beginTransaction(); }
        try {
            $asset = self::assetForCreate($conn, $ctx, $assetId, true);
            $live = self::liveRow($conn, $assetId, null, true);
            if ($live) {
                if ($own) { $conn->commit(); }
                return ['cmdb_object_id' => (int)$live['cmdb_object_id'], 'link_id' => (int)$live['id'], 'created' => false];
            }
            $class = self::mappedClass($conn, $asset['asset_type_id']);
            if (!$class) {
                throw new ServiceError('validation', 'no_class_mapped', 'This asset\'s type is not mapped to a CMDB class (Assets -> Settings -> Asset types).');
            }
            if (!$ignoreMatches && self::possibleMatches($conn, $asset, $class['id'])) {
                throw self::conflict('possible_matches', 'A configuration item with this name already exists. Link it instead, or create a new one anyway.');
            }
            $name = trim((string)($in['name'] ?? '')) ?: trim((string)$asset['hostname']);
            $ciId = CmdbService::createInTransaction($conn, $ctx, [
                'name' => $name, 'class_id' => $class['id'],
                'tenant_id' => self::tenantOf($conn, $asset['tenant_id']),
                'properties' => is_array($in['properties'] ?? null) ? $in['properties'] : [],
            ]);
            $link = self::link($conn, $ctx, $access, $assetId, $ciId, false, [], $source);
            if ($own) { $conn->commit(); }
        } catch (Throwable $e) {
            if ($own && $conn->inTransaction()) { $conn->rollBack(); }
            throw $e;
        }
        if ($own) { CmdbService::announceCreated($conn, $ciId); }
        return ['cmdb_object_id' => $ciId, 'link_id' => (int)$link['link_id'], 'created' => true];
    }

    /** The asset with its type, for Part C (not found for another company's). */
    private static function assetForCreate(PDO $conn, ActorContext $ctx, int $id, bool $lock): array
    {
        $row = self::asset($conn, $ctx, $id, $lock);
        $st = $conn->prepare('SELECT asset_type_id FROM assets WHERE id = ?');
        $st->execute([$id]);
        $row['asset_type_id'] = ($t = $st->fetchColumn()) ? (int)$t : null;
        return $row;
    }

    /** Unlinked CIs of this class in the asset's company with the asset's name, ignoring case. */
    private static function possibleMatches(PDO $conn, array $asset, int $classId): array
    {
        $name = trim((string)$asset['hostname']);
        if ($name === '') { return []; }
        $tenant = self::tenantOf($conn, $asset['tenant_id']);
        $st = $conn->prepare('SELECT o.id, o.name FROM cmdb_objects o
            WHERE o.class_id = ? AND COALESCE(o.tenant_id, ?) = ? AND LOWER(o.name) = LOWER(?)
              AND NOT EXISTS (SELECT 1 FROM asset_ci_links l WHERE l.cmdb_object_id = o.id AND l.unlinked_at IS NULL)
            ORDER BY o.id LIMIT 10');
        $st->execute([$classId, getDefaultTenantId($conn), $tenant, $name]);
        return array_map(fn($r) => ['id' => (int)$r['id'], 'name' => $r['name']], $st->fetchAll(PDO::FETCH_ASSOC));
    }

    /** The ticked CARD_FIELDS. A missing or unreadable setting means all of them. */
    public static function cardFields(PDO $conn): array
    {
        try {
            $st = $conn->prepare('SELECT setting_value FROM system_settings WHERE setting_key = ?');
            $st->execute([self::CARD_SETTING]);
            $raw = $st->fetchColumn();
        } catch (Throwable) {
            $raw = false;
        }
        if ($raw === false || $raw === null || $raw === '') { return self::CARD_FIELDS; }
        $list = json_decode((string)$raw, true);
        return is_array($list) ? array_values(array_intersect(self::CARD_FIELDS, $list)) : self::CARD_FIELDS;
    }

    /** Store the ticked CARD_FIELDS (unknown keys are dropped). The caller has checked the capability. */
    public static function saveCardFields(PDO $conn, array $fields): array
    {
        $fields = array_values(array_intersect(self::CARD_FIELDS, array_map('strval', $fields)));
        $conn->prepare('INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)')
            ->execute([self::CARD_SETTING, json_encode($fields)]);
        return $fields;
    }

    // ======================================================================
    //  Internals
    // ======================================================================

    private static function canChange(array $access): bool
    {
        return !empty($access['assets_write']) && !empty($access['cmdb_write']);
    }

    private static function requireChange(array $access): void
    {
        if (!self::canChange($access)) {
            throw new ServiceError('forbidden', 'read_only', 'Linking needs permission to change both Assets and the CMDB.');
        }
    }

    private static function requireReady(PDO $conn): void
    {
        if (!self::ready($conn)) {
            throw new ServiceError('validation', 'not_ready', 'Run Database Verification first (System -> Database Verification).');
        }
    }

    private static function conflict(string $code, string $message): ServiceError
    {
        return new ServiceError('conflict', $code, $message);
    }

    /** NULL tenant_id means the Default company's. */
    private static function tenantOf(PDO $conn, $tenantId): int
    {
        return $tenantId === null ? (int)getDefaultTenantId($conn) : (int)$tenantId;
    }

    /** The asset, or not found (also for another company's). */
    private static function asset(PDO $conn, ActorContext $ctx, int $id, bool $lock): array
    {
        $st = $conn->prepare('SELECT id, hostname, tenant_id FROM assets WHERE id = ?' . ($lock ? ' FOR UPDATE' : ''));
        $st->execute([$id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if (!$row || ($ctx->companyScope !== null && !in_array(self::tenantOf($conn, $row['tenant_id']), $ctx->companyScope, true))) {
            throw new ServiceError('not_found', 'not_found', 'Asset not found.');
        }
        $row['id'] = (int)$row['id'];
        return $row;
    }

    /** The CI, or not found (also for another company's). */
    private static function ci(PDO $conn, ActorContext $ctx, int $id, bool $lock): array
    {
        $st = $conn->prepare('SELECT id, name, tenant_id FROM cmdb_objects WHERE id = ?' . ($lock ? ' FOR UPDATE' : ''));
        $st->execute([$id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if (!$row || ($ctx->companyScope !== null && !in_array(self::tenantOf($conn, $row['tenant_id']), $ctx->companyScope, true))) {
            throw new ServiceError('not_found', 'not_found', 'Configuration item not found.');
        }
        $row['id'] = (int)$row['id'];
        return $row;
    }

    /** The live link of an asset or a CI, or null. */
    private static function liveRow(PDO $conn, ?int $assetId, ?int $ciId, bool $lock): ?array
    {
        [$col, $val] = $assetId !== null ? ['asset_id', $assetId] : ['cmdb_object_id', (int)$ciId];
        $st = $conn->prepare("SELECT * FROM asset_ci_links WHERE $col = ? AND unlinked_at IS NULL ORDER BY id DESC LIMIT 1" . ($lock ? ' FOR UPDATE' : ''));
        $st->execute([$val]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        return $r ?: null;
    }

    /** Every period for one asset or CI, newest first, with the analysts' names. */
    private static function rows(PDO $conn, string $where, array $args): array
    {
        $st = $conn->prepare("SELECT l.*, lb.full_name AS linked_by_name, ub.full_name AS unlinked_by_name
            FROM asset_ci_links l
            LEFT JOIN analysts lb ON lb.id = l.linked_by_id
            LEFT JOIN analysts ub ON ub.id = l.unlinked_by_id
            WHERE l.$where ORDER BY l.linked_at DESC, l.id DESC");
        $st->execute($args);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** End one period: who, how, why, and the CI's name and class as they were. */
    private static function close(PDO $conn, ActorContext $ctx, array $row, string $reason, string $source): void
    {
        $ci = self::ciView($conn, (int)$row['cmdb_object_id']);
        $conn->prepare('UPDATE asset_ci_links SET unlinked_at = UTC_TIMESTAMP(), unlinked_by_id = ?, unlink_source = ?, unlink_reason = ?,
                ci_name = ?, ci_class_name = ? WHERE id = ? AND unlinked_at IS NULL')
            ->execute([$ctx->actorId > 0 ? $ctx->actorId : null, mb_substr($source, 0, 80), $reason,
                $ci['name'] !== null ? mb_substr($ci['name'], 0, 255) : ($row['ci_name'] ?? null),
                $ci['class_name'] !== null ? mb_substr($ci['class_name'], 0, 150) : ($row['ci_class_name'] ?? null), (int)$row['id']]);
    }

    /**
     * A line in the asset's own history. Values are 'ci:<id>', never a name:
     * get_asset_history.php resolves them for readers who may open the CMDB, so the
     * history cannot leak a CI's name to someone who cannot.
     */
    private static function history(PDO $conn, ActorContext $ctx, int $assetId, ?string $old, ?string $new): void
    {
        $conn->prepare("INSERT INTO asset_history (asset_id, analyst_id, field_name, old_value, new_value, created_datetime)
            VALUES (?, ?, 'cmdb_link', ?, ?, UTC_TIMESTAMP())")
            ->execute([$assetId, $ctx->actorId > 0 ? $ctx->actorId : null, $old, $new]);
    }

    /** The CI's id, name, class and icon - from the live record, or the row's copy once it is gone. */
    private static function ciView(PDO $conn, int $ciId): array
    {
        $st = $conn->prepare('SELECT o.id, o.name, c.name AS class_name, i.icon_key
            FROM cmdb_objects o JOIN cmdb_classes c ON c.id = o.class_id LEFT JOIN cmdb_icons i ON i.id = c.icon_id WHERE o.id = ?');
        $st->execute([$ciId]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if ($r) {
            return ['id' => (int)$r['id'], 'name' => $r['name'], 'class_name' => $r['class_name'], 'icon_key' => $r['icon_key'], 'exists' => true];
        }
        $snap = $conn->prepare('SELECT ci_name, ci_class_name FROM asset_ci_links WHERE cmdb_object_id = ? AND ci_name IS NOT NULL ORDER BY id DESC LIMIT 1');
        $snap->execute([$ciId]);
        $s = $snap->fetch(PDO::FETCH_ASSOC) ?: [];
        return ['id' => $ciId, 'name' => $s['ci_name'] ?? null, 'class_name' => $s['ci_class_name'] ?? null, 'icon_key' => null, 'exists' => false];
    }

    /**
     * The asset as the CI's card shows it - name always, the rest only for the ticked
     * CARD_FIELDS. The caller has already decided the reader may open the asset.
     *
     * @param bool $details  false for just id and name (history, conflicts)
     */
    private static function assetView(PDO $conn, ActorContext $ctx, int $assetId, bool $details): array
    {
        $st = $conn->prepare('SELECT a.id, a.hostname, a.manufacturer, a.model, a.service_tag, a.asset_tag, a.last_seen,
                t.name AS type_name, s.name AS status_name, l.name AS location_name
            FROM assets a LEFT JOIN asset_types t ON t.id = a.asset_type_id LEFT JOIN asset_status_types s ON s.id = a.asset_status_id
            LEFT JOIN asset_locations l ON l.id = a.location_id WHERE a.id = ?');
        $st->execute([$assetId]);
        $a = $st->fetch(PDO::FETCH_ASSOC);
        if (!$a) { return ['id' => $assetId, 'name' => null, 'exists' => false]; }
        $out = ['id' => (int)$a['id'], 'name' => $a['hostname'], 'exists' => true];
        if (!$details) { return $out; }
        $fields = self::cardFields($conn);
        $d = [];
        if (in_array('type', $fields, true))      { $d['type'] = $a['type_name']; }
        if (in_array('make_model', $fields, true)) { $d['manufacturer'] = $a['manufacturer']; $d['model'] = $a['model']; }
        if (in_array('serial', $fields, true))    { $d['serial'] = $a['service_tag']; }
        if (in_array('asset_tag', $fields, true)) { $d['asset_tag'] = $a['asset_tag']; }
        if (in_array('status', $fields, true))    { $d['status'] = $a['status_name']; }
        if (in_array('location', $fields, true))  { $d['location'] = $a['location_name']; }
        if (in_array('freshness', $fields, true)) { $d['freshness'] = self::freshness($conn, $ctx, (int)$a['id'], $a['last_seen']); }
        $out['details'] = $d;
        $out['fields'] = $fields;
        return $out;
    }

    /**
     * Last seen, and the worst state across the asset's inventory sources (current, stale,
     * failed), or 'none' when nothing reports it - so a CI whose box went quiet shows it.
     */
    private static function freshness(PDO $conn, ActorContext $ctx, int $assetId, ?string $lastSeen): array
    {
        require_once __DIR__ . '/asset_inventory.php';
        $worst = 'none';
        try {
            $staleBefore = time() - AssetInventoryService::staleDays($conn) * 86400;
            $rank = ['none' => 0, 'current' => 1, 'stale' => 2, 'failed' => 3];
            foreach (AssetInventoryService::provenance($conn, $ctx, $assetId) as $src) {
                foreach ((array)$src['sections'] as $s) {
                    $state = ($s['last_outcome'] ?? '') === 'failed' ? 'failed'
                        : (!empty($s['observed_at']) && strtotime($s['observed_at']) < $staleBefore ? 'stale' : 'current');
                    if ($rank[$state] > $rank[$worst]) { $worst = $state; }
                }
            }
        } catch (Throwable) {
            $worst = 'none';   // before Database Verification: no sources to judge
        }
        return ['last_seen' => $lastSeen, 'state' => $worst];
    }

    /** Who, when and how - for the live link's hover and the history. */
    private static function meta(array $r): array
    {
        return ['link_id' => (int)$r['id'], 'linked_at' => $r['linked_at'], 'linked_by' => $r['linked_by_name'], 'link_source' => $r['link_source']];
    }

    /**
     * One history row, from one side. The other end is named only if the reader may open
     * it; a row made on a since-merged asset says so (original_asset_id).
     *
     * @param string $side  'asset' (the other end is a CI) or 'ci' (the other end is an asset)
     */
    private static function historyEntry(PDO $conn, array $r, string $side, array $access): array
    {
        $e = [
            'link_id'        => (int)$r['id'],
            'linked_at'      => $r['linked_at'],
            'linked_by'      => $r['linked_by_name'],
            'link_source'    => $r['link_source'],
            'unlinked_at'    => $r['unlinked_at'],
            'unlinked_by'    => $r['unlinked_by_name'],
            'unlink_source'  => $r['unlink_source'],
            'unlink_reason'  => $r['unlink_reason'],
        ];
        if ($side === 'asset') {
            $e['other'] = $access['cmdb_read']
                ? ($r['unlinked_at'] === null || $r['ci_name'] === null
                    ? array_intersect_key(self::ciView($conn, (int)$r['cmdb_object_id']), ['id' => 1, 'name' => 1, 'class_name' => 1, 'exists' => 1])
                    : ['id' => (int)$r['cmdb_object_id'], 'name' => $r['ci_name'], 'class_name' => $r['ci_class_name'], 'exists' => false])
                : ['restricted' => true];
            if ((int)$r['original_asset_id'] !== (int)$r['asset_id']) {
                $e['made_on'] = ['id' => (int)$r['original_asset_id'], 'name' => $r['asset_name']];
            }
        } else {
            $e['other'] = $access['assets_read']
                ? ['id' => (int)$r['asset_id'], 'name' => self::assetName($conn, (int)$r['asset_id']), 'made_on_name' => $r['asset_name'],
                    'merged' => (int)$r['original_asset_id'] !== (int)$r['asset_id']]
                : ['restricted' => true];
        }
        return $e;
    }

    private static function assetName(PDO $conn, int $id): string
    {
        $st = $conn->prepare('SELECT hostname FROM assets WHERE id = ?');
        $st->execute([$id]);
        return (string)($st->fetchColumn() ?: '#' . $id);
    }

    private static function ciName(PDO $conn, int $id): string
    {
        return (string)(self::ciView($conn, $id)['name'] ?? '#' . $id);
    }
}
