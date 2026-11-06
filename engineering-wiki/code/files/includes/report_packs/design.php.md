# includes/report_packs/design.php

> PHP - 304 lines

Report Packs: the design document - its shape, its starter, and the check every save goes through.

A pack's design is one JSON document (report_packs.design). The designer reads
and writes it whole; the layout engine (assets/js/report-packs/) turns it into
pages; nothing else interprets it.

RICH TEXT IS NOT HTML
---------------------
Text boxes, the header, the footer and the cover are stored as a small document
model - paragraphs of runs - rather than as HTML:

  paragraph  {t: p|h1|h2|h3|li|img, a: left|center|right|justify,
              l: ul|ol (lists), lv: 0-4 (indent), r: [runs], w: mm (img)}
  run        {x: text, b, i, u, s: bool, c: '#rrggbb', hl: '#rrggbb',
              sz: points, fld: page|pages|date_from|date_to|today|title|company}

Two reasons. The layout engine needs exactly this to break lines identically on
screen and in the PDF, so HTML would only be parsed into it anyway. And there is
then no markup to sanitise: every field below is checked for type and range, the
renderer writes text with textContent, and a run cannot carry a script or a
remote image because there is nowhere to put one.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rpPara()](/code/functions/rpPara.md) | 44 | A paragraph of plain text, for the starter design. |
| [rpDefaultDesign()](/code/functions/rpDefaultDesign.md) | 54 | The design a new pack starts from: a header like Enrique's, a footer, one heading. |
| [rpColour()](/code/functions/rpColour.md) | 97 | A #rrggbb colour in lower case, or `$default` when the value is not one. |
| [rpNum()](/code/functions/rpNum.md) | 109 | A number clamped between `$min` and `$max`, or `$default` when the value is not numeric. |
| [rpStr()](/code/functions/rpStr.md) | 119 | A string cut to `$max` characters, with control and zero-width characters removed. |
| [rpPick()](/code/functions/rpPick.md) | 135 | The value when it is one of `$allowed`, else `$default`. |
| [rpCleanDoc()](/code/functions/rpCleanDoc.md) | 146 | Check a rich-text document. Anything unrecognised is dropped, not kept. |
| [rpCleanDesign()](/code/functions/rpCleanDesign.md) | 193 | Check a whole design from the browser. Returns the cleaned design; throws InvalidArgumentException when it is not a design at all or is too large. |

## How it hangs together

**Includes:** [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md)

**Included by:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [rpCleanOpts()](/code/functions/rpCleanOpts.md), [rpHandlers()](/code/functions/rpHandlers.md), [t()](/code/functions/t.md)

