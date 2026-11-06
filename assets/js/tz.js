/**
 * Shared per-analyst timezone helpers (Phase 2 of the per-user timezone rollout).
 *
 * `window.USER_TIMEZONE` is published server-side by Tz::scriptTag() (see
 * includes/timezone.php). Every datetime is stored UTC; these helpers render it
 * in the analyst's chosen zone. When USER_TIMEZONE is unset the `timeZone`
 * option is simply omitted, so dates fall back to the browser's own zone.
 *
 * Load this file BEFORE any module JS that formats dates. It exposes globals
 * that mirror the proven tickets/inbox.js implementation, so a module's date
 * formatters become timezone-aware by:
 *   1. parsing the DB string as UTC:      const d = parseUTCDate(str);
 *   2. spreading tzOpts() into Intl opts:  d.toLocaleString(locale, tzOpts({ ... }))
 *   3. bucketing Today/Yesterday via:      ymdInZone(d) === ymdInZone(new Date())
 */
(function () {
    // Parse a DB datetime string as UTC (append Z if it carries no zone marker),
    // returning an absolute-instant Date. Returns null for empty input.
    window.parseUTCDate = function (dateStr) {
        if (!dateStr) return null;
        if (!/[Z+\-]\d{0,4}$/.test(dateStr)) {
            dateStr = String(dateStr).replace(' ', 'T') + 'Z';
        }
        return new Date(dateStr);
    };

    // Merge the analyst's display zone into an Intl.DateTimeFormat / toLocale*
    // options object. Pass the options you'd normally use; the timeZone is added
    // only when the analyst has chosen one.
    window.tzOpts = function (extra) {
        var o = Object.assign({}, extra || {});
        if (window.USER_TIMEZONE) o.timeZone = window.USER_TIMEZONE;
        return o;
    };

    // 'YYYY-MM-DD' for a Date, evaluated in the analyst's display zone. Use for
    // Today/Yesterday bucketing against the same zone the time is shown in.
    window.ymdInZone = function (date) {
        if (!date) return '';
        return date.toLocaleDateString('en-CA', window.tzOpts());
    };

    // Parse a NAIVE wall-clock datetime (a user-entered scheduling value stored
    // WITHOUT a zone — change plan windows, ticket work-start, calendar events,
    // PIR actuals) into a Date built from its literal components, with NO zone
    // interpretation. Format the result WITHOUT tzOpts, so "2pm" reads 2pm for
    // every analyst. These values are NOT UTC instants — never run them through
    // parseUTCDate/tzOpts. See the "Timezones and Time Handling" design note.
    window.parseNaiveDate = function (str) {
        if (!str) return null;
        var s = String(str).replace('T', ' ');
        var m = s.match(/(\d{4})-(\d{2})-(\d{2})[ ](\d{1,2}):(\d{2})/);
        if (m) return new Date(+m[1], +m[2] - 1, +m[3], +m[4], +m[5]);
        // DATE-ONLY ('2026-08-05', e.g. a CMDB date property or a task due date).
        // `new Date('2026-08-05')` parses as UTC midnight, which reads back as
        // the PREVIOUS day for anyone west of Greenwich. Build it from the
        // literal components instead, like the branch above.
        m = s.match(/^\s*(\d{4})-(\d{2})-(\d{2})\s*$/);
        if (m) return new Date(+m[1], +m[2] - 1, +m[3]);
        return new Date(str);
    };

    // --- A picked wall clock that is stored as a UTC INSTANT (GH #116) --------
    // The THIRD date kind, and the one that had no helper. `parseNaiveDate`
    // above covers values stored without a zone; `parseUTCDate` covers instants
    // the SERVER stamped. These two cover the case in between: a datetime-local
    // input the analyst fills in, whose value must be stored as UTC and will be
    // read back through parseUTCDate.
    //
    // The zone that matters is USER_TIMEZONE, NOT the browser's. An analyst in
    // London with their display zone set to Europe/Vienna sees every other date
    // in Vienna time; a picker prefilled from `new Date().getHours()` would
    // disagree with the list directly beneath it. When USER_TIMEZONE is unset
    // both helpers fall back to the browser zone, which is then the same thing.

    // Milliseconds to ADD to a UTC instant to get the wall clock in `tz`.
    // Derived by formatting the instant in that zone and reading the parts back
    // as if they were UTC — the only way to get a named zone's offset, DST and
    // all, without shipping a timezone library.
    function zoneOffsetMs(date, tz) {
        var dtf = new Intl.DateTimeFormat('en-CA', {
            timeZone: tz, hour12: false,
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
        var p = {};
        dtf.formatToParts(date).forEach(function (part) { p[part.type] = part.value; });
        // Some engines render midnight as hour '24'.
        var hour = p.hour === '24' ? 0 : +p.hour;
        return Date.UTC(+p.year, +p.month - 1, +p.day, hour, +p.minute, +p.second) - date.getTime();
    }

    // 'YYYY-MM-DDTHH:MM' for NOW in the analyst's display zone — the value to
    // prefill a datetime-local input with.
    window.nowForInput = function () {
        var now = new Date();
        if (!window.USER_TIMEZONE) {
            /** Pads a number to two digits with a leading zero. */
            var pad = function (n) { return String(n).padStart(2, '0'); };
            return now.getFullYear() + '-' + pad(now.getMonth() + 1) + '-' + pad(now.getDate())
                 + 'T' + pad(now.getHours()) + ':' + pad(now.getMinutes());
        }
        return new Date(now.getTime() + zoneOffsetMs(now, window.USER_TIMEZONE))
            .toISOString().substring(0, 16);
    };

    // A datetime-local value ('YYYY-MM-DDTHH:MM'), read as a wall clock in the
    // analyst's display zone, converted to an ISO-8601 UTC string ending in Z —
    // which is what the server expects. Sending the raw input value instead
    // hands the server a zone-less string it can only read as UTC, so the entry
    // lands offset by the analyst's whole UTC offset (GH #116).
    //
    // Two passes: the first guesses the offset using the wall clock read as if
    // it were UTC, the second re-reads it at the instant that guess produced.
    // That second pass is what makes the hour either side of a DST change land
    // on the right instant.
    window.inputToUTC = function (value) {
        if (!value) return null;
        var m = String(value).match(/(\d{4})-(\d{2})-(\d{2})[T ](\d{1,2}):(\d{2})(?::(\d{2}))?/);
        if (!m) return null;
        if (!window.USER_TIMEZONE) {
            return new Date(+m[1], +m[2] - 1, +m[3], +m[4], +m[5], +(m[6] || 0)).toISOString();
        }
        var asUTC = Date.UTC(+m[1], +m[2] - 1, +m[3], +m[4], +m[5], +(m[6] || 0));
        var ts = asUTC - zoneOffsetMs(new Date(asUTC), window.USER_TIMEZONE);
        ts = asUTC - zoneOffsetMs(new Date(ts), window.USER_TIMEZONE);
        return new Date(ts).toISOString();
    };

    // The inverse: a stored UTC datetime -> 'YYYY-MM-DDTHH:MM' in the analyst's
    // display zone, for prefilling a picker when EDITING an existing value.
    window.utcToInput = function (dbStr) {
        var d = window.parseUTCDate(dbStr);
        if (!d || isNaN(d.getTime())) return '';
        if (!window.USER_TIMEZONE) {
            /** Pads a number to two digits with a leading zero. */
            var pad = function (n) { return String(n).padStart(2, '0'); };
            return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate())
                 + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
        }
        return new Date(d.getTime() + zoneOffsetMs(d, window.USER_TIMEZONE))
            .toISOString().substring(0, 16);
    };
})();

/**
 * Date & time FORMAT helpers (GH #105) — the browser half of includes/timezone.php.
 *
 * `window.DATE_FORMAT` is published server-side by Tz::scriptTag() and carries
 * the analyst's chosen templates plus the month/weekday names for the interface
 * language. PHP and JS render through the SAME token templates, so the two can
 * never drift.
 *
 * Format is orthogonal to zone. tzOpts()/parseUTCDate decide WHICH INSTANT is
 * being shown; these decide WHAT IT LOOKS LIKE. Every function here accepts
 * either a DB datetime string or a Date, and returns '' for empty input.
 *
 *   fmtDate('2026-08-25 13:30:00')      -> '25 Aug 2026'
 *   fmtTime('2026-08-25 13:30:00')      -> '14:30'   (Europe/London)
 *   fmtDateTime('2026-08-25 13:30:00')  -> '25 Aug 2026 14:30'
 *
 * DISPLAY ONLY. For machine output — sort keys, bucketing, <input type="date">,
 * anything compared or round-tripped — use ymdInZone() below, which is pinned to
 * ISO and never consults the format setting. Routing a sort key through the
 * display family would reorder tables the moment somebody picks a new format.
 */
(function () {
    // Built-in fallbacks, used when a page somehow renders without
    // Tz::scriptTag() (the browser extension, or a page yet to be wired up).
    // These reproduce the pre-#105 output, so an unwired page looks unchanged
    // rather than broken.
    var FALLBACK = {
        dateTemplate: 'DD MON YYYY',
        timeTemplate: 'HH:mi',
        dayMonthTemplate: 'DD MON',
        months: ['January', 'February', 'March', 'April', 'May', 'June',
                 'July', 'August', 'September', 'October', 'November', 'December'],
        monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        weekdays: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        weekdaysShort: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    };

    /** Returns window.DATE_FORMAT, or the built-in FALLBACK when the page did not publish one. */
    function cfg() {
        var c = window.DATE_FORMAT;
        if (!c || !c.dateTemplate) return FALLBACK;
        return c;
    }

    // Coerce a call-site argument (DB string, Date, or null) to a Date.
    function toDate(value) {
        if (value === null || value === undefined || value === '') return null;
        var d = (value instanceof Date) ? value : window.parseUTCDate(value);
        return (d && !isNaN(d.getTime())) ? d : null;
    }

    // Numeric calendar components for a Date, evaluated in the analyst's display
    // zone. Intl does the zone arithmetic only — the locale is pinned to 'en-US'
    // and hourCycle to 'h23' so we always get plain digits and never a '24:30',
    // whatever the browser's own locale is. The digits are then assembled by our
    // own renderer, which is what makes the output identical to PHP's.
    var PART_FMT = null;
    /** Returns {year, month, day, hour, minute} for a Date as seen in the analyst's display zone (window.USER_TIMEZONE). */
    function partsInZone(d) {
        var opts = {
            year: 'numeric', month: '2-digit', day: '2-digit',
            hour: '2-digit', minute: '2-digit', hourCycle: 'h23'
        };
        if (window.USER_TIMEZONE) opts.timeZone = window.USER_TIMEZONE;
        // Cache the formatter: constructing Intl.DateTimeFormat is the expensive
        // part, and list views call this once per row.
        if (!PART_FMT || PART_FMT.zone !== (window.USER_TIMEZONE || '')) {
            PART_FMT = { zone: window.USER_TIMEZONE || '', fmt: new Intl.DateTimeFormat('en-US', opts) };
        }
        var out = {};
        PART_FMT.fmt.formatToParts(d).forEach(function (p) {
            if (p.type !== 'literal') out[p.type] = parseInt(p.value, 10);
        });
        return {
            year: out.year, month: out.month, day: out.day,
            hour: out.hour % 24, minute: out.minute
        };
    }

    // Components of a NAIVE wall-clock Date exactly as parseNaiveDate built it —
    // no zone interpretation, so "2pm" reads 2pm for every analyst.
    function partsNaive(d) {
        return {
            year: d.getFullYear(), month: d.getMonth() + 1, day: d.getDate(),
            hour: d.getHours(), minute: d.getMinutes()
        };
    }

    // ISO weekday for a y/m/d triple: 1 = Monday .. 7 = Sunday, matching the
    // index order of DateFmt::weekdays(). Computed from the components rather
    // than the Date so it agrees with whichever zone produced them.
    function isoWeekday(p) {
        var day = new Date(Date.UTC(p.year, p.month - 1, p.day)).getUTCDay();
        return day === 0 ? 7 : day;
    }

    /** Pads a number to two digits with a leading zero. */
    function pad2(n) { return (n < 10 ? '0' : '') + n; }

    // The single JS-side renderer — mirrors DateFmt::render(). One regex pass
    // with the longest tokens first, so MONTH is not eaten by MON nor YYYY by
    // YY, and so a substituted month name is never rescanned for tokens.
    var TOKENS = /MONTH|YYYY|MON|DD|MM|YY|mi|HH|D|h|A/g;
    /**
     * Renders date parts through a token template (DD, MON, YYYY, HH, mi...) using the analyst's localised month and weekday names.
     */
    function render(p, template) {
        var c = cfg();
        var hour12 = p.hour % 12; if (hour12 === 0) hour12 = 12;
        // A day number beside the month selects the in-date month form for the
        // languages that inflect — Russian "март" standing alone becomes
        // "5 марта" in a date; Polish "marzec" becomes "5 marca". Mirrors
        // DateFmt::render(). Locales that do not need it publish the same list.
        var monthNames = (String(template).indexOf('D') !== -1 && c.monthsInDate)
            ? c.monthsInDate : c.months;
        return String(template).replace(TOKENS, function (tok) {
            switch (tok) {
                case 'MONTH': return monthNames[p.month - 1];
                case 'YYYY':  return String(p.year);
                case 'MON':   return c.monthsShort[p.month - 1];
                case 'DD':    return pad2(p.day);
                case 'MM':    return pad2(p.month);
                case 'YY':    return pad2(p.year % 100);
                case 'mi':    return pad2(p.minute);
                case 'HH':    return pad2(p.hour);
                case 'D':     return String(p.day);
                case 'h':     return String(hour12);
                case 'A':     return p.hour < 12 ? 'AM' : 'PM';
            }
            return tok;
        });
    }

    // --- UTC-instant formatters (server-stamped timestamps) ------------------

    /**
     * Formats a UTC timestamp (DB string or Date) as a date in the analyst's zone and chosen date format; '' for empty input.
     */
    window.fmtDate = function (value) {
        var d = toDate(value); if (!d) return '';
        return render(partsInZone(d), cfg().dateTemplate);
    };

    /** Formats a UTC timestamp as a time in the analyst's zone and chosen time format; '' for empty input. */
    window.fmtTime = function (value) {
        var d = toDate(value); if (!d) return '';
        return render(partsInZone(d), cfg().timeTemplate);
    };

    /** Formats a UTC timestamp as date then time in the analyst's zone and chosen formats; '' for empty input. */
    window.fmtDateTime = function (value) {
        var d = toDate(value); if (!d) return '';
        var p = partsInZone(d);
        return render(p, cfg().dateTemplate) + ' ' + render(p, cfg().timeTemplate);
    };

    /** '25 Aug' — compact chips and list rows where the year is implied. */
    window.fmtDayMonth = function (value) {
        var d = toDate(value); if (!d) return '';
        return render(partsInZone(d), cfg().dayMonthTemplate);
    };

    /** 'August 2026' — calendar headers. Always the full month name. */
    window.fmtMonthYear = function (value) {
        var d = toDate(value); if (!d) return '';
        return render(partsInZone(d), 'MONTH YYYY');
    };

    /** 'Tuesday' / 'Tue' */
    window.fmtWeekday = function (value, short) {
        var d = toDate(value); if (!d) return '';
        var c = cfg();
        return (short ? c.weekdaysShort : c.weekdays)[isoWeekday(partsInZone(d)) - 1];
    };

    // --- NAIVE wall-clock formatters (user-entered scheduling values) --------
    // Same output shape as the pair above, so the two are drop-in interchangeable
    // at a call site. See the parseNaiveDate note above for which is which —
    // these must NEVER be pointed at a server-stamped UTC timestamp.

    /** Coerces a naive wall-clock value (DB string or Date) to a Date via parseNaiveDate, or null when empty or invalid. */
    function naive(value) {
        if (value === null || value === undefined || value === '') return null;
        var d = (value instanceof Date) ? value : window.parseNaiveDate(value);
        return (d && !isNaN(d.getTime())) ? d : null;
    }

    /** Formats a naive wall-clock value as a date in the chosen date format, with no zone conversion. */
    window.fmtNaiveDate = function (value) {
        var d = naive(value); if (!d) return '';
        return render(partsNaive(d), cfg().dateTemplate);
    };

    /** Formats a naive wall-clock value as a time in the chosen time format, with no zone conversion. */
    window.fmtNaiveTime = function (value) {
        var d = naive(value); if (!d) return '';
        return render(partsNaive(d), cfg().timeTemplate);
    };

    /** Formats a naive wall-clock value as date then time in the chosen formats, with no zone conversion. */
    window.fmtNaiveDateTime = function (value) {
        var d = naive(value); if (!d) return '';
        var p = partsNaive(d);
        return render(p, cfg().dateTemplate) + ' ' + render(p, cfg().timeTemplate);
    };

    /** Formats a naive wall-clock value as a compact day and month ('25 Aug'), with no zone conversion. */
    window.fmtNaiveDayMonth = function (value) {
        var d = naive(value); if (!d) return '';
        return render(partsNaive(d), cfg().dayMonthTemplate);
    };

    /** 'Tuesday' / 'Tue' for a naive wall-clock value. */
    window.fmtNaiveWeekday = function (value, short) {
        var d = naive(value); if (!d) return '';
        var c = cfg();
        return (short ? c.weekdaysShort : c.weekdays)[isoWeekday(partsNaive(d)) - 1];
    };

    /** 'August 2026' for a naive wall-clock value — calendar headers. */
    window.fmtNaiveMonthYear = function (value) {
        var d = naive(value); if (!d) return '';
        return render(partsNaive(d), 'MONTH YYYY');
    };

    // --- Escape hatch for genuinely bespoke labels ---------------------------
    // A few places need a shape none of the functions above produce - a calendar
    // week title that compresses "5 - 11 May 2026", say. Before #105 those wrote
    // their own toLocale* call with a hardcoded locale, which is exactly the
    // drift this work removes. These render an arbitrary TOKEN TEMPLATE (see the
    // token list in includes/timezone.php) through the same renderer and the
    // same localised month and weekday names, so a bespoke label still follows
    // the analyst's language.
    //
    // They do NOT follow the analyst's chosen date FORMAT - the caller is
    // choosing the arrangement itself. Use them only where the shape is dictated
    // by the layout rather than by preference, and prefer fmtDate/fmtDateTime
    // everywhere else.
    window.fmtTemplate = function (value, template) {
        var d = toDate(value); if (!d) return '';
        return render(partsInZone(d), template);
    };

    /** Renders a naive wall-clock value through a caller-chosen token template - the naive twin of fmtTemplate. */
    window.fmtNaiveTemplate = function (value, template) {
        var d = naive(value); if (!d) return '';
        return render(partsNaive(d), template);
    };
})();
