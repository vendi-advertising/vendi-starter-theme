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
        $this->loadCommonSettings();
    }
}
