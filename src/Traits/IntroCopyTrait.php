<?php

namespace Vendi\Theme\Traits;

use Vendi\Theme\Enums\IntroCopyRenderEnum;

trait IntroCopyTrait
{
    private string $introCopyAriaId = '';

    public function getIntroCopyAriaId(): string
    {
        if (empty($this->introCopyAriaId)) {
            $this->introCopyAriaId = 'intro-copy-' . md5(uniqid('', true));
        }

        return $this->introCopyAriaId;
    }

    public function getIntroCopyText(): ?string
    {
        return get_sub_field('intro_copy');
    }

    public function getIntroCopyTextRender(): ?IntroCopyRenderEnum
    {
        return IntroCopyRenderEnum::tryFrom($this->getSubField('intro_copy_render_settings'));
    }

    public function getIntroCopyTextTextWrapStyle(): ?string
    {
        if ($this->shouldRenderHeadingTag()) {
            return $this->getSubField('intro_copy_text_wrap_style');
        }

        return null;
    }

    public function maybeRenderIntroCopy(): void
    {
        if ($this->getIntroCopyTextRender() === IntroCopyRenderEnum::omit) {
            return;
        }

        $classes = ['intro-copy'];

        if ($this->getIntroCopyTextRender() === IntroCopyRenderEnum::aria_description_only) {
            $classes[] = 'visually-hidden';
        }

        ?>
        <p class="<?php esc_attr_e(implode(' ', $classes)); ?>" id="<?php esc_attr_e($this->getIntroCopyAriaId()); ?>">
            <?php echo wp_kses_post($this->getIntroCopyText()); ?>
        </p>
        <?php
    }

    public function maybeSetAriaDescription(): void
    {
        if ($this->getIntroCopyTextRender() === IntroCopyRenderEnum::aria_description_only) {
            $this->addRootAttribute('aria-describedby', strip_tags($this->getIntroCopyAriaId()));
        }
    }
}
