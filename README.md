# Vendi Starter Theme
This theme is the general starter theme for Vendi sites.

## Installation
The installation script `./bin/install.sh` requires two variables to be set when
run.

### `THEME_NAME_FOR_CONST`
Set to a short descriptive name for your theme to be used when prefixing certain
theme-specific PHP constants.

#### Requirements
 * Only letters and underscores
 * Always uppercase
 * Ideal lengths are less than 20 characters total
 * Acronyms should be avoided but can be used

#### Examples
Example values are `TORO` or `DRAGON_BOAT`.

#### Notes
This value is used as the prefix for certain PHP constants specific to the
theme, specifically the theme's root folder. These are used instead of
constantly requesting them from WordPress.

### `CLIENT_NAMESPACE`
Set to a short descriptive name for your theme to be used as the child PHP
namespace under Vendi's root namespace of `Vendi`.

#### Requirements
 * Only letters
 * Always title case
 * Ideal lengths are less than 20 characters total
 * Avoid acronyms unless really needed

#### Examples
Example values are `Toro` or `DragonBoat`.

#### Notes
This value is used by PHP's autoloader as the starting namespace to search for
code in the `src` folder. See `includes/autoload.php` for more details.

Even if you don't use this feature you should still set this to something
to be ready for future changes.

For advanced users you may set this to include a slashes for sub-namespaces but
you might need to tweak the `sed` command.
