<?php

use Vendi\Theme\Component\Accordion;
use Vendi\Theme\ComponentUtility;

/** @var Accordion $component */
$component = ComponentUtility::get_new_component_instance(Accordion::class);
if ( ! $component->renderComponentWrapperStart()) {
    return;
}

?>

<?php $component->maybeRenderComponentHeader(); ?>
<?php if ($additional_copy = $component->getAdditionalCopy()): ?>
    <div class="copy padding-inline-small">
        <?php esc_html_e($additional_copy); ?>
    </div>
<?php endif; ?>
<?php vendi_load_component_v3('accordion/accordion-controls', component: $component); ?>
    <div
        class="accordion-items"
        data-columns-count="<?php echo esc_attr($component->getNumberOfColumns()); ?>"
    >
        <?php

        $rows = [];
        while ($component->haveRows('accordion_items')) {
            $component->theRow();

            ob_start();
            vendi_load_component_v3(['accordion', get_row_layout()]);
            $rows[] = ob_get_clean();
        }

        // Just in case any are empty
        $rows = array_filter($rows);

        if (1 === $component->getNumberOfColumns()) {
            $columns = [$rows];
        } else {
            $columns = array_chunk($rows, ceil(count($rows) / $component->getNumberOfColumns()));
        }

        array_walk(
            $columns,
            static function (&$column) {
                $column = '<div class="accordion-column">' . implode('', $column) . '</div>';
            },
        );

        // If there's more than one column, add a line between each
        echo implode('<div class="line"></div>', $columns);

        ?>

    </div>
<?php

$component->renderComponentWrapperEnd();
