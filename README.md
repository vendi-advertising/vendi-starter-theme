# Install
 1. Run the following, replacing `YOUR_THEME_NAME_HERE` with a unique name for your theme, ideally ending with `_THEME`:<br />
 `find . -type f -name "*.php" -exec sed -i 's/%VENDI_CUSTOM_THEME%/YOUR_THEME_NAME_HERE/g' {} +`
 1. Run `composer install`
