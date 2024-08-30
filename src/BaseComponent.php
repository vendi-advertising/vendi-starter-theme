<?php

namespace Vendi\Theme;


use Vendi\Theme\DTO\ComponentStyles;

abstract class BaseComponent {

    public readonly ComponentStyles $componentStyles;
    private readonly int $componentIndex;
    private array $fieldCache = [];

    public function __construct(
        public readonly string $componentName,
        public readonly bool $supportsBackgroundVideo = true,
        public readonly bool $supportsCommonContentAreaSettings = true,
    ) {

        $this->componentStyles = new ComponentStyles();
        $this->componentIndex  = ComponentUtility::get_instance()->get_next_id_for_component( $this->componentName );
    }

    public function setComponentCssProperties(): void {

    }

    protected function abortRender(): bool {
        return false;
    }

    public function getSubFieldAndCache( string $fieldName ): mixed {

        if ( ! isset( $this->fieldCache[ $fieldName ] ) ) {
            $this->fieldCache[ $fieldName ] = get_sub_field( $fieldName );
        }

        return $this->fieldCache[ $fieldName ];
    }

    public function getRootClasses(): array {
        $ret = [
            $this->componentName,
        ];

        if ( $this->supportsBackgroundVideo ) {
            $ret[] = 'component-background-video-wrapper';
        }

        if ( $this->supportsCommonContentAreaSettings ) {
            $ret = array_merge( $ret, $this->getCommonContentAreaSettings()['classes'] );
        }

        return array_filter( array_merge( $ret, $this->getAdditionalRootClasses() ) );
    }

    public function getComponentIndex(): int {
        return $this->componentIndex;
    }

    protected function getCommonContentAreaFields(): array {
        return [
            'content_max_width',
            'content_placement',
            'content_vertical_padding',
            'content_horizontal_padding',
        ];
    }

    private function getCommonContentAreaSettings(): array {
        $ret = [ 'classes' => [] ];

        $settingsGroup = get_sub_field( 'content_area_settings' );

        if ( ! $settingsGroup ) {
            return $ret;
        }

        // NOTE: XX-Large is not included in the UI currently but was left here for consistency
        $settings = $this->getCommonContentAreaFields();

        $classes = [];

        foreach ( $settings as $setting ) {
            if ( ! $value = $settingsGroup[ $setting ] ?? null ) {
                continue;
            }
            switch ( $setting ) {
                case 'content_max_width':
                    $ret[ $setting ] = vendi_constrain_item_to_list( $value, [ 'full', 'narrow', 'slim' ], 'narrow' );
                    $classes[]       = 'content-max-width-' . $ret[ $setting ];
                    break;
                case 'content_placement':
                    $ret[ $setting ] = vendi_constrain_item_to_list( $value, [ 'left', 'middle' ], 'left' );
                    $classes[]       = 'content-placement-' . $ret[ $setting ];
                    break;
                case 'content_vertical_padding':
                    $ret[ $setting ] = vendi_constrain_item_to_list( $value, [ 'xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none' ], 'medium' );
                    $classes[]       = 'content-vertical-padding-' . $ret[ $setting ];
                    break;
                case 'content_horizontal_padding':
                    $ret[ $setting ] = vendi_constrain_item_to_list( $value, [ 'xx-large', 'x-large', 'large', 'medium', 'small', 'x-small', 'xx-small', 'none' ], 'medium' );
                    $classes[]       = 'content-horizontal-padding-' . $ret[ $setting ];
                    break;
            }
        }

        $ret['classes'] = $classes;

        return $ret;
    }

    protected function getKeyForBackgrounds(): string {
        return 'backgrounds';
    }

    protected function renderComponentSpecificCssBlock(): void {
        $this->setComponentCssProperties();
        vendi_get_background_settings( $this->componentStyles, key: $this->getKeyForBackgrounds() );
        ?>
        <style media="screen">
            [data-component-name="<?php esc_attr_e($this->componentName); ?>"][data-component-index="<?php esc_attr_e($this->getComponentIndex()); ?>"] {
            <?php echo $this->componentStyles->__toString(); ?>
            }
        </style>
        <?php
    }

    protected function getAdditionalRootAttributes(): array {
        return [];
    }

    protected function getAdditionalRootClasses(): array {
        return [];
    }

    protected function getRootTag(): string {
        return 'section';
    }

    public function renderComponentWrapperStart(): bool {

        if ( $this->abortRender() ) {
            return false;
        }

        $this->renderComponentSpecificCssBlock();
        echo '<' . $this->getRootTag() . ' ';

        vendi_render_class_attribute( $this->getRootClasses() );
        foreach ( $this->getAdditionalRootAttributes() as $key => $value ) {
            if ( null === $value ) {
                echo esc_attr( $key );
            } else {
                echo sprintf( '%s="%s"', esc_attr( $key ), esc_attr( $value ) );
            }
        }
        vendi_render_row_id_attribute()
        ?>
        >
        <div
        class="component-wrapper"
        <?php vendi_render_component_data_name_and_index_attributes( $this->componentName, $this->getComponentIndex() ); ?>
        >

        <?php
        if ( $this->supportsBackgroundVideo ) {
            vendi_maybe_render_background_video();
        }
        ?>

        <div class="region">
        <div class="content-wrap">
        <?php

        return true;
    }

    public function renderComponentWrapperEnd(): void {
        ?>
        </div>
        </div>
        </div>

        <?php
        echo '</' . $this->getRootTag() . '>';
    }
}
