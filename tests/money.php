<?php
/* 🔴 NEVER OVER THE WEB - see tests/README.md. This one touches no database, but
   every test file refuses the web the same way so none is ever the exception. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Currencies (GH #189): the registry, the code check, the settings cleaners
 * and the formatter in includes/money.php.
 *
 * No database: Money::init() keeps its defaults when there is none, and every
 * format here is passed in explicitly, so an install's own settings can never
 * change a result. assets/js/money.js is the same algorithm - when a case here
 * changes, change that file too.
 *
 * Run:  php tests/money.php
 */

require_once __DIR__ . '/../includes/money.php';

$pass = 0; $fail = 0;
function is_(string $what, $got, $want): void {
    global $pass, $fail;
    if ($got === $want) { $pass++; printf("  PASS  %-58s %s\n", $what, var_export($got, true)); }
    else { $fail++; printf("  FAIL  %-58s got %s, wanted %s\n", $what, var_export($got, true), var_export($want, true)); }
}
/** Money::format() with the non-breaking spaces shown as '_' so a case reads plainly. */
function f($amount, ?string $code, string $fmt): string {
    return str_replace("\u{00A0}", '_', Money::format($amount, $code, $fmt));
}

echo "\nCurrencies (#189)\n" . str_repeat('=', 78) . "\n";

echo "\nThe registry:\n";
$reg = Money::registry();
is_('more than 150 currencies', count($reg) > 150, true);
is_('every code is three capital letters', count(array_filter(array_keys($reg), fn($c) => !preg_match('/^[A-Z]{3}$/', $c))), 0);
is_('every row has a name and a symbol', count(array_filter($reg, fn($r) => $r[0] === '' || $r[1] === '')), 0);
is_('decimals are only 0, 2 or 3', count(array_filter($reg, fn($r) => !in_array($r[2], [0, 2, 3], true))), 0);
is_('IDR is there (the request)', Money::isCurrent('IDR'), true);
is_('IDR has no decimals', $reg['IDR'][2], 0);
is_('JPY has no decimals', $reg['JPY'][2], 0);
is_('KWD has three', $reg['KWD'][2], 3);

echo "\nRecognising a code:\n";
is_('GBP is a code', Money::isCode('GBP'), true);
is_('lower case is not (callers upper-case first)', Money::isCode('gbp'), false);
is_('a typo is not', Money::isCode('UKP'), false);
is_('a fund code is left out', Money::isCode('CLF'), false);
is_('gold is left out', Money::isCode('XAU'), false);
is_('a retired currency is recognised...', Money::isCode('BGN'), true);
is_('...but never offered', Money::isCurrent('BGN'), false);
is_('normalise upper-cases and trims', Money::normalise(' idr '), 'IDR');
is_('normalise: blank is null', Money::normalise(''), null);
is_('normalise: null is null', Money::normalise(null), null);
$threw = false;
try { Money::normalise('ABC'); } catch (InvalidArgumentException $e) { $threw = str_contains($e->getMessage(), 'ABC'); }
is_('normalise refuses an unknown code, naming it', $threw, true);

echo "\nCleaning settings as they are read:\n";
is_('favourites: unknown and duplicate codes dropped', Money::cleanFavourites(['gbp', 'XXX', 'EUR', 'GBP']), ['GBP', 'EUR']);
is_('favourites: a retired code dropped', Money::cleanFavourites(['GBP', 'BGN']), ['GBP']);
is_('favourites: at most five', count(Money::cleanFavourites(['GBP', 'EUR', 'USD', 'IDR', 'JPY', 'AUD', 'CAD'])), 5);
is_('the default goes first', Money::withDefault(['GBP', 'EUR', 'USD'], 'USD'), ['USD', 'GBP', 'EUR']);
is_('a default not yet a favourite displaces the last', Money::withDefault(['GBP', 'EUR', 'USD', 'IDR', 'JPY'], 'AUD'), ['AUD', 'GBP', 'EUR', 'USD', 'IDR']);
is_('a good format key', Money::cleanFormat('dot_comma:symbol_after:std'), 'dot_comma:symbol_after:std');
is_('a bad separator', Money::cleanFormat('dot_dot:symbol_after:std'), null);
is_('a bad symbol', Money::cleanFormat('dot_comma:left:std'), null);
is_('a bad decimals', Money::cleanFormat('dot_comma:symbol_after:7'), null);
is_('two parts', Money::cleanFormat('dot_comma:symbol_after'), null);
is_('the map keeps only good entries', Money::cleanFormatMap('{"idr":"dot_comma:symbol_before_space:std","XXX":"dot_comma:symbol_after:std","EUR":"nonsense"}'), ['IDR' => 'dot_comma:symbol_before_space:std']);
is_('the map survives junk', Money::cleanFormatMap('not json'), []);

echo "\nWriting an amount:\n";
$std = 'comma_dot:symbol_before:std';
is_('pounds',                      f(1234567.891, 'GBP', $std), '£1,234,567.89');
is_('rupiah, built-in format',     f(1500000, 'IDR', $std), 'Rp_1,500,000');
is_('rupiah, the Indonesian way',  f(1500000, 'IDR', 'dot_comma:symbol_before_space:std'), 'Rp_1.500.000');
is_('euro, German',                f(-1234.5, 'EUR', 'dot_comma:symbol_after:std'), '-1.234,50_€');
is_('rupees, Indian grouping',     f(1234567.5, 'INR', 'indian:symbol_before:std'), '₹12,34,567.50');
is_('Indian grouping, a crore',    f(123456789, 'INR', 'indian:symbol_before:0'), '₹12,34,56,789');
is_('three decimals',              f(12.3456, 'KWD', $std), 'KD_12.346');
is_('no decimals',                 f(1000, 'JPY', $std), '¥1,000');
is_('a letter symbol gets a space', f(1234.5, 'CHF', $std), 'CHF_1,234.50');
is_('rounding up carries over',    f(999.999, 'USD', $std), '$1,000.00');
is_('rounds a half the PHP way',   f(1.005, 'USD', $std), '$1.01');
is_('a tiny minus is not "-£0.00"', f(-0.001, 'GBP', $std), '£0.00');
is_('spaces and the symbol after', f(1234567, 'SEK', 'space_comma:symbol_after:0'), '1_234_567_kr');
is_('Swiss apostrophes, the code', f(1234567.25, 'CHF', 'apos_dot:code_before:2'), 'CHF_1’234’567.25');
is_('no separator',                f(1234567.25, 'USD', 'none_dot:code_after:std'), '1234567.25_USD');
is_('under a thousand, no separator', f(999, 'GBP', $std), '£999.00');
is_('an unknown code still shows', f(5, 'ZZZ', $std), 'ZZZ_5.00');
is_('blank is blank',              Money::format('', 'GBP'), '');
is_('null is blank',               Money::format(null, 'GBP'), '');
is_('text is blank',               Money::format('abc', 'GBP'), '');
is_('a numeric string works',      f('2500', 'GBP', $std), '£2,500.00');

echo "\n" . str_repeat('-', 78) . "\n  $pass passed, $fail failed\n\n";
exit($fail ? 1 : 0);
