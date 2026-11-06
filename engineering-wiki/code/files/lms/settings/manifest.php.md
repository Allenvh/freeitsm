# lms/settings/manifest.php

> PHP - 65 lines

LMS — settings manifest.

The LMS is the one module whose capability is not really about a settings TAB. Its
whole management surface — authoring courses, running learning groups, assigning
training, seeing everyone's progress — sits behind a single capability, and plain
module access means "you may take the training assigned to you" (the My Courses page).
So the umbrella is the capability, and the settings page has one tab behind it.

Declared here anyway, rather than special-cased, so that every module's capabilities
come from the same place and the Roles picker has a single source. See
includes/capabilities.php.

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md)

