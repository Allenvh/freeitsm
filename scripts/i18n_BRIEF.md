# Translation brief — read this before translating a chunk

You are translating UI strings for **FreeITSM**, a self-hosted IT service desk
used by small internal IT teams and managed service providers. The reader is an
IT analyst at work, or a colleague of theirs raising a request.

Your job is the **values**. Never the keys.

---

## The hard rules

A chunk that breaks any of these is rejected mechanically and has to be redone,
so they are worth more than any stylistic judgement.

1. **Same keys, same order.** Output every key you were given, in the order you
   were given, once each. Do not add, drop, rename or reorder a key.
2. **Placeholders survive exactly.** `{name}`, `{n}`, `{error}`, `%s`, `%d` are
   filled in at runtime. The same tokens must appear in your translation,
   spelled identically. You may move them so the sentence reads naturally.
   🔴 **`%s` and `%d` substitute by POSITION**, so if English has `%d` then
   `%s`, yours must too — swapping them prints the count where the name goes.
3. **Keep HTML exactly** — the same tags in the same order. Translate the words
   between them, never the tags or attributes.
4. **Keep line breaks.** A `\n` in the English means a real line break; keep the
   same number.
5. **Never leave a non-empty string empty.** If English has a value, so must you.

   > ✅ **You will no longer be given a key whose English is blank.** For a long
   > time you were, and it was the most-failed rule here: broken eleven times,
   > across eleven translators and several languages, always on one of the seven
   > keys in the product whose English is empty. Every translator had checked
   > their own work and reported it correct, and two of them named the offending
   > key and quoted the command they had run.
   >
   > 🔑 They were not careless. **The Write tool strips trailing whitespace**, so
   > a line whose correct content is `key` + TAB + nothing cannot survive being
   > written — the tab is removed after you compose the line and before it
   > reaches the disk. No amount of care could have fixed that, which is what
   > eleven attempts showed.
   >
   > So the pipeline stopped asking. `i18n_chunk.php` withholds those keys and
   > `i18n_merge.php` fills them, since a blank English value has exactly one
   > correct translation in every language. If you ever *do* see a blank English
   > value, something upstream is wrong — say so in your reply rather than
   > guessing.
6. **UTF-8, written directly.** Never HTML entities, never escapes for letters.

## What to leave in English

- HTML tags and attributes, anything in `<code>` or backticks
- File names, paths, PHP identifiers, setting keys, URLs
- **Protocol and product names**: IMAP, SMTP, OAuth, API, LDAP, CardDAV, SLA,
  Slack, Microsoft 365, Jira, Azure DevOps, Tika
- A term where your language's IT professionals genuinely use the English word

## 🔑 The rule that matters most for Indian languages

**Indian IT works in English.** An analyst in Bengaluru or Chennai speaks Hindi,
Tamil or Kannada at their desk and says *ticket*, *asset*, *dashboard*, *server*,
*backup*, *SLA*, *login* — in English, inside the sentence. That is not laziness
or borrowing; it is how the register actually works.

So:

- **Prefer the English technical noun, in your own script where that is the
  convention, over a coined or Sanskritised equivalent.** A freshly invented
  compound for "dashboard" reads as *worse* than the English to the person
  actually using this software. It makes the product look translated rather
  than usable.
- **Translate the connective language** — the verbs, the instructions, the
  explanations, the error messages. That is where a translation earns its keep:
  *"{n} tickets could not be imported because the address book did not answer"*
  should read naturally, with *tickets* and *address book* rendered the way an
  IT team would actually say them.
- **When in doubt, leave it English.** A familiar English word beats a correct
  but unfamiliar neologism. This instruction is deliberate and you will not be
  marked down for following it.
- ⚠️ **Do not mix registers within a file.** Pick a consistent level of
  Englishness for the technical nouns and hold it. Two screens in two registers
  is worse than either one on its own.

> If your language has a genuinely established, widely-used term — one an IT
> worker would recognise instantly — use it. The rule above is about *inventing*
> terms, not about refusing real ones.

## Tone

- **Plain and direct.** Short sentences. Say what the thing does.
- **Address the reader as a competent adult** at work. Not chatty, not formal to
  the point of stiffness. Use whatever the polite-neutral register is in your
  language — the one business software uses.
- **Buttons are short.** One or two words. If English says *Save*, do not
  produce a phrase.
- **Error messages say what happened and what to do.** Never blame the reader.
- **Match the English length where you can.** These are buttons, labels, table
  headers and menu items in a dense interface; a label three times longer than
  the English will be cut off or will break the layout.

## Things that are not what they look like

- **A value identical to English is sometimes right** — "Email", "OK", "SLA", a
  product name. Do not translate something just to avoid leaving it alone.
- **`{n} contacts` has no plural form available.** There is no pluralisation in
  this system, so English says things like *"Contacts read: 1"* on purpose.
  Do not restructure a string to add agreement the code cannot supply — put the
  number where your language tolerates it for any count.
- **A key with blank English** is a blank column header or spacer. Keep it blank,
  and **keep the tab** in the output line — a line with no tab is rejected.

## 🔴 How to write the file — this is not style advice

Two agents on this pipeline were **killed outright** by the 64,000 output-token
limit, both before writing a single line of their file. Neither had done
anything unusual; they simply produced the same content more than once.

- **Write each output file exactly ONCE, in a single write.** Do not write it,
  read it back, and write it again. A second full write of the same content is
  what exhausts the budget, and you are killed without warning and without
  output — your work is lost entirely, not truncated.
- **Never echo translated lines in your reply.** Your final message is one line
  per file: the filename and the number of lines. Nothing else.
- **To correct a line after writing, edit that line.** Never rewrite the whole
  file for a single fix.
- **Use the Write tool, not a shell heredoc.** On the 2026-09-23 run a
  `cat > file <<'EOF'` heredoc carrying a 171-line Malayalam chunk failed with
  an unbalanced-quote parse error at roughly **28 KB of command text** - Git
  Bash truncates the argument at about that size, and the failure looks like a
  quoting mistake in your content rather than a length limit. Any chunk in a
  script that expands (Malayalam, Hindi, Gujarati, Ukrainian) can cross 28 KB.

> 🔑 These three rules are why a chunk can be as large as it is. Measured on the
> 2026-09-22 runs: chunks of ~45 KB completed comfortably **with** these rules,
> while ~60 KB chunks died **without** them. The size limit the orchestrator
> uses assumes you are following this section.

## The output format

One key per line: **the key, a tab, your translation.** Nothing else — no
header, no numbering, no commentary, no quotes around values.

```
calendar.filters.title	<your translation>
calendar.filters.clear	<your translation>
```

If a value needs a line break, write it as a literal `\n`, exactly as the
English does.

Write the file to the path you are given and nothing else. **Do not edit any
file under `lang/`** — the orchestrator verifies your output and merges it.
