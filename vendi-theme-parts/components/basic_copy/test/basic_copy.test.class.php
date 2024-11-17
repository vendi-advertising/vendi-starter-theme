<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\TestComponentUtilsTrait;

class TestBasicCopy extends BasicCopy
{
    use TestComponentUtilsTrait;

    public function __construct(
        private readonly array $testData,
    ) {
        parent::__construct();
    }

    protected function initComponent(): void
    {
        $this->fieldCache['copy'] = $this->getTestData('copy');
        $this->fieldCache['content_area_settings']['content_max_width'] = $this->testData['content_area_settings']['content_max_width'] ?? null;
        $this->fieldCache['content_area_settings']['content_placement'] = $this->testData['content_area_settings']['content_alignment'] ?? null;
        $this->loadTestHeadingSettings();
    }
}
