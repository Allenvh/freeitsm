<?php
/**
 * API: the shape of the directory, for the OU browser.
 *
 * POST { provider_id, ...live form values... }
 *
 * Returns every organisational unit under the provider's base DN, as a flat
 * list of { dn, name, parent, people, total }, plus the root itself.
 *
 * `people` is how many entries sit DIRECTLY in that OU and `total` is the whole
 * branch. Both matter: ticking Staff imports 24 people, and knowing that before
 * you run anything is the difference between choosing a scope and guessing at
 * one. A tree of names alone tells you the directory's shape but nothing about
 * what ticking a box would actually do.
 *
 * The counts come from ONE paged search for people-DNs, rolled up in PHP —
 * not a search per OU, which on a directory of any size would be dozens of
 * round trips to render one screen.
 *
 * Reads only. Administrators only.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/encryption.php';
require_once '../../includes/directory_sync.php';

header('Content-Type: application/json');

/** Above this we stop counting rather than enumerate an entire enterprise to draw a tree. */
const OU_BROWSE_MAX_PEOPLE = 20000;
const OU_BROWSE_MAX_OUS    = 3000;

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();
    if (!analystIsAdmin($conn, (int)$_SESSION['analyst_id'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Administrator access required']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $pid  = (int)($data['provider_id'] ?? 0);

    $s = $conn->prepare("SELECT * FROM auth_providers WHERE id = ? AND protocol = 'ldap'");
    $s->execute([$pid]);
    $provider = $s->fetch(PDO::FETCH_ASSOC);
    if (!$provider) {
        echo json_encode(['success' => false, 'error' => 'No such directory provider']);
        exit;
    }
    $provider['ldap_bind_password'] = decryptValue($provider['ldap_bind_password'] ?? '');

    // Browse against what is ON THE FORM. Somebody correcting a base DN wants to
    // see the tree that correction produces, not the one the old value produced.
    foreach (['ldap_base_dn', 'sync_filter', 'ldap_host', 'ldap_port', 'ldap_encryption', 'ldap_bind_dn'] as $k) {
        if (array_key_exists($k, $data) && trim((string)$data[$k]) !== '') {
            $provider[$k] = trim((string)$data[$k]);
        }
    }

    // Lower-cased for comparison, because every "is X under Y" test in here is
    // a string comparison — but kept as typed for display, or the top of the
    // tree reads "northwind" when the OU is called "Northwind".
    $rootAsTyped = trim((string)($provider['ldap_base_dn'] ?? ''));
    $root = strtolower($rootAsTyped);
    if ($root === '') {
        echo json_encode(['success' => false, 'error' => 'Set the base DN on the Signing in tab first — that is where browsing starts from.']);
        exit;
    }

    $ds = ldapOpen($provider);
    ldapBindService($ds, $provider);

    // ---- the containers -----------------------------------------------------
    // Both objectClass=organizationalUnit AND container: plenty of small Active
    // Directory installs never make an OU at all and leave everybody in the
    // built-in CN=Users, which is a container. Showing only OUs would present
    // those sites with an empty tree and no way to pick anybody.
    $ouFilter = '(|(objectClass=organizationalUnit)(objectClass=container))';

    // The base DN itself is a tickable place, and on a directory whose whole
    // structure hangs off one OU it is the ONLY parent there is — so without it
    // "tick the branch, carve one child out of it" cannot be expressed at all.
    $ous = [$root => [
        'dn'     => $root,
        'name'   => ouBrowseRdnValue($rootAsTyped),
        'parent' => '',
        'people' => 0,
        'total'  => 0,
    ]];
    $cookie = '';
    do {
        $controls = [['oid' => LDAP_CONTROL_PAGEDRESULTS, 'value' => ['size' => 500, 'cookie' => $cookie]]];
        $res = @ldap_search($ds, $root, $ouFilter, ['ou', 'cn', 'description'], 0, 0, 0, LDAP_DEREF_NEVER, $controls);
        if ($res === false) {
            echo json_encode(['success' => false, 'error' => 'Could not read the directory structure: ' . ldap_error($ds)]);
            exit;
        }
        $entries = @ldap_get_entries($ds, $res) ?: ['count' => 0];
        for ($i = 0; $i < ($entries['count'] ?? 0); $i++) {
            $e  = $entries[$i];
            $dn = strtolower((string)($e['dn'] ?? ''));
            if ($dn === '' || $dn === $root) continue;
            if (ouBrowseIsPlumbing($dn, $root)) continue;
            $ous[$dn] = [
                'dn'     => $dn,
                'name'   => (string)($e['ou'][0] ?? $e['cn'][0] ?? $dn),
                'parent' => ouBrowseParent($dn),
                'people' => 0,
                'total'  => 0,
            ];
            if (count($ous) >= OU_BROWSE_MAX_OUS) break 2;
        }
        $cookie = '';
        if (@ldap_parse_result($ds, $res, $ec, $md, $em, $ref, $ctrls)) {
            $cookie = $ctrls[LDAP_CONTROL_PAGEDRESULTS]['value']['cookie'] ?? '';
        }
    } while ($cookie !== null && $cookie !== '');

    // ---- the head count -----------------------------------------------------
    // The SAME filter the import uses, so the number on a branch is the number
    // that branch would actually import. A different filter here would produce
    // a tree that promises 24 and delivers 19, which is worse than no number.
    $peopleFilter = trim((string)($provider['sync_filter'] ?? '')) ?: '(&(objectClass=user)(objectCategory=person))';
    $counted  = 0;
    $capped   = false;
    $cookie   = '';
    do {
        $controls = [['oid' => LDAP_CONTROL_PAGEDRESULTS, 'value' => ['size' => 500, 'cookie' => $cookie]]];
        // Ask for one cheap attribute: we only need each person's position in
        // the tree, and 'dn' comes back regardless of what is requested.
        $res = @ldap_search($ds, $root, $peopleFilter, ['cn'], 0, 0, 0, LDAP_DEREF_NEVER, $controls);
        if ($res === false) break;   // a tree with no counts still beats no tree
        $entries = @ldap_get_entries($ds, $res) ?: ['count' => 0];
        for ($i = 0; $i < ($entries['count'] ?? 0); $i++) {
            $dn = strtolower((string)($entries[$i]['dn'] ?? ''));
            if ($dn === '') continue;
            $parent = ouBrowseParent($dn);
            if (isset($ous[$parent])) $ous[$parent]['people']++;
            // Roll the person up every ancestor, so a branch total includes
            // people sitting in OUs nested any number of levels down.
            // Includes the root itself, which is a node like any other now —
            // stopping AT the root would leave the top of the tree reading 0
            // while every branch under it showed a number.
            $walk = $parent;
            while ($walk !== '') {
                if (isset($ous[$walk])) $ous[$walk]['total']++;
                if ($walk === $root) break;
                $walk = ouBrowseParent($walk);
            }
            if (++$counted >= OU_BROWSE_MAX_PEOPLE) { $capped = true; break 2; }
        }
        $cookie = '';
        if (@ldap_parse_result($ds, $res, $ec, $md, $em, $ref, $ctrls)) {
            $cookie = $ctrls[LDAP_CONTROL_PAGEDRESULTS]['value']['cookie'] ?? '';
        }
    } while ($cookie !== null && $cookie !== '');

    // Sorted by DN depth then name, so the browser can build the tree in one
    // pass knowing a parent always arrives before its children.
    $list = array_values($ous);
    usort($list, function ($a, $b) {
        // Shallowest first. A DN's depth is its comma count, so fewer commas is
        // nearer the root — $da <=> $db, NOT the reverse, or every child is
        // emitted before the parent it needs to attach to.
        $da = substr_count($a['dn'], ','); $db = substr_count($b['dn'], ',');
        return $da === $db ? strcasecmp($a['name'], $b['name']) : $da <=> $db;
    });

    $scopes = dsyncScopes($provider);

    echo json_encode([
        'success'  => true,
        'root'     => ['dn' => $root, 'total' => $counted],
        'ous'      => $list,
        'includes' => $scopes['includes'],
        'excludes' => $scopes['excludes'],
        'counted'  => $counted,
        'capped'   => $capped,
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

/**
 * The DN one level up. '' once there is nothing left.
 *
 * @param string $dn  an LDAP distinguished name, e.g. OU=Staff,DC=example,DC=com
 */
function ouBrowseParent(string $dn): string
{
    // Split on the first unescaped comma. A DN component may legitimately
    // contain '\,' — splitting naively would cut a name in half and orphan
    // everything beneath it.
    $parts = preg_split('/(?<!\\\\),/', $dn, 2);
    return isset($parts[1]) ? trim($parts[1]) : '';
}

/**
 * The readable half of a DN's first component: "OU=Staff,…" → "Staff".
 *
 * @param string $dn  an LDAP distinguished name, e.g. OU=Staff,DC=example,DC=com
 */
function ouBrowseRdnValue(string $dn): string
{
    $first = preg_split('/(?<!\\\\),/', $dn, 2)[0];
    $eq    = strpos($first, '=');
    return $eq === false ? $first : trim(substr($first, $eq + 1));
}

/**
 * Active Directory's own furniture, which nobody is importing people from.
 *
 * Only containers directly under the root are judged: an OU somebody has
 * genuinely called "System" deeper in their own tree is theirs, not ours to
 * hide. Being wrong here hides part of somebody's directory, so the list is
 * deliberately short and only covers what AD creates itself.
 *
 * @param string $dn  the distinguished name of a container found by the search
 * @param string $root  the base DN being browsed; only containers directly under it are judged
 */
function ouBrowseIsPlumbing(string $dn, string $root): bool
{
    static $names = [
        'cn=system', 'cn=program data', 'cn=foreignsecurityprincipals',
        'cn=managed service accounts', 'cn=ntds quotas', 'cn=infrastructure',
        'cn=lostandfound', 'cn=builtin', 'cn=keys', 'cn=tpm devices',
        'cn=microsoft exchange system objects',
    ];
    if (ouBrowseParent($dn) !== $root) return false;
    $first = strtolower(trim(preg_split('/(?<!\\\\),/', $dn, 2)[0]));
    return in_array($first, $names, true);
}
