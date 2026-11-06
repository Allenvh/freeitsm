# includes/services/handover_templates.php

> PHP - 583 lines

Handover document templates (discussion #56).

Lets an administrator lay out the equipment handover document: which sections
appear, in what order, what they say, and which columns the equipment table
shows — with merge codes for the person, the date and so on.

─────────────────────────────────────────────────────────────────────────────
 WHY BLOCKS AND NOT A FREE-FORM EDITOR
─────────────────────────────────────────────────────────────────────────────
The obvious "designer" is a rich-text canvas you type a document into. It is
the wrong tool here for one structural reason: the middle of this document is
a REPEATING REGION. One row per asset, count unknown until it renders. Every
WYSIWYG editor either cannot express that or expresses it as a loop the
administrator has to write, which is not a designer any more.

So the document is a list of blocks from a fixed catalogue. You reorder them,
switch them off, edit the words in the ones that have words, and choose the
columns on the one that is a table. That covers what people actually change
about a handover form, and it means:

  - the stored template is always renderable (nothing arbitrary is kept)
  - the equipment table stays a real table rather than hand-written markup
  - an added block type appears in the designer for free

⚠️ NOTHING FREE-FORM IS EVER STORED AS MARKUP. Editable text is plain text,
escaped at render. Merge codes are substituted AFTER escaping, so a person
whose display name contains markup cannot inject anything into a document
that gets emailed. See `renderBlocks()`.

## Classes

- [HandoverTemplates](/code/classes/HandoverTemplates.md) (class, line 35)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 115 | `HandoverTemplates::customColumns` | read [asset_fields](/code/tables/asset_fields.md) |  |
| 241 | `HandoverTemplates::listAll` | read [asset_handover_templates](/code/tables/asset_handover_templates.md) |  |
| 263 | `HandoverTemplates::load` | read [asset_handover_templates](/code/tables/asset_handover_templates.md) |  |
| 298 | `HandoverTemplates::effective` | read [asset_handover_templates](/code/tables/asset_handover_templates.md) |  |
| 330 | `HandoverTemplates::save` | **update** [asset_handover_templates](/code/tables/asset_handover_templates.md) (`name`, `blocks`, `is_active`, `updated_datetime`) |  |
| 336 | `HandoverTemplates::save` | **insert** [asset_handover_templates](/code/tables/asset_handover_templates.md) (`name`, `blocks`, `is_active`, `created_datetime`, `updated_datetime`) |  |
| 355 | `HandoverTemplates::makeDefault` | **update** [asset_handover_templates](/code/tables/asset_handover_templates.md) (`is_default`) |  |
| 356 | `HandoverTemplates::makeDefault` | **update** [asset_handover_templates](/code/tables/asset_handover_templates.md) (`is_default`, `is_active`) |  |
| 366 | `HandoverTemplates::delete` | **delete** [asset_handover_templates](/code/tables/asset_handover_templates.md) |  |

## How it hangs together

**Includes:** [includes/timezone.php](/code/files/includes/timezone.php.md)

**Included by:** [api/assets/email_handover.php](/code/files/api/assets/email_handover.php.md), [api/assets/handover_templates.php](/code/files/api/assets/handover_templates.php.md), [asset-management/handover.php](/code/files/asset-management/handover.php.md)

**Calls:** [fmt_local()](/code/functions/fmt_local.md)

