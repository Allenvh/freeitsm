# process-mapper/includes/shapes.php

> PHP - 29 lines

Built-in shape primitives for Process Mapper step types.

Each entry: shape key => default canvas size [w, h] for a step of that shape.
Display names are i18n keys: process-mapper.shapes.<key>.
Geometry (clip-path / border-radius) lives in assets/css/process-mapper.css
as [data-shape="<key>"] rules. The "cloud" shape additionally needs the
<clipPath id="pmShapeCloud"> SVG defs block present on the page.

Single source of truth shared by the Settings page (shape picker) and the
editor — index.php exports this array to JS as window.SHAPE_SIZES so
`stepDims()` can size new canvas steps based on the chosen type's shape.

## How it hangs together

**Included by:** [process-mapper/index.php](/code/files/process-mapper/index.php.md), [process-mapper/settings/index.php](/code/files/process-mapper/settings/index.php.md)

