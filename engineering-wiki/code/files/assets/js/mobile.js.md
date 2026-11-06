# assets/js/mobile.js

> JavaScript - 6363 lines

============================================================================ mobile.js — Mobile-only inbox master-detail behaviour (Outlook-style pane stack). Paired with mobile.css (LAYER 2).

HARD RULE mirror of the CSS: every behaviour here is gated on
   matchMedia('(max-width: 768px)'), so on desktop it is inert — no pane
   switching, and the injected sub-bar is display:none. Desktop is untouched.

   Loaded AFTER inbox.js so it can wrap the global selectEmail / selectFolder
   handlers that the list rows and folder items already call.
   ==========================================================================

## Functions

| Function | Line | What it does |
|---|--:|---|
| `injectViewsHamburger()` | 25 | The views hamburger (top-right) -> right-side slide-in drawer. |
| `moveTenantIntoWaffle()` | 46 | Company switcher into the waffle drawer — also shared, also a no-op at N=1. |
| `syncMorningChecksControls()` | 85 | MORNING CHECKS (#1270) — the date picker, Today and Save to PDF move into the views drawer on a phone. |
| `syncShell()` | 106 | Shows the views button only at phone width and closes the views drawer when leaving it, then re-syncs the Morning Checks controls - the shared shell for every page. |
| `initAssetsMobile()` | 204 | Wires the Assets page for phones - a list/detail pane stack with a Back sub-bar showing the open asset's name. |
| `setPane(p)` | 206 | Records the current Assets pane on body[data-mobile-pane] for the CSS to act on. |
| `currentPane()` | 208 | Returns the current Assets pane, defaulting to the list. |
| `pushPane(p)` | 213 | Switches the Assets pane and, at phone width, pushes a history entry so the device Back button returns to the list. |
| `selectAsset(assetId)` | 247 | Wraps the Assets module's selectAsset so opening an asset on a phone slides to the detail pane and puts the asset's hostname in the sub-bar. |
| `show()` | 254 | Copies the open asset's hostname into the Assets sub-bar. |
| `syncAssetsBar()` | 268 | Shows the Assets sub-bar and views button only at phone width, and clears the pane state on desktop so both panes show. |
| `initCalendarMobile()` | 303 | ================================================================== CALENDAR (#998) |
| `tr(key, fallback)` | 309 | Prefer the module's own translations; fall back only if a key is missing (i18n's lookup echoes the key back when it can't resolve). |
| `buildSheet(cls, title)` | 335 | Builds one of the Calendar's full-screen phone sheets (options or day agenda) with a title and a Close button. |
| `openSheet(el)` | 355 | Opening a sheet pushes a history entry so the DEVICE BACK BUTTON closes it, the same move that makes the ticket pane stack feel native rather than like a resized website. |
| `hideSheets()` | 360 | Hides both Calendar phone sheets without touching history. |
| `closeSheet()` | 368 | Closes the open Calendar sheet, popping its history entry when there is one so the device Back button stays in step. |
| `sidebarIntoSheet()` | 381 | Relocated rather than rebuilt so `#categoryFilterList` keeps its id and `renderCategoryFilters()` still finds it, and so the subscribe block keeps its own wiring. Moved lazily on first open and moved BACK when the viewport leaves mobile, so resizing a desktop browser through the breakpoint can't strand the sidebar inside a hidden sheet (16a hides it in the container). |
| `sidebarBackToPage()` | 393 | Moves the Calendar sidebar out of the options sheet and back into the page, for when the viewport leaves phone width. |
| `localDateLabel(dateStr)` | 414 | Formats a YYYY-MM-DD date as the Calendar agenda heading (weekday plus day and month) in the interface language, falling back to the raw string. |
| `renderAgenda()` | 433 | Fills the Calendar day sheet with the tapped day's events, each opening the module's edit modal, plus a New event button pre-filled with that day. |
| `openDaySheet(dateStr)` | 505 | Opens the Calendar agenda sheet for the given date. |
| `openEventModal(eventId, dateStr, hour)` | 518 | Wraps the Calendar's openEventModal so tapping a day in the month grid on a phone opens that day's agenda instead of a blank New event form. |
| `scrollToWorkingHours()` | 539 | Week and day views are 24 rows of 60px and open at the top, so a phone lands on 12 AM — three screens above anything that happens in a working day. On a desktop pane you at least see through to ~10 AM; at 360px you see 12 AM to 6 AM and nothing else. Scroll to 7 AM after a render. Mobile only: the desktop start position is untouched. |
| `renderCalendar()` | 554 | Wraps the Calendar's renderCalendar so an open agenda sheet is refreshed after a re-render and week/day views scroll to working hours on a phone. |
| `after()` | 560 | Runs once the Calendar has re-rendered - refreshes the agenda sheet if it is open and scrolls to 7 AM. |
| `syncCalendarBar()` | 573 | Shows the Calendar sub-bar only at phone width, and on desktop closes the sheets and puts the sidebar back. |
| `initChangesMobile()` | 597 | ================================================================== SERVICE STATUS (#1004) |
| `tr(key, fallback)` | 601 | Local, like every other module branch has: the sibling `tr`s are nested inside THEIR functions and are not in scope here. A missing one is a runtime ReferenceError that no parse check would catch. |
| `setPane(name)` | 608 | Records the Change Management view on body[data-cm-pane] at phone width, and clears it on desktop. |
| `showView(view)` | 619 | Wraps Change Management's showView so the phone pane state follows the list, detail or editor view. |
| `wireRichTextCards()` | 653 | ================================================================= Rich text on a phone: CARDS, and ONE editor at a time (#1189) ================================================================= #1187 put the whole tabbed widget full screen. That fixed the typing but left six TinyMCE instances living in the form, and Ed came back with "the tinymce editor is still causing a bit of havoc with the screen layout" — which it was: six iframes, each with its own toolbar, sizing themselves independently inside a 312px column. |
| `panels()` | 667 | Returns the rich-text panels in the Change Management form widget as an array. |
| `tabFor(key)` | 671 | Returns the Change Management rich-text tab for a field key. |
| `areaFor(key)` | 675 | Returns the textarea behind a Change Management rich-text field key. |
| `liveEditor(id)` | 680 | Returns the live TinyMCE editor for an id, or null when none is initialised. |
| `excerpt(html)` | 689 | The card shows an EXCERPT, never the stored markup. Assigning it through textContent means no author-written HTML is ever parsed into this page, so the card needs no sanitiser — see the safe-html rule. `innerHTML` on a detached div is only used to let the browser do entity decoding and tag stripping for us. |
| `renderCards()` | 725 | Rebuilds the phone cards for the Change Management rich-text fields - name, plain-text excerpt and Edit - skipping fields whose tab is hidden. |
| `currentValue(key)` | 771 | Returns a Change Management rich-text field's current HTML, from the live editor when one is open, otherwise from its textarea. |
| `openField(key)` | 782 | Opens one Change Management rich-text field full screen on a phone and creates a TinyMCE editor for that field only. |
| `setup(editor)` | 813 | TinyMCE setup hook - loads the textarea's value into the editor once it is ready and fires a resize so it lays out. |
| `closeField()` | 827 | Leaves the full-screen Change Management field, writing the editor's content back to the textarea before removing the editor, then redraws the cards. |
| `initEditors(callback)` | 858 | Wraps Change Management's initEditors so a phone creates no editors and instead clears every field's textarea and redraws the cards. |
| `destroyEditors()` | 879 | Wraps Change Management's destroyEditors so a phone first closes any full-screen field before the original runs. |
| `setEditorContent(id, content)` | 890 | Wraps Change Management's setEditorContent so a phone with no live editor writes into the textarea and redraws the cards. |
| `getEditorContent(id)` | 902 | Wraps Change Management's getEditorContent so it reads a live editor first, then the textarea on a phone - keeping Save from blanking fields. |
| `refreshFormLayout()` | 919 | Wraps Change Management's refreshFormLayout so the phone cards are rebuilt after field visibility changes. |
| `sync()` | 935 | Clears the Change Management pane state and full-screen editor class when the viewport leaves phone width. |
| `initProblemsMobile()` | 946 | Wires Problem Management for phones so the sidebar steps aside while a problem is open. |
| `setPane(name)` | 948 | Records the Problem Management view on body[data-pm-pane] at phone width, and clears it on desktop. |
| `pmOpenDetail()` | 961 | Wraps pmOpenDetail so the phone switches to the detail pane only once the problem has actually loaded. |
| `pmBackToList()` | 977 | Wraps pmBackToList so the phone returns to the list pane. |
| `sync()` | 985 | Rotating to a desktop width must not leave the sidebar hidden. |
| `initStatusMobile()` | 994 | Wires the Service Status board for phones - a Services/Incidents switcher and whole-card taps that open an incident. |
| `tr(key, fallback)` | 1002 | Translates a key for the Service Status phone switcher, falling back to the given English when the key is missing. |
| `setTab(name)` | 1032 | Shows the Services or Incidents half of the Service Status board on a phone and marks the matching switcher button. |
| `syncStatusBar()` | 1064 | Shows the Service Status switcher only at phone width, defaulting to Services, and clears the tab on desktop so both halves show. |
| `initKnowledgeMobile()` | 1099 | ================================================================== KNOWLEDGE (#1000) |
| `tr(key, fallback)` | 1107 | Translates a key for the Knowledge phone controls, falling back to the given English when the key is missing. |
| `openSheet()` | 1137 | Opens the Knowledge tags sheet and pushes a history entry so the device Back button closes it. |
| `hideSheet()` | 1142 | Hides the Knowledge tags sheet without touching history. |
| `closeSheet()` | 1144 | Closes the Knowledge tags sheet, popping its history entry when there is one. |
| `sidebarIntoPlace()` | 1154 | The search box is MOVED, not copied — `#articleSearch` keeps its id and its inline `onkeyup="debounceSearch()"`, so the module's own search keeps working with no rewiring. Its now-empty section in the sidebar is marked rather than found by position. |
| `sidebarBackToPage()` | 1166 | Puts the Knowledge sidebar and its search box back where they were on desktop. |
| `syncBackLabel()` | 1198 | Shortens the Knowledge article Back to list button to Back on a phone and restores the long label on desktop. |
| `wireMetaToggle()` | 1209 | ---- the collapsible meta block (Gmail-style) ---- The whole meta row is the control: a bigger target than a chevron, and its accessible name is the visible "Modified: …" text, so the toggle needs no label string in 24 languages. The reading pane is rebuilt on every article open, so this re-runs after each render and is idempotent. |
| `toggle()` | 1219 | Expands or collapses the Knowledge article meta block on a phone. |
| `renderArticleDetail()` | 1235 | Wraps Knowledge's renderArticleDetail so the collapsible meta block is wired after each article renders. |
| `wireFullScreenEditor()` | 1251 | ---- full-screen text editing ---- An "expand" control above the editor, and a Close bar inside it. Both labels reuse existing translated keys, so no new strings: `knowledge.editor.popout_title` already reads "Toggle full-screen view" in all 24 locales (it labels the desktop pop-out button, which is hidden on mobile), and the sheets' Close does the rest. Built after the editor exists, and idempotently — the editor view is not re-rendered, but syncKnowledgeBar can run again on resize. |
| `setFull(on)` | 1289 | Turns the Knowledge editor's full-screen mode on or off, showing the article's title in its bar and nudging TinyMCE to re-measure. |
| `readView()` | 1317 | Works out which Knowledge view (list, detail or editor) is showing from the views' inline display styles. |
| `setKbView(v)` | 1327 | Named setKbView, not setView: the inbox branch has a top-level setView-alike (`setPane`) and a plain `setView` here would read as the calendar module's view toggle. |
| `showView(view)` | 1336 | Wraps Knowledge's showView so body[data-kb-view] follows the view, the desktop pop-out is stripped and the full-screen editor control is wired. |
| `stripEditorPopout()` | 1353 | ⚠️ The #762 trap, second sighting. `applyEditorPopoutFromPref()` reads localStorage on every edit and re-applies `.editor-popout`, which gives the form a FIXED 340px property panel — the whole screen at 360px, leaving the editor itself nothing. Neutralise at the source (the CSS in 17d is only the backstop), and leave the stored preference alone so the desktop behaviour is unchanged. |
| `syncKnowledgeBar()` | 1371 | Shows the Knowledge sub-bar at phone width and wires the phone controls, or on desktop undoes every phone-only state and puts the sidebar back. |
| `setPane(p)` | 1397 | Records the tickets inbox pane (list, folders or reading) on body[data-mobile-pane] for the CSS. |
| `currentPane()` | 1399 | Returns the current tickets inbox pane, defaulting to the list. |
| `pushPane(p)` | 1403 | Navigate INTO a pane, pushing a history entry so the device Back button (and our Back chevron) pops back out of it. |
| `selectEmail()` | 1422 | Wraps the inbox's selectEmail so opening a ticket on a phone slides to the reading pane and applies the phone refinements once it renders. |
| `loadTicketById()` | 1448 | Wraps the inbox's loadTicketById so a ticket opened by id (deep links, linked-ticket pills) also shows the reading pane on a phone. |
| `selectFolder()` | 1460 | Wraps the inbox's selectFolder so picking a folder on a phone pops the folders pane back to the list. |
| `syncPopoutToTicketState()` | 1477 | Wraps the inbox's syncPopoutToTicketState so the desktop pop-out mode is stripped again on a phone. |
| `renderAttachmentInfoBar()` | 1492 | Wraps the inbox's renderAttachmentInfoBar so the compact phone attachment badge is refreshed when attachments arrive. |
| `relocateSections()` | 1564 | Move each section's node(s) into its sheet and add its toolbar button. Runs after every ticket render (the reading pane is rebuilt each time). Time/CMDB containers may still be empty (populated async) — relocating the container node is fine, its async loader finds it again by id. |
| `syncLinksCount()` | 1596 | Sets the ticket Links sheet button's label to show how many linked items the sheet holds. |
| `afterTicketRender()` | 1608 | Run after every ticket render: relocate the section sheets, then apply the reading-pane tidy-ups (subject-only heading + reference in the sub-bar, attachment badge, single-row action bar with a "…" overflow). |
| `getCurrentEmail()` | 1615 | inbox.js keeps the open ticket in the top-level `currentEmail` binding (shared across classic scripts). Read it defensively. |
| `decorateReadingPane()` | 1623 | Tidies an opened ticket on a phone - bare subject heading, ticket reference in the sub-bar, attachment badge and a single-row action bar. |
| `syncAttachBadge()` | 1645 | Compact yellow attachment badge on the subject row, replacing the full "…has N attachments" bar (hidden on mobile). Tapping it opens the list. |
| `buildToolbarOverflow()` | 1675 | Keep the action bar to a single row: five icons + a "…" button whose panel holds the rest (with their word labels). The toolbar is rebuilt on every render, so this re-collapses each time. |
| `showAuditHistory()` | 1742 | Wraps the inbox's showAuditHistory so a phone opens the full-screen history sheet instead of the desktop table modal. |
| `openAuditSheet()` | 1752 | Opens the ticket history sheet on a phone and loads the open ticket's audit trail into it from get_ticket_audit.php. |
| `splitStamp(text)` | 1768 | Split "Mon, 14 Jul 2026 09:32 AM" (formatFullDateTime's shape) into the day — said once, as a sticky heading — and the time, kept per entry. If the format ever changes and the time can't be found, the whole stamp rides in the time slot and the day headings simply don't appear. |
| `span(cls, text)` | 1774 | Creates a span with a class and text content, for the ticket history feed. |
| `renderAuditFeed(entries)` | 1785 | Build the day-grouped card feed from the audit rows (newest first, as the endpoint returns them). One card per change: field + time on top, old → new beneath, who did it under that; the date is a sticky heading said once per day. |
| `syncBar()` | 1849 | Injected chrome (sub-bar + views hamburger) is mobile-only; keep it out of desktop entirely (belt-and-suspenders alongside the `@media`-only styling). |
| `columns()` | 1886 | Returns the Tasks board's columns. |
| `build()` | 1894 | Rebuilds the Tasks board position dots to match the column count, showing them only on a phone with more than one column. |
| `mark()` | 1910 | Highlights the Tasks board dot for the column nearest the left edge of the scroller. |
| `renderBoard()` | 1937 | Wraps the Tasks renderBoard so the position dots are rebuilt after each board render. |
| `sync()` | 1945 | Rebuilds the Tasks board dots when the viewport crosses the phone breakpoint. |
| `labelCardFeed(table, columns)` | 2249 | Copies a table's header text onto the chosen columns' cells as data-mobile-label so the phone card feed can print column labels, skipping empty cells and short placeholder rows. |
| `apply()` | 2294 | Labels every configured card-feed table on the page at phone width. |
| `wrapLabel(btn)` | 2386 | ⚠️ These buttons carry their label as a BARE TEXT NODE beside an <svg>, so `span:not(.action-btn-icon)` — LAYER 5's way of hiding a label — has nothing to match. Wrap it once, idempotently. |
| `clearOverflow()` | 2408 | Removes the Forms editor footer's "…" overflow, putting its buttons back on the bar first. |
| `buildOverflow()` | 2424 | Measures the Forms editor footer and moves whichever visible buttons do not fit into a "…" overflow panel, always keeping Save on the bar. |
| `ensureIcon(btn)` | 2512 | Gives a Forms editor footer button with no SVG (Cancel) a text glyph so it is not left blank once its label is hidden. |
| `moveIn()` | 2528 | Moves the Forms editor's toolbar buttons into the footer before Cancel, in order, wrapping their labels and adding missing icons. |
| `moveOut()` | 2550 | Puts the moved Forms editor toolbar buttons back where they came from and removes the overflow. |
| `sync()` | 2564 | Moves the Forms editor toolbar into the footer bar at phone width, or back out again on desktop. |
| `apply()` | 2626 | Gives each icon-only Contracts detail action button a title taken from its own label, at phone width only. |
| `clear()` | 2643 | ⚠️ And take it away again when the viewport leaves mobile, so a desktop browser dragged wide is left exactly as it would have been had it never been narrow. The Calendar round set the precedent for restoring rather than one-way conversion (LAYER 16). It also means the desktop control can assert `[title]` finds nothing here, which is a stronger check than asserting a tooltip does not show. |
| `onChange()` | 2657 | Re-applies or clears the Contracts action button titles when the viewport crosses the phone breakpoint. |
| `t(key, fallback)` | 2716 | Translates a key for the Contracts suppliers/contacts directory view, falling back to the given English when the key is missing. |
| `rowIsRecord(tr)` | 2769 | Tells a real Contracts supplier or contact row from the single-cell loading, empty or error row. |
| `filter()` | 2780 | Filters the Contracts suppliers or contacts list by the directory search text and shows a no-results line when nothing matches. |
| `clearFilter()` | 2800 | Shows every Contracts suppliers or contacts row again and hides the no-results line. |
| `setView(v)` | 2807 | ---- view state ------------------------------------------------- |
| `syncChrome()` | 2839 | ---- and it all goes away above 768px -------------------------- Injected chrome must be hidden off-mobile, and the body attribute has to go with it or a desktop resize would leave the page in a view whose CSS no longer exists — the strip would stay hidden with no switch left to bring it back. The Calendar round set the precedent for restoring rather than converting one way (LAYER 16), and this is the case that makes it non-optional. |
| `tr(key, fallback)` | 2905 | The panel's own heading is the honest label for the button that opens it — the editor's side panel is headed "Lessons" already, in whatever language the reader is using. |
| `panelLabel()` | 2914 | Returns the label for the LMS lessons/contents panel button - the panel's own heading, or the Lessons translation when it has none. |
| `open()` | 2932 | Opens the LMS lessons/contents panel as a phone sheet and shows the scrim that closes it. |
| `close()` | 2944 | Closes the LMS lessons/contents phone sheet and hides its scrim. |
| `sync()` | 2977 | ⚠️ And it all goes away above 768px: the button hides and the state attribute is removed, or a desktop resize would leave the page with a panel pinned open over the content and the CSS that positions it gone. The Calendar round set the precedent for restoring rather than converting one way. |
| `syncTabName()` | 3050 | The lesson's name, kept in step with the field as it is typed and as another lesson is chosen. Truncated by CSS rather than here, so the full name is still the button's accessible label. |
| `setPage(which)` | 3069 | Shows the lesson text or questions page of the LMS lesson editor on a phone and marks the matching tab. |
| `claim(el)` | 3090 | Moves an LMS editor control into the phone bottom bar, remembering where it came from. |
| `restore()` | 3096 | Puts every LMS editor control moved into the bottom bar back exactly where it was. |
| `gather()` | 3109 | Moves Course settings, Preview and the AI write-this-lesson button into the LMS editor's phone bottom bar, once. |
| `sync()` | 3125 | ---- and it all goes away above 768px ---- The nodes go back where they came from and the page attribute is removed, or a desktop resize would leave half the lesson hidden with no switch to bring it back. |
| `tr(key, fallback)` | 3184 | Translates a key for the LMS Progress filter bar, falling back to the given English when the key is missing. |
| `filtersIntoSheet()` | 3222 | Moves the LMS Progress filter dropdowns into the phone filter sheet. |
| `filtersBackToPage()` | 3228 | Puts the LMS Progress filter dropdowns back where they were in the page. |
| `openSheet()` | 3235 | Opens the LMS Progress filter sheet and pushes a history entry so the device Back button closes it. |
| `hideSheet()` | 3241 | Hides the LMS Progress filter sheet without touching history. |
| `closeSheet()` | 3243 | Closes the LMS Progress filter sheet, popping its history entry when there is one. |
| `progressVisible()` | 3252 | ---- shown only on the Progress tab, and only on a phone ---- |
| `sync()` | 3259 | Shows the LMS Progress filter bar only on a phone while the Progress tab is visible, and otherwise puts the filters back in the page. |
| `tr(key, fallback)` | 3312 | Translates a key for the Process Mapper process-list button, falling back to the given English when the key is missing. |
| `open()` | 3334 | Opens the Process Mapper process list as a phone sheet and shows the scrim that closes it. |
| `close()` | 3346 | Closes the Process Mapper process-list sheet and hides its scrim. |
| `sync()` | 3403 | ⚠️ And it all goes away above 768px, or a desktop resize leaves the page with a fixed panel over the canvas and a scrim across it. |
| `tr(key, fallback)` | 3444 | Translates a key for the Network Mapper palette button, falling back to the given English when the key is missing. |
| `open()` | 3467 | Opens the Network Mapper CMDB class palette as a phone sheet and shows the scrim that closes it. |
| `close()` | 3479 | Closes the Network Mapper palette sheet and hides its scrim. |
| `sync()` | 3539 | ⚠️ And it all goes away above 768px, or a desktop resize leaves the page with a scrim across it and the palette parked off-screen. |
| `sectionOf(link)` | 3592 | Returns the help page section a contents link points at, from its data-section id. |
| `readOnly()` | 3707 | Read-only versions: the module's own handlers bail on `is_current`, so a synthesised event is harmless — but a ghost that follows your finger and then does nothing is worse than no gesture at all. |
| `buzz()` | 3713 | Gives a short vibration, where the device supports it, when a Network Mapper long press turns into a drag. |
| `clearTimer()` | 3718 | Cancels the pending Network Mapper long-press timer. |
| `cleanup()` | 3723 | Ends a Network Mapper touch gesture - cancels the timer, removes the drag ghost and resets the gesture state. |
| `beginPlace(tile, pt)` | 3737 | Starts placing a Network Mapper class tile by touch - closes the palette sheet and shows a ghost tile under the finger. |
| `finishPlace(pt)` | 3762 | Drops the dragged Network Mapper class tile onto the map by firing a real drop event at the canvas, so the module's own handler places it. |
| `beginMove(node, pt)` | 3788 | Starts moving a Network Mapper node by touch, firing a mousedown on it so the module's own drag handling takes over. |
| `relayMouse(type, pt)` | 3803 | Fires a synthetic mouse event at the document at a touch point, feeding the Network Mapper's own node drag. |
| `point(e)` | 3814 | Returns the client coordinates of the first touch in a touch event, or null. |
| `syncDel()` | 3915 | Shows the Network Mapper node sheet's injected Delete button only at phone width. |
| `sync()` | 3923 | ⚠️ And a resize out of mobile mid-drag must not strand a ghost, a lifted node, or a drag the module still thinks is in progress. |
| `reveal()` | 3972 | Scrolls the Workflow editor's map pane so the selected node sits in view above the inspector on a phone. |
| `tr(key, fallback)` | 4049 | Translates a key for the System wiki folder-tree sheet, falling back to the given English when the key is missing. |
| `open()` | 4092 | Opens the System wiki folder tree as a phone sheet and pushes a history entry so the device Back button closes it. |
| `hide()` | 4099 | Hides the System wiki folder-tree sheet without touching history. |
| `close()` | 4101 | Closes the System wiki folder-tree sheet, popping its history entry when there is one. |
| `treeIntoSheet()` | 4113 | Moved LAZILY on first open and moved BACK on the way to desktop. Relocating the real node rather than cloning it is what keeps `#folderTree` unique — a clone would give the page two elements with that id and `loadFolderTree()` would render into whichever came first. |
| `treeBackToPage()` | 4118 | Puts the System wiki folder tree back into the page container. |
| `sync()` | 4161 | 5. …and it all goes away above 768px, or a desktop resize leaves the folder tree inside a hidden sheet with the page's own copy of it display:none in the container. |
| `tr(key, fallback)` | 4227 | Prefer the module's own translations; fall back only if a key is missing, which can happen if a page exports fewer namespaces. |
| `colCount()` | 4234 | Columns per row, read from the class rota.js sets on the grid. |
| `day()` | 4246 | Returns the translated label for the Tickets rota Day view. |
| `analyst()` | 4248 | Returns the translated label for the Tickets rota Analyst view. |
| `week()` | 4250 | Returns the translated label for the Tickets rota Week view. |
| `buildBar()` | 4260 | Builds the Tickets rota phone footer, with a slot for Copy/Paste week and the Day, Analyst and Week view buttons, once. |
| `placeActions(intoFooter)` | 4290 | Move the real Copy / Paste buttons, never copies of them: rota.js finds them with getElementById and toggles Paste's display, so a clone would leave it talking to the one still in the header. |
| `syncBarPressed()` | 4301 | Marks the Tickets rota footer button for the current view as pressed. |
| `reserveSpace()` | 4311 | The footer's height is not a constant - it holds two rows, and Paste appears and disappears. Measure it and let the CSS reserve exactly that much, or the last analyst row hides behind it. |
| `buildChooser()` | 4319 | Builds the Tickets rota chooser strip under the header, whose chips pick the day or analyst to show, once. |
| `renderChooser()` | 4336 | Labels harvested from the grid's own cells (section 21): the day chips read the day name and number the page already rendered, the analyst chips read the name. Nothing invented, so it is right in all 24 locales. |
| `syncChooserPressed()` | 4371 | Marks the chosen day or analyst chip in the Tickets rota chooser and scrolls it into sight. |
| `setView(v)` | 4390 | Switches the Tickets rota phone view (day, analyst or week), remembers it in localStorage and re-applies the grid filtering. |
| `defaults()` | 4399 | Day defaults to today when this week holds it; analyst to the first row. |
| `clearTags()` | 4413 | Removes the phone hide class and date labels from every Tickets rota grid cell. |
| `dayLabel(iso)` | 4428 | The analyst view labels each stacked cell with its date, and it must be the format the operator chose in preferences. |
| `apply()` | 4441 | Hides the Tickets rota grid cells outside the chosen day or analyst on a phone, labels stacked analyst cells with their dates and redraws the chooser. |
| `goToThisWeek()` | 4514 | Wraps the rota's goToThisWeek so on a phone the chosen day resets and defaults back to today. |
| `sync()` | 4524 | Sets up the Tickets rota footer and chooser at phone width, or on desktop puts Copy/Paste back and removes every phone-only change. |
| `tr(key, fallback)` | 4605 | Translates a key for the users pane-stack Back button, falling back to the given English when the key is missing. |
| `buildBack()` | 4617 | 🔴 The open-a-record functions REDRAW the detail pane wholesale, which throws this button away with everything else. Exactly the same fault as the rota chooser: anything injected into a pane a page re-renders has to be put back, and the only reliable trigger is watching the pane. |
| `showPane(which)` | 4636 | Shows the list or detail pane of a users pane stack (Tickets or Assets Users) on a phone, starting a newly opened record at the top. |
| `sync()` | 4676 | Sets up the users pane stack's Back button and starting pane at phone width (the detail pane for a user_id deep link), or removes it on desktop. |
| `SVG(paths)` | 4717 | Wraps SVG path markup in a stroked 24px icon for the Tickets Users phone action footer. |
| `restore()` | 4743 | Puts the Tickets Users action buttons back into the user's header with their original text and empties the phone footer. |
| `fill()` | 4760 | Moves the open user's People, Edit, Manager access and Delete buttons into the phone footer as icons, hiding the footer when there are none (a group). |
| `sync()` | 4788 | Fills the Tickets Users action footer at phone width, or on desktop puts the buttons back and hides it. |
| `iconFor(el)` | 4834 | Which icon belongs to which control, by the handler the page already wrote - not by position, which a future edit would quietly change. |
| `toIcons()` | 4846 | Swaps the tickets calendar modal's footer buttons for icons, moving each label into aria-label. |
| `toText()` | 4862 | Restores the tickets calendar modal's footer buttons to their original text. |
| `sync()` | 4872 | Shows the tickets calendar modal's footer buttons as icons at phone width and as text on desktop. |
| `build()` | 4916 | Creates the Checklists editor's phone footer bar, once. |
| `toIcons()` | 4927 | Moves the Checklists editor's Back and Save into the phone footer as icons, moving each label into aria-label. |
| `toText()` | 4941 | Puts the Checklists editor's Back and Save back where they were, with their original text. |
| `reserve()` | 4955 | The footer is fixed, so the editor needs to reserve its height or the last step sits behind it. Measured rather than assumed - the bar is one row here, but a constant is wrong in whichever state you did not measure. |
| `sync()` | 4965 | Shows the Checklists editor's Back/Save footer at phone width, or on desktop restores the buttons and drops the reserved height. |
| `tr(key, fallback)` | 5021 | Translates a key for the Checklists list filter sheet, falling back to the given English when the key is missing. |
| `build()` | 5038 | Builds the Checklists list filter sheet and its Filters footer button, closing the sheet when a filter link is tapped, once. |
| `setOpen(open)` | 5088 | Opens or closes the Checklists list filter sheet via body[data-chk-sheet]. |
| `place(intoSheet)` | 5094 | Moves the Checklists sidebar into the filter sheet, or back to its place in the page. |
| `reserve()` | 5103 | Records the Checklists filter footer's height in --chk-bar-h so the list is not hidden behind it. |
| `sync()` | 5110 | Moves the Checklists sidebar into a sheet with a footer button at phone width, or puts it back on desktop. |
| `tr(key, fallback)` | 5164 | Translates a key for the Domains register filter sheet, falling back to the given English when the key is missing. |
| `build()` | 5181 | Builds the Domains register filter sheet and its Filters footer button, closing the sheet when a view is tapped, once. |
| `showView()` | 5245 | Shows the active Domains register view's name beside Filters on the phone footer button, leaving its count out. |
| `setOpen(open)` | 5264 | Opens or closes the Domains register filter sheet via body[data-dom-sheet]. |
| `place(intoSheet)` | 5270 | Moves the Domains register sidebar into the filter sheet, or back to its place in the page. |
| `reserve()` | 5279 | Records the Domains register filter footer's height in --dom-bar-h so the list is not hidden behind it. |
| `sync()` | 5288 | Moves the Domains register sidebar into a sheet with a footer button at phone width, or puts it back on desktop. |
| `build()` | 5347 | Creates the Domains detail page's phone action footer, once. |
| `toIcons()` | 5358 | Moves the Domains detail page's Back, Refresh, Check now, Edit and Delete buttons into the phone footer as icons, moving each label into aria-label. |
| `toText()` | 5374 | Puts the Domains detail page's action buttons back where they were, with their original content. |
| `reserve()` | 5385 | Reserve the footer's real height, so the last card is not behind it. |
| `sync()` | 5394 | Shows the Domains detail action footer at phone width, or on desktop restores the buttons and drops the reserved height. |
| `tr(key, fallback)` | 5444 | Translates a key for the Contracts list phone footer, falling back to the given English when the key is missing. |
| `esc(s)` | 5450 | Escapes a string for safe use in HTML text or a double-quoted attribute. |
| `SVG(p)` | 5454 | Wraps SVG path markup in a stroked 24px icon for the Contracts list phone footer. |
| `buildSheet()` | 5497 | Builds the Contracts list filter sheet with one option per entry in the real party filter, marking the current one. |
| `openSheet()` | 5520 | Rebuilds and opens the Contracts list filter sheet. |
| `closeSheet()` | 5522 | Hides the Contracts list filter sheet. |
| `syncDot()` | 5526 | Shows a dot on the Contracts list Filter footer button while a party filter is set. |
| `setView(v)` | 5534 | Switches the Contracts list phone view between Overview and Contracts, remembers it in localStorage and scrolls back to the top. |
| `act(id)` | 5548 | Runs a Contracts list phone footer action - switch view, open the search modal, go to New contract or open the filter sheet. |
| `sync()` | 5562 | Shows the Contracts list phone footer at phone width with the remembered view, or on desktop clears the view and closes the filter sheet. |
| `label()` | 5603 | Returns the question-count column heading from the LMS Tests builder's hidden skill header. |
| `apply()` | 5611 | Stamps the question-count heading onto each LMS Tests builder skill row at phone width, and removes it on desktop. |
| `tr(key, fallback)` | 5652 | Translates a key for the Projects portfolio filter sheet, falling back to the given English when the key is missing. |
| `build()` | 5668 | Builds the Projects portfolio filter sheet and its Filter footer button, closing the sheet when a view is tapped, once. |
| `showView()` | 5723 | Shows the active Projects portfolio view's name beside Filter on the phone footer button. |
| `setOpen(open)` | 5732 | Opens or closes the Projects portfolio filter sheet. |
| `place(intoSheet)` | 5739 | Moves the Projects portfolio sidebar into the filter sheet, or back to its place in the page. |
| `reserve()` | 5748 | Records the Projects portfolio filter footer's height in --prj-bar-h so the cards are not hidden behind it. |
| `sync()` | 5757 | Moves the Projects portfolio sidebar into a sheet with a footer button at phone width, or puts it back on desktop. |
| `build()` | 5813 | Creates the Projects detail page's phone action footer, once. |
| `toIcons()` | 5824 | Moves the project's Back link and Edit, Template and Delete buttons into the phone footer as icons, moving each label into aria-label. |
| `toText()` | 5843 | Puts the project's Back link and action buttons back where they were, with their original content. |
| `reserve()` | 5854 | Records the Projects detail footer's height in --prj-vbar-h so the page is not hidden behind it. |
| `sync()` | 5863 | Shows the Projects detail action footer at phone width, or on desktop restores the buttons and drops the reserved height. |
| `tr(key, fallback)` | 5917 | Translates a key for the Projects plan move-task sheet, falling back to the given English when the key is missing. |
| `txt(el)` | 5926 | Returns an element's text with whitespace collapsed, or an empty string - for the Projects plan move-task picker. |
| `build()` | 5933 | Builds the Projects plan move-task sheet, whose lane options move the chosen task, once. |
| `setOpen(open)` | 5966 | Opens or closes the Projects plan move-task sheet, forgetting the task when it closes. |
| `laneWord()` | 5973 | The plan's own word for a lane, from the first lane's kind chip. |
| `openFor(row)` | 5981 | Opens the Projects plan move-task sheet for a task row, listing every lane with the task's current one marked. |
| `fire(target, type)` | 6015 | Dispatches a drag event with a stub dataTransfer carrying the chosen task id, so the Projects plan's own drag handlers move it. |
| `moveTo(laneId)` | 6026 | Moves the chosen Projects plan task to a lane by firing dragstart, drop and dragend, so the page's own `moveTask()` does the work. |
| `decorate()` | 6041 | Adds a move button to each Projects plan task row on a phone, and shows a Moved toast once a pending move has been redrawn. |
| `strip()` | 6070 | Removes the Projects plan move buttons and hides the move-task sheet. |
| `sync()` | 6089 | Adds the Projects plan move buttons at phone width, or strips them on desktop. |
| `tr(key, fallback)` | 6139 | Translates a key for the Projects scope move sheet, falling back to the given English when the key is missing. |
| `txt(el)` | 6148 | Returns an element's text with whitespace collapsed, or an empty string - for the Projects scope priority picker. |
| `headText(h)` | 6151 | A column heading's own words - text nodes only, so the count badge beside them does not come along. |
| `build()` | 6165 | Builds the Projects scope priority sheet, whose MoSCoW column options move the chosen card, once. |
| `setOpen(open)` | 6197 | Opens or closes the Projects scope priority sheet, forgetting the card when it closes. |
| `openFor(card)` | 6207 | Opens the Projects scope priority sheet for a card, listing every MoSCoW column with the card's current one marked. |
| `fire(target, type)` | 6231 | Dispatches a drag event with a stub dataTransfer carrying the chosen card id, so the Projects scope board's own drag handlers move it. |
| `moveTo(moscow)` | 6242 | Moves the chosen Projects scope card to a MoSCoW column by firing dragstart, dragover, drop and dragend, so the page's own handlers save it. |
| `decorate()` | 6259 | Adds a priority button to each movable Projects scope card on a phone, and shows a Moved toast once a pending move has been redrawn. |
| `strip()` | 6282 | Removes the Projects scope priority buttons and hides the priority sheet. |
| `sync()` | 6301 | Adds the Projects scope priority buttons at phone width, or strips them on desktop. |
| `apply()` | 6342 | Stamps each Projects RACI cell with its member's name as data-prj-member at phone width, and removes it on desktop. |

## Loaded by

- [asset-management/dashboard/index.php](/code/files/asset-management/dashboard/index.php.md)
- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [asset-management/leases/index.php](/code/files/asset-management/leases/index.php.md)
- [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md)
- [asset-management/manager-access.php](/code/files/asset-management/manager-access.php.md)
- [asset-management/servers/index.php](/code/files/asset-management/servers/index.php.md)
- [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md)
- [asset-management/table.php](/code/files/asset-management/table.php.md)
- [asset-management/users.php](/code/files/asset-management/users.php.md)
- [calendar/help.php](/code/files/calendar/help.php.md)
- [calendar/index.php](/code/files/calendar/index.php.md)
- [calendar/settings/index.php](/code/files/calendar/settings/index.php.md)
- [calendar/table/index.php](/code/files/calendar/table/index.php.md)
- [change-management/table.php](/code/files/change-management/table.php.md)
- [checklists/edit/index.php](/code/files/checklists/edit/index.php.md)
- [checklists/help.php](/code/files/checklists/help.php.md)
- [checklists/index.php](/code/files/checklists/index.php.md)
- [checklists/settings/index.php](/code/files/checklists/settings/index.php.md)
- [cmdb/audit.php](/code/files/cmdb/audit.php.md)
- [cmdb/help.php](/code/files/cmdb/help.php.md)
- [cmdb/index.php](/code/files/cmdb/index.php.md)
- [cmdb/object.php](/code/files/cmdb/object.php.md)
- [cmdb/settings/index.php](/code/files/cmdb/settings/index.php.md)
- [contracts/contacts/index.php](/code/files/contracts/contacts/index.php.md)
- [contracts/edit.php](/code/files/contracts/edit.php.md)
- [contracts/help.php](/code/files/contracts/help.php.md)
- [contracts/index.php](/code/files/contracts/index.php.md)
- [contracts/rfp-builder/audit.php](/code/files/contracts/rfp-builder/audit.php.md)
- [contracts/rfp-builder/compare.php](/code/files/contracts/rfp-builder/compare.php.md)
- [contracts/rfp-builder/consolidate.php](/code/files/contracts/rfp-builder/consolidate.php.md)
- [contracts/rfp-builder/coverage.php](/code/files/contracts/rfp-builder/coverage.php.md)
- [contracts/rfp-builder/document.php](/code/files/contracts/rfp-builder/document.php.md)
- [contracts/rfp-builder/documents.php](/code/files/contracts/rfp-builder/documents.php.md)
- [contracts/rfp-builder/extracted.php](/code/files/contracts/rfp-builder/extracted.php.md)
- [contracts/rfp-builder/help.php](/code/files/contracts/rfp-builder/help.php.md)
- [contracts/rfp-builder/index.php](/code/files/contracts/rfp-builder/index.php.md)
- [contracts/rfp-builder/scoring.php](/code/files/contracts/rfp-builder/scoring.php.md)
- [contracts/rfp-builder/suppliers.php](/code/files/contracts/rfp-builder/suppliers.php.md)
- [contracts/rfp-builder/view.php](/code/files/contracts/rfp-builder/view.php.md)
- [contracts/settings/index.php](/code/files/contracts/settings/index.php.md)
- [contracts/suppliers/index.php](/code/files/contracts/suppliers/index.php.md)
- [contracts/suppliers/view/index.php](/code/files/contracts/suppliers/view/index.php.md)
- [contracts/view.php](/code/files/contracts/view.php.md)
- [domains/accounts/index.php](/code/files/domains/accounts/index.php.md)
- [domains/dashboard/index.php](/code/files/domains/dashboard/index.php.md)
- [domains/help.php](/code/files/domains/help.php.md)
- [domains/index.php](/code/files/domains/index.php.md)
- [domains/settings/index.php](/code/files/domains/settings/index.php.md)
- [domains/table/index.php](/code/files/domains/table/index.php.md)
- [domains/view.php](/code/files/domains/view.php.md)
- [files/help.php](/code/files/files/help.php.md)
- [files/index.php](/code/files/files/index.php.md)
- [files/settings/index.php](/code/files/files/settings/index.php.md)
- [forms/approvals.php](/code/files/forms/approvals.php.md)
- [forms/collection.php](/code/files/forms/collection.php.md)
- [forms/fill.php](/code/files/forms/fill.php.md)
- [forms/help.php](/code/files/forms/help.php.md)
- [forms/help/_bottom.php](/code/files/forms/help/_bottom.php.md)
- [forms/settings/index.php](/code/files/forms/settings/index.php.md)
- [forms/submissions.php](/code/files/forms/submissions.php.md)
- [knowledge/assistant/index.php](/code/files/knowledge/assistant/index.php.md)
- [knowledge/help.php](/code/files/knowledge/help.php.md)
- [knowledge/help/_bottom.php](/code/files/knowledge/help/_bottom.php.md)
- [knowledge/index.php](/code/files/knowledge/index.php.md)
- [knowledge/review/index.php](/code/files/knowledge/review/index.php.md)
- [knowledge/settings/index.php](/code/files/knowledge/settings/index.php.md)
- [lms/editor.php](/code/files/lms/editor.php.md)
- [lms/help.php](/code/files/lms/help.php.md)
- [lms/index.php](/code/files/lms/index.php.md)
- [lms/my-courses.php](/code/files/lms/my-courses.php.md)
- [lms/native-player.php](/code/files/lms/native-player.php.md)
- [lms/player.php](/code/files/lms/player.php.md)
- [morning-checks/help.php](/code/files/morning-checks/help.php.md)
- [morning-checks/index.php](/code/files/morning-checks/index.php.md)
- [morning-checks/settings/index.php](/code/files/morning-checks/settings/index.php.md)
- [network-mapper/diagram.php](/code/files/network-mapper/diagram.php.md)
- [network-mapper/help.php](/code/files/network-mapper/help.php.md)
- [network-mapper/index.php](/code/files/network-mapper/index.php.md)
- [process-mapper/help.php](/code/files/process-mapper/help.php.md)
- [process-mapper/index.php](/code/files/process-mapper/index.php.md)
- [process-mapper/settings/index.php](/code/files/process-mapper/settings/index.php.md)
- [projects/capacity.php](/code/files/projects/capacity.php.md)
- [projects/index.php](/code/files/projects/index.php.md)
- [projects/settings/index.php](/code/files/projects/settings/index.php.md)
- [projects/tutorial.php](/code/files/projects/tutorial.php.md)
- [projects/view.php](/code/files/projects/view.php.md)
- [reporting/help.php](/code/files/reporting/help.php.md)
- [reporting/index.php](/code/files/reporting/index.php.md)
- [reporting/intune/index.php](/code/files/reporting/intune/index.php.md)
- [reporting/packs/index.php](/code/files/reporting/packs/index.php.md)
- [reporting/tickets/index.php](/code/files/reporting/tickets/index.php.md)
- [service-status/help.php](/code/files/service-status/help.php.md)
- [service-status/index.php](/code/files/service-status/index.php.md)
- [service-status/settings/index.php](/code/files/service-status/settings/index.php.md)
- [software/dashboard/index.php](/code/files/software/dashboard/index.php.md)
- [software/dashboard/library.php](/code/files/software/dashboard/library.php.md)
- [software/help.php](/code/files/software/help.php.md)
- [software/index.php](/code/files/software/index.php.md)
- [software/licences/index.php](/code/files/software/licences/index.php.md)
- [software/settings/index.php](/code/files/software/settings/index.php.md)
- [system/ai/index.php](/code/files/system/ai/index.php.md)
- [system/analysts/index.php](/code/files/system/analysts/index.php.md)
- [system/api/docs.php](/code/files/system/api/docs.php.md)
- [system/api/index.php](/code/files/system/api/index.php.md)
- [system/branding/index.php](/code/files/system/branding/index.php.md)
- [system/calendar-sync/index.php](/code/files/system/calendar-sync/index.php.md)
- [system/colours/index.php](/code/files/system/colours/index.php.md)
- [system/companies/index.php](/code/files/system/companies/index.php.md)
- [system/cost-centres/index.php](/code/files/system/cost-centres/index.php.md)
- [system/date-formats/index.php](/code/files/system/date-formats/index.php.md)
- [system/db-verify/index.php](/code/files/system/db-verify/index.php.md)
- [system/debug-tools/includes/tool-page.php](/code/files/system/debug-tools/includes/tool-page.php.md)
- [system/debug-tools/index.php](/code/files/system/debug-tools/index.php.md)
- [system/demo-data/index.php](/code/files/system/demo-data/index.php.md)
- [system/docker/index.php](/code/files/system/docker/index.php.md)
- [system/email-routing-test/index.php](/code/files/system/email-routing-test/index.php.md)
- [system/encryption/index.php](/code/files/system/encryption/index.php.md)
- [system/help/index.php](/code/files/system/help/index.php.md)
- [system/help/_bottom.php](/code/files/system/help/_bottom.php.md)
- [system/index.php](/code/files/system/index.php.md)
- [system/integrations/help.php](/code/files/system/integrations/help.php.md)
- [system/integrations/index.php](/code/files/system/integrations/index.php.md)
- [system/integrations/provider.php](/code/files/system/integrations/provider.php.md)
- [system/integrations/slack.php](/code/files/system/integrations/slack.php.md)
- [system/integrations/tika.php](/code/files/system/integrations/tika.php.md)
- [system/managers/index.php](/code/files/system/managers/index.php.md)
- [system/modules/index.php](/code/files/system/modules/index.php.md)
- [system/orphaned-tickets/index.php](/code/files/system/orphaned-tickets/index.php.md)
- [system/photo-album/index.php](/code/files/system/photo-album/index.php.md)
- [system/portal-profile/index.php](/code/files/system/portal-profile/index.php.md)
- [system/preferences/index.php](/code/files/system/preferences/index.php.md)
- [system/roles/index.php](/code/files/system/roles/index.php.md)
- [system/search/index.php](/code/files/system/search/index.php.md)
- [system/security/index.php](/code/files/system/security/index.php.md)
- [system/self-service/index.php](/code/files/system/self-service/index.php.md)
- [system/sso/index.php](/code/files/system/sso/index.php.md)
- [system/sso/provider.php](/code/files/system/sso/provider.php.md)
- [system/status-portal/index.php](/code/files/system/status-portal/index.php.md)
- [system/teams/index.php](/code/files/system/teams/index.php.md)
- [system/topology/index.php](/code/files/system/topology/index.php.md)
- [system/webhooks/index.php](/code/files/system/webhooks/index.php.md)
- [tasks/calendar/index.php](/code/files/tasks/calendar/index.php.md)
- [tasks/help.php](/code/files/tasks/help.php.md)
- [tasks/index.php](/code/files/tasks/index.php.md)
- [tasks/settings/index.php](/code/files/tasks/settings/index.php.md)
- [tasks/table/index.php](/code/files/tasks/table/index.php.md)
- [tasks/timeline/index.php](/code/files/tasks/timeline/index.php.md)
- [tickets/activity.php](/code/files/tickets/activity.php.md)
- [tickets/calendar.php](/code/files/tickets/calendar.php.md)
- [tickets/csat/index.php](/code/files/tickets/csat/index.php.md)
- [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md)
- [tickets/dashboard/index.php](/code/files/tickets/dashboard/index.php.md)
- [tickets/dashboard/library.php](/code/files/tickets/dashboard/library.php.md)
- [tickets/help-calendar-sync.php](/code/files/tickets/help-calendar-sync.php.md)
- [tickets/help-email-templates.php](/code/files/tickets/help-email-templates.php.md)
- [tickets/help-mailbox-auth.php](/code/files/tickets/help-mailbox-auth.php.md)
- [tickets/help-sla.php](/code/files/tickets/help-sla.php.md)
- [tickets/help.php](/code/files/tickets/help.php.md)
- [tickets/index.php](/code/files/tickets/index.php.md)
- [tickets/manager-access.php](/code/files/tickets/manager-access.php.md)
- [tickets/rota.php](/code/files/tickets/rota.php.md)
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)
- [tickets/triage/index.php](/code/files/tickets/triage/index.php.md)
- [tickets/users.php](/code/files/tickets/users.php.md)
- [war-room/help.php](/code/files/war-room/help.php.md)
- [war-room/index.php](/code/files/war-room/index.php.md)
- [war-room/settings/index.php](/code/files/war-room/settings/index.php.md)
- [watchtower/help.php](/code/files/watchtower/help.php.md)
- [watchtower/index.php](/code/files/watchtower/index.php.md)
- [watchtower/settings/index.php](/code/files/watchtower/settings/index.php.md)
- [workflow/editor.php](/code/files/workflow/editor.php.md)
- [workflow/executions.php](/code/files/workflow/executions.php.md)
- [workflow/help-ssl.php](/code/files/workflow/help-ssl.php.md)
- [workflow/help-webhooks.php](/code/files/workflow/help-webhooks.php.md)
- [workflow/help.php](/code/files/workflow/help.php.md)
- [workflow/index.php](/code/files/workflow/index.php.md)
- [workflow/settings/index.php](/code/files/workflow/settings/index.php.md)

