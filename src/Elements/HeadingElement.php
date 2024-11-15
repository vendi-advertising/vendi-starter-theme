<?php

namespace Vendi\Theme\Elements;

class HeadingElement extends AbstractElement
{
    public function __construct(
        public ?string $tag,
        public ?string $text,
        array $classes = [],
    ) {
        parent::__construct($classes);
    }

    public function render(bool $echo = true): ?string
    {
        $ret = sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            esc_html($this->tag),
            implode(' ', $this->classes),
            esc_html($this->text),
        );

        if ($echo) {
            echo $ret;
        }

        return $ret;
    }
}
