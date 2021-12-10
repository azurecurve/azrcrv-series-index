=== Series Index ===

Description:	Displays index of posts in a series using the series-index shortcode.
Version:		1.2.2
Tags:			Series,Posts,Index
Author:			azurecurve
Author URI:		https://development.azurecurve.co.uk/
Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/series-index/
Download link:	https://github.com/azurecurve/azrcrv-series-index/releases/download/v1.2.2/azrcrv-series-index.zip
Donate link:	https://development.azurecurve.co.uk/support-development/
Requires PHP:	5.6
Requires:		1.0.0
Tested:			4.9.99
Text Domain:	series-index
Domain Path:	/languages
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Displays index of posts in a series using the series-index shortcode.

== Description ==

# Description

Displays Index of Series Posts using `series-index` shortcode. This plugin is multi-site compatible and integrates with the [Toggle Show/Hide](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/) plugin from azurecurve. The shortcode can be used on posts and pages; the format of the index and other options are user configurable through an admin page.

Two custom fields (`Series` and `Series Position`) need to be added to each post in the series; these fields are used for selecting the posts and ordering them when the index is displayed; series index post should be Series Position of 0 (this is used for determining the link for the title).

Shortcode `[series-index]` is placed in the post where you want the index.

Shortcode `[series-index-link]` can be used in a post a link back to the series index (Series Position = 0) post using the series title as the link text; `[series-index-link]alternative text[/series-index-link]` to display text different to the series title. title attribute can be used to create link to other series index post; e.g. `[series-index-link title='Implementing Jet Reports' /]`.

This plugin is multisite compatible; each site will need settings to be configured in the admin dashboard.

== Installation ==

# Installation Instructions

 * Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/azrcrv-series-index/releases/latest/).
 * Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
 * Activate the plugin.
 * Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Frequently Asked Questions ==

# Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugins languages folder; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

== Changelog ==

# Changelog

### [Version 1.2.2](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.2.2)
 * Update azurecurve menu.
 * Update readme files.

### [Version 1.2.1](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.2.1)
 * Update azurecurve menu and logo.
 
### [Version 1.2.0](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.2.0)
 * Fix plugin action link to use admin_url() function.
 * Rewrite option handling so defaults not stored in database on plugin initialisation.
 * Add plugin icon and banner.
 * Update azurecurve plugin menu.
 * Amend to only load css when shortcode on page.

### [Version 1.1.8](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.8)
 * Fix bug with select of series index posts when title includes an apostrophe.

### [Version 1.1.7](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.7)
 * Fix bug with setting of default options.
 * Fix bug with plugin menu.
 * Update plugin menu css.

### [Version 1.1.6](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.6)
 * Rewrite default option creation function to resolve several bugs.
 * Upgrade azurecurve plugin to store available plugins in options.
 
### [Version 1.1.5](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.5)
 * Update Update Manager class to v2.0.0.
 * Update action link.
 * Update azurecurve menu icon with compressed image.
 
### [Version 1.1.4](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.4)
 * Fix bug with series-index shortcode when series title contains apostrophes.

### [Version 1.1.3](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.3)
 * Fix bug with series-index-link shortcode when series title contains apostrophes.

### [Version 1.1.2](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.2)
 * Fix bug with selecting series containing apostrophes in series title.

### [Version 1.1.1](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.1)
 * Fix bug with incorrect language load text domain.

### [Version 1.1.0](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.1.0)
 * Add integration with Update Manager for automatic updates.
 * Fix issue with display of azurecurve menu.
 * Change settings page heading.
 * Add load_plugin_textdomain to handle translations.

### [Version 1.0.1](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.0.1)
 * Update azurecurve menu for easier maintenance.
 * Move require of azurecurve menu below security check.

### [Version 1.0.0](https://github.com/azurecurve/azrcrv-series-index/releases/tag/v1.0.0)
 * Initial release for ClassicPress forked from azurecurve Series Index WordPress Plugin.
 * Remove toggle and integrate with Toggle Show/Hide plugin.

== Other Notes ==

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

Some of the other plugins available from **azurecurve** are:
 * BBCode - [details](https://development.azurecurve.co.uk/classicpress-plugins/bbcode/) / [download](https://github.com/azurecurve/azrcrv-bbcode/releases/latest/)
 * Conditional Links - [details](https://development.azurecurve.co.uk/classicpress-plugins/conditional-links/) / [download](https://github.com/azurecurve/azrcrv-conditional-links/releases/latest/)
 * Disable FLoC - [details](https://development.azurecurve.co.uk/classicpress-plugins/disable-floc/) / [download](https://github.com/azurecurve/azrcrv-disable-floc/releases/latest/)
 * Display After Post Content - [details](https://development.azurecurve.co.uk/classicpress-plugins/display-after-post-content/) / [download](https://github.com/azurecurve/azrcrv-display-after-post-content/releases/latest/)
 * Events - [details](https://development.azurecurve.co.uk/classicpress-plugins/events/) / [download](https://github.com/azurecurve/azrcrv-events/releases/latest/)
 * Floating Featured Image - [details](https://development.azurecurve.co.uk/classicpress-plugins/floating-featured-image/) / [download](https://github.com/azurecurve/azrcrv-floating-featured-image/releases/latest/)
 * Gallery From Folder - [details](https://development.azurecurve.co.uk/classicpress-plugins/gallery-from-folder/) / [download](https://github.com/azurecurve/azrcrv-gallery-from-folder/releases/latest/)
 * Insult Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/insult-generator/) / [download](https://github.com/azurecurve/azrcrv-insult-generator/releases/latest/)
 * SMTP - [details](https://development.azurecurve.co.uk/classicpress-plugins/smtp/) / [download](https://github.com/azurecurve/azrcrv-smtp/releases/latest/)
 * Update Admin Menu - [details](https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/) / [download](https://github.com/azurecurve/azrcrv-update-admin-menu/releases/latest/)
