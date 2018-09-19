<?php

declare(strict_types=1);

namespace Vendi\CLIENT\Theme\WordPress\CustomPostTypes;

use Vendi\CLIENT\Theme\AbstractClassWithoutMagicGetSet;

abstract class CPTBase extends AbstractClassWithoutMagicGetSet
{
    private $singular;

    private $plural;

    private $singular_lc;

    private $plural_lc;

    private $type_name;

    public function __construct(string $type_name)
    {
        $this->type_name = $type_name;
    }

    abstract public function get_register_args() : array;

    final public static function register()
    {
        $obj = new static();
        $obj->_register_post_type();
    }

    public function _maybe_set_lower_case_versions()
    {
        if ($this->has_title_case_singular_name() && ! $this->has_lower_case_singular_name()) {
            $this->set_lower_case_singular_name(mb_strtolower($this->get_title_case_singular_name()));
        }

        if ($this->has_title_case_plural_name() && ! $this->has_lower_case_plural_name()) {
            $this->set_lower_case_plural_name(mb_strtolower($this->get_title_case_plural_name()));
        }
    }

    public function get_type_name() : string
    {
        return $this->type_name;
    }

    public function with_title_case_singular_name(string $singular) : self
    {
        $clone = clone $this;
        $clone->set_title_case_singular_name($singular);
        return $clone;
    }

    public function set_title_case_singular_name(string $singular)
    {
        $this->singular = $singular;
        $this->_maybe_set_lower_case_versions();
    }

    public function get_title_case_singular_name() : string
    {
        return $this->singular;
    }

    public function has_title_case_singular_name() : bool
    {
        return isset($this->singular);
    }

    public function with_title_case_plural_name(string $plural) : self
    {
        $clone = clone $this;
        $clone->set_title_case_plural_name($plural);
        return $clone;
    }

    public function set_title_case_plural_name(string $plural)
    {
        $this->plural = $plural;
        $this->_maybe_set_lower_case_versions();
    }

    public function get_title_case_plural_name() : string
    {
        return $this->plural;
    }

    public function has_title_case_plural_name() : bool
    {
        return isset($this->plural);
    }

    public function with_lower_case_singular_name(string $singular) : self
    {
        $clone = clone $this;
        $clone->set_lower_case_singular_name($singular);
        return $clone;
    }

    public function set_lower_case_singular_name(string $singular)
    {
        $this->singular_lc = $singular;
    }

    public function get_lower_case_singular_name() : string
    {
        return $this->singular_lc;
    }

    public function has_lower_case_singular_name() : bool
    {
        return isset($this->singular_lc);
    }

    public function with_lower_case_plural_name(string $plural) : self
    {
        $clone = clone $this;
        $clone->set_lower_case_plural_name($plural);
        return $clone;
    }

    public function set_lower_case_plural_name(string $plural)
    {
        $this->plural_lc = $plural;
    }

    public function get_lower_case_plural_name() : string
    {
        return $this->plural_lc;
    }

    public function has_lower_case_plural_name() : bool
    {
        return isset($this->plural_lc);
    }

    public function _register_post_type()
    {
        \register_post_type(
                            $this->get_type_name(),
                            $this->get_register_args()
                        );
    }

    public function _make_labels() : array
    {
        $singular = $this->get_title_case_singular_name();
        $plural = $this->get_title_case_plural_name();
        $singular_lc = $this->get_lower_case_singular_name();
        $plural_lc = $this->get_lower_case_plural_name();

        return [
                'name'                  => $plural,
                'singular_name'         => $singular,
                'menu_name'             => $plural,
                'name_admin_bar'        => $singular,
                'archives'              => "$singular Archives",
                'attributes'            => "$singular Attributes",
                'parent_item_colon'     => "Parent $singular:",
                'all_items'             => "All $plural",
                'add_new_item'          => "Add New $singular",
                'add_new'               => 'Add New',
                'new_item'              => "New $singular",
                'edit_item'             => "Edit $singular",
                'update_item'           => "Update $singular",
                'view_item'             => "View $singular",
                'view_items'            => "View $singular",
                'search_items'          => "Search $singular",
                'not_found'             => 'Not found',
                'not_found_in_trash'    => 'Not found in Trash',
                'featured_image'        => 'Featured Image',
                'set_featured_image'    => 'Set featured image',
                'remove_featured_image' => 'Remove featured image',
                'use_featured_image'    => 'Use as featured image',
                'insert_into_item'      => "Insert into $singular_lc",
                'uploaded_to_this_item' => "Uploaded to this $singular_lc",
                'items_list'            => "$singular list",
                'items_list_navigation' => "$singular list navigation",
                'filter_items_list'     => "Filter $plural_lc list",
        ];
    }
}
