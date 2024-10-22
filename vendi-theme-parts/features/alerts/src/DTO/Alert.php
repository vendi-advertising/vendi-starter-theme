<?php

namespace Vendi\Theme\Feature\Alert\DTO;

use DateTimeImmutable;
use Vendi\Theme\DTO\SimpleLink;
use Vendi\Theme\Feature\Alert\Enum\AlertAppearanceEnum;
use Vendi\Theme\Feature\Alert\Enum\AlertTypeEnum;

class Alert
{
    private function __construct(
        public readonly int $id,
        public readonly AlertAppearanceEnum $appearance,
        public readonly ?string $headline,
        private readonly ?string $copy,
        public readonly AlertTypeEnum $type,
        public readonly DateTimeImmutable $startDate,
        public readonly DateTimeImmutable $endDate,
        public readonly int $version,
        public readonly bool $dismissible,
        public readonly ?SimpleLink $callToAction,
    ) {}

    public function hasText(): bool
    {
        return $this->headline || $this->copy;
    }

    public function getCopyHtmlEscaped(): ?string
    {
        if ( ! $this->copy) {
            return null;
        }

        return wp_kses(
        // We are collapsing all paragraphs and/or breaks into a single line
            $this->copy, [
                'a'      => [
                    'href'   => [],
                    'title'  => [],
                    'target' => [],
                ],
                'strong' => [],
                'em'     => [],
            ],
        );
    }

    public static function createFromPost($post): self
    {
        $callToAction = null;
        if (($link = get_field('more_info_link', $post)) && is_array($link)) {
            $callToAction = SimpleLink::tryCreate($link);
        }

        // Attempt parsing the string to an enum, if it fails, pick a default
        $appearance = AlertAppearanceEnum::tryFrom(get_field('site_appearance', $post)) ?: AlertAppearanceEnum::AboveGlobalSiteHeader;
        $type       = AlertTypeEnum::tryFrom(get_field('alert_type', $post)) ?: AlertTypeEnum::Info;

        return new self(
            id: $post->ID, appearance: $appearance, headline: get_field('headline', $post), copy: get_field('copy', $post), type: $type, startDate: new DateTimeImmutable(get_field('start_date', $post)), endDate: new DateTimeImmutable(get_field('end_date', $post)), version: (int)get_field('alert_version', $post), dismissible: (string)get_field('dismissible', $post) === 'yes', callToAction: $callToAction,
        );
    }
}
