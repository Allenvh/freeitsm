<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Directory sync — which parts of the directory are in scope.
 *
 *   php tests/directory-sync-scopes.php
 *
 * Pure logic, no fixture and no database: this is the arithmetic that decides
 * who gets imported, and it is worth being able to check it on any machine
 * without starting a directory.
 *
 * Two rules earn most of these cases:
 *
 *  1. A ticked branch means the whole branch, so being "under" something is
 *     decided by DN suffix — and the comma in that suffix is load-bearing.
 *     Without it OU=Sales matches OU=WholesaleSales and a carve-out silently
 *     swallows an unrelated department.
 *
 *  2. An install upgraded from before the OU browser has neither column set,
 *     and MUST go on importing exactly who it imported yesterday. The fallback
 *     is not a nicety — without it, upgrading imports nobody, and the sanity
 *     brake is then the only thing standing between that and every person in
 *     the company being marked as having left.
 */
require_once __DIR__ . '/../includes/directory_sync.php';

$pass = 0; $fail = 0;

function chk(string $label, $got, $want): void {
    global $pass, $fail;
    if ($got === $want) { printf("  ok    %s\n", $label); $pass++; return; }
    printf("  FAIL  %s\n          got  %s\n          want %s\n", $label,
        json_encode($got), json_encode($want));
    $fail++;
}

echo "\nDirectory sync — import scope\n" . str_repeat('=', 80) . "\n";

echo "\n-- what counts as 'under' a branch --\n";
chk('a branch contains itself',        dsyncDnIsUnder('ou=sales,dc=x', 'ou=sales,dc=x'), true);
chk('a child is under its parent',     dsyncDnIsUnder('cn=amy,ou=sales,dc=x', 'ou=sales,dc=x'), true);
chk('a grandchild is under it too',    dsyncDnIsUnder('cn=b,ou=uk,ou=sales,dc=x', 'ou=sales,dc=x'), true);
// ⚠️ The one that matters. A suffix test without the comma says yes here.
chk('WholesaleSales is NOT under Sales', dsyncDnIsUnder('ou=wholesalesales,dc=x', 'ou=sales,dc=x'), false);
chk('...nor is anybody inside it',     dsyncDnIsUnder('cn=x,ou=wholesalesales,dc=x', 'ou=sales,dc=x'), false);
chk('case does not matter',            dsyncDnIsUnder('CN=Amy,OU=Sales,DC=X', 'ou=sales,dc=x'), true);
chk('an empty ancestor matches nothing', dsyncDnIsUnder('ou=sales,dc=x', ''), false);

echo "\n-- overlapping ticks --\n";
chk('a child tick is dropped when the parent is ticked',
    dsyncScopes(['sync_ou_includes' => "OU=Staff,DC=x\nOU=IT,OU=Staff,DC=x\nOU=Sales,DC=x"])['includes'],
    ['ou=staff,dc=x', 'ou=sales,dc=x']);
chk('the same branch listed twice is one branch',
    dsyncScopes(['sync_ou_includes' => "OU=Staff,DC=x\nou=staff,dc=x"])['includes'],
    ['ou=staff,dc=x']);
chk('blank lines are ignored',
    dsyncScopes(['sync_ou_includes' => "\n  OU=Staff,DC=x  \n\n"])['includes'],
    ['ou=staff,dc=x']);

echo "\n-- the upgrade path (neither column set) --\n";
chk('falls back to sync_base_dn',
    dsyncScopes(['sync_base_dn' => 'OU=Old,DC=x', 'ldap_base_dn' => 'DC=x'])['includes'], ['ou=old,dc=x']);
chk('then to the sign-in base DN',
    dsyncScopes(['sync_base_dn' => '', 'ldap_base_dn' => 'DC=x'])['includes'], ['dc=x']);
chk('a ticked selection wins over a stale sync_base_dn',
    dsyncScopes(['sync_ou_includes' => 'OU=New,DC=x', 'sync_base_dn' => 'OU=Old,DC=x'])['includes'], ['ou=new,dc=x']);
chk('nothing configured anywhere yields nothing (the caller must refuse to run)',
    dsyncScopes([])['includes'], []);

echo "\n-- carve-outs --\n";
chk('the carved-out branch itself',    dsyncDnIsExcluded('ou=c,dc=x', ['ou=c,dc=x']), true);
chk('somebody inside it',              dsyncDnIsExcluded('cn=p,ou=c,dc=x', ['ou=c,dc=x']), true);
chk('a sibling is untouched',          dsyncDnIsExcluded('cn=p,ou=d,dc=x', ['ou=c,dc=x']), false);
chk('CONTROL: no carve-outs excludes nobody', dsyncDnIsExcluded('cn=p,ou=c,dc=x', []), false);
chk('carve-outs are read independently of includes',
    dsyncScopes(['sync_ou_includes' => 'DC=x', 'sync_ou_excludes' => "OU=C,DC=x\nOU=D,DC=x"])['excludes'],
    ['ou=c,dc=x', 'ou=d,dc=x']);

echo "\n" . str_repeat('=', 80) . "\n  $pass passed, $fail failed\n";
exit($fail ? 1 : 0);
