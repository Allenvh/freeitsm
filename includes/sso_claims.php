<?php
/**
 * SSO groups (3.5.0, proposed in PR #174) - the OIDC twin of LDAP's analyst
 * group, plus team membership that follows the identity provider.
 *
 * An OIDC sign-in carries CLAIMS about the person, and one is usually their
 * groups or roles. An OIDC provider (System -> SSO -> Groups & teams) can use it:
 *
 *   oidc_group_claim    which claim holds the groups: groups, roles,
 *                       realm_access.roles (Keycloak) or another the admin names
 *   oidc_analyst_group  only members sign in as - and are created as - analysts.
 *                       LDAP's ldap_analyst_group rule: someone who has left it
 *                       is refused; their account is left as it is
 *   oidc_admin_group    members are administrators, non-members are not
 *                       (the last active administrator is never demoted)
 *   sso_group_teams     group -> team rows, picked from dropdowns. At every
 *                       analyst sign-in, membership of each MAPPED team follows
 *                       the groups; a team with no mapping is never touched.
 *                       The team decides modules, companies and roles, exactly
 *                       as it does for anyone added to it by hand.
 *   sso_groups_seen     every group a provider has sent, so the screen offers
 *                       groups in a dropdown instead of asking anyone to type one -
 *                       each with a NAME the admin gives it (label), because some
 *                       providers (Microsoft Entra) send codes, not names
 *
 * 🔑 Nobody types codes. A group name is the identity provider's own, chosen
 * from what it has sent; everything a group GIVES comes from a team set up on
 * the Teams screen, in the admin's own language.
 *
 * Group names match ignoring case - for any alphabet, not only A-Z (mb_strtolower),
 * a fix LDAP's ldapInGroup() gets too.
 */

require_once __DIR__ . '/service_context.php';

/** True once Database Verification has added the columns and tables. */
function ssoClaimsReady(PDO $conn): bool
{
    static $ok = null;
    if ($ok !== null) return $ok;
    try {
        $conn->query("SELECT oidc_group_claim, oidc_analyst_group, oidc_admin_group, oidc_groups_last_seen FROM auth_providers LIMIT 0");
        $conn->query("SELECT 1 FROM sso_group_teams LIMIT 0");
        $conn->query("SELECT 1 FROM sso_groups_seen LIMIT 0");
        return $ok = true;
    } catch (Throwable) { return $ok = false; }
}

/** The claims offered in the dropdown; anything else is "Other". */
function ssoCommonGroupClaims(): array
{
    return ['groups', 'roles', 'realm_access.roles'];
}

/**
 * The values of claim $path as a list of strings. A dotted path walks nested
 * objects ("realm_access.roles"); a claim whose own name has dots (a URL) is
 * matched whole first. An array gives its scalar members; a string gives itself.
 * Works on the decoded ID token (nested parts are stdClass) and on userinfo.
 *
 * @param array $claims  the decoded ID token or userinfo claims (nested parts may be stdClass)
 * @param string $path  the claim name, or a dotted path into nested objects, e.g. 'groups' or 'realm_access.roles'
 * @return string[]
 */
function ssoClaimValues(array $claims, string $path): array
{
    $path = trim($path);
    if ($path === '') return [];
    $node = json_decode(json_encode($claims), true);
    if (is_array($node) && array_key_exists($path, $node)) {
        $node = $node[$path];
    } else {
        foreach (explode('.', $path) as $part) {
            if (!is_array($node) || !array_key_exists($part, $node)) return [];
            $node = $node[$part];
        }
    }
    if (is_string($node) || is_int($node)) {
        $v = trim((string)$node);
        return $v === '' ? [] : [$v];
    }
    if (!is_array($node)) return [];
    $out = [];
    foreach ($node as $v) {
        if (is_string($v) || is_int($v)) {
            $v = trim((string)$v);
            if ($v !== '') $out[] = $v;
        }
    }
    return array_values(array_unique($out));
}

/**
 * The same group, ignoring case in any alphabet and stray spaces.
 *
 * @param string $a  one group name or id
 * @param string $b  the other; a blank $a never matches
 */
function ssoSameGroup(string $a, string $b): bool
{
    $a = trim($a); $b = trim($b);
    return $a !== '' && mb_strtolower($a, 'UTF-8') === mb_strtolower($b, 'UTF-8');
}

/**
 * Is $wanted among $groups?
 *
 * @param array $groups  the group values the sign-in sent, from ssoClaimValues()
 * @param string $wanted  the group to look for, matched with ssoSameGroup()
 */
function ssoInGroup(array $groups, string $wanted): bool
{
    foreach ($groups as $g) {
        if (ssoSameGroup((string)$g, $wanted)) return true;
    }
    return false;
}

/**
 * A provider's group -> team rows, with the team names.
 *
 * @param int $providerId  the sign-in provider (auth_providers.id)
 */
function ssoTeamMappings(PDO $conn, int $providerId): array
{
    if (!ssoClaimsReady($conn)) return [];
    $s = $conn->prepare("SELECT m.group_name, m.team_id, t.name AS team_name
                           FROM sso_group_teams m JOIN teams t ON t.id = m.team_id
                          WHERE m.provider_id = ? ORDER BY m.group_name, t.name");
    $s->execute([$providerId]);
    return array_map(fn($r) => ['group' => $r['group_name'], 'team_id' => (int)$r['team_id'], 'team_name' => $r['team_name']],
                     $s->fetchAll(PDO::FETCH_ASSOC));
}

/**
 * Replace a provider's group -> team rows. $rows: [['group' => .., 'team_id' => ..]].
 * Blank rows are skipped; an unknown team is refused (ServiceError).
 *
 * @param int $providerId  the sign-in provider (auth_providers.id)
 * @param array $rows  the new mappings, as [['group' => name, 'team_id' => int], ...]; rows with neither are skipped
 */
function ssoSaveTeamMappings(PDO $conn, int $providerId, array $rows): void
{
    $clean = [];
    $teamOk = $conn->prepare("SELECT id FROM teams WHERE id = ?");
    foreach ($rows as $r) {
        $group = mb_substr(trim((string)($r['group'] ?? '')), 0, 255);
        $team  = (int)($r['team_id'] ?? 0);
        if ($group === '' && $team === 0) continue;
        if ($group === '') throw new ServiceError('validation', 'invalid_field', 'Choose a group for every team row.');
        $teamOk->execute([$team]);
        if (!$teamOk->fetchColumn()) throw new ServiceError('validation', 'invalid_field', 'Choose a team for every group row.');
        $clean[mb_strtolower($group, 'UTF-8') . '|' . $team] = [$group, $team];
    }
    $conn->prepare("DELETE FROM sso_group_teams WHERE provider_id = ?")->execute([$providerId]);
    $ins = $conn->prepare("INSERT INTO sso_group_teams (provider_id, group_name, team_id) VALUES (?, ?, ?)");
    foreach ($clean as [$g, $t]) $ins->execute([$providerId, $g, $t]);
}

/**
 * Groups this provider has sent: [['group' => code, 'label' => name|null,
 * 'last_seen' => datetime]], named ones first, then most recently seen.
 *
 * @param int $providerId  the sign-in provider (auth_providers.id)
 */
function ssoSeenGroups(PDO $conn, int $providerId): array
{
    if (!ssoClaimsReady($conn)) return [];
    $s = $conn->prepare("SELECT group_name, label, last_seen_datetime FROM sso_groups_seen WHERE provider_id = ?
                          ORDER BY (label IS NULL OR label = ''), label, last_seen_datetime DESC, group_name LIMIT 2000");
    $s->execute([$providerId]);
    return array_map(fn($r) => ['group' => $r['group_name'], 'label' => ($r['label'] ?? '') !== '' ? $r['label'] : null, 'last_seen' => $r['last_seen_datetime']],
                     $s->fetchAll(PDO::FETCH_ASSOC));
}

/**
 * Name the groups a provider has sent. $labels: [['group' => code, 'label' => name]].
 * Only groups it has actually sent can be named; a blank name clears it.
 *
 * @param int $providerId  the sign-in provider (auth_providers.id)
 * @param array $labels  [['group' => code, 'label' => name], ...]; a blank label clears it
 */
function ssoSaveGroupLabels(PDO $conn, int $providerId, array $labels): void
{
    $up = $conn->prepare("UPDATE sso_groups_seen SET label = ? WHERE provider_id = ? AND group_name = ?");
    foreach ($labels as $l) {
        $group = trim((string)($l['group'] ?? ''));
        if ($group === '') continue;
        $label = mb_substr(trim((string)($l['label'] ?? '')), 0, 100);
        $up->execute([$label !== '' ? $label : null, $providerId, $group]);
    }
}

/**
 * A group's name if it has one, else the code - for messages and the last-sign-in line.
 *
 * @param array $seen  the provider's groups, from ssoSeenGroups()
 * @param string $group  the group code to name
 */
function ssoGroupDisplay(array $seen, string $group): string
{
    foreach ($seen as $s) {
        if (ssoSameGroup($s['group'], $group)) return $s['label'] ?? $group;
    }
    return $group;
}

/**
 * Record what a sign-in sent: the last sign-in line on the provider, and each
 * group in sso_groups_seen for the dropdowns. Best effort - never fails a sign-in.
 *
 * @param int $providerId  the sign-in provider (auth_providers.id)
 * @param string $who  who signed in, for the last-sign-in line: their email, preferred_username or sub claim
 * @param array $groups  the group values the sign-in sent, from ssoClaimValues()
 */
function ssoRememberGroups(PDO $conn, int $providerId, string $who, array $groups): void
{
    try {
        $conn->prepare("UPDATE auth_providers SET oidc_groups_last_seen = ? WHERE id = ?")
             ->execute([json_encode(['at' => gmdate('Y-m-d H:i:s'), 'who' => mb_substr($who, 0, 200), 'groups' => array_slice($groups, 0, 200)]), $providerId]);
        $up = $conn->prepare("INSERT INTO sso_groups_seen (provider_id, group_name, first_seen_datetime, last_seen_datetime)
                              VALUES (?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())
                              ON DUPLICATE KEY UPDATE last_seen_datetime = UTC_TIMESTAMP()");
        foreach (array_slice($groups, 0, 500) as $g) $up->execute([$providerId, mb_substr($g, 0, 255)]);
    } catch (Throwable) { /* never fails a sign-in */ }
}

/**
 * At an analyst sign-in: admin from the admin group, team membership from the
 * mappings. Returns what changed, for the log and the tests:
 *   ['admin' => true|false|null, 'added' => [team ids], 'removed' => [team ids]]
 *
 * @param array $provider  the auth_providers row (id, oidc_admin_group)
 * @param int $analystId  the analyst signing in, whose teams and administrator flag are set
 * @param array $groups  the group values the sign-in sent, from ssoClaimValues()
 */
function ssoApplyGroupAccess(PDO $conn, array $provider, int $analystId, array $groups): array
{
    $out = ['admin' => null, 'added' => [], 'removed' => []];
    if (!ssoClaimsReady($conn)) return $out;
    $providerId = (int)$provider['id'];

    // --- teams: only the mapped ones ---
    $want = [];
    $mapped = [];
    foreach (ssoTeamMappings($conn, $providerId) as $m) {
        $mapped[$m['team_id']] = true;
        if (ssoInGroup($groups, $m['group'])) $want[$m['team_id']] = true;
    }
    if ($mapped) {
        $cur = $conn->prepare("SELECT team_id FROM analyst_teams WHERE analyst_id = ?");
        $cur->execute([$analystId]);
        $have = array_fill_keys(array_map('intval', $cur->fetchAll(PDO::FETCH_COLUMN)), true);
        $add = $conn->prepare("INSERT INTO analyst_teams (analyst_id, team_id, created_datetime) VALUES (?, ?, UTC_TIMESTAMP())");
        $del = $conn->prepare("DELETE FROM analyst_teams WHERE analyst_id = ? AND team_id = ?");
        foreach (array_keys($mapped) as $teamId) {
            if (isset($want[$teamId]) && !isset($have[$teamId])) { $add->execute([$analystId, $teamId]); $out['added'][] = $teamId; }
            if (!isset($want[$teamId]) && isset($have[$teamId])) { $del->execute([$analystId, $teamId]); $out['removed'][] = $teamId; }
        }
        // A team can set the sign-in method of those who follow it (GH #41).
        if (($out['added'] || $out['removed']) && file_exists(__DIR__ . '/analyst_signin.php')) {
            require_once __DIR__ . '/analyst_signin.php';
            if (function_exists('analystSignInReady') && analystSignInReady($conn)) analystSignInApplyMany($conn, [$analystId]);
        }
    }

    // --- administrator ---
    $adminGroup = trim((string)($provider['oidc_admin_group'] ?? ''));
    if ($adminGroup !== '') {
        require_once __DIR__ . '/services/analysts.php';
        $isAdmin = ssoInGroup($groups, $adminGroup);
        try {
            AnalystsService::setAccess($conn, ActorContext::system('SSO sign-in'), $analystId, ['is_admin' => $isAdmin]);
            $out['admin'] = $isAdmin;
        } catch (ServiceError $e) {
            // The last active administrator keeps it rather than lock everyone out of System.
            if ($e->kind !== 'conflict') throw $e;
            error_log("SSO groups: analyst #$analystId kept administrator - they are the last active one.");
        }
    }
    return $out;
}

/**
 * When a provider maps groups to teams, the analysts it CREATES get their
 * modules from those teams - so, unless the provider names default modules,
 * they start with no modules of their own (the account would otherwise have
 * every module, and the teams could add nothing to that). Called by
 * oidcCreateAnalyst() right after the insert.
 *
 * @param int $providerId  the sign-in provider (auth_providers.id) that created the analyst
 * @param int $analystId  the analyst just created by the sign-in
 * @param ?string $defaultModules  the provider's default modules setting; anything non-blank leaves the analyst alone
 */
function ssoJitStartForTeams(PDO $conn, int $providerId, int $analystId, ?string $defaultModules): void
{
    if (!ssoClaimsReady($conn) || trim((string)$defaultModules) !== '') return;
    $n = $conn->prepare("SELECT COUNT(*) FROM sso_group_teams WHERE provider_id = ?");
    $n->execute([$providerId]);
    if ((int)$n->fetchColumn() > 0) {
        $conn->prepare("UPDATE analysts SET can_access_all_modules = 0 WHERE id = ?")->execute([$analystId]);
    }
}
