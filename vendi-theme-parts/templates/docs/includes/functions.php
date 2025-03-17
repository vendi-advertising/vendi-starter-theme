<?php

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Filesystem\Path;

function vendi_theme_docs_get_documentation_for_component(string $vendi_selected_theme_page): string
{
    //
    $all = get_posts(
        [
            'post_type'      => '__component-docs',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'     => 'component_slug',
                    'value'   => $vendi_selected_theme_page,
                    'compare' => '=',
                ],
            ],
        ],
    );

    foreach ($all as $post) {
        if ( ! get_field('component_slug') === $vendi_selected_theme_page) {
            continue;
        }

        if ($documentation = get_field('documentation', $post->ID)) {
            return $documentation;
        }
        break;
    }

    return vendi_theme_docs_get_markdown($vendi_selected_theme_page);
}

function vendi_theme_docs_get_markdown(string $vendi_selected_theme_page): ?string
{
    $markdownFiles = [
        Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $vendi_selected_theme_page, $vendi_selected_theme_page . '.md'),
        Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $vendi_selected_theme_page, 'readme.md'),
    ];
    $markdownFile  = null;
    foreach ($markdownFiles as $possibleMarkdownFile) {
        if (is_readable($possibleMarkdownFile)) {
            $markdownFile = $possibleMarkdownFile;
            break;
        }
    }
    if ( ! $markdownFile) {
        echo 'File not found: ' . $markdownFile;

        return null;
    }
    $config      = [];
    $environment = new Environment($config);
    $environment->addExtension(new CommonMarkCoreExtension());
    $environment->addExtension(new FrontMatterExtension());
    $converter = new MarkdownConverter($environment);

    return $converter->convert(file_get_contents($markdownFile))->getContent();
}

function vendi_theme_docs_render_examples($vendi_selected_theme_page): void
{
    $all = get_posts(
        [
            'post_type'      => '__component-docs',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'     => 'component_slug',
                    'value'   => $vendi_selected_theme_page,
                    'compare' => '=',
                ],
            ],
        ],
    );

    foreach ($all as $post) {
        if (get_field('component_slug', $post) !== $vendi_selected_theme_page) {
            continue;
        }

        while (have_rows('examples', $post->ID)) {
            the_row();
            vendi_theme_docs_render_example();
        }

        return;
    }
}

function vendi_theme_docs_render_example(): void
{
    ?>
    <div class="component-basic-copy content-max-width-full content-placement-left documentation-example">
        <div class="component-wrapper">
            <div class="region">
                <div class="content-wrap">
                    <?php

                    echo '<h4>' . esc_html(get_sub_field('title')) . '</h4>';
                    if ($description = get_sub_field('description')) {
                        echo '<p>' . esc_html($description) . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php


    while (have_rows('content_components')) {
        the_row();
        vendi_load_component_v3(get_row_layout());
    }
}
