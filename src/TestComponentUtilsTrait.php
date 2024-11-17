<?php

namespace Vendi\Theme;

trait TestComponentUtilsTrait
{
    private string $loremIpsum = <<<LOREM
                       Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent quis magna sapien. Integer tempus lorem enim, vel pulvinar mi consectetur in. Maecenas nisl ante, egestas consectetur sem nec, mollis ullamcorper justo. Ut non volutpat felis. Vivamus venenatis cursus egestas. Morbi egestas leo et augue imperdiet, in condimentum leo varius. Quisque imperdiet porta tortor ac pulvinar. In hac habitasse platea dictumst. Donec ut elit at dui ultricies laoreet eget hendrerit quam. Cras sed laoreet lorem. Mauris consequat sagittis elementum. In et lobortis lacus, eget pharetra turpis. Quisque et facilisis elit, vitae finibus mi. Mauris eget magna non purus semper tempor. Cras vehicula sed massa id gravida.
                       LOREM;

    protected function applyLoremIpsumFunction(?string $copy): ?string
    {
        if (!$copy) {
            return $copy;
        }

        //"copy": "@lorem(500, 3)"
        if (preg_match('/^@lorem\((?<words>\d+),\s*(?<paragraphs>\d+)\)$/', $copy, $matches)) {
            $paragraphs = [];
            for ($i = 0; $i < $matches['paragraphs']; $i++) {
                $paragraphs[] = implode(' ', array_slice(explode(' ', $this->loremIpsum), 0, $matches['words']));
            }

            $copy = wpautop(implode("\n\n", $paragraphs));
        }

        return $copy;
    }

    protected function loadCommonContentAreaSettings(): void
    {
        $this->fieldCache['content_area_settings']['content_max_width'] = $this->testData['content_area_settings']['content_max_width'] ?? null;
        $this->fieldCache['content_area_settings']['content_placement'] = $this->testData['content_area_settings']['content_alignment'] ?? null;
    }

    protected function loadTestHeadingSettings(): void
    {
        $this->loadTestDataByExactKeys('heading', 'heading_render', 'heading_tag');
    }

    protected function loadTestDataByExactKeys(string ...$keys): void
    {
        foreach ($keys as $key) {
            $this->fieldCache[$key] = $this->getTestData($key);
        }
    }

    protected function getTestData(string $key, bool $applyLoremIpsum = true): mixed
    {
        $value = $this->testData[$key] ?? null;
        if ($applyLoremIpsum && is_string($value)) {
            $value = $this->applyLoremIpsumFunction($value);
        }

        return $value;
    }

    protected function loadAutoloadTestData(): void
    {
        $keys = $this->testData['__autoload'] ?? [];
        $this->loadTestDataByExactKeys(...$keys);
    }
}
