<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\TestComponentUtilsTrait;

class TestFigure extends Figure {

    use TestComponentUtilsTrait;

    public function __construct(
        private readonly array $testData,
    ) {
        parent::__construct();
    }

    public function getImage() {
        return [ 'ID' => 0 ];
    }

    public function getImageHtml( int $imageId, string $size ): string {
        if ( ! isset( $this->fieldCache['image'] ) ) {
            return '';
        }

        $attributes = [];
        foreach ( $this->fieldCache['image'] as $key => $value ) {
            if ( $value !== null ) {
                $attributes[] = sprintf( '%s="%s"', $key, $value );
            }
        }

        return sprintf( '<img %1$s />', implode( ' ', $attributes ) );
    }

    protected function initComponent(): void {
        $this->fieldCache['caption']                                    = $this->testData['caption'] ?? null;
        $this->fieldCache['photo_credit']                               = $this->testData['photo_credit'] ?? null;
        $this->fieldCache['image']                                      = $this->testData['image'] ?? null;

        if ( $image = ( $this->testData['image'] ?? null ) ) {
            $this->fieldCache['image'] = [
                'alt'    => $image['alt'] ?? null,
                'title'  => $image['title'] ?? null,
                'src'    => $image['src'] ?? null,
                'width'  => $image['width'] ?? null,
                'height' => $image['height'] ?? null,
            ];
        }

        $this->loadCommonSettings();
    }

    protected function abortRender(): bool {
        return null === ( $this->fieldCache['image'] ?? null );
    }
}
