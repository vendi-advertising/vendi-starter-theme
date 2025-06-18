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

        if ( ! $documentation_type = get_field('documentation_type', $post->ID)) {
            $documentation_type = 'component';
        }

        switch ($documentation_type) {
            case 'component':
                while (have_rows('examples', $post->ID)) {
                    the_row();
                    vendi_theme_docs_render_example();
                }
                break;
            case 'hero':
                while (have_rows('heroes', $post->ID)) {
                    the_row();
                    vendi_theme_docs_render_example_hero();
                }
                break;
        }


        return;
    }
}

function vendi_theme_docs_render_example_hero(): void
{
    ?>
    <div class="component-accordion content-max-width-full content-placement-middle documentation-example always-full-width">
        <div class="component-wrapper">
            <div class="region">
                <div class="content-wrap">
                    <div class="accordion-items" data-columns-count="1">
                        <div class="accordion-column">
                            <details class="single-accordion-item">
                                <summary>
                                    <h4><?php esc_html_e(get_sub_field('title')) ?></h4>
                                    <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>
                                    <?php if ($description = get_sub_field('description')) : ?>
                                        <p><?php esc_html_e($description) ?></p>
                                    <?php endif; ?>
                                </summary>
                                <?php
                                $backing_page = get_sub_field('backing_page');
                                if ($backing_page && is_array($backing_page)) {
                                    $backing_page = array_shift($backing_page);
                                }

                                $old_post = null;

                                if ($backing_page instanceof WP_Post) {
                                    global $post;
                                    $old_post = $post;
                                    $post     = $backing_page;
                                    setup_postdata($post);
                                }
                                ?>
                                <div class="documentation-hero-wrap">
                                    <?php
                                    vendi_load_component_v3('header');
                                    vendi_load_component_v3('hero');
                                    ?>
                                </div>
                                <?php

                                if ($old_post instanceof WP_Post) {
                                    wp_reset_postdata();
                                    $post = $old_post;
                                }
                                ?>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}


function vendi_theme_docs_render_example(): void
{
    ?>
    <div class="component-accordion content-max-width-full content-placement-middle documentation-example always-full-width">
        <div class="component-wrapper">
            <div class="region">
                <div class="content-wrap">
                    <div class="accordion-items" data-columns-count="1">
                        <div class="accordion-column">
                            <details class="single-accordion-item">
                                <summary>
                                    <h4><?php esc_html_e(get_sub_field('title')) ?></h4>
                                    <span class="expand-collapse-single-item"><?php vendi_get_svg('images/starter-content/plus-minus.svg'); ?></span>
                                    <?php if ($description = get_sub_field('description')) : ?>
                                        <p><?php esc_html_e($description) ?></p>
                                    <?php endif; ?>
                                </summary>
                                <?php
                                while (have_rows('content_components')) {
                                    the_row();
                                    vendi_load_component_v3(get_row_layout());
                                }
                                ?>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
