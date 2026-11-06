/**
 * Hover preview - a small card beside a list item that shows what the list has
 * no room for: the full name a sidebar cuts off with "...", and a few facts
 * about the record (#202, first used by Process Mapper's process list).
 *
 * It opens when the pointer rests on an item, or straight away when the item
 * gets keyboard focus, so it is not a mouse-only reveal. It is read-only and
 * never takes the pointer itself: anything you need to copy belongs on the page
 * proper (Process Mapper puts the open process's name in the action row).
 *
 * Usage:
 *
 *   HoverPreview.attach(listElement, {
 *       item:   '.my-row',                 // the elements that get a preview
 *       render: row => '<div>...</div>',   // HTML for the card - ESCAPE what you put in it
 *   });
 *
 * Styles: assets/css/hover-preview.css (theme tokens only).
 */
(function () {
    'use strict';

    const SHOW_DELAY_MS = 400;
    const GAP = 8;
    let card = null;
    let shownFor = null;
    let shownIn = null;      // the list it belongs to, to tell when it has scrolled out of view
    let timer = null;

    /** The one card element on the page, made on first use. */
    function getCard() {
        if (!card) {
            card = document.createElement('div');
            card.className = 'hover-preview';
            card.id = 'hoverPreview';
            card.setAttribute('role', 'tooltip');
            card.hidden = true;
            document.body.appendChild(card);
        }
        return card;
    }

    /** Fills the card for an item and places it beside it, flipping left or up when it would leave the window. */
    function show(item, render, container) {
        shownIn = container;
        clearTimeout(timer);
        const html = render(item);
        if (!html) { hide(); return; }
        const c = getCard();
        c.innerHTML = html;
        c.hidden = false;
        if (shownFor && shownFor !== item) shownFor.removeAttribute('aria-describedby');
        shownFor = item;
        item.setAttribute('aria-describedby', c.id);
        place();
    }

    /** Puts the card beside the item it is showing for; hides it once that item has gone or scrolled out of its list. */
    function place() {
        const item = shownFor, c = card;
        if (!item || !c) return;
        const r = item.getBoundingClientRect();
        const box = shownIn ? shownIn.getBoundingClientRect() : r;
        if (!item.isConnected || r.bottom <= box.top || r.top >= box.bottom) { hide(); return; }
        const w = c.offsetWidth, h = c.offsetHeight;
        const vw = document.documentElement.clientWidth, vh = document.documentElement.clientHeight;
        let left = r.right + GAP;
        if (left + w > vw - GAP) left = Math.max(GAP, r.left - GAP - w);
        let top = Math.min(r.top, vh - GAP - h);
        c.style.left = left + 'px';
        c.style.top = Math.max(GAP, top) + 'px';
    }

    /** Hides the card and drops the item's link to it. */
    function hide() {
        clearTimeout(timer);
        if (card) card.hidden = true;
        if (shownFor) shownFor.removeAttribute('aria-describedby');
        shownFor = null;
        shownIn = null;
    }

    /** Wires a container's items (matched by opts.item, now or rendered later) to the preview. */
    function attach(container, opts) {
        if (!container) return;
        const canHover = window.matchMedia && window.matchMedia('(hover: hover)').matches;
        const itemOf = e => e.target.closest ? e.target.closest(opts.item) : null;

        if (canHover) {
            container.addEventListener('mouseover', e => {
                const item = itemOf(e);
                if (!item || item === shownFor) return;
                clearTimeout(timer);
                // Moving down the list with a card already open swaps it at
                // once; only the first one waits, so a pass over the list on
                // the way to the canvas does not flash cards.
                if (shownFor) show(item, opts.render, container);
                else timer = setTimeout(() => show(item, opts.render, container), SHOW_DELAY_MS);
            });
            container.addEventListener('mouseout', e => {
                const item = itemOf(e);
                if (!item || (e.relatedTarget && item.contains(e.relatedTarget))) return;
                clearTimeout(timer);
                // The next item's mouseover fires straight after; give it the
                // chance to swap the card rather than close and reopen it.
                timer = setTimeout(hide, 60);
            });
        }
        container.addEventListener('focusin', e => {
            const item = itemOf(e);
            // :focus-visible, so a tap or a click (which also focuses) does
            // not open a card - only the keyboard does.
            if (item && e.target === item && item.matches(':focus-visible')) show(item, opts.render, container);
        });
        container.addEventListener('focusout', e => {
            if (itemOf(e) === shownFor) hide();
        });
        container.addEventListener('click', hide);
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape' && shownFor) hide(); });
    window.addEventListener('resize', hide);
    // TRAP: follow a scroll, never close on one. Focusing a row with Tab
    // scrolls its list to bring it into view, so closing on scroll would shut
    // the keyboard's card the moment it opened.
    window.addEventListener('scroll', place, { passive: true, capture: true });
    // ...and after a transition: Process Mapper's "Show on hover" sidebar
    // slides open when a row takes keyboard focus, so the row is still moving
    // when the card is first placed.
    document.addEventListener('transitionend', place, true);

    window.HoverPreview = { attach, hide };
})();
