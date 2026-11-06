<?php
// Its own small file so a page can draw provider badges without loading every
// provider class (Tickets -> Settings). create() names the classes; they are loaded
// by includes/messaging/messaging.php, which is the only caller of create().

/**
 * Which service a messaging channel talks through (4.0 - until then a switch on the
 * string, and a JavaScript ternary chain on the Messaging tab that repeated it).
 *
 * The value is what messaging_channels.provider stores, so a case's value must never
 * change. match() makes adding a provider answer every method below - including which
 * class serves it, so the factory can no longer silently miss one.
 *
 * TRAP: compare a stored value only after turning it into a case (fromChannel /
 * tryFrom); an enum compared with a string is silently false.
 */
enum MessagingProviderKind: string
{
    case Twilio     = 'twilio';
    case Meta       = 'meta';
    case Freeitsm   = 'freeitsm';    // the website chat widget - no third party
    case Slack      = 'slack';
    case Telegram   = 'telegram';
    case Teams      = 'teams';
    case Mattermost = 'mattermost';

    /**
     * A channel row's provider; no provider saved is Twilio, as before. An unknown
     * one throws, with the message the old switch gave.
     *
     * @param array $channel  a messaging_channels row (only 'provider' is read)
     */
    public static function fromChannel(array $channel): self
    {
        $name = (string)($channel['provider'] ?? 'twilio');
        return self::tryFrom($name) ?? throw new Exception('Unknown messaging provider: ' . ($name !== '' ? $name : '?'));
    }

    /**
     * The provider object for a channel.
     *
     * @param array $channel  the messaging_channels row, credentials already decoded
     */
    public function create(array $channel): MessagingProvider
    {
        return match ($this) {
            self::Twilio     => new TwilioProvider($channel),
            self::Meta       => new MetaCloudProvider($channel),
            self::Freeitsm   => new FreeitsmProvider($channel),
            self::Slack      => new SlackProvider($channel),
            self::Telegram   => new TelegramProvider($channel),
            self::Teams      => new TeamsProvider($channel),
            self::Mattermost => new MattermostProvider($channel),
        };
    }

    /** Can an administrator add one on the Messaging tab? Web chat channels are made by Tickets -> Web chat. */
    public function isUserCreatable(): bool
    {
        return $this !== self::Freeitsm;
    }

    /** The badge text on the Messaging tab. A product name, so not translated. */
    public function label(): string
    {
        return match ($this) {
            self::Twilio     => 'Twilio',
            self::Meta       => 'Meta',
            self::Freeitsm   => 'Web chat',
            self::Slack      => 'Slack',
            self::Telegram   => 'Telegram',
            self::Teams      => 'Teams',
            self::Mattermost => 'Mattermost',
        };
    }

    /**
     * The badge's colours - fixed, because a badge is data (the same in light and dark).
     *
     * @return array{background:string,color:string}
     */
    public function badgeColours(): array
    {
        return match ($this) {
            self::Twilio     => ['background' => '#e8f5e9', 'color' => '#2e7d32'],
            self::Meta       => ['background' => '#e3f2fd', 'color' => '#1565c0'],
            self::Freeitsm   => ['background' => '#eceff1', 'color' => '#455a64'],
            self::Slack      => ['background' => '#f3e5f5', 'color' => '#6a1b9a'],
            self::Telegram   => ['background' => '#e1f5fe', 'color' => '#0288d1'],
            self::Teams      => ['background' => '#ede7f6', 'color' => '#4527a0'],
            self::Mattermost => ['background' => '#e6f4ea', 'color' => '#1e7e34'],
        };
    }

    /**
     * Every provider for the browser: value => label and colours.
     *
     * @return array<string,array{label:string,background:string,color:string}>
     */
    public static function forJs(): array
    {
        $out = [];
        foreach (self::cases() as $p) $out[$p->value] = ['label' => $p->label()] + $p->badgeColours();
        return $out;
    }
}
