<?php

global $vendi_component_object_state;

if (!$link = $vendi_component_object_state['link']) {
    return;
}

$classes = $link['classes'] ?? '';
if (!is_array($classes)) {
    $classes = explode(' ', $classes);
}

$type = $link['type'];
$url = $link['url'];
$title = $link['title'];
$target = $link['target'];
$title_escaped = $link['title_escaped'] ?? false;

$classes[] = 'link';
$classes[] = 'link-type-'.$type;

$classes = array_filter($classes);

if (!$url) {
    return;
}

?>

<a
    href="<?php echo esc_url($url); ?>"
    <?php if ($classes): ?>
        class="<?php echo esc_attr(implode(' ', $classes)); ?>"
    <?php endif; ?>
    target="<?php echo esc_attr($target); ?>">
    <span>
        <?php echo $title_escaped ? $title : esc_html($title); ?>
    </span>
</a>
