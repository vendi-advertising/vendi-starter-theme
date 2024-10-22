<?php

namespace Vendi\Theme\Component;

class TestBlockquote extends Blockquote {

    private string $loremIpsum = <<<LOREM
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent quis magna sapien. Integer tempus lorem enim, vel pulvinar mi consectetur in. Maecenas nisl ante, egestas consectetur sem nec, mollis ullamcorper justo. Ut non volutpat felis. Vivamus venenatis cursus egestas. Morbi egestas leo et augue imperdiet, in condimentum leo varius. Quisque imperdiet porta tortor ac pulvinar. In hac habitasse platea dictumst. Donec ut elit at dui ultricies laoreet eget hendrerit quam. Cras sed laoreet lorem. Mauris consequat sagittis elementum. In et lobortis lacus, eget pharetra turpis. Quisque et facilisis elit, vitae finibus mi. Mauris eget magna non purus semper tempor. Cras vehicula sed massa id gravida.
LOREM;


    public function __construct(
        private readonly array $testData,
    ) {
        parent::__construct();
    }

    protected function initComponent(): void {
        $this->fieldCache['copy']                                       = $this->testData['copy'] ?? null;
        $this->fieldCache['attribution']                                = $this->testData['attribution'] ?? null;
        $this->fieldCache['content_area_settings']['content_max_width'] = $this->testData['content_area_settings']['content_max_width'] ?? null;
        $this->fieldCache['content_area_settings']['content_placement'] = $this->testData['content_area_settings']['content_alignment'] ?? null;

        //"copy": "@lorem(500, 3)"
        if ( preg_match( '/^@lorem\((?<words>\d+),\s*(?<paragraphs>\d+)\)$/', $this->fieldCache['copy'], $matches ) ) {
            $paragraphs = [];
            for ( $i = 0; $i < $matches['paragraphs']; $i ++ ) {
                $paragraphs[] = implode( ' ', array_slice( explode( ' ', $this->loremIpsum ), 0, $matches['words'] ) );
            }

            $this->fieldCache['copy'] = wpautop( implode( "\n\n", $paragraphs ) );
        }
    }
}
