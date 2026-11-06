/**
 * "Add to address book" for a person who is not in one (#133).
 *
 * Shared by Tickets → Users and Assets → Users, so the two screens cannot
 * disagree about when it is offered or what it says. The server decides both:
 * this only draws what api/tickets/address_book_add.php returns, and asks
 * before writing - a new contact in somebody's address book is not a thing to
 * do on a single click.
 *
 *   AddressBookAdd.mount(hostElement, userId, personName, apiBase, onDone)
 */
(function () {
    'use strict';
    if (window.AddressBookAdd) return;

    /** Translates a key under common.address_book_add. */
    const t = (k, p) => window.t('common.address_book_add.' + k, p);

    /**
     * The confirmation message for adding a person to an address book - what will be
     * written, any category or group it is filed under, and the checks the server makes.
     */
    function confirmText(book, person) {
        const parts = [t('intro', { person: person, name: book.name })];
        const fields = Object.keys(book.fields || {}).map(f => t('field_' + f));
        if (fields.length) parts.push(t('will_write', { fields: fields.join(', ') }));
        if (book.scope === 'category' && book.scope_value) parts.push(t('will_tag', { value: book.scope_value }));
        if (book.scope === 'group' && book.scope_value) parts.push(t('will_group', { value: book.scope_value }));
        parts.push(t('checks'));
        return parts.join(' ');
    }

    /**
     * Asks for confirmation, then adds the person to the address book through
     * address_book_add.php and calls onDone when it worked. The button is disabled while
     * the request runs.
     */
    async function add(book, userId, person, apiBase, onDone, btn) {
        const ok = await window.showConfirm({
            title: t('title', { name: book.name }),
            message: confirmText(book, person),
            okLabel: t('ok'),
            okClass: 'primary'
        });
        if (!ok) return;
        btn.disabled = true;
        try {
            const r = await fetch(apiBase + 'address_book_add.php', {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, provider_id: book.id })
            });
            const d = await r.json();
            if (d.success) {
                window.showToast(t('added', { name: book.name }), 'success');
                if (typeof onDone === 'function') onDone();
            } else {
                window.showToast(t('failed', { error: d.error || '' }), 'error', 9000);
                btn.disabled = false;
            }
        } catch (e) {
            window.showToast(t('failed', { error: '' }), 'error');
            btn.disabled = false;
        }
    }

    /**
     * Draw the button(s) into `host` if this person can be added anywhere.
     * One address book: one button naming it. Several: one each. None, or a
     * person who is already linked: nothing at all.
     */
    async function mount(host, userId, person, apiBase, onDone) {
        if (!host) return;
        host.innerHTML = '';
        let d;
        try {
            d = await (await fetch(apiBase + 'address_book_add.php?user_id=' + encodeURIComponent(userId),
                                   { credentials: 'same-origin' })).json();
        } catch (e) { return; }
        // The panel may have moved on to somebody else while this was loading.
        if (String(host.dataset.userId || '') !== String(userId)) return;
        if (!d || !d.success || !(d.books || []).length) return;

        d.books.forEach(book => {
            const b = document.createElement('button');
            b.type = 'button';
            b.className = host.dataset.btnClass || 'btn btn-secondary';
            b.textContent = d.books.length === 1 ? t('button') : t('button_named', { name: book.name });
            b.title = book.name;
            b.addEventListener('click', () => add(book, userId, person, apiBase, onDone, b));
            host.appendChild(b);
        });
    }

    window.AddressBookAdd = { mount: mount };
})();
