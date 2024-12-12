<?php

namespace Vendi\Theme\Documentation;

use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use Symfony\Component\Filesystem\Path;

use function Symfony\Component\String\u;

class ComponentMeta
{
    private readonly string $componentRoot;
    private mixed $frontMatter = null;

    public function __construct(
        public readonly \SplFileInfo $directory,
    ) {
        $this->componentRoot = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components');
        $this->getComponentFriendlyName();
    }

    public function getComponentUrl(): ?string
    {
        $possibleMarkdownFile = $this->getMarkdownFile();
        if (is_readable($possibleMarkdownFile)) {
            return Path::join(VENDI_PATH_ROOT_THEME_DOCUMENTATION, $this->getComponentFolder());
        }

        return null;
    }

    private function getFrontMatter(): mixed
    {
        if ( ! $this->frontMatter && $this->hasMarkdownFile()) {
            $converter = MarkdownUtility::getInstance()->getConverter();
            $document  = $converter->convert(file_get_contents($this->getMarkdownFile()));
            if ($document instanceof RenderedContentWithFrontMatter) {
                $this->frontMatter = $document->getFrontMatter();
            }
        }

        return $this->frontMatter;
    }

    public function getComponentGroup(): string
    {
        if ($frontMatter = $this->getFrontMatter()) {
            if (isset($frontMatter['group'])) {
                return $frontMatter['group'];
            }
        }

        return 'Components';
    }

    public function getComponentFriendlyName(): string
    {
        if ($frontMatter = $this->getFrontMatter()) {
            if (isset($frontMatter['title'])) {
                return $frontMatter['title'];
            }
        }

        return u($this->getComponentFolder())->replace('-', ' ')->title()->toString();
    }

    public function getMarkdownFile(): ?string
    {
        $directoryName = $this->getComponentFolder();

        return Path::join($this->componentRoot, $directoryName, $directoryName . '.md');
    }

    public function hasMarkdownFile(): bool
    {
        return is_readable($this->getMarkdownFile());
    }

    public function getComponentFolder(): string
    {
        return $this->directory->getRelativePathname();
    }
}
