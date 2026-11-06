/**
 * Money - currencies in the browser (GH #189). The twin of includes/money.php:
 * format() here and Money::format() there are the same algorithm, so a figure
 * on screen and the same figure in a PDF agree. Change both together.
 *
 * Needs window.MONEY, published by Money::scriptTag() before this file loads.
 * Without it (a page nobody updated) it still works: GBP and the built-in
 * format, with the browser's own currency formatting as the fallback.
 *
 *   Money.format(1500000, 'IDR')        -> "Rp 1.500.000" (in that viewer's format)
 *   Money.fill(selectEl, 'EUR')         -> favourites first, then every current currency
 *   Money.def()                         -> the install's default currency
 *
 * @wiki Currencies
 */
(function () {
    'use strict';

    const M = window.MONEY || { l: {}, c: {}, def: 'GBP', fav: ['GBP'], all: 'comma_dot:symbol_before:std', per: {}, sep: {}, max: 5 };
    const NB = ' ';
    const L = (k, fallback) => (M.l && M.l[k]) || fallback;

    /** The registry row for a code: [name, symbol, decimals, retired] - or a stand-in for an unknown code. */
    function info(code) { return M.c[code] || [code, code, 2, 0]; }

    /** Whether a code is recognised (retired ones included). */
    function isCode(code) { return !!M.c[code]; }

    /** The format key that applies to a currency for this viewer (resolved server-side; see money.php). */
    function formatFor(code) { return (M.per && M.per[code]) || M.all || 'comma_dot:symbol_before:std'; }

    /** Rounds like PHP's round(): 1.005 -> 1.01, which Math.round(1.005 * 100) gets wrong. */
    function round(v, dp) { return Number(Math.round(Number(v + 'e' + dp)) + 'e-' + dp); }

    /** Puts the thousands separator into a string of digits - threes, Indian twos (12,34,567), or none. */
    function group(int, sep, grouping) {
        if (grouping === 'none' || int.length <= 3) return int;
        const size = grouping === 'indian' ? 2 : 3;
        let out = int.slice(-3), rest = int.slice(0, -3);
        while (rest) { out = rest.slice(-size) + sep + out; rest = rest.length > size ? rest.slice(0, -size) : ''; }
        return out;
    }

    /**
     * Writes an amount for a person to read. Blank for no amount. Not for inputs
     * or anything read back - those take the plain number.
     *
     * @param {number|string|null} amount
     * @param {string} [code]    the currency; blank means the install's default
     * @param {string} [fmtKey]  a format key to use instead of the viewer's (the settings previews)
     */
    function format(amount, code, fmtKey) {
        if (amount === null || amount === undefined || amount === '' || isNaN(Number(amount))) return '';
        code = code ? String(code).toUpperCase() : M.def;
        const r = info(code);
        const f = String(fmtKey || formatFor(code)).split(':');
        const s = M.sep[f[0]];
        if (!s) {
            // No payload: the browser's own formatting, as the modules did before #189.
            try { return new Intl.NumberFormat(document.documentElement.lang || undefined, { style: 'currency', currency: code }).format(Number(amount)); }
            catch (e) { return code + ' ' + Number(amount).toFixed(2); }
        }
        const dp = f[2] === 'std' ? r[2] : parseInt(f[2], 10);
        const v = Number(amount);
        const abs = round(Math.abs(v), dp);
        const parts = abs.toFixed(dp).split('.');
        let num = group(parts[0], s[0], s[2]) + (parts[1] ? s[1] + parts[1] : '');
        if (v < 0 && abs !== 0) num = '-' + num;
        const sym = r[1];
        switch (f[1]) {
            case 'symbol_after':        return num + NB + sym;
            case 'symbol_before_space': return sym + NB + num;
            case 'code_before':         return code + NB + num;
            case 'code_after':          return num + NB + code;
            // A symbol ending in a letter ('CHF', 'Rp', 'kr') needs a space or it runs into the digits.
            default:                    return sym + (/\p{L}$/u.test(sym) ? NB : '') + num;
        }
    }

    /**
     * An amount with NO currency (a contract value nobody gave a currency):
     * the viewer's separators, two decimals, no symbol - never a guessed one.
     *
     * @param {number|string|null} amount
     */
    function number(amount) {
        if (amount === null || amount === undefined || amount === '' || isNaN(Number(amount))) return '';
        const f = String(M.all || 'comma_dot').split(':');
        const s = M.sep[f[0]] || [',', '.', 'three'];
        const dp = f[2] === '0' ? 0 : 2;
        const v = Number(amount), abs = round(Math.abs(v), dp);
        const parts = abs.toFixed(dp).split('.');
        return (v < 0 && abs !== 0 ? '-' : '') + group(parts[0], s[0], s[2]) + (parts[1] ? s[1] + parts[1] : '');
    }

    let names = null;
    /** The currency's name in the viewer's language (the browser knows them all), else the English one. */
    function name(code) {
        if (names === null) {
            try { names = new Intl.DisplayNames([document.documentElement.lang || 'en'], { type: 'currency' }); }
            catch (e) { names = false; }
        }
        let n = '';
        if (names) { try { n = names.of(code); } catch (e) { n = ''; } }
        // DisplayNames echoes the code back when it has no name; the registry's English is better.
        return (n && n !== code) ? n : info(code)[0];
    }

    const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const fill = (tpl, p) => String(tpl).replace(/\{(\w+)\}/g, (m, k) => (p[k] !== undefined ? p[k] : m));

    let sorted = null;
    /** Every currency still issued, sorted by code. */
    function current() {
        if (!sorted) sorted = Object.keys(M.c).filter(c => !M.c[c][3]).sort();
        return sorted;
    }

    /**
     * The <option>s for a currency dropdown: the install's favourites (default
     * first), then every other current currency by code. A value that is not
     * offered - a retired currency, or a code nobody recognises - is kept as an
     * option of its own, so opening an old record never silently changes it.
     * Code first in each label, so typing "IDR" on a focused dropdown jumps to it.
     *
     * @param {string} [selected]  the stored code
     * @param {object} [opts]      { blank: label } adds an empty first option
     */
    function optionsHtml(selected, opts) {
        opts = opts || {};
        selected = selected ? String(selected).toUpperCase() : '';
        const opt = c => '<option value="' + esc(c) + '"' + (c === selected ? ' selected' : '') + '>' + esc(c + ' - ' + name(c)) + '</option>';
        let html = '';
        if (opts.blank !== undefined) html += '<option value=""' + (selected === '' ? ' selected' : '') + '>' + esc(opts.blank || L('none', 'None')) + '</option>';
        const favs = (M.fav || []).filter(c => M.c[c]);
        if (selected && !favs.includes(selected) && !current().includes(selected)) {
            const label = isCode(selected)
                ? fill(L('retired', '{code} - {name} (no longer issued)'), { code: selected, name: name(selected) })
                : fill(L('unknown', '{code} - not a recognised currency'), { code: selected });
            html += '<option value="' + esc(selected) + '" selected>' + esc(label) + '</option>';
        }
        html += '<optgroup label="' + esc(L('favourites', 'Favourites')) + '">' + favs.map(opt).join('') + '</optgroup>';
        html += '<optgroup label="' + esc(L('all', 'All currencies')) + '">' + current().filter(c => !favs.includes(c)).map(opt).join('') + '</optgroup>';
        return html;
    }

    /**
     * Fills a <select> with optionsHtml(). With no selected value and no blank
     * option, the install's default currency is selected - what a new record starts in.
     *
     * @param {HTMLSelectElement} sel
     * @param {string} [selected]
     * @param {object} [opts]  as optionsHtml()
     */
    function fillSelect(sel, selected, opts) {
        if (!sel) return;
        opts = opts || {};
        if (!selected && opts.blank === undefined) selected = M.def;
        sel.innerHTML = optionsHtml(selected, opts);
    }

    /**
     * The three <select>s that choose a format, each option showing its own
     * example in the given currency. Used by System -> Currencies and Preferences.
     *
     * @param {string} code     the currency the examples are written in
     * @param {string} fmtKey   the current format key
     * @param {string} [cls]    a class for the three selects
     */
    function formatPickerHtml(code, fmtKey, cls) {
        const f = String(fmtKey || 'comma_dot:symbol_before:std').split(':');
        const sample = 1234567.891;
        const sel = (part, items) => '<select class="' + esc(cls || '') + '" data-part="' + part + '" aria-label="' + esc(L(part === 0 ? 'sep' : part === 1 ? 'symbol' : 'decimals', '')) + '">'
            + items.map(([v, label]) => '<option value="' + esc(v) + '"' + (v === f[part] ? ' selected' : '') + '>' + esc(label) + '</option>').join('') + '</select>';
        const ex = (a, b, c) => format(sample, code, a + ':' + b + ':' + c);
        return sel(0, Object.keys(M.sep).map(k => [k, format(sample, code, k + ':code_after:2').replace(NB + code, '')]))
            + sel(1, ['symbol_before', 'symbol_before_space', 'symbol_after', 'code_before', 'code_after'].map(k => [k, ex(f[0], k, f[2])]))
            + sel(2, [['std', fill(L('dec_std', 'As the currency ({n})'), { n: info(code)[2] })], ['0', L('dec_0', 'None')], ['2', L('dec_2', 'Two')]]);
    }

    /** Reads the format key back out of a container holding formatPickerHtml(). */
    function readPicker(box) {
        const v = p => { const s = box.querySelector('select[data-part="' + p + '"]'); return s ? s.value : ''; };
        return v(0) + ':' + v(1) + ':' + v(2);
    }

    window.Money = {
        format, number, name, isCode, info, formatFor, optionsHtml, fill: fillSelect, current,
        formatPickerHtml, readPicker,
        def: () => M.def,
        favourites: () => (M.fav || []).slice(),
        symbol: code => info(code ? String(code).toUpperCase() : M.def)[1],
        max: () => M.max || 5,
    };
})();
