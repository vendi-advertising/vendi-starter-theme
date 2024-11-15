<?php

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;

use function Symfony\Component\String\u;

global $vendi_selected_theme_page;

function getExampleHtml(string $description, int $idx, string $url, int $subIdx = 0): string
{
    $fullIdx = $idx . '-' . $subIdx;

    return <<<EOF
        <details>
        <summary>{$description}</summary>
        <iframe id="iframe-{$fullIdx}" src="{$url}"></iframe>
        </details>
        EOF;
}

function getCombinations($arrays, $current = [])
{
    $keys = array_keys($arrays);

    if (empty($keys)) {
        return [$current];
    }

    $key    = array_shift($keys);
    $result = [];

    foreach ($arrays[$key] as $value) {
        $newCurrent = array_merge($current, [$key => $value]);
        $subArrays  = array_intersect_key($arrays, array_flip($keys));
        $result     = array_merge($result, getCombinations($subArrays, $newCurrent));
    }

    return $result;
}

add_filter('show_admin_bar', '__return_false');

?>
<!DOCTYPE html>
<html lang="en" style="margin-top: 0 !important;">
<head>
    <?php wp_head(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Theme Documentation</title>
    <meta name="robots" content="noindex, nofollow">
    <script src="<?php echo Path::join(VENDI_CUSTOM_THEME_URL, 'vendi-theme-parts', 'templates', 'docs', 'iframe-resizer.parent.js'); ?>"></script>
    <style>
        html {
            font-family: sans-serif;
            font-size: 62.5%;
        }

        * {
            box-sizing: border-box;
        }

        :where(body) {
            margin: 0;
            padding: 0;
            font-size: 1.6rem;
        }

        :where(main) {
            display: grid;
            grid-template-columns: 200px 1fr;

            & > :where(*) {
                min-height: 100dvh;
            }

            & > :where(.sidebar) {
                background-color: #f0f0f0;
                padding: 1rem;

                & > :where(.nav-items) {
                    list-style-type: none;
                    padding: 0;
                    margin: 0;
                }
            }

            & > :where(.content) {
                padding: 1rem;

                summary {
                    cursor: pointer;
                    background-color: #ddd;
                    padding: 1rem;
                    position: relative;
                    user-select: none;
                    padding-left: 2rem;

                    &:before {
                        content: '';
                        border-width: .6rem;
                        border-style: solid;
                        border-color: transparent transparent transparent #000;
                        position: absolute;
                        top: 1.2rem;
                        left: 1rem;
                        transform: rotate(0);
                        transform-origin: .2rem 50%;
                        transition: .25s transform ease;
                    }

                    details[open] > &:before {
                        transform: rotate(90deg);
                    }

                    details &::marker {
                        content: none;
                    }
                }

                iframe {
                    width: 100%;
                    border: none;
                    height: 100vh;
                }
            }
        }
    </style>
</head>
<main>
    <nav class="sidebar">
        <ul class="nav-items">
            <?php
            $componentRoot = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components');
            $finder        = new Finder();

            foreach ($finder->files()->in($componentRoot)->directories()->sortByName()->depth(0) as $directory) {
                $directoryName        = $directory->getRelativePathname();
                $possibleMarkdownFile = Path::join($componentRoot, $directoryName, $directoryName . '.md');
                $directoryNamePretty  = u($directoryName)->replace('_', ' ')->replace(' with ', ' w/ ')->title()->toString();
                if (is_readable($possibleMarkdownFile)) {
                    echo '<li><a href="/' . Path::join(VENDI_PATH_ROOT_THEME_DOCUMENTATION, $directoryName) . '">' . $directoryNamePretty . '</a></li>';
                } else {
                    echo '<li>' . $directoryNamePretty . '</li>';
                }
            }
            ?>
        </ul>
    </nav>
    <section class="content">
        <?php

        acf_enqueue_scripts();

        acf_form([
            'post_id'      => 'new_post', // Or specific post ID to edit
            'field_groups' => ['group_61dc8e97982a7'], // Field group ID(s)
            'submit_value' => null,
            'form'         => false,
        ]);

        ?>
        <?php if ('index' === $vendi_selected_theme_page): ?>
            <h1>Select a component</h1>
        <?php else: ?>
            <?php
            $markdownFile = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $vendi_selected_theme_page, $vendi_selected_theme_page . '.md');
            if ( ! is_readable($markdownFile)) {
                echo 'File not found: ' . $markdownFile;

                return;
            }
            $config      = [];
            $environment = new Environment($config);
            $environment->addExtension(new CommonMarkCoreExtension());
            $environment->addExtension(new FrontMatterExtension());
            $converter = new MarkdownConverter($environment);

            $result       = $converter->convert(file_get_contents($markdownFile));
            $testJsonFile = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components', $vendi_selected_theme_page, 'test', $vendi_selected_theme_page . '.test.json');

            $examples = '';
            if (is_readable($testJsonFile)) {
                global $vendi_theme_test_mode;
                $vendi_theme_test_mode = true;

                $testJson = json_decode(file_get_contents($testJsonFile), true);
                $tests    = $testJson['tests'];

                foreach ($tests as $idx => $test) {
                    if (isset($test['__test_template'])) {
                        $test_content = $test['__test_template']['test_content'];
                        $test_matrix  = $test['__test_template']['matrix'];

                        $matrixTests = getCombinations($test_matrix);

                        foreach ($matrixTests as $subIdx => $queryStrings) {
                            $desc = $test['__test_description'];

                            $qs = '?';
                            foreach ($queryStrings as $key => $value) {
                                $qs .= $key . '=' . $value . '&';

                                $desc = str_replace('@matrix(' . $key . ')', $value, $desc);
                            }

                            $url = '/' . Path::join(VENDI_PATH_ROOT_THEME_DOCUMENTATION, $vendi_selected_theme_page, 'test', $idx) . $qs;

                            $examples .= getExampleHtml(
                                description: $desc,
                                idx: $idx,
                                url: $url,
                                subIdx: $subIdx,
                            );
                        }

                        $template = $test['__test_template'];
                        $matrix   = $template['matrix'];
                        $tests    = [];
                        foreach ($matrix as $matrixItem) {
                            $test = $template['test_content'];
                            foreach ($matrixItem as $key => $value) {
                                $test[$key] = $value;
                            }
                            $tests[] = $test;
                        }
                        //@matrix(content_max_width)

                    } else {
                        $desc = $test['__test_description'];

                        $examples .= getExampleHtml(
                            description: $desc,
                            idx: $idx,
                            url: '/' . Path::join(VENDI_PATH_ROOT_THEME_DOCUMENTATION, $vendi_selected_theme_page, 'test', $idx),
                        );
                    }
                }
            }

            $html = $result->getContent();
            $html = str_replace('{%examples%}', $examples, $html);

            echo $html;
            ?>
        <?php endif; ?>
    </section>
</main>
<footer>
    <script>
        /*global window */

        (function (w) {

                'use strict'; //Force strict mode

                let minHeight = 0;

                const
                    document = w.document,
                    load = () => {


                        const resizeObserver = new ResizeObserver(
                            (entries) => {
                                const h = entries[0].target.clientHeight;

                                if (h > minHeight) {
                                    minHeight = h;
                                    document.body.style.minHeight = minHeight + 'px';
                                }
                            }
                        );

                        resizeObserver.observe(document.body)


                        document
                            .querySelectorAll('iframe')
                            .forEach(
                                (iframe) => {
                                    iframeResize(
                                        {
                                            license: 'GPLv3',
                                            waitForLoad: false,
                                            log: true,
                                        },
                                        iframe
                                    );
                                }
                            )
                        ;
                    },

                    init = () => {
                        if (['complete', 'interactive'].includes(document.readyState)) {
                            //If the DOM is already set then invoke our load function
                            load();
                        } else {
                            //Otherwise, wait for the ready event
                            document.addEventListener('DOMContentLoaded', load);
                        }
                    }
                ;

                //Kick everything off
                init();
            }
        )(window);
    </script>
</footer>
<?php wp_footer(); ?>
</html>
