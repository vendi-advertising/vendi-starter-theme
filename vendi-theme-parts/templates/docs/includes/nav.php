<?php

use League\CommonMark\Extension\FrontMatter\Data\SymfonyYamlFrontMatterParser;
use League\CommonMark\Extension\FrontMatter\FrontMatterParser;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;

use function Symfony\Component\String\u;

$navItems     = [
    'component'     => [],
    'shell'         => [],
];
$navItemNames = [
    'component'     => 'Components',
    'shell'         => 'Shell',
];

global $vendi_selected_theme_page;
?>

<?php
$componentRoot = Path::join(VENDI_CUSTOM_THEME_PATH, 'vendi-theme-parts', 'components');
$finder        = new Finder();

foreach ($finder->files()->in($componentRoot)->directories()->sortByName()->depth(0) as $directory) {
    $directoryName         = $directory->getRelativePathname();
    $possibleMarkdownFiles = [
        Path::join($componentRoot, $directoryName, $directoryName . '.md'),
        Path::join($componentRoot, $directoryName, 'readme.md'),
    ];
    $directoryNamePretty   = u($directoryName)->replace('_', ' ')->replace(' with ', ' w/ ')->title()->toString();
    $found                 = null;
    foreach ($possibleMarkdownFiles as $possibleMarkdownFile) {
        if (is_readable($possibleMarkdownFile)) {
            $found = $possibleMarkdownFile;
            break;
        }
    }
    if ( ! $found) {
        continue;
    }

    $frontMatterParser = new FrontMatterParser(new SymfonyYamlFrontMatterParser());
    $result            = $frontMatterParser->parse(file_get_contents($possibleMarkdownFile));

    if ( ! $frontMatter = $result->getFrontMatter()) {
        continue;
    }

    if ( ! $componentRole = ($frontMatter['role'] ?? null)) {
        continue;
    }

    if ( ! array_key_exists($componentRole, $navItems)) {
        continue;
    }

    $navItems[$componentRole][] = [
        'slug' => $directoryName,
        'path' => Path::join(VENDI_PATH_ROOT_THEME_DOCUMENTATION, $directoryName),
        'name' => $directoryNamePretty,
    ];
}
?>
<sidebar class="sidebar">
    <?php foreach ($navItems as $type => $stuff): ?>
        <nav>
            <h2><?php esc_html_e($navItemNames[$type] ?? 'Nav item name missing'); ?></h2>
            <ul class="nav-items">
                <?php foreach ($stuff as $item): ?>
                    <li><a
                            href="/<?php echo $item['path']; ?>"
                            <?php if ($vendi_selected_theme_page === $item['slug']): ?>
                                class="selected"
                            <?php endif; ?>
                        ><?php echo $item['name']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endforeach; ?>
    <button class="side-bar-toggle" data-role="toggle-sidebar">
        <span class="message-to-show-when-open">⮜ Close sidebar</span>
        <span class="message-to-show-when-closed">
            <span class="text">Open sidebar</span>
            <span class="arrow">⮞</span>
        </span>
    </button>
</sidebar>
<script>
    document
        .querySelector('.side-bar-toggle')
        .addEventListener(
            'click',
            function () {
                document.documentElement.classList.toggle('sidebar-closed');
            }
        )
    ;
</script>
