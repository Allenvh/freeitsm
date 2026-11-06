<?php
/**
 * System Help - Currencies (GH #189).
 * What the page sets, how a format is put together, which format wins when the
 * install and an analyst disagree, and what changing any of it does NOT do.
 */
require __DIR__ . '/_init.php';

$helpSlug = 'currencies';
require __DIR__ . '/_top.php';
?>

<!-- 1. Overview -->
<div class="help-section" id="overview">
    <div class="help-section-header"><?php echo helpSectionNum('overview'); ?>
        <div>
            <h3>What this sets</h3>
            <p>Everything to do with currencies across FreeITSM, in one place: which currencies come first in a list, which one a new record starts in, and how amounts are written.</p>
        </div>
    </div>
    <p>Contracts, software licences, domains, lease schedules and projects all record money, and all of them offer the same list: <strong>every currency in use in the world</strong>, from the international ISO 4217 standard. There is nothing to add or switch on - the Indonesian rupiah, the Kenyan shilling and the Swiss franc are already there.</p>
    <div class="help-note"><strong>A record stores its currency's three-letter code</strong> - <code>GBP</code>, <code>EUR</code>, <code>IDR</code>. That is what reports, exports and the API see, and nothing on this page ever changes it.</div>
</div>

<!-- 2. Favourites and the default -->
<div class="help-section" id="favourites">
    <div class="help-section-header"><?php echo helpSectionNum('favourites'); ?>
        <div>
            <h3>Favourites and the default</h3>
            <p>Up to five currencies at the top of every currency list, and the one new records start in.</p>
        </div>
    </div>
    <p>A list of 150-odd currencies is long, so the ones you actually use sit at the top of every dropdown under <strong>Favourites</strong>, with the rest underneath in code order. Typing the code on a focused list - <kbd>I</kbd><kbd>D</kbd><kbd>R</kbd> - jumps straight to it.</p>
    <p>One favourite is marked <strong>Default</strong>. A new contract, licence, domain, lease schedule or project starts in that currency; whoever creates it can still choose another. Out of the box the favourites are pounds, euros and US dollars, with pounds as the default.</p>
    <ul>
        <li><strong>Changing the default never touches an existing record.</strong> A contract already in euros stays in euros.</li>
        <li><strong>Projects has its own setting.</strong> <em>Projects - Settings - Budget - Currency for new projects</em> wins for projects when it is set; left unset, it follows this default.</li>
        <li>The default cannot be removed from the favourites. Mark another one as default first.</li>
    </ul>
</div>

<!-- 3. How amounts are written -->
<div class="help-section" id="format">
    <div class="help-section-header"><?php echo helpSectionNum('format'); ?>
        <div>
            <h3>How amounts are written</h3>
            <p>Three choices, each shown with its own example.</p>
        </div>
    </div>
    <div class="help-table"><table>
        <thead><tr><th>Choice</th><th>Options</th></tr></thead>
        <tbody>
            <tr><td>Separators</td><td><code>1,234.56</code> <strong>(default)</strong>, <code>1.234,56</code>, <code>1 234,56</code>, <code>1 234.56</code>, <code>1’234.56</code>, <code>1234.56</code>, and Indian grouping <code>12,34,567.89</code></td></tr>
            <tr><td>Symbol</td><td><code>£1,234</code> <strong>(default)</strong>, <code>£ 1,234</code>, <code>1,234 £</code>, or the code instead: <code>GBP 1,234</code>, <code>1,234 GBP</code></td></tr>
            <tr><td>Decimal places</td><td><strong>As the currency (default)</strong> - none for the yen, two for the pound, three for the Kuwaiti dinar - or always none, or always two</td></tr>
        </tbody>
    </table></div>
    <div class="help-note ok"><strong>"As the currency" is usually right.</strong> The rupiah is shown with no decimal places by default, the way it is written in Indonesia: <code>Rp 1.500.000</code>, not <code>Rp 1.500.000,00</code>.</div>
    <p>Where several currencies share a symbol, the less common one carries a prefix so two contracts never look alike: <code>$</code> is the US dollar, <code>A$</code> the Australian, <code>CA$</code> the Canadian. A currency with no widely known symbol is shown by its code. If your team deals in several dollars, choosing <em>the code instead</em> removes all doubt.</p>
</div>

<!-- 4. Exceptions -->
<div class="help-section" id="exceptions">
    <div class="help-section-header"><?php echo helpSectionNum('exceptions'); ?>
        <div>
            <h3>Exceptions for particular currencies</h3>
            <p>A different format for one currency, while everything else follows the format above.</p>
        </div>
    </div>
    <p>A UK organisation with an office in Jakarta might write pounds as <code>£1,234.56</code> and rupiah the Indonesian way, <code>Rp 1.234.568</code>. Add the rupiah as an exception and give it its own separators and symbol.</p>
</div>

<!-- 5. Per person -->
<div class="help-section" id="per-person">
    <div class="help-section-header"><?php echo helpSectionNum('per-person'); ?>
        <div>
            <h3>When somebody wants their own</h3>
            <p>Any analyst can choose how money looks for them, under <strong>Preferences - Money</strong>.</p>
        </div>
    </div>
    <p>Two people can read the same euro contract differently - <code>€1,234.56</code> for one, <code>1.234,56 €</code> for the other - and the contract itself is unchanged. When this page and an analyst disagree, the first of these that is set wins:</p>
    <ol>
        <li>The analyst's own format for that currency</li>
        <li>The analyst's own format for every currency</li>
        <li>This page's exception for that currency</li>
        <li>This page's format</li>
    </ol>
    <div class="help-note"><strong>The favourites and the default are yours alone.</strong> Analysts choose how amounts <em>look</em>; which currencies come first and which one new records start in are the same for everybody.</div>
</div>

<!-- 6. Codes that are not recognised -->
<div class="help-section" id="unknown">
    <div class="help-section-header"><?php echo helpSectionNum('unknown'); ?>
        <div>
            <h3>Codes that are not recognised</h3>
            <p>Left over from when some modules let you type a currency in.</p>
        </div>
    </div>
    <p>Domains, lease schedules and projects used to take a typed three-letter code, so an old record might hold a typo such as <code>UKP</code>. Any such code is listed at the bottom of this page, with the module and how many records hold it. It still shows on the record as it is, but FreeITSM will not save that record again until a real currency is chosen from the list.</p>
    <p>Retired currencies are different: the Bulgarian lev, the Croatian kuna and a handful of others are no longer issued, so they are not offered for new records, but a record that already holds one keeps it and saves normally.</p>
</div>

<?php require __DIR__ . '/_bottom.php'; ?>
