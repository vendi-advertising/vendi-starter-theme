<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\BaseComponentWithPrimaryHeading;
use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;
use Vendi\Theme\Traits\ColorSchemeTrait;
use WP_Post;

class Testimonial extends BaseComponentWithPrimaryHeading implements ColorSchemeAwareInterface
{
    use ColorSchemeTrait;
    public function __construct()
    {
        parent::__construct('component-testimonial');
    }

    public function getTestimonialObject(): ?WP_Post
    {
        $ret = $this->getSubField('testimonial')[0] ?? null;

        return $ret instanceof WP_Post ? $ret : null;
    }

    public function getCopy(): ?string
    {
        if ($testimonial = $this->getTestimonialObject()) {
            return get_field('copy', $testimonial->ID);
        }

        return null;
    }

    public function getAttribution(): ?string
    {
        if ($testimonial = $this->getTestimonialObject()) {
            return get_field('attribution', $testimonial->ID);
        }

        return null;
    }

    public function getThumbnail(): ?string
    {
        if ($testimonial = $this->getTestimonialObject()) {
            return get_the_post_thumbnail($testimonial->ID, 'medium');
        }

        return null;
    }

    protected function abortRender(): bool
    {
        return null === $this->getCopy();
    }
}
