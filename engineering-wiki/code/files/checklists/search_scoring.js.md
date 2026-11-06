# checklists/search_scoring.js

> JavaScript - 56 lines

FreeITSM SOP / Checklists: Template Search & Relevance Scoring Utility

Provides multi-token search and field-weighted relevance scoring across:
- title / label (exact > prefix > substring)
- keywords (exact > substring)
- category (exact > substring)
- description

## Functions

| Function | Line | What it does |
|---|--:|---|
| `scoreChecklistTemplate(item, query)` | 17 | Matches a template against a search (every word must appear somewhere) and scores it, title hits highest, then keywords, category and description. |

## Loaded by

- [checklists/index.php](/code/files/checklists/index.php.md)
- [tickets/index.php](/code/files/tickets/index.php.md)
- [workflow/editor.php](/code/files/workflow/editor.php.md)

