# Component Migration: v2 to v3

**When asked to migrate a component**, use the `migrate-component` skill located at `.claude/skills/migrate-component/SKILL.md`. The skill contains comprehensive
guidance for the entire migration process including file structure, class creation, template refactoring, sub-layouts, and documentation.

## Code Style Guidelines

### Template Files
Templates follow a three-part structure:
1. **PHP at the top** - Component initialization and setup
2. **Template markup in the middle** - HTML with embedded PHP
3. **Closing PHP at the bottom** - Component wrapper end

**Use alternative PHP syntax** in template section (middle):
```php
<?php
// Top: Setup
use Vendi\Theme\Component\example;
$component = ComponentUtility::get_new_component_instance(example::class);
if (!$component->renderComponentWrapperStart()) {
    return;
}
?>
    <!-- Middle: Template with alternative syntax -->
    <?php if ($condition): ?>
        <div>Content</div>
    <?php endif; ?>

    <?php foreach ($items as $item): ?>
        <p><?php echo $item; ?></p>
    <?php endforeach; ?>
<?php
// Bottom: Cleanup
$component->renderComponentWrapperEnd();
```

### HTML Attributes
- Always use **double quotes** for HTML attributes
- ❌ `<div class='example'>`
- ✅ `<div class="example">`

### SVG Loading
- Use `vendi_get_svg()` helper instead of `file_get_contents()`
- ❌ `<?php echo file_get_contents(VENDI_CUSTOM_THEME_PATH . '/svgs/icon.svg'); ?>`
- ✅ `<?php vendi_get_svg('/svgs/icon.svg'); ?>`

### Variable Usage
- Echo variables directly instead of creating intermediate variables
- ❌ `<?php $image = vendi_fly_get_attachment_picture($id, 'size'); echo $image; ?>`
- ✅ `<?php echo vendi_fly_get_attachment_picture($id, 'size'); ?>`

### Class Methods
- **Avoid rendering from class methods** - Classes should not output to HTTP stream
- Use getter methods to return data, render in templates
- ❌ `public function renderSomething(): void { echo "<div>...</div>"; }`
- ✅ `public function getSomething(): array { return $data; }` (render in template)
- Exception: Very rare cases where rendering from class is necessary

## Git Commit Messages

When creating git commits for component migrations or other work:
- Do NOT include the Claude Code attribution footer
- Do NOT include "Co-Authored-By: Claude" tag
- Do NOT run `git add` in any fashion, the user will do this manually
- Write clear, descriptive commit messages in your own voice
- Focus on what was changed and why
