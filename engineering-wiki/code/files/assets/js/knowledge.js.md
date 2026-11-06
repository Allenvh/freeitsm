# assets/js/knowledge.js

> JavaScript - 3811 lines

Knowledge Base JavaScript

## Functions

| Function | Line | What it does |
|---|--:|---|
| `loadCompanies()` | 75 | Companies this analyst can file an article against. Same source and hide-unless-more-than-one idiom as the tickets/changes "Move to company" pickers — on a single-company install the control never appears. |
| `audienceFromControls()` | 114 | The editor shows the three stored audiences as TWO choices plus an opt-in tickbox. This pair converts between the two shapes; nothing else in the product knows the difference, because the stored value is unchanged. |
| `audienceToControls(stored)` | 130 | Sets the audience dropdown and the public tickbox from a stored audience value ('public' becomes 'customer' plus the tick), then refreshes the hint. |
| `updateAudienceHint()` | 140 | Spell out what the chosen audience actually means, in the editor. |
| `resetVisibilityFields()` | 172 | Put the two visibility controls back to the safe default (a new article). |
| `visibilityPayload()` | 184 | The visibility half of a save payload. |
| `loadAnalysts()` | 200 | Load analysts for owner dropdown |
| `initTinyMCE()` | 224 | Initialize TinyMCE editor |
| `setup(editor)` | 277 | TinyMCE setup hook - keeps a reference to the editor, toggles the kb-editor-fullscreen body class with full screen, and lets Esc leave full screen. |
| `kbEditorFullScreen()` | 297 | Just the editor, filling the screen - TinyMCE's own fullscreen plugin. |
| `kbEditorIsFullScreen()` | 304 | Returns true when the article editor is currently in TinyMCE's full-screen mode. |
| `initTagInput()` | 317 | Initialize tag input functionality |
| `loadBrowseModePreference()` | 398 | Loads the analyst's browse-mode preference ('panel' or 'explorer', defaulting to 'panel') and applies it. |
| `loadLayoutPreference()` | 413 | Loads the analyst's saved main-pane layout (list, cards, tree or details) and applies it, re-fetching the articles first for the tree. |
| `applyLayout(save)` | 437 | Highlights the active layout button, redraws the article list, and saves the layout as a user preference when save is true. |
| `setLayout(mode)` | 455 | Switches the main pane to another layout; tree and details reset to "All articles" and reload the unfiltered list before applying. |
| `kbSortDetails(col)` | 484 | Sorts the Details table by the clicked column, reversing the direction when the same column is clicked again. |
| `renderDetailsLayout()` | 495 | Builds the HTML for the Details table - the subfolders of the current folder first, then the articles sorted by the chosen column. |
| `val(a, col)` | 503 | Returns the sort key for an article in the given Details column. |
| `arrow(c)` | 515 | Returns the up or down arrow for the column currently sorted on, or nothing for the others. |
| `renderTreeLayout()` | 565 | The whole shape at once: every folder, expanded, with its articles inside. |
| `byName(a, b)` | 573 | ⚠️ ALPHABETICAL, and by NAME rather than by whatever order the list happened to arrive in. The articles come back newest-first, which is right for a list you are scanning for recent work and wrong for a tree, where you are looking for a particular thing by its name. |
| `article(a, depth)` | 585 | Builds one article row of the tree layout, indented for its depth. |
| `walk(parent, depth)` | 600 | Walks the folder tree from a parent, adding each folder row (alphabetical) followed by its subfolders and then its own articles. |
| `applyBrowseMode()` | 631 | Hides the folder tree in the left panel on the explorer setting and redraws the article list. |
| `loadSidebarModePreference()` | 640 | Loads the analyst's left panel preference ('always' or 'hover') and applies it, falling back to 'always'. |
| `applySidebarMode(mode)` | 651 | Adds or removes the sidebar-hover class on the knowledge container for the chosen left panel mode. |
| `kbConfirm(opts)` | 678 | The app's shared confirmation dialog, with a safety net. |
| `kbPrompt(title, value)` | 689 | Opens the app's own name dialog and returns a Promise of the typed text, falling back to the browser's `prompt()` when the dialog is missing. |
| `kbPromptAccept()` | 714 | Closes the name dialog and resolves the pending `kbPrompt()` with the typed value. |
| `kbPromptCancel()` | 722 | Cancel resolves with null — the same thing `prompt()` returns, so callers that check for null keep working unchanged. |
| `kbPromptClose()` | 728 | Hides the name dialog. |
| `loadFolders()` | 746 | Fetches the folder tree the analyst may see, then redraws the article list, the left panel tree and the editor's folder picker. |
| `folderChildren(parent)` | 777 | Children of `parent`, in the order the server sent them (by name). |
| `renderFolderTree(rootCount)` | 785 | Draws the folder tree in the left panel, with "All articles" at the top as a drop target and a key button on each folder for those who can manage permissions. |
| `walk(parent, depth)` | 799 | Walks the folders from a parent, adding an indented, draggable tree row for each one and then its subfolders. |
| `renderFolderPicker()` | 829 | Fill the editor's folder picker, indented so the shape is readable. |
| `walk(parent, depth)` | 840 | Walks the folders from a parent, adding an indented option for each one to the editor's folder picker. |
| `goUpOneLevel()` | 861 | Up one level. |
| `selectFolder(value)` | 869 | Makes a folder the current one, updates the URL and reloads the folder tree and the article list for it. |
| `createFolderPrompt()` | 881 | Asks for a name and creates a folder inside the current folder, or at the top level from "All articles". |
| `renameFolderPrompt(id, current)` | 891 | Asks for a new name and renames the folder. |
| `deleteFolderPrompt(id, name)` | 898 | Asks for confirmation and deletes the folder. |
| `folderAction(payload, okKey)` | 912 | Posts a folder or filing action to folders.php, shows the result as a toast and reloads the folders and articles on success. |
| `kbDragStart(e, type, id)` | 955 | Starts dragging an article or folder - records what is in flight and fades the row being dragged. |
| `kbDragEnd()` | 969 | Clears the drag state, the faded row and any drop-target highlights left by an abandoned drag. |
| `kbDragOver(e)` | 980 | Allows a drop on a folder row and highlights it, except a folder dragged over itself. |
| `kbDragLeave(e)` | 992 | Removes the drop-target highlight when the drag leaves a row. |
| `kbDrop(e, folderId)` | 997 | Files the dropped article in the folder (Ctrl makes a shortcut instead) or moves the dropped folder under it. |
| `kbContextMenu(e, type, id, name)` | 1028 | The right-click menu. |
| `closeContextMenu()` | 1100 | Removes the right-click menu if one is open. |
| `renameArticlePrompt(id, current)` | 1114 | Rename an article without opening the editor. |
| `createFolderIn(parentId)` | 1133 | "New folder" from a folder's own menu means a subfolder of THAT folder. |
| `kbTreeIsVisible()` | 1162 | Is the folder tree actually on screen? |
| `folderPath(id)` | 1168 | The chain from the root down to the folder being viewed. |
| `renderBreadcrumb()` | 1181 | Draws the breadcrumb for the current folder with an "up one level" button, or hides it at "All articles". |
| `renderFolderRows()` | 1209 | Subfolders of wherever we are, as rows above the articles. |
| `renderCardTags(article)` | 1270 | Returns the tag pills for an article, capped in the cards layout with a "+N more" pill that opens the full list. |
| `pill(t)` | 1273 | Returns the HTML for one tag pill. |
| `kbShowAllTags(el)` | 1307 | Show every tag on the article in a small dialog. |
| `closeTagsModal()` | 1326 | Closes the all-tags dialog. |
| `kbFolderLabel(article)` | 1350 | Which folder an article is in, shown on the card at "All articles". |
| `kbRowIsShortcut(articleId)` | 1368 | Returns true when the article is shown in the current folder by a shortcut, because its real folder is a different one. |
| `openExceptionsModal()` | 1382 | Everything with its OWN permissions rather than its parent's. |
| `closeExceptionsModal()` | 1410 | Closes the permission exceptions dialog. |
| `openArticlePermModal()` | 1415 | The article being read. Its own rules, not its folder's. |
| `openPermModal(type, id, name)` | 1421 | Opens the "Who can see this" dialog for a folder or an article and loads its access list. |
| `closePermModal()` | 1433 | Closes the permissions dialog and reloads the folders and articles, since visibility may have changed. |
| `permLoad()` | 1443 | Fetches the access list and mode for the current permissions target and draws the inherited rules and the list. |
| `renderInherited(d)` | 1480 | What this inherits, shown READ-ONLY. |
| `renderPermList()` | 1516 | Draws the access list for the current target, with the explanation and empty state worded for open or restricted mode. |
| `permSetMode(askIfWiping)` | 1544 | Save the two tickboxes. |
| `permRemove(entryId, name)` | 1582 | Take somebody off the list. |
| `permAdd(type, id)` | 1602 | Adds a person or group to the current target's access list and reloads it. |
| `permPost(payload)` | 1614 | Posts a permissions change for the current target to permissions.php, toasting any error; returns the reply or null on failure. |
| `permSearch()` | 1635 | Searches people and groups to add to the access list, debounced and only from two characters. |
| `loadTags()` | 1655 | Fetches all knowledge tags and redraws the tag filters in the sidebar. |
| `loadArticles(search = '', tagIds = [])` | 1670 | Load articles |
| `renderTagFilters()` | 1736 | Render tag filters in sidebar |
| `toggleTagFilter(tagId)` | 1754 | Toggle tag filter |
| `kbVisibleArticleIds()` | 1810 | The article ids in the order they are ON SCREEN. |
| `kbRowEl(id)` | 1817 | Returns the on-screen row element for an article id. |
| `kbRenderSelection()` | 1822 | Repaint ticks, row highlighting and the bar from the Set. |
| `kbSetSelection(ids, { anchor = null, focus = null } = {})` | 1835 | Replaces the selection with the given article ids, optionally moving the anchor and the focus, and repaints. |
| `kbSelectRangeTo(id, { add = false } = {})` | 1844 | Everything between the anchor and `id`, as displayed. |
| `kbToggleOne(id)` | 1857 | Toggles one article in or out of the selection and makes it the anchor and the focused row. |
| `kbCheckboxClick(e, id)` | 1868 | A click on the tick itself. Returns nothing — the tick is the mechanism, so this never opens anything. |
| `kbRowSelectClick(e, id)` | 1882 | A click on the row itself. Returns true if it was handled as a SELECTION, in which case the caller must not open the article. |
| `kbRowClick(e, id)` | 1890 | Every article row's onclick. |
| `clearArticleSelection()` | 1896 | Clears the selection, the anchor and the focus. |
| `selectAllVisibleArticles()` | 1904 | Selects every article row currently on screen. |
| `kbMoveFocus(delta, { extend = false, keepSelection = false } = {})` | 1916 | Moves the keyboard focus up or down a row, either selecting just that row, extending the range from the anchor, or keeping the selection as it is. |
| `kbSelectionKeydown(e)` | 1943 | ⚠️ A DOCUMENT-LEVEL KEY HANDLER IN A MODULE THAT CONTAINS A RICH TEXT EDITOR has to be paranoid about when it is allowed to act. Swallowing Space or Ctrl+A while somebody is writing an article would be a far worse bug than the one this fixes, so the guards come first and refuse by default. |
| `kbSelectBox(id)` | 1972 | The tick, rendered the same way in every view that has rows. |
| `renderBulkBar()` | 1983 | Shows the bulk-action bar with the selected count while anything is selected and refreshes its folder list, or hides it. |
| `kbRenderBulkFolders()` | 2000 | "Move them to…" — the folder list, rebuilt whenever the bar appears because folders can be created while a selection is held. |
| `walk(parent, depth)` | 2010 | Walks the folders from a parent, adding an indented option for each one to the bulk "Move" list in name order. |
| `applyBulkMove()` | 2031 | Move everything selected into one folder. |
| `applyBulkAudience()` | 2069 | Sets the chosen audience on every selected article in one call to knowledge_bulk_audience.php and reports any articles that could not be changed. |
| `renderArticleList()` | 2112 | Render article list |
| `debounceSearch()` | 2214 | Debounced search |
| `viewArticle(articleId)` | 2222 | View article detail |
| `renderArticleDetail()` | 2244 | Render article detail |
| `openCreateArticle()` | 2328 | Open create article view |
| `editCurrentArticle()` | 2355 | Edit current article |
| `toggleEditorPopout()` | 2407 | Per-analyst editor popout state — same localStorage pattern the tickets inbox uses for its full-screen toggle. The CSS does all the layout; this just flips a class on .knowledge-container. |
| `applyEditorPopoutFromPref()` | 2415 | Restores the editor pop-out layout from the value saved in localStorage. |
| `saveAsNewVersion()` | 2424 | Save as new version |
| `saveArticle()` | 2479 | Save article |
| `deleteCurrentArticle()` | 2526 | Delete current article |
| `toggleRecycleBin()` | 2555 | Recycle Bin functions |
| `loadRecycleBin()` | 2580 | Lists the archived articles in the main pane with their retention notice and View, Restore and Delete forever buttons. |
| `restoreArticle(id)` | 2636 | Restores an archived article and refreshes the tags and the recycle bin. |
| `hardDeleteArticle(id, title)` | 2659 | Asks for confirmation, then permanently deletes an archived article and refreshes the tags and the recycle bin. |
| `viewArchivedArticle(id)` | 2684 | Shows an archived article read-only in a dialog. |
| `closeArchivedArticleModal()` | 2711 | Closes the archived article dialog. |
| `cancelEdit()` | 2717 | Cancel edit |
| `syncArticleUrl(view)` | 2754 | Keep the address bar on the article you are actually looking at. |
| `showView(view)` | 2791 | Show/hide views |
| `addTag(tagName)` | 2821 | Tag input functions |
| `removeTag(tagName)` | 2830 | Removes a tag from the article being edited. |
| `renderSelectedTags()` | 2836 | Draws the tags chosen for the article being edited, each with a remove button. |
| `showTagSuggestions(query)` | 2850 | Shows existing tags matching what has been typed, plus an option to create a new tag when there is no exact match. |
| `hideSuggestions()` | 2882 | Hides the tag suggestions list. |
| `jsAttr(value)` | 2900 | A JS string literal that is safe INSIDE a double-quoted HTML attribute. |
| `escapeHtml(text)` | 2926 | Escapes text for use as HTML content; does not escape quotes, so it is not safe on its own inside an attribute (use jsAttr there). |
| `toggleShareDropdown()` | 2934 | Share dropdown functions |
| `closeShareDropdownOnClickOutside(e)` | 2947 | Closes the share menu when a click lands outside it. |
| `closeShareDropdown()` | 2956 | Closes the share menu and removes its outside-click listener. |
| `shareArticleLink()` | 2962 | Share article link - copy to clipboard |
| `buildArticlePdf()` | 2987 | Build a searchable jsPDF document from the current article |
| `ensureSpace(needed)` | 3042 | Starts a new PDF page when the next block would run past the bottom margin. |
| `printLines(lines, x, lineH)` | 3047 | Print wrapped lines one-by-one, adding pages as needed |
| `renderNode(node)` | 3059 | Writes one node of the article body into the PDF - text, headings, paragraphs and lists - adding pages as needed. |
| `shareArticlePdf()` | 3177 | Export article as PDF |
| `shareArticleBoth()` | 3186 | Open email share modal with both link and PDF options |
| `closeShareEmailModal()` | 3202 | Closes the share-by-email dialog. |
| `sendShareEmail()` | 3207 | Send share email |
| `blobToBase64(blob)` | 3270 | Convert blob to base64 |
| `formatDate(dateStr)` | 3341 | Server-stamped UTC timestamps (created/modified/archived). Parse as UTC and render in the analyst's chosen zone so the calendar day is correct locally. |
| `openAiChat()` | 3350 | Opens the AI chat panel and focuses its input. |
| `closeAiChat()` | 3357 | Closes the AI chat panel. |
| `askAi()` | 3363 | Sends the typed question to ai_chat.php and adds the answer, or the error, to the chat panel. |
| `formatAiResponse(text, articlesList)` | 3439 | Turns the AI's answer into HTML - quoted article titles become links to the articles, and simple Markdown becomes bold, italics, code, paragraphs and lists. |
| `kbFolderOptions(selectedId)` | 3544 | Returns the option HTML for a folder picker, indented by depth with the given folder pre-selected. |
| `walk(parent, depth)` | 3547 | Walks the folders from a parent, adding an indented option for each one in name order. |
| `kbMoveCurrentArticle()` | 3562 | Opens the move dialog for the article being read, pre-selected to its current folder. |
| `kbCloseMoveModal()` | 3574 | Closes the move dialog. |
| `kbConfirmMoveArticle()` | 3580 | Moves the article being read to the chosen folder through the same action as a drag. |
| `mountEditorDocuments()` | 3604 | The documents panel inside the EDITOR — where attaching actually happens. |
| `kbHelpCentreHelpUrl()` | 3653 | The help page about documents in the Help Centre (GH #177). |
| `openAuditModal(type, id, name)` | 3669 | Opens the history dialog for a folder or an article and loads its entries. |
| `closeAuditModal()` | 3683 | Closes the history dialog. |
| `openArticleAuditModal()` | 3690 | The current article's history, from the article itself. |
| `auditLoad()` | 3696 | Fetches the history entries for the folder or article in the history dialog and draws them. |
| `renderAuditEntry(e)` | 3725 | One line of history. |
| `renderAuditDetail(e)` | 3757 | The detail blob, in words where we understand it. |
| `principalLabel(raw)` | 3792 | "team:21" -> "Team 21" is useless; resolve what we can from what is loaded. |
| `formatDateTime(dateStr)` | 3807 | A stored timestamp as a date AND a time in the reader's zone. |

## API endpoints it calls

- [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md)
- [api/system/get_user_preference.php](/code/files/api/system/get_user_preference.php.md)
- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)
- [api/knowledge/knowledge_bulk_audience.php](/code/files/api/knowledge/knowledge_bulk_audience.php.md)

## Loaded by

- [knowledge/index.php](/code/files/knowledge/index.php.md)

## Traps

> [!WARNING]
> **Line 3440:** escape the WHOLE answer first, then add markup. The answer is model

