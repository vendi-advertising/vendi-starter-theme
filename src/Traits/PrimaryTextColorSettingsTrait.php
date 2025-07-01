<?php

namespace Vendi\Theme\Traits;

use Deprecated;
use Vendi\Theme\ComponentInterface;
trait PrimaryTextColorSettingsTrait
{
    private const string ACF_FIELD_FOR_PRIMARY_TEXT_COLOR = 'primary_text_color';

    #[Deprecated('Use ColorSchemeTrait')]
    public function getPrimaryTextColor(string $default = '#000000'): ?string
    {
        if ( ! $this instanceof ComponentInterface) {
            throw new \RuntimeException('This trait can only be used by classes that implement \Vendi\Theme\ComponentInterface');
        }

        $ret = $this->getSubField(self::ACF_FIELD_FOR_PRIMARY_TEXT_COLOR) ?? $default;

        if ($ret === '#000000') {
            $ret = 'var(--color-brand-dark-gray)';
        }

        return $ret;
    }
}
