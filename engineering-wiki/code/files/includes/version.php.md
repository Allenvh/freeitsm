# includes/version.php

> PHP - 43 lines

The one place FreeITSM's version number is written down.

Requested in discussion #92: an operator should be able to open a screen and
read which version they are running, rather than "whatever main looked like on
some Tuesday". Bumped as step 2 of the release procedure in RELEASING.md, in
the same commit that gets tagged.

⚠️ This must live in a file the application SHIPS. It must never move into
config.php: that file belongs to the operator, Docker copies over it, and
putting anything the application depends on there took every page down in #129.

The number here is the source of truth for the System screen, Debug Tools, and
anywhere else the version is shown. It is not read from git, because a Docker
image has no git history to read.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [freeitsmVersion()](/code/functions/freeitsmVersion.md) | 24 | The version as displayed: "1.0.0". |
| [freeitsmVersionTag()](/code/functions/freeitsmVersionTag.md) | 33 | The matching git tag, "v1.0.0" — the tag is v-prefixed, the version is not. Useful for linking to the release notes on GitHub. |
| [freeitsmReleaseUrl()](/code/functions/freeitsmReleaseUrl.md) | 39 | Link to this version's release notes on GitHub. |

## How it hangs together

**Included by:** [forms/approvals.php](/code/files/forms/approvals.php.md), [system/debug-tools/includes/tool-page.php](/code/files/system/debug-tools/includes/tool-page.php.md), [system/index.php](/code/files/system/index.php.md)

