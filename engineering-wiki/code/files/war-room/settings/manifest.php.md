# war-room/settings/manifest.php

> PHP - 44 lines

War room — settings manifest. See includes/capabilities.php.

USING the war room is deliberately plain module access — during an outage you
do not want a permission sitting between an analyst and the only chat that
still works, and that includes creating a channel or opening a DM. What IS
gated is the pair of decisions an administrator owns: how long messages are
kept, and which AI provider gets shown the transcript.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

