<?php
$classes = ['component-basic-copy'];
if ($col = get_layout_col()) {
    $classes[] = "col-".$col;
}
?>
<section
    <?php vendi_render_class_attribute($classes); ?>
    <?php vendi_maybe_get_row_id_attribute_from_subfield(); ?>
>
    <div class="region">
        <?php echo get_sub_field('copy') ?>
    </div>
</section>

<style>
    .content-components {
        display: flex;
    }

    .wrap {
        flex-wrap: wrap;
    }

    .col-12 {
        flex: 0 0 100%
    }

    .col-11 {
        flex: 0 0 calc(100% / 12 * 11);
    }

    .col-10 {
        flex: 0 0 calc(100% / 12 * 10);
    }

    .col-9 {
        flex: 0 0 calc(100% / 12 * 9);
    }

    .col-8 {
        flex: 0 0 calc(100% / 12 * 8);
    }

    .col-7 {
        flex: 0 0 calc(100% / 12 * 7)
    }

    .col-6 {
        flex: 0 0 calc(100% / 12 * 6)
    }

    .col-5 {
        flex: 0 0 calc(100% / 12 * 5)
    }

    .col-4 {
        flex: 0 0 calc(100% / 12 * 4)
    }

    .col-3 {
        flex: 0 0 calc(100% / 12 * 3)
    }

    .col-2 {
        flex: 0 0 calc(100% / 12 * 2)
    }

    .col-1 {
        flex: 0 0 calc(100% / 12 * 1)
    }

    .col-auto {
        flex: 1
    }

    .align-center {
        justify-content: center
    }

    .align-left {
        justify-content: flex-start
    }

    .align-right {
        justify-content: flex-end
    }

    .align-space-evenly {
        justify-content: space-evenly
    }

    .align-space-between {
        justify-content: space-between
    }

    .align-space-around {
        justify-content: space-around
    }

    .valign-stretch {
        align-items: stretch
    }

    .valign-top {
        align-items: flex-start
    }

    .valign-center {
        align-items: center
    }

    .valign-bottom {
        align-items: flex-end
    }
</style>
