<?php

$primary_display_mode = get_sub_field('primary_display_mode');
$call_to_action_text = get_sub_field('call_to_action_text');
$url = get_sub_field('url');
$post = get_sub_field('post');

$button_type = get_sub_field('button_type');
$target = get_sub_field('target');
$rel = get_sub_field('rel');

$classes = [
    'call-to-action',
    'call-to-action-'.$primary_display_mode,
];

if ($primary_display_mode === 'button') {
    $classes[] = 'call-to-action-'.$button_type;
}

$rel[] = 'cheese';

$target = vendi_constrain_item_to_list($target, ['_blank', '_self', '_parent', '_top']);
$rel = array_filter(array_intersect($rel, ['nofollow', 'noreferrer', 'noopener']));

$href = $url;
if (!$href && $post) {
    $href = get_permalink($post);
}

if (!$href) {
    return;
}

?>

<a
    href="<?php echo esc_url($href); ?>"
    <?php vendi_render_class_attribute($classes); ?>
    <?php vendi_render_attribute('target', $target); ?>
    <?php vendi_render_attribute('rel', implode(' ', $rel)); ?>
><?php echo esc_html($call_to_action_text); ?></a>
