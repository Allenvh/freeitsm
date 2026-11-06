# includes/config_requirements.php

> PHP - 152 lines

What config.php is expected to define - the ONE list (GH #129).

config.php is the operator's file: it is edited once and kept, so an upgrade
never adds a line to it. Debug tool D017 reads this list to tell an operator
whether their copy is missing anything, and tests/config-not-load-bearing.php
asserts the list matches both shipped templates (config.php and
docker/config.php) and that every "fallback" really has one in shipped code.
Add a constant to a template without adding it here and that test fails.

🔴 NOTHING HERE, AND NOTHING THAT READS IT, EVER PRINTS A VALUE. Names and
present/absent only - docker/config.php defines a real DB_PASSWORD.

kind:
  required  - the app cannot work without it; there is no default.
  fallback  - shipped code supplies a default when config.php does not.
  optional  - off / unset is a normal, supported state.

where:    which file normally defines it.
fallback: (fallback/optional) the shipped file that copes when it is absent.
without:  what happens when it is absent, in plain words.
add:      the line(s) to add, with a placeholder - never a real value.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [configRequirements()](/code/functions/configRequirements.md) | 30 | Returns every constant config.php is expected to define, keyed by name, with its kind, where it lives, any fallback, what happens without it and the line to add. |
| [configFileShape()](/code/functions/configFileShape.md) | 89 | What a config file DECLARES, read with the tokeniser so commented-out lines do not count. Returns names only: the constants it `define()`s and the functions it declares. Never values. |
| [configRequirementsReport()](/code/functions/configRequirementsReport.md) | 125 | Compare the running state and the operator's config.php with the list. Each row: name, kind, defined (bool), inConfig (bool), status ('ok' \| 'default' \| 'problem'). Names and booleans only. |

## How it hangs together

**Included by:** [api/system/debug-tools/D017_config_completeness.php](/code/files/api/system/debug-tools/D017_config_completeness.php.md)

