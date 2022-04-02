# Vendi Starter Theme
This theme is the general starter theme for Vendi sites.

## Version 2022 changes
  * Symfony has been upgraded to 6
  * The tracking pixel hook after the opening body has been removed since GTM is usually used
  * Upgraded theme development default template for missing components
  * Added ACF Theme Options with a tab for global JavaScript
  * Content Components are now standard

## Version 2021 changes
  * Added theme support for HTML5 [style and script](https://github.com/vendi-advertising/vendi-starter-theme/commit/12122a0a1b43b997c0c78d85208947129334ade3)
  * Added [thumbnail style YAML](https://github.com/vendi-advertising/vendi-thumbnail-from-yaml) support
  * Added [Fly Dynamic Image Resizer](https://wordpress.org/plugins/fly-dynamic-image-resizer/) along with
    [`picture` tag](https://github.com/vendi-advertising/fly-picture-tag-generator) support
  * [Fixed search for URL](https://github.com/vendi-advertising/vendi-starter-theme/commit/e41f8aa2cb5bdabb3e65047d88055ddd2c2c352c) for non-standard installs
  * Added standard [long-term UTM tracking](https://github.com/vendi-advertising/vendi-starter-theme/commit/c5cdfc7663705db967bdb9ffb297b33536276b72)

## Version 2020 changes
The theme has been upgraded to Chris's 2020 brain and installation should be greatly simplified.

### Overview of changes
  * The install.sh script has been completely removed
  * The theme's namespace for code in `/src/` will now always be `Vendi\\Theme` unless someone manually changes
    this.
  * Symfony has been upgraded to version 5
  * The `WebMozart` path loader has been abstracted away, see the _Vendi Component Loader_ below.
  * Custom Post Types should all be declared in `/.config/cpts.yaml`. , see the _Vendi Custom Post Type Loader_ below.
  * Asset (CSS and JS) loading logic has been moved into a shared library, see the _Vendi Asset Loader_ below.
  * The WordPress VIP code standards are installed, and you are encouraged to run the theme against that code
    sniffer for that ruleset.

### New components
All themes should now use the open source components created and maintained by Vendi including the following. See
each component's documentation page for further details.
  * [Vendi Component Loader](https://github.com/vendi-advertising/vendi-component-loader) - Allows loading
    components such as ACF Flexible Layouts as well as general abstract objects such as headers, footers, heroes
    and any other asset that can be componentized.
  * [Vendi Custom Post Type Loader](https://github.com/vendi-advertising/vendi-cpt-from-yaml) - Created CPTs directly
    from a YAML file instead of having to deal with PHP arrays.
  * [Vendi Asset Loader](https://github.com/vendi-advertising/vendi-asset-loader) - Dynamically loads all CSS and JS
    files from a common directory in alphabetical order.

## Installation
THEMENAMEHERE should be renamed to your theme's folder name. eg. stoptheclot
 * Run `git clone git@github.com:vendi-advertising/vendi-starter-theme.git` from the theme's folder.
 * `mv vendi-starter-theme THEMENAMEHERE`
 * `cd THEMENEAMEHERE`
 * `composer install`
 * `composer up`
 * run `rm -rf ./.git/` from the theme directory
 * make a new GIT repository `https://github.com/organizations/vendi-advertising/repositories/new`


## Required Plugins
This theme assumes that you have certain plugins installed
 * [Fly Dynamic Image Resizer](https://wordpress.org/plugins/fly-dynamic-image-resizer/) : `wp plugin install fly-dynamic-image-resizer --activate`
 * [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/)
 * [Responsive Menu Pro](https://responsive.menu/)
