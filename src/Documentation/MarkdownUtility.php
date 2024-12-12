<?php

namespace Vendi\Theme\Documentation;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownUtility
{
    private static ?MarkdownUtility $instance = null;
    private readonly MarkdownConverter $converter;

    public static function getInstance(): self
    {
        if ( ! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $config      = [];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FrontMatterExtension());
        $this->converter = new MarkdownConverter($environment);
    }

    public function getConverter(): MarkdownConverter
    {
        return $this->converter;
    }
}
