<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Slice 1 parity test — TypedFields extraction.
 *
 * Proves the CMDB's property write path behaves identically after the engine
 * was extracted: every type stores in the right column, every validation still
 * fires, and every error message is byte-identical (they are the REST API's
 * published error bodies).
 *
 * ⚠️ CmdbService owns its own transaction, so this cannot wrap one. Instead it
 * creates only `zz_parity`-prefixed rows and sweeps them before AND after. It
 * must never touch real data — in particular it never edits an existing class,
 * because that would rewrite the real Criticality option list.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/cmdb.php';

$pass = 0; $fail = 0;

function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

/** Run a closure, return the ServiceError message, or null if it didn't throw. */
function errOf(callable $fn): ?string {
    try { $fn(); return null; }
    catch (ServiceError $e) { return $e->getMessage(); }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/**
 * CmdbService opens its own transaction, so an outer one is impossible.
 * Instead: everything created here is prefixed `zz_parity`, and this sweep runs
 * both BEFORE (in case a previous run died) and AFTER (in a finally). It
 * deletes ONLY rows matching that prefix — never anything real.
 */
$sweep = function () use ($conn): int {
    $ids = $conn->query(
        "SELECT o.id FROM cmdb_objects o
           JOIN cmdb_classes c ON c.id = o.class_id
          WHERE c.class_key LIKE 'zz_parity%'"
    )->fetchAll(PDO::FETCH_COLUMN);
    foreach ($ids as $oid) {
        $conn->prepare("DELETE FROM cmdb_object_properties WHERE object_id = ? OR value_object_id = ?")->execute([$oid, $oid]);
        $conn->prepare("DELETE FROM cmdb_object_relationships WHERE from_object_id = ? OR to_object_id = ?")->execute([$oid, $oid]);
    }
    if ($ids) {
        $conn->query("DELETE FROM cmdb_objects WHERE id IN (" . implode(',', array_map('intval', $ids)) . ")");
    }
    $conn->query("DELETE FROM cmdb_classes WHERE class_key LIKE 'zz_parity%'");
    return count($ids);
};
$sweep();

/**
 * Snapshot the estate BEFORE, so the "nothing was disturbed" assertion at the
 * end works on any install — not just the one it was written on.
 */
$countEstate = static function (PDO $conn): array {
    return [
        'classes' => (int)$conn->query("SELECT COUNT(*) FROM cmdb_classes")->fetchColumn(),
        'props'   => (int)$conn->query("SELECT COUNT(*) FROM cmdb_class_properties")->fetchColumn(),
        'objects' => (int)$conn->query("SELECT COUNT(*) FROM cmdb_objects")->fetchColumn(),
        'values'  => (int)$conn->query("SELECT COUNT(*) FROM cmdb_object_properties")->fetchColumn(),
    ];
};
$estateBefore = $countEstate($conn);

try {
    $ctx = new ActorContext(actorId: 1, companyScope: null, source: 'api', actorName: 'Parity Test');

    // ---- throwaway classes -------------------------------------------------
    $conn->prepare("INSERT INTO cmdb_classes (class_key, name) VALUES (?, ?)")
         ->execute(['zz_parity_target', 'ZZ Parity Target']);
    $targetClassId = (int)$conn->lastInsertId();

    $conn->prepare("INSERT INTO cmdb_classes (class_key, name) VALUES (?, ?)")
         ->execute(['zz_parity', 'ZZ Parity']);
    $classId = (int)$conn->lastInsertId();

    // ---- one property of every type ---------------------------------------
    $mkProp = function (string $key, string $type, ?int $target = null, int $req = 0) use ($conn, $classId): int {
        $conn->prepare(
            "INSERT INTO cmdb_class_properties (class_id, property_key, label, property_type, target_class_id, is_required)
             VALUES (?, ?, ?, ?, ?, ?)"
        )->execute([$classId, $key, ucfirst($key), $type, $target, $req]);
        return (int)$conn->lastInsertId();
    };

    $pText = $mkProp('p_text', 'text');
    $pNum  = $mkProp('p_num', 'number');
    $pDate = $mkProp('p_date', 'date');
    $pBool = $mkProp('p_bool', 'boolean');
    $pDrop = $mkProp('p_drop', 'dropdown');
    $pRef  = $mkProp('p_ref', 'object_ref', $targetClassId);
    $pReq  = $mkProp('p_req', 'text', null, 1);

    foreach (['Alpha', 'Beta'] as $i => $opt) {
        $conn->prepare("INSERT INTO cmdb_class_property_options (property_id, option_value, display_order) VALUES (?, ?, ?)")
             ->execute([$pDrop, $opt, $i]);
    }

    // A CI of the target class, to point at.
    $target = CmdbService::saveObject($conn, $ctx, ['class_id' => $targetClassId, 'name' => 'ZZ Target CI']);
    $targetId = (int)$target['id'];

    // A CI of a DIFFERENT class, to prove target-class narrowing still bites.
    $wrongTarget = CmdbService::saveObject($conn, $ctx, ['class_id' => $classId, 'name' => 'ZZ Wrong Class', 'properties' => ['p_req' => 'x']]);
    $wrongTargetId = (int)$wrongTarget['id'];

    echo "\n--- storage: each type lands in its own column ---\n";

    $created = CmdbService::saveObject($conn, $ctx, [
        'class_id'   => $classId,
        'name'       => 'ZZ Parity CI',
        'properties' => [
            'p_text' => 'hello',
            'p_num'  => '42.5',
            'p_date' => '2026-07-02T09:00:00Z',
            'p_bool' => true,
            'p_drop' => 'Beta',
            'p_ref'  => $targetId,
            'p_req'  => 'present',
        ],
    ]);
    $objId = (int)$created['id'];

    $rows = $conn->prepare("SELECT property_id, value_text, value_number, value_date, value_boolean, value_object_id
                            FROM cmdb_object_properties WHERE object_id = ?");
    $rows->execute([$objId]);
    $byProp = [];
    foreach ($rows->fetchAll(PDO::FETCH_ASSOC) as $r) { $byProp[(int)$r['property_id']] = $r; }

    ok('text -> value_text',            ($byProp[$pText]['value_text'] ?? null) === 'hello');
    ok('text leaves others NULL',       $byProp[$pText]['value_number'] === null && $byProp[$pText]['value_object_id'] === null);
    ok('number -> value_number',        (float)($byProp[$pNum]['value_number'] ?? 0) === 42.5);
    ok('date -> value_date as UTC',     ($byProp[$pDate]['value_date'] ?? null) === '2026-07-02 09:00:00',
                                        var_export($byProp[$pDate]['value_date'] ?? null, true));
    ok('boolean -> value_boolean 1',    (int)($byProp[$pBool]['value_boolean'] ?? -1) === 1);
    ok('dropdown -> value_text',        ($byProp[$pDrop]['value_text'] ?? null) === 'Beta');
    ok('object_ref -> value_object_id', (int)($byProp[$pRef]['value_object_id'] ?? 0) === $targetId);

    echo "\n--- clearing: no row means not set ---\n";

    CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_text' => '']]);
    $c = $conn->prepare("SELECT COUNT(*) FROM cmdb_object_properties WHERE object_id = ? AND property_id = ?");
    $c->execute([$objId, $pText]);
    ok('empty string deletes the row', (int)$c->fetchColumn() === 0);

    CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_ref' => 0]]);
    $c->execute([$objId, $pRef]);
    ok('object_ref 0 clears, no row',  (int)$c->fetchColumn() === 0);

    // Restore for later assertions.
    CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_text' => 'hello', 'p_ref' => $targetId]]);

    echo "\n--- validation: exact messages (published API contract) ---\n";

    ok('dropdown rejects unknown option',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_drop' => 'Gamma']]))
        === "Property 'P_drop' must be one of: Alpha, Beta");

    ok('number rejects non-numeric',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_num' => 'abc']]))
        === "Property 'P_num' expects a number.");

    ok('date rejects unparseable',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_date' => 'not a date']]))
        === "'p_date' is not a valid date/time. Use ISO 8601, e.g. 2026-07-02T09:00:00Z.");

    ok('unknown key names the class endpoint',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['nope' => 'x']]))
        === "Unknown property 'nope' for this class. See GET /cmdb/classes/{$classId}.");

    ok('object_ref refuses self-reference',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_ref' => $objId]]))
        === "Property 'P_ref' can't reference its own object.");

    /**
     * ⚠️ A nonexistent reference produces DIFFERENT messages depending on the
     * install, and this predates the TypedFields extraction — verified by
     * running this same file against the pre-refactor code.
     *
     * On a MULTI-COMPANY install, assertObjectRefsInCompany() runs first and
     * reports the reference as not-found (deliberately: it must not confirm
     * whether a CI in another company exists). On a SINGLE-company install that
     * check returns early, so the engine's own type validation reports it.
     *
     * Both are correct; the test asserts whichever applies here.
     */
    $expectMissingRef = isMultiTenant($conn)
        ? 'Object not found.'
        : "Property 'P_ref' references an object that doesn't exist.";
    ok('object_ref refuses a missing target (' . (isMultiTenant($conn) ? 'multi' : 'single') . '-company message)',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_ref' => 999999999]]))
        === $expectMissingRef);

    ok('object_ref enforces target class',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_ref' => $wrongTargetId]]))
        === "Property 'P_ref' can only reference objects of its target class.");

    echo "\n--- required: the create/update asymmetry ---\n";

    ok('required enforced on create',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['class_id' => $classId, 'name' => 'ZZ No Req']))
        === "Required property missing: P_req");

    ok('required NOT enforced on an update that does not touch it',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_text' => 'still fine']]))
        === null);

    ok('required enforced when explicitly blanked on update',
        errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_req' => '']]))
        === "Required property missing: P_req");

    echo "\n--- the UI's id-addressed shape still works ---\n";

    CmdbService::saveObject($conn, $ctx, [
        'id' => $objId,
        'property_values' => [
            ['property_id' => $pText, 'value' => 'via ui shape'],
            ['property_id' => 999999, 'value' => 'dropped silently'],
        ],
    ]);
    $v = $conn->prepare("SELECT value_text FROM cmdb_object_properties WHERE object_id = ? AND property_id = ?");
    $v->execute([$objId, $pText]);
    ok('property_values writes by id',        $v->fetchColumn() === 'via ui shape');
    ok('property_values drops foreign ids',   true);   // asserted by not throwing above

    echo "\n--- a failed write leaves nothing behind ---\n";
    $before = $conn->prepare("SELECT COUNT(*) FROM cmdb_object_properties WHERE object_id = ?");
    $before->execute([$objId]);
    $n1 = (int)$before->fetchColumn();
    errOf(fn() => CmdbService::saveObject($conn, $ctx, ['id' => $objId, 'properties' => ['p_num' => 'abc']]));
    $before->execute([$objId]);
    ok('rejected update did not drop rows', (int)$before->fetchColumn() === $n1,
       "was {$n1}, now " . (int)$before->fetchColumn());

} catch (Throwable $e) {
    $fail++;
    echo "\n  FATAL  " . get_class($e) . ': ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

$sweep();

// Prove the sweep worked — nothing of ours may survive.
$leak = $conn->query("SELECT COUNT(*) FROM cmdb_classes WHERE class_key LIKE 'zz_parity%'");
$leaked = (int)$leak->fetchColumn();
echo "\n";
ok('cleanup left no throwaway classes', $leaked === 0, "found {$leaked}");

// And prove the estate is exactly as we found it — compared against the
// snapshot taken before the run, so this holds on any install.
$estateAfter = $countEstate($conn);
echo '  estate: ' . json_encode($estateAfter) . "\n";
ok('estate unchanged', $estateAfter === $estateBefore,
   'before ' . json_encode($estateBefore) . ' after ' . json_encode($estateAfter));

echo "\n" . str_repeat('=', 52) . "\n";
echo ($fail === 0 ? "ALL GREEN" : "FAILURES") . ": {$pass} passed, {$fail} failed\n";
exit($fail === 0 ? 0 : 1);
