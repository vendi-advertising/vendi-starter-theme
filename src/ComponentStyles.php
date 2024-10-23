<?php

namespace Vendi\Theme;

use ArrayAccess;
use Countable;
use Iterator;
use Stringable;

class ComponentStyles implements ArrayAccess, Iterator, Countable, Stringable
{
    private array $container;
    private array $keys;
    private int $position;
    private array $errors = [];

    public function __construct()
    {
        $position = 0;

        $this->container = [];
        $this->keys = array_keys($this->container);
    }

    public function addCssProperty(string $key, string $value): void
    {
        if (!$value) {
            $this->errors[] = 'Value for '.$key.' is empty';

            return;
        }
        $this->offsetSet($key, $value);
    }

    public function addStyle(string $key, string $value): void
    {
        $newValue = rtrim(trim($value), '; ');

        if (!$oldValue = $this->offsetGet($key)) {
            $this->offsetSet($key, $newValue);

            return;
        }

        if (!is_array($oldValue)) {
            $oldValue = [$oldValue];
        }
        $oldValue[] = $value;

        $newValue = $oldValue;

        $this->offsetSet($key, $newValue);
    }

    public function addBackgroundColor(string $value): void
    {
        $this->addStyle('background-color', $value);
    }

    public function addBackground(string $value): void
    {
        $this->addStyle('background', $value);
    }

    public function addBackgroundImage(string $value): void
    {
        $this->addStyle('background-image', $value);
    }

    public function count(): int
    {
        return count($this->keys);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): mixed
    {
        return $this->container[$this->keys[$this->position]];
    }

    public function key(): mixed
    {
        return $this->keys[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function valid(): bool
    {
        return isset($this->keys[$this->position]);
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
            $this->keys[] = array_key_last($this->container);
        } else {
            $this->container[$offset] = $value;
            if (!in_array($offset, $this->keys, true)) {
                $this->keys[] = $offset;
            }
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
        unset($this->keys[array_search($offset, $this->keys, true)]);
        $this->keys = array_values($this->keys);
    }

    public function offsetGet($offset): mixed
    {
        return $this->container[$offset] ?? null;
    }

    public function __toString(): string
    {
        $ret = '';
        foreach ($this->container as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            $ret .= $key.': '.$value.'; ';
        }

        if (count($this->errors)) {
            $ret .= '/* Errors: '.PHP_EOL.implode(PHP_EOL, $this->errors).PHP_EOL.' */';
        }

        return $ret;
    }
}
