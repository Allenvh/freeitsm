<?php
/* 🔴 NEVER OVER THE WEB - see tests/README.md. This one touches no database, but
   every test file refuses the web the same way so none is ever the exception. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Display order (GH #187): the rule that puts a saved order on top of a list
 * that keeps growing - the personal module order and the shared Watchtower card
 * order - and the guards that keep the lists it orders from drifting apart.
 *
 * No database.
 *
 * Run:  php tests/display-order.php
 */

require_once __DIR__ . '/../includes/display_order.php';
require_once __DIR__ . '/../includes/watchtower_settings.php';

$pass = 0; $fail = 0;
function is_(string $what, $got, $want): void {
    global $pass, $fail;
    if ($got === $want) { $pass++; printf("  PASS  %-62s\n", $what); }
    else { $fail++; printf("  FAIL  %-62s got %s, wanted %s\n", $what, json_encode($got), json_encode($want)); }
}

echo "\nDisplay order (#187)\n" . str_repeat('=', 78) . "\n";

echo "\nThe merge rule:\n";
$def = ['a', 'b', 'c', 'd'];
is_('nothing saved = the default order',             displayOrderMerge([], $def), $def);
is_('a full saved order is used as saved',           displayOrderMerge(['d', 'c', 'b', 'a'], $def), ['d', 'c', 'b', 'a']);
is_('a NEW key goes in its default place, after the saved ones', displayOrderMerge(['c', 'a', 'b'], $def), ['c', 'a', 'b', 'd']);
is_('two new keys keep their default order',         displayOrderMerge(['b'], $def), ['b', 'a', 'c', 'd']);
is_('a REMOVED key drops out',                       displayOrderMerge(['x', 'b', 'a'], $def), ['b', 'a', 'c', 'd']);
is_('duplicates count once',                         displayOrderMerge(['b', 'b', 'a'], $def), ['b', 'a', 'c', 'd']);
is_('non-strings are ignored',                       displayOrderMerge([3, null, ['a'], 'c'], $def), ['c', 'a', 'b', 'd']);
is_('every default key comes back exactly once',     count(displayOrderMerge(['d', 'x', 'd', 7], $def)), 4);

echo "\nReading a stored order:\n";
is_('a JSON list',            displayOrderParse('["b","a"]'), ['b', 'a']);
is_('blank is no order',      displayOrderParse(''), []);
is_('null is no order',       displayOrderParse(null), []);
is_('junk is no order',       displayOrderParse('not json'), []);
is_('a JSON number is no order', displayOrderParse('42'), []);
is_('non-strings dropped',    displayOrderParse('["a",1,null,"b"]'), ['a', 'b']);

echo "\nWatchtower: the one card list matches the dashboard:\n";
$reg = wtCardRegistry();
$dash = file_get_contents(__DIR__ . '/../watchtower/index.php');
$colours = file_get_contents(__DIR__ . '/../includes/module-colors.php');
$missingEl = []; $missingCol = [];
foreach ($reg as $k => $c) {
    if (!str_contains($dash, 'id="' . $c['element'] . '"')) $missingEl[] = $k;
    if (!str_contains($colours, "'" . $c['module'] . "'")) $missingCol[] = $k;
}
is_('every card has its element on the dashboard', $missingEl, []);
is_('every card has a module colour',              $missingCol, []);
preg_match_all('/<div class="wt-card" id="(wt[A-Za-z]+)"/', $dash, $m);
is_('positive control: the dashboard\'s cards were found', count($m[1]), count($reg));
is_('every card on the dashboard is in the list',  array_values(array_diff($m[1], array_column($reg, 'element'))), []);
is_('wtCardKeys() is the list, in order',          wtCardKeys(), array_keys($reg));
$en = require __DIR__ . '/../lang/en/watchtower.php';
is_('every card has a settings name', array_values(array_filter(array_keys($reg), fn($k) => !isset($en['settings']['card_' . $k]))), []);

echo "\nModules: one list for every launcher:\n";
$home = file_get_contents(__DIR__ . '/../index.php');
is_('the home page draws from the module list',  str_contains($home, 'analystVisibleModules($modules)'), true);
is_('the home page has no hand-written cards',   substr_count($home, '<a href="watchtower/"'), 0);
$waffle = file_get_contents(__DIR__ . '/../includes/waffle-menu.php');
is_('the waffle draws from the module list',     substr_count($waffle, 'analystVisibleModules($modules)'), 2);   // drawer + Ctrl+K
is_('the Files start menu draws from it',        str_contains(file_get_contents(__DIR__ . '/../files/index.php'), 'analystVisibleModules($modules)'), true);

echo "\n" . str_repeat('-', 78) . "\n  $pass passed, $fail failed\n\n";
exit($fail ? 1 : 0);
