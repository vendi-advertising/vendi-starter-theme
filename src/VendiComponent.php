<?php

namespace Vendi\Theme;

use Vendi\Theme\ComponentInterfaces\ColorSchemeAwareInterface;

abstract class VendiComponent implements ComponentInterface
{
    private array $rootClasses = [];
    protected array $fieldCache = [];
    protected array $rootAttributes = [];
    protected array $componentCache = [];

    public function __construct(
        public readonly string $componentName,
        public array $additionalRootClasses = [],
    ) {
        $this->initComponent();

        $this->addRootClasses($additionalRootClasses);
        $this->addRootClass($this->componentName);
    }

    protected function getFromComponentCache(string $key, mixed $default = null): mixed
    {
        return $this->componentCache[$key] ?? $default;
    }

    protected function setInComponentCache(string $key, mixed $value): void
    {
        $this->componentCache[$key] = $value;
    }

    protected function doesComponentCacheContain(string $key): bool
    {
        return isset($this->componentCache[$key]);
    }

    /**
     * This is used by traits to better help understand where they are being used.
     * It is weird, I know, but the overhead is negligible and it makes PHPStorm
     * happier.
     */
    final public function getComponent(): VendiComponent
    {
        return $this;
    }

    protected function initComponent(): void
    {
        if ($this instanceof ColorSchemeAwareInterface) {
            $this->setColorScheme();
        }
    }

    public function getRootClasses(): array
    {
        return array_filter($this->rootClasses);
    }

    public function addRootClasses(array $classes): void
    {
        $this->rootClasses = array_merge($this->rootClasses, $classes);
    }

    public function addRootClass(string $class): void
    {
        $this->rootClasses[] = $class;
    }

    protected function removeRootClass(string $class): void
    {
        $this->rootClasses = array_filter($this->rootClasses, static function ($existingClass) use ($class) {
            return $existingClass !== $class;
        });
    }

    public function addRootAttribute(string $key, string $value = null): void
    {
        $this->rootAttributes[$key] = $value;
    }

    public function getRootAttribute(string $key): ?string
    {
        return $this->rootAttributes[$key] ?? null;
    }

    public function getComponentName(): string
    {
        return $this->componentName;
    }

    public function renderComponentWrapperStart(): bool
    {
        if ($this->abortRender()) {
            return false;
        }

        echo '<' . $this->getRootTag() . ' ';

        $this->vendi_render_class_attribute($this->getRootClasses());
        foreach ($this->getAdditionalRootAttributes() as $key => $value) {
            if (null === $value) {
                echo esc_attr($key);
            } else {
                echo sprintf('%s="%s"', esc_attr($key), esc_attr($value));
            }
        }

        echo '>';

        return true;
    }

    public function renderComponentWrapperEnd(): void
    {
        echo '</' . $this->getRootTag() . '>';
    }


    protected function abortRender(): bool
    {
        return false;
    }

    /**
     * This function is a pass-through to get_sub_field() for normal template
     * usage, but it allows overriding in when running in test/documentation
     * mode.
     *
     * @param string $fieldName
     *
     * @return mixed
     */
    public function getSubField(?string $fieldName): mixed
    {
        if (null === $fieldName) {
            return null;
        }
        // If you notice a component that keeps repeating the same values for fields,
        /// this is the function that is doing that. You must subclass VendiComponent
        /// for the subcomponents of your component, even if it just a repeater.
        /// See Stats and SingleStat for an example.
        if ( ! isset($this->fieldCache[$fieldName])) {
            $this->fieldCache[$fieldName] = get_sub_field($fieldName);
        }

        return $this->fieldCache[$fieldName];
    }

    public function haveRows($selector, $post_id = false): bool
    {
        return have_rows($selector, $post_id);
    }

    public function theRow($format = false): mixed
    {
        return the_row($format);
    }

    public function getRootTag(): string
    {
        return 'section';
    }

    protected function vendi_render_class_attribute(array|string $classes): void
    {
        if (is_string($classes)) {
            $classes = explode(' ', $classes);
        }

        if ( ! $classes = array_filter($classes)) {
            return;
        }
        echo 'class="' . esc_attr(implode(' ', $classes)) . '" ';
    }

    protected function getAdditionalRootAttributes(): array
    {
        return $this->rootAttributes;
    }
}
