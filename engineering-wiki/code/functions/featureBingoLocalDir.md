# featureBingoLocalDir()

Where an install keeps ITS OWN cards - for its own processes, or for a feature it has added to its copy of FreeITSM. Same format, same checks.

Deliberately NOT includes/feature_bingo/cards/: that folder ships with every
release, and in Docker the code is part of the image, so a file added there
is gone after the next update. `local/` is ignored by git and never shipped;
define FEATURE_BINGO_LOCAL_DIR in config.php to put the folder elsewhere -
for Docker, somewhere on a volume.

```php
function featureBingoLocalDir(): string
```

Defined in [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md), line 274.

**Returns:** `string`

## Called from

- [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md) - in [featureBingoCards()](/code/functions/featureBingoCards.md)

