# includes/typed_fields.php

> PHP - 551 lines

TypedFields — the shared engine for user-defined, strongly-typed fields.

One definition somewhere says "this thing has a field called Warranty Expiry,
and it is a date". One value row holds the answer in a column matching its
type. This file owns everything between those two facts: what the types are,
how a raw input becomes a stored value, what counts as valid, and how the
required rule works.

WHAT THIS FILE DOES NOT OWN
---------------------------
- **Which fields a thing has.** Each module answers that with its own query
  and hands the result in as canonical definitions (see defFrom* below). The
  CMDB resolves them from a class; Assets will resolve them from the field
  sets attached to a type plus any attached to the individual asset. Trying to
  generalise that query would force one attachment model on both.
- **Authorisation.** Multi-tenancy gates, module access and actor scope stay
  in the calling service. A security rule living in a generic layer degrades
  silently, and not toward safety. CmdbService::assertObjectRefsInCompany()
  is the live example: this file checks a reference EXISTS and points at the
  right kind of thing; only CmdbService decides whether the actor may see it.
- **Reading values for display.** Not yet — see the note on readValues in
  docs/design/flexible-asset-fields.md §4.4. The CMDB's read path joins for
  reference labels under a tenancy filter, which is module business.

SHAPE OF A DEFINITION (what callers pass in)
--------------------------------------------
  [
    'id'         => int,      // the definition row's id
    'key'        => string,   // stable machine key (property_key / field_key)
    'label'      => string,   // display name, used in error messages
    'type'       => string,   // one of self::TYPES
    'required'   => bool,
    'config'     => array,    // per-type settings; [] where a module has none
    'ref_kind'   => ?string,  // 'ref' only: which registry entry
    'ref_target' => ?int,     // 'ref' only: optional narrowing (e.g. a class id)
  ]

SHAPE OF A SCHEMA (where the values live)
-----------------------------------------
  [
    'value_table'  => 'cmdb_object_properties',
    'owner_column' => 'object_id',
    'def_column'   => 'property_id',
    'columns'      => [ storage class => column name ],   // see STORAGE
    'options'      => callable(PDO, int `$defId`): string[],// dropdown values
    'unknown_hint' => string,                             // appended to the
                                                          // unknown-key error
  ]

- See: [docs/design/flexible-asset-fields.md](/code/files/docs/design/flexible-asset-fields.md.md)
## Classes

- [TypedFields](/code/classes/TypedFields.md) (class, line 57)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 489 |  | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 496 |  | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 514 |  | read [users](/code/tables/users.md) |  |
| 521 |  | read [users](/code/tables/users.md) |  |
| 537 |  | read [assets](/code/tables/assets.md) |  |
| 545 |  | read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md)

**Included by:** [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md)

