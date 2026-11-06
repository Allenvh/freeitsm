# software/settings/manifest.php

> PHP - 47 lines

Software — settings manifest. See includes/capabilities.php.

One tab, and it MINTS CREDENTIALS: the API keys that software-inventory agents present
when they push data in. Whoever can generate one can feed the inventory; whoever can
revoke one can stop it dead.

That is nothing like being able to browse the software list, which is what module access
gives you — which is exactly why it is split out, even though it leaves the module with a
single capability.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

