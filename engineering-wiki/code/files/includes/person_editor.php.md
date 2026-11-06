# includes/person_editor.php

> PHP - 500 lines

The ONE editor for a person (a `users` row: a requester, not an analyst).

WHY THERE IS ONE
----------------
Tickets -> Users and Assets -> Users each grew their own editor, both posting
to api/tickets/save_user.php, and they drifted exactly as includes/users.php
warned field lists would:
  - Assets had no preferred name, password or company;
  - Assets CLEARED a manager the analyst could not see (no option matched, so
    the select posted "no manager") - Tickets had long since guarded that;
  - Assets posted to a Tickets-only endpoint, so an analyst with Assets and
    not Tickets was offered Edit and refused on Save.
Managers (discussion #62) make the reporting line a question of who may read
whose tickets, so two editors disagreeing about it stopped being tolerable.
This is the superset of both, and each screen opens it:

    PersonEditor.open(id | null, { onSaved: async function (savedId) {...} })

WHAT IT MUST KEEP DOING (each one was a bug once)
-------------------------------------------------
 - Directory-owned fields are PER RECORD (managed_fields from the server):
   LDAP owns all seven, an address book five, one with write-back none. They
   are shown disabled and LEFT OUT of the payload - save_user.php refuses the
   whole save if a managed record's body mentions an owned key.
 - A manager the analyst cannot see is kept: the key is omitted, never sent
   as "no manager".
 - Company: blank means "work it out from their email" for a NEW person
   (key omitted) and "no company" for an existing one (sent). Hidden, and never
   sent, on a single-company install.
 - The address book is a SEPARATE outcome from the save and is always said
   out loud: written / conflict (stays up) / failed (stays up).
 - Absent means "don't touch" on the server, so only shown fields are sent.
 - JSON Content-Type on every write, or request_guard.php refuses it.

Deliberately NOT here: Delete (Tickets -> Users) and Deactivate/Reactivate
(Assets -> Users) stay on their screens' own buttons.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [personEditorRender()](/code/functions/personEditorRender.md) | 44 | Print the editor's markup, styles and script. Call once, inside <body>. |

## How it hangs together

**Includes:** [includes/users.php](/code/files/includes/users.php.md)

**Included by:** [asset-management/users.php](/code/files/asset-management/users.php.md), [tickets/users.php](/code/files/tickets/users.php.md)

**Calls:** [t()](/code/functions/t.md)

