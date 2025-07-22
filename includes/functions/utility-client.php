<?php

function vendi_has_hero_type(string $type): bool
{
    if (have_rows('hero')) {
        while (have_rows('hero')) {
            the_row();
            if (get_row_layout() === $type) {
                reset_rows();

                return true;
            }
        }
    }

    return false;
}
