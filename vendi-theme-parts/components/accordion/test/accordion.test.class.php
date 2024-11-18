<?php

namespace Vendi\Theme\Component;

use Vendi\Theme\TestComponentUtilsTrait;

class TestAccordion extends Accordion {
    use TestComponentUtilsTrait;

    public function getAccordionItems(): array {
        return [];
    }

    protected function initComponent(): void {
        $this->loadAutoloadTestData();
        $this->loadCommonSettings();
    }
}
