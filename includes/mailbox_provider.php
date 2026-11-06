<?php
/**
 * Where a ticket mailbox's email comes from (4.0).
 *
 * Until 4.0 the three providers were the strings 'microsoft', 'google' and 'imap',
 * spelled out in a dozen files, each with its own `?? 'microsoft'` default - and the
 * Mailboxes tab drew its coloured badge with a hand-written if/else in JavaScript
 * that repeated what the PHP knew. This is the one place that says what a provider
 * is; the tab's dropdown and badge are generated from it.
 *
 * The value is what target_mailboxes.provider stores, so a case's value must never
 * change. Calendar sync has its own, different list (microsoft, caldav) and is not
 * this.
 *
 * TRAP: an enum compared with a string is silently false. Turn a stored value into a
 * case with fromMailbox() / fromValue() BEFORE comparing; never compare
 * $mailbox['provider'] with MailboxProvider::X directly.
 */
enum MailboxProvider: string
{
    case Microsoft = 'microsoft';
    case Google    = 'google';
    case Imap      = 'imap';

    /**
     * A mailbox row's provider. No provider, or one the code does not know, is
     * Microsoft - exactly what every `?? 'microsoft'` used to say.
     *
     * @param array|null $mailbox  a target_mailboxes row (only 'provider' is read)
     */
    public static function fromMailbox(?array $mailbox): self
    {
        return self::fromValue($mailbox['provider'] ?? null);
    }

    /**
     * A stored provider value, as fromMailbox().
     *
     * @param mixed $value  the stored value - a string, null, or false from fetchColumn()
     */
    public static function fromValue($value): self
    {
        return self::tryFrom((string)$value) ?? self::Microsoft;
    }

    /** Signs in with OAuth (and so has a token to refresh) rather than a password. */
    public function usesOAuth(): bool
    {
        return $this !== self::Imap;
    }

    /** Can run app-only (client credentials) - Microsoft Graph only. */
    public function supportsAppOnly(): bool
    {
        return $this === self::Microsoft;
    }

    /** The badge text on the Mailboxes tab. A product name, so not translated. */
    public function label(): string
    {
        return match ($this) {
            self::Microsoft => 'Microsoft',
            self::Google    => 'Google',
            self::Imap      => 'IMAP',
        };
    }

    /**
     * The badge's colours. Badges are DATA - they mean the same in light and dark -
     * so these are fixed colours, not theme tokens, like every other status badge.
     *
     * @return array{background:string,color:string}
     */
    public function badgeColours(): array
    {
        return match ($this) {
            self::Microsoft => ['background' => '#e3f2fd', 'color' => '#1565c0'],
            self::Google    => ['background' => '#e8f5e9', 'color' => '#2e7d32'],
            self::Imap      => ['background' => '#ede7f6', 'color' => '#5e35b1'],
        };
    }

    /**
     * Every provider for the browser: value => label, colours, OAuth. The Mailboxes tab
     * json_encode()s this, so its JavaScript never spells a provider out.
     *
     * @return array<string,array{label:string,background:string,color:string,oauth:bool}>
     */
    public static function forJs(): array
    {
        $out = [];
        foreach (self::cases() as $p) {
            $out[$p->value] = ['label' => $p->label(), 'oauth' => $p->usesOAuth()] + $p->badgeColours();
        }
        return $out;
    }
}
