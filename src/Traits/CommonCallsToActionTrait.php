<?php

namespace Vendi\Theme\Traits;

use Vendi\Theme\VendiComponent;

trait CommonCallsToActionTrait
{
    abstract public function getComponent(): VendiComponent;

    public function maybeRenderCallsToAction(string $subfieldName = 'calls_to_action', string $wrapperClass = 'ctas'): void
    {
        ?>
        <div class="<?php esc_attr_e($wrapperClass); ?>">
            <?php
            while (have_rows($subfieldName)) {
                the_row();
                if ('button' !== get_row_layout()) {
                    continue;
                }
                if ($link = get_sub_field('link')) {
                    vendi_load_component_v3(
                        'buttons/button',
                        [
                            'link'         => $link,
                            'button_style' => $this->getComponent()->getSubField('global-button-style'),
                        ],
                    );
                }
            }

            ?>
        </div>
        <?php
    }
}
