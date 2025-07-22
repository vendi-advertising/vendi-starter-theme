<?php

global $vendi_component_object_state;

if ( ! $link = $vendi_component_object_state['link']) {
    return;
}

$aria_labelledby  = $vendi_component_object_state['aria-labelledby'] ?? $vendi_component_object_state['link_hash'] ?? null;
$aria_describedby = $vendi_component_object_state['aria-describedby'] ?? null;

$html_before_link = $vendi_component_object_state['html_before_link'] ?? null;
$html_after_link  = $vendi_component_object_state['html_after_link'] ?? null;

$classes = $link['classes'] ?? '';
if ( ! is_array($classes)) {
    $classes = explode(' ', $classes);
}

$type          = $link['type'];
$url           = $link['url'];
$title         = $link['title'];
$name          = $link['name'];
$target        = $link['target'];
$title_escaped = $link['title_escaped'] ?? false;

$text = $title ?? $name;

$classes[] = 'link';
$classes[] = 'link-type-' . $type;

$classes = array_filter($classes);

if ( ! $url) {
    return;
}

$render_opening_tag  = true;
$render_tag_contents = true;
$render_closing_tag  = true;

if ($vendi_component_object_state['do_not_render_opening_tag'] ?? false) {
    $render_opening_tag = false;
}

if ($vendi_component_object_state['do_not_render_tag_contents'] ?? false) {
    $render_tag_contents = false;
}

if ($vendi_component_object_state['do_not_render_closing_tag'] ?? false) {
    $render_closing_tag = false;
}

?>

<?php if ($render_opening_tag): ?>
    <a
    href="<?php echo esc_url($url); ?>"
    <?php if ($classes): ?>
        class="<?php esc_attr_e(implode(' ', $classes)); ?>"
    <?php endif; ?>
    <?php if ($aria_labelledby): ?>
        aria-labelledby="<?php esc_attr_e($aria_labelledby); ?>"
    <?php endif; ?>
    <?php if ($aria_describedby): ?>
        aria-describedby="<?php esc_attr_e($aria_describedby); ?>"
    <?php endif; ?>
    <?php if ($target): ?>
        target="<?php esc_attr_e($target); ?>"
    <?php endif; ?>
    >
<?php endif; ?>

<?php if ($render_tag_contents): ?>
    <?php echo $html_before_link; ?>

    <span>
        <?php echo $title_escaped ? $text : esc_html($text); ?>
    </span>

    <?php echo $html_after_link; ?>

<?php endif; ?>

<?php if ($render_closing_tag): ?>
    </a>

<?php endif; ?>
