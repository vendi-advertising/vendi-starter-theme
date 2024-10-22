<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\TestComponentUtilsTrait;

class TestImageGallery extends ImageGallery {
    use TestComponentUtilsTrait;

    public function __construct(
        private readonly array $testData,
    ) {
        parent::__construct();
    }

    public function getAttachmentImageSrc( array|int $imageArrayOrId, array|string $size ): array {
        return $this->fieldCache['images'][ $imageArrayOrId ];
    }

    public function getAttachmentImage( int $attachment_id = 0, array|string $size = '', mixed $crop = null, array $attr = [] ): ?string {
        if ( ! $src = $this->fieldCache['images'][ $attachment_id ]['src'] ) {
            return null;
        }

        return sprintf(
            '<img src="%s" alt="%s" />',
            $src,
            $this->fieldCache['images'][ $attachment_id ]['alt'] ?? null
        );
    }

    protected function initComponent(): void {
        $this->loadAutoloadTestData();
    }
}
