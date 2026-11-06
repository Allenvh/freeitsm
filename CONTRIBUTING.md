# Contributing to FreeITSM

Pull requests are welcome. Please read **[Contributing](https://github.com/edmozley/freeitsm/wiki/Contributing)** on the wiki first - it is the checklist every pull request is reviewed against, and every rule on it comes from a real review. Give it to your AI coding assistant too.

The short version:

1. **Agree the design first.** Open an issue for anything bigger than a fix. The design goes on the wiki, and the code uses its names.
2. **Base it on current `main`**, one feature per pull request.
3. **Upgrades must be invisible** until somebody chooses a new option.
4. **Nothing runs from a URL unless it is meant to.** Every file in `tests/` and `scripts/` starts with the `PHP_SAPI !== 'cli'` guard. Check access, write access, the company and the API key's permission before writing anything.
5. **Schema goes in `database/freeitsm.sql` and `includes/db_verify_schema.php`**, then run `php scripts/gen_db_verify_indexes.php`. Never edit `includes/db_verify_indexes.php` by hand.
6. **One write path, in `includes/services/`**, shared by the UI and the API. Side effects run after the commit, and errors are never swallowed.
7. **Tests run against a real database.** Create `ZZ`-prefixed fixtures, delete them by the ids you created, and never fire real workflows or emails.
8. **New strings go in `lang/en` only**, and existing English wording stays as it is.

Every pull request gets a verdict - **merge**, **merge with fixes** (your commit under your name, then mine), or **rewrite** (credited) - and a written review. Past reviews are linked from the wiki page.
