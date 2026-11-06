# api/knowledge/permission_model.php

> API endpoint - 187 lines

API Endpoint: the install-wide folder permission model. Actions: get, preview, set

─────────────────────────────────────────────────────────────────────────────
TWO DEFENSIBLE PHILOSOPHIES, NOT A SAFETY DIAL

  containers  You must be able to read EVERY folder above a document. A locked
              cabinet is locked. (Default.)
  filing      Permissions live on documents; folders only organise. The
              nearest rules win and the ones above them do not apply.

Deliberately NOT called strict/loose: that framing reads as secure/insecure,
so nobody would ever choose the second on its merits — and it IS a merit
choice. An MSP whose folders are filing wants the second; anyone with
confidential matters wants the first.

⚠️ INSTALL-WIDE, and it has to be.
  - NOT per folder: mixed models in one tree make "can Bob read this?" depend
    on which model each ancestor happens to be in, which nobody can answer in
    a support call.
  - NOT per company: articles with tenant_id IS NULL are SHARED with every
    company, so a per-company posture would make one document strict for one
    client and loose for another.

⚠️ THIS IS THE HIGHEST-PRIVILEGE ACTION IN THE MODULE. It changes who can read
live documents with no per-document change to point at, so 'preview' exists to
say exactly how many documents move BEFORE anything is written, and the change
itself is audited.
─────────────────────────────────────────────────────────────────────────────

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/permission_model.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson); Permission: Cap::KNOWLEDGE_MANAGE (requireCapabilityJson) |
| Reads | `GET action`, `GET model` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [handleGet()](/code/functions/handleGet.md) | 69 | Returns the install-wide folder permission model in use: 'containers' or 'filing'. |
| [handlePreview()](/code/functions/handlePreview.md) | 90 | How many documents would change hands, and for whom. |
| [handleSet()](/code/functions/handleSet.md) | 171 | Saves the install-wide folder permission model ('filing', anything else meaning 'containers') and writes an audit row naming the old and new model. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 109 | `handlePreview` | read [analysts](/code/tables/analysts.md) |  |
| 116 | `handlePreview` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 128 | `handlePreview` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 146 | `handlePreview` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 176 | `handleSet` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [knowledgeAclHasAnyRows()](/code/functions/knowledgeAclHasAnyRows.md), [knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md), [knowledgeAuditLog()](/code/functions/knowledgeAuditLog.md), [knowledgeFolderPermissionModel()](/code/functions/knowledgeFolderPermissionModel.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

