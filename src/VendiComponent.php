<?php

namespace Vendi\Theme;

abstract class VendiComponent implements ComponentInterface
{
    private array $rootClasses = [];
    protected array $fieldCache = [];
    private array $rootAttributes = [];

    public function __construct(
        public readonly string $componentName,
        public array $additionalRootClasses = [],
    ) {
        $this->initComponent();

        $this->addRootClasses($additionalRootClasses);
        $this->addRootClass($this->componentName);
    }

    protected function initComponent(): void {}

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
    public function getSubField(string $fieldName): mixed
    {
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
        echo 'class="' . esc_attr(implode(' ', $classes)) . '"';
    }

    protected function getAdditionalRootAttributes(): array
    {
        return $this->rootAttributes;
    }
}
