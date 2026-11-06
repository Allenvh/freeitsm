<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * mailboxResolveFolderId() — reading mail from a folder that is not Inbox (GH #77).
 *
 * WHY THIS TEST EXISTS. `/mailFolders/<x>/messages` does not take folder NAMES.
 * Graph accepts a short list of well-known aliases there and treats everything
 * else as an opaque folder id, so a mailbox told to read "freeitsm" got:
 *
 *     400  ErrorInvalidIdMalformed — "Id is malformed."
 *
 * "INBOX" had always worked purely because it is on the alias list. Reading a
 * folder by name had never worked for any name off it.
 *
 * The resolver takes its HTTP fetcher as an argument, so the whole thing is
 * exercised here against a fixture with no network and no mailbox. What this
 * canNOT prove is what Graph really returns — see tests/integrations for that.
 *
 * Run:  php tests/mailbox-folder-resolve.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/mailbox_graph.php';

mailboxGraphBase('/users/test@example.com');

// A mailbox with a custom top-level folder AND a same-named one inside Inbox,
// so "freeitsm" and "Inbox/freeitsm" must not resolve to the same place.
$TOP = [
    ['id' => 'AAA_inbox',   'displayName' => 'Inbox'],
    ['id' => 'AAA_free',    'displayName' => 'freeitsm'],
    ['id' => 'AAA_archive', 'displayName' => 'Archive'],
];
$CHILD_OF_INBOX = [['id' => 'BBB_free', 'displayName' => 'freeitsm']];

$calls = [];
$get = function ($url) use (&$calls, $TOP, $CHILD_OF_INBOX) {
    $calls[] = $url;
    if (str_contains($url, '/childFolders')) {
        return ['code' => 200, 'body' => ['value' => str_contains($url, 'inbox') ? $CHILD_OF_INBOX : []]];
    }
    return ['code' => 200, 'body' => ['value' => $TOP]];
};

$pass = 0; $fail = 0;
function check($label, $got, $want) {
    global $pass, $fail;
    if ($got === $want) { $pass++; echo "  ok   $label\n"; }
    else { $fail++; echo "  FAIL $label\n         got:  " . var_export($got, true) . "\n         want: " . var_export($want, true) . "\n"; }
}
function checkThrows($label, callable $fn, $needle) {
    global $pass, $fail;
    try { $fn(); $fail++; echo "  FAIL $label (expected an exception, got none)\n"; }
    catch (Exception $e) {
        if (stripos($e->getMessage(), $needle) !== false) { $pass++; echo "  ok   $label\n"; }
        else { $fail++; echo "  FAIL $label\n         message: " . $e->getMessage() . "\n         wanted to contain: $needle\n"; }
    }
}

echo "Well-known aliases resolve without a request (this is why INBOX always worked)\n";
$before = count($calls);
check('INBOX -> inbox',          mailboxResolveFolderId('INBOX', $get), 'inbox');
check('Inbox -> inbox',          mailboxResolveFolderId('Inbox', $get), 'inbox');
check('Sent Items -> sentitems', mailboxResolveFolderId('Sent Items', $get), 'sentitems');
check('sentitems unchanged',     mailboxResolveFolderId('sentitems', $get), 'sentitems');
check('no HTTP calls made',      count($calls) - $before, 0);

echo "\nThe reported case: a custom folder alongside Inbox\n";
check('freeitsm resolves to an id', mailboxResolveFolderId('freeitsm', $get), 'AAA_free');
check('matching is case-insensitive', mailboxResolveFolderId('FreeITSM', $get), 'AAA_free');
check('surrounding spaces trimmed',   mailboxResolveFolderId('  freeitsm  ', $get), 'AAA_free');

echo "\nNested folders, because putting it under Inbox is what people try first\n";
check('Inbox/freeitsm uses childFolders', mailboxResolveFolderId('Inbox/freeitsm', $get), 'BBB_free');
check('nested is NOT the top-level one',
    mailboxResolveFolderId('Inbox/freeitsm', $get) !== mailboxResolveFolderId('freeitsm', $get), true);

echo "\nA failure names the segment that was missing\n";
checkThrows('unknown top-level folder', function () use ($get) { mailboxResolveFolderId('nosuch', $get); }, 'nosuch');
checkThrows('unknown child names its parent', function () use ($get) { mailboxResolveFolderId('Inbox/nope', $get); }, 'inside "Inbox"');
checkThrows('empty setting', function () use ($get) { mailboxResolveFolderId('   ', $get); }, 'No mail folder');
checkThrows('Graph error surfaces', function () {
    mailboxResolveFolderId('whatever', function ($u) { return ['code' => 403, 'body' => null]; });
}, '403');

echo "\nNEGATIVE CONTROL — the old behaviour must NOT pass\n";
// The old code put the typed name straight in the path. If the resolver ever
// returns the typed name again for a custom folder, the bug is back and every
// assertion above would still be green.
check('a custom folder never resolves to the typed name',
    mailboxResolveFolderId('freeitsm', $get) !== 'freeitsm', true);

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);
